<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\Dtks\DtksEnum;
use App\Enums\HubunganRTMEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\SasaranEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusRTMEnum;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\Dtks;
use App\Models\Penduduk;
use App\Models\Rtm as RtmModel;
use App\Models\Wilayah;
use App\Services\DtksService;
use App\Traits\Upload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use OpenSpout\Reader\XLSX\Reader;

defined('BASEPATH') || exit('No direct script access allowed');

class Rtm extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'kependudukan';
    public $sub_modul_ini = 'rumah-tangga';
    private $judulStatistik;
    private $filterColumn = [];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {

        // Secara dinamis menerapkan filter dari statistik
        if ($statistikFilter = $this->input->get('statistikfilter')) {
            foreach ($statistikFilter as $key => $value) {
                $this->filterColumn[$key] = $value;
            }
        }

        $manualFilters = ['status', 'dusun', 'rw', 'rt', 'sex'];

        foreach ($manualFilters as $filter) {
            if ($this->input->get($filter)) {
                $this->filterColumn[$filter] = $this->input->get($filter);
            }
        }

        $data = [
            'status'          => [StatusRTMEnum::YA => 'Aktif', StatusRTMEnum::TIDAK => 'Tidak Aktif', StatusRTMEnum::TANPA_KEPALA_KELUARGA => 'Tanpa Kepala Keluarga'],
            'jenis_kelamin'   => JenisKelaminEnum::all(),
            'wilayah'         => Wilayah::treeAccess(),
            'judul_statistik' => $this->judulStatistik,
            'filterColumn'    => $this->filterColumn,
            'formatImpor'     => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-impor-rtm.xlsx')),
        ];
        view('admin.penduduk.rtm.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $canDelete = can('h');
            $canUpdate = can('u');

            return datatables()->of($this->sumberData())
                ->orderColumn(
                    'no_kk',
                    static fn ($query, $order) => $query->orderByRaw('CAST(no_kk AS UNSIGNED) ' . match (strtoupper($order)) {
                        'DESC'  => 'DESC',
                        default => 'ASC'
                    })
                )
                ->addColumn('ceklist', static function ($row) use ($canDelete) {
                    if ($canDelete) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addColumn('foto', static fn ($row) => '<img class="penduduk_kecil" src="' . AmbilFoto($row->kepalaKeluarga->foto, '', $row->kepalaKeluarga->sex) . '" alt="Foto Penduduk" />')->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate): string {
                    $aksi = '';

                    $aksi .= View::make('admin.layouts.components.tombol_detail', [
                        'url'   => ci_route('rtm.anggota', $row->id),
                        'judul' => 'Rincian Anggota Rumah Tangga',
                    ])->render();

                    if ($canUpdate && $row->kepalaKeluarga->status_dasar == StatusDasarEnum::HIDUP) {
                        $aksi .= View::make('admin.layouts.components.buttons.btn', [
                            'url'        => ci_route('rtm.ajax_add_anggota', $row->id),
                            'icon'       => 'fa fa-plus',
                            'judul'      => 'Tambah Anggota Rumah Tangga',
                            'type'       => 'btn-success',
                            'buttonOnly' => true,
                            'modal'      => true,
                        ])->render();

                        $aksi .= View::make('admin.layouts.components.buttons.edit', [
                            'url'   => 'rtm/edit_nokk/' . $row->id,
                            'modal' => true,
                        ])->render();

                        $aksi .= View::make('admin.layouts.components.buttons.btn', [
                            'url'        => ci_route('penduduk.ajax_penduduk_maps.' . $row->kepalaKeluarga->id, 0),
                            'icon'       => 'fa fa-map-marker',
                            'judul'      => 'Lokasi Tempat Tinggal',
                            'type'       => 'btn-success',
                            'buttonOnly' => true,
                        ])->render();
                    }

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => ci_route('rtm.delete', $row->id),
                        'confirmDelete' => true,
                    ])->render();

                    if ($row->terdaftar_dtks && can('u', 'dtks')) {
                        $aksi .= View::make('admin.layouts.components.buttons.btn', [
                            'url'         => ci_route('dtks.new', $row->id),
                            'icon'        => 'fa fa-plus',
                            'judul'       => 'DTKS',
                            'type'        => 'bg-purple',
                            'buttonOnly'  => true,
                            'withJudul'   => 'DTKS',
                            'modal'       => true,
                            'onclick'     => 'show_confirm(this)',
                            'modalTarget' => 'show_confirm_modal',
                        ])->render();
                    }

                    return $aksi;

                })
                ->editColumn('kepala_keluarga.nik', static function ($row) {
                    if (isset($row->kepalaKeluarga->nik)) {
                        return '<a href="' . ci_route('penduduk.detail', $row->kepalaKeluarga->id) . '"><span>' . $row->kepalaKeluarga->nik . '</span></a>';
                    }

                    return '-';
                })
                ->editColumn('no_kk', static fn ($row) => '<a href="' . ci_route('rtm.anggota', $row->id) . '"><span>' . $row->no_kk . '</span></a>')
                ->editColumn('tgl_daftar', static fn ($q) => tgl_indo($q->tgl_daftar))
                ->editColumn('terdaftar_dtks', static fn ($q) => $q->terdaftar_dtks ? 'Terdaftar' : 'Tidak Terdaftar')
                ->rawColumns(['aksi', 'no_kk', 'kepala_keluarga.nik', 'ceklist', 'foto'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null): void
    {
        isCan('u');

        if ($id) {
            $data['form_action'] = ci_route('rtm.update', $id);
        } else {
            $data['menu']        = null;
            $data['form_action'] = ci_route('rtm.insert');
        }
        view('admin.penduduk.rtm.form', $data);
    }

    public function edit_nokk($id = 0): void
    {
        isCan('u');
        $data['kk']          = RtmModel::findOrFail($id) ?? show_404();
        $data['form_action'] = ci_route($this->controller . '.update_nokk', $id);

        view('admin.penduduk.rtm.ajax_edit_no_rtm', $data);
    }

    public function update_nokk($id = 0): void
    {
        isCan('u');

        try {
            $post                   = $this->input->post();
            $data['no_kk']          = nama_terbatas($post['no_kk']);
            $data['bdt']            = empty($post['bdt']) ? null : bilangan($post['bdt']);
            $data['terdaftar_dtks'] = empty($post['terdaftar_dtks']) ? 0 : 1;
            $this->validasiNoRtm($data['no_kk']);

            $rtm = RtmModel::findOrFail($id);
            if ($data['no_kk']) {
                $adaNoKKLain = RtmModel::where(['no_kk' => $data['no_kk']])->where('id', '!=', $id)->count();
                if ($adaNoKKLain) {
                    redirect_with('error', 'Nomor RTM itu sudah ada. Silakan ganti dengan yang lain.');
                }
                Penduduk::where(['id_rtm' => $rtm->no_kk])->update(['id_rtm' => $data['no_kk']]);
            }
            $rtm->update($data);

            // proses insert dtks jika terdaftar dtks
            $this->createOrDeleteDtks($post, $rtm);

            redirect_with('success', 'Data RTM berhasil disimpan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data RTM gagal disimpan');
        }
    }

    public function insert(): void
    {
        isCan('u');
        $post = $this->input->post();
        $nik  = nama_terbatas($post['nik']);

        try {
             // Jika input no_rtm dikosongkan → sistem akan generate nomor otomatis
            if (empty($post['no_rtm'])) {
                $lastRtm = RtmModel::select(['no_kk'])
                    ->where('config_id', identitas('id'))
                    ->orderBy(DB::raw('length(no_kk)'), 'desc')
                    ->orderBy(DB::raw('no_kk'), 'desc')
                    ->first();

                if ($lastRtm) {
                    $noRtm = $lastRtm->no_kk; // Ambil nomor KK terakhir yang ditemukan

                    if (strlen($noRtm) >= 5) {
                        // Jika panjang nomor KK minimal 5 karakter → mode 5 digit increment

                        $kw = substr($noRtm, 0, strlen($noRtm) - 5);
                        // Ambil prefix (selain 5 digit terakhir)

                        $noUrut = substr($noRtm, -5);
                        // Ambil 5 digit terakhir untuk di-increment

                        preg_match('/^(.*?)([0-9]+)$/', $noUrut, $matches_suffix);
                        // Cek apakah 5 digit terakhir berakhiran angka

                        if (count($matches_suffix) == 3) {
                            $textPart    = $matches_suffix[1];     // Bagian non angka
                            $numericPart = $matches_suffix[2];  // Bagian angka

                            $incrementedNumericPart = (int) $numericPart + 1;
                            // Increment angka

                            $noUrut = $textPart . str_pad($incrementedNumericPart, strlen($numericPart), '0', STR_PAD_LEFT);
                            // Rekonstruksi 5 digit baru
                        } else {
                            redirect_with('success', "Format Nomor Rumah Tangga terakhir tidak valid untuk diincrement: '{$noRtm}'. Pastikan 5 digit terakhir adalah angka atau memiliki akhiran angka.");
                            // Format salah → tampilkan pesan
                        }

                        $rtm['no_kk'] = $kw . $noUrut;
                        // Gabungkan prefix + angka baru

                    } else {
                        // Jika nomor KK panjangnya < 5 karakter → mode increment full string

                        preg_match('/^(.*?)([0-9]+)$/', $noRtm, $matches_suffix);
                        // Pisahkan text dan angka dari akhir string

                        if (count($matches_suffix) == 3) {
                            $textPart    = $matches_suffix[1];
                            $numericPart = $matches_suffix[2];

                            $incrementedNumericPart = (int) $numericPart + 1;

                            $rtm['no_kk'] = $textPart . str_pad($incrementedNumericPart, strlen($numericPart), '0', STR_PAD_LEFT);
                        } else {
                            redirect_with('success', "Format Nomor Rumah Tangga terakhir tidak valid untuk diincrement: '{$noRtm}'. Pastikan memiliki akhiran angka.");
                        }
                    }

                } else {
                    // Jika tabel kosong, generate nomor pertama
                    $kw           = identitas()->kode_desa;
                    $rtm['no_kk'] = $kw . str_pad('1', 5, '0', STR_PAD_LEFT);
                }

            } else {
                // Jika user mengisi nomor, lakukan validasi manual

                $clean = preg_replace('/[^\x20-\x7E]/', '', $post['no_rtm']);
                // Hilangkan karakter non-ASCII (hidden chars)

                $clean = trim($clean);

                $this->validasiNoRtm($clean);
                // Validasi format menggunakan function Anda

                $rtm['no_kk'] = strtoupper($clean);
                // Simpan nomor dengan uppercase
            }

            // Cek duplikasi nomor RTM
            if (RtmModel::isNomorExist($rtm['no_kk'])) {
                redirect_with('error', "Nomor Rumah Tangga '{$rtm['no_kk']}' sudah digunakan. Silakan gunakan nomor lain.");
            }

            $rtm['nik_kepala']     = $nik;
            $rtm['bdt']            = empty($post['bdt']) ? null : bilangan($post['bdt']);
            $rtm['terdaftar_dtks'] = empty($post['terdaftar_dtks']) ? 0 : 1;
            DB::beginTransaction();
            $newRtm = RtmModel::create($rtm);

            // proses insert dtks jika terdaftar dtks
            $this->createOrDeleteDtks($post, $newRtm);

            DB::commit();

            $default['id_rtm']     = $rtm['no_kk'];
            $default['rtm_level']  = 1;
            $default['updated_at'] = date('Y-m-d H:i:s');
            $default['updated_by'] = ci_auth()->id;
            Penduduk::where(['id' => $nik])->update($default);

            // anggota
            $default['rtm_level'] = 2;
            if ($post['anggota_kk']) {
                Penduduk::whereIn('id', $post['anggota_kk'])->update($default);
            }

            redirect_with('success', 'Rumah Tangga berhasil disimpan');
        } catch (Exception  $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Rumah Tangga gagal disimpan');
        }
    }

    public function update($parent, $id): void
    {
        isCan('u');
        $post = $this->input->post();

        DB::beginTransaction();

        try {
            $rtm = RtmModel::findOrFail($id);
            $rtm->update($post);

            // proses insert dtks jika terdaftar dtks
            $this->createOrDeleteDtks($post, $rtm);

            DB::commit();

            redirect_with('success', 'Rumah Tangga berhasil disimpan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            DB::rollBack();

            redirect_with('error', 'Rumah Tangga gagal disimpan');
        }
    }

    /**
     * Create or Delete DTKS record based on RTM form input.
     *
     * @param mixed $post
     * @param mixed $rtm
     *
     * @throws Exception
     */
    public function createOrDeleteDtks($post, $rtm): void
    {
        if (! empty($post['terdaftar_dtks'])) {
            // Cek apakah data sudah ada di DTKS, jika belum maka buat baru
            if (! Dtks::where('id_rtm', $rtm->id)->exists()) {
                $dtks = Dtks::create([
                    'id_rtm'          => $rtm->id,
                    'versi_kuisioner' => DtksEnum::VERSION_CODE,
                    'is_draft'        => StatusRTMEnum::YA,
                ]);
                // Panggil method dari DtksService untuk sinkronisasi
                (new DtksService())->synchroniseDTKSWithOpenSid($dtks);
            }
        } else {
            // Jika tidak terdaftar, hapus dari DTKS jika ada
            Dtks::where('id_rtm', $rtm->id)->delete();
        }
    }

    public function delete($id = null): void
    {
        isCan('h');

        try {
            RtmModel::destroy($this->request['id_cb'] ?? $id);
            redirect_with('success', 'Rumah Tangga berhasil dihapus');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Rumah Tangga gagal dihapus');
        }
    }

    public function apipendudukrtm()
    {
        if ($this->input->is_ajax_request()) {
            $cari = $this->input->get('q');

            $penduduk = Penduduk::select(['id', 'nik', 'nama', 'id_cluster', 'kk_level'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->where(static function ($q) use ($cari) {
                        $q->where('nik', 'like', "%{$cari}%")
                            ->orWhere('nama', 'like', "%{$cari}%");
                    });
                })
                ->where(static function ($query): void {
                    $query->where('id_rtm', 0)
                        ->orWhereNull('id_rtm');
                })
                ->statusDasar([
                    StatusDasarEnum::HIDUP,
                ])
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->id,
                        'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama .
                            ' RT-' . $item->wilayah->rt .
                            ', RW-' . $item->wilayah->rw .
                            ', ' . strtoupper(setting('sebutan_dusun') . ' ' .
                            $item->wilayah->dusun . ' - ' . $item->penduduk_hubungan),
                    ]),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    /**
     * Impor Pengelompokan Data Rumah Tangga
     * Alur :
     * Cek apakah NIK ada atau tidak.
     * 1. Jika Ya, update data penduduk (rtm) berdasarkan data impor.
     * 2. Jika Tidak, tampilkan notifikasi baris data yang gagal.
     *
     * @param mixed $hapus
     */
    public function impor()
    {
        isCan('u');
        $configId                = identitas('id');
        $config['upload_path']   = sys_get_temp_dir();
        $config['allowed_types'] = 'xls|xlsx|xlsm';

        $this->upload('userfile', $config);

        $reader = new Reader();
        $reader->open($_FILES['userfile']['tmp_name']);
        $pesan = '';

        foreach ($reader->getSheetIterator() as $sheet) {
            $baris_pertama = false;
            $gagal         = 0;
            $nomor_baris   = 0;

            if ($sheet->getName() === 'RTM') {
                foreach ($sheet->getRowIterator() as $row) {
                    // Abaikan baris pertama yg berisi nama kolom
                    if (! $baris_pertama) {
                        $baris_pertama = true;

                        continue;
                    }

                    $nomor_baris++;

                    $rowData = [];
                    $cells   = $row->getCells();

                    foreach ($cells as $cell) {
                        $rowData[] = $cell->getValue();
                    }
                    //ID RuTa
                    $id_rtm = $rowData[1];

                    if (empty($id_rtm)) {
                        $pesan .= "Pesan Gagal : Baris {$nomor_baris} Nomer Rumah Tannga Tidak Boleh Kosong</br>";
                        $gagal++;

                        continue;
                    }

                    //Level
                    $rtm_level = (int) $rowData[2];

                    if ($rtm_level === 0) {
                        $pesan .= "Pesan Gagal : Baris {$nomor_baris} Kode Hubungan Rumah Tangga Tidak Diketahui</br>";
                        $gagal++;
                        $outp = false;

                        continue;
                    }

                    if ($rtm_level > 1) {
                        $rtm_level = 2;
                    }

                    //NIK
                    $nik = $rowData[0];

                    if (empty($nik)) {
                        $pesan .= "Pesan Gagal : Baris {$nomor_baris} NIK tidak boleh kosong.</br>";
                        $gagal++;
                        $outp = false;

                        continue;
                    }
                    // pakai withOnly, karena  kalau tidak akan melakukan query terhadap semua relationship yang didefine pada $with
                    $penduduk = Penduduk::select(['id', 'nik'])->withOnly(['wilayah'])->whereNik($nik)->first();

                    if ($penduduk) {
                        $ada = [
                            'id_rtm'     => $id_rtm,
                            'rtm_level'  => $rtm_level,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];

                        if (! $penduduk->update($ada)) {
                            $pesan .= "Pesan Gagal : Baris {$nomor_baris} Data penduduk dengan NIK : { {$nik} } gagal disimpan</br>";
                            $gagal++;
                            $outp = false;

                            continue;
                        }

                        if ($rtm_level == 1) {
                            // untuk upsert harus tetap menyertakan data config_id
                            $dataRTM = [
                                'nik_kepala' => $penduduk->id,
                                'no_kk'      => $id_rtm,
                                'config_id'  => $configId,
                            ];

                            if (! RtmModel::upsert($dataRTM, ['config_id', 'no_kk'])) {
                                $pesan .= "Pesan Gagal : Baris {$nomor_baris} Data penduduk dengan NIK : {$nik} gagal disimpan</br>";
                                $gagal++;
                                $outp = false;

                                continue;
                            }
                        }
                    } else {
                        $pesan .= "Pesan Gagal: Baris {$nomor_baris} data penduduk dengan NIK: {$nik} tidak ditemukan.</br>";
                        $gagal++;
                        $outp = false;
                    }
                }
                $berhasil = ($nomor_baris - $gagal);
                $pesan .= "Jumlah Berhasil : {$berhasil} </br>";
                $pesan .= "Jumlah Gagal : {$gagal} </br>";
                $pesan .= "Jumlah Data : {$nomor_baris} </br>";

                break;
            }
        }
        $reader->close();
        if (empty($pesan)) {
            redirect_with('error', 'File impor tidak sesuai');
        }
        redirect_with('success', $pesan);
    }

    public function cetak($aksi = 'cetak', $privasi_nik = 0)
    {
        $query = datatables(
            $this->sumberData()
                ->when($this->input->post('id_cb'), static function ($query, $ids) {
                    $query->whereIn('id', json_decode($ids));
                })
        );

        $data = [
            'main'  => $query->prepareQuery()->results(),
            'start' => app('datatables.request')->start(),
            'judul' => $this->input->post('judul'),
            'aksi'  => 'cetak',
        ];

        if ($privasi_nik == 1) {
            $data['privasi_nik'] = true;
        }

        if ($aksi == 'unduh') {
            header('Content-type: application/xls');
            header('Content-Disposition: attachment; filename=rtm_' . date('Y-m-d') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }

        return view('admin.penduduk.rtm.cetak', $data);
    }

    public function ajax_cetak($aksi = '')
    {
        $data['aksi']   = $aksi;
        $data['action'] = ci_route("rtm.cetak.{$aksi}");

        return view('admin.layouts.components.ajax-cetak-bersama', $data);
    }

    public function anggota($id = 0): void
    {
        $rtm = RtmModel::with(['kepalaKeluarga', 'anggota' => static fn ($q) => $q->orderBy('rtm_level')])
            ->withCount('anggota')
            ->findOrFail($id);

        if ($rtm->anggota_count < 1) {
            redirect_with('error', 'Rumah tangga tersebut tidak memiliki anggota/kosong.', ci_route($this->controller));
        }

        $data['kk']        = $id;
        $data['main']      = $rtm->anggota->toArray();
        $data['kepala_kk'] = array_merge(['bdt' => $rtm->bdt, 'no_kk' => $rtm->no_kk, 'jumlah_kk' => $rtm->jumlah_kk], optional($rtm->kepalaKeluarga)->toArray() ?? []);
        $data['program']   = ['programkerja' => BantuanPeserta::with(['bantuan'])->whereHas('bantuan', static fn ($q) => $q->whereSasaran(SasaranEnum::RUMAH_TANGGA))->wherePeserta($rtm->no_kk)->get()->toArray()];

        view('admin.penduduk.rtm.anggota', $data);
    }

    public function ajax_add_anggota($id = 0): void
    {
        isCan('u');

        $data['form_action'] = ci_route($this->controller . '.add_anggota', $id);

        view('admin.penduduk.rtm.ajax_add_anggota_rtm_form', $data);
    }

    public function datatables_anggota($id)
    {
        if ($this->input->is_ajax_request()) {
            $rtm = RtmModel::with([
                'anggota.keluarga.wilayah',
                'anggota.Wilayah', // Case-sensitive!
            ])->findOrFail($id);

            $canDelete = can('h');
            $canUpdate = can('u');

            return datatables()->of($rtm->anggota)
                ->addIndexColumn()
                ->addColumn(
                    'ceklist',
                    static fn ($row) => $canDelete
                    ? '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>'
                    : ''
                )
                ->addColumn('aksi', static function ($row) use ($id, $canUpdate) {
                    $aksi = '';

                    $aksi .= View::make('admin.layouts.components.buttons.edit', [
                        'url' => 'penduduk/form/' . $row->id,
                    ])->render();

                    if ($canUpdate) {
                        $aksi .= View::make('admin.layouts.components.buttons.btn', [
                            'url'        => ci_route("rtm.edit_anggota.{$id}", $row->id),
                            'icon'       => 'fa fa-link',
                            'judul'      => 'Ubah Hubungan',
                            'type'       => 'bg-navy',
                            'modal'      => true,
                            'buttonOnly' => true,
                        ])->render();
                    }

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => ci_route("rtm.delete_anggota.{$id}", $row->id),
                        'confirmDelete' => true,
                    ])->render();

                    return $aksi;
                })
                ->editColumn('nik', static fn ($row) => '<a href="' . ci_route('penduduk.detail', $row->id) . '">' . $row->nik . '</a>')
                ->editColumn('keluarga.no_kk', static fn ($row) => '<a href="' . ci_route('keluarga.anggota', $row->keluarga->id) . '">' . $row->keluarga->no_kk . '</a>')
                ->editColumn('nama', static fn ($row) => strtoupper($row->nama))
                ->addColumn('alamat_wilayah', static fn ($row) => $row->alamat_wilayah)
                ->editColumn('sex', static fn ($row) => strtoupper(JenisKelaminEnum::valueOf($row->sex)))
                ->editColumn('rtm_level', static fn ($row) => strtoupper(HubunganRTMEnum::valueOf($row->rtm_level)))
                ->rawColumns(['ceklist', 'aksi', 'nik', 'keluarga.no_kk'])
                ->make(true);
        }

        return show_404();
    }

    public function datables_anggota($id_pend = null)
    {
        if ($this->input->is_ajax_request()) {
            $penduduk = Penduduk::with(['keluarga', 'keluarga.anggota'])
                ->where('kk_level', '=', 1)
                ->find($id_pend);
            $anggota = collect($penduduk->keluarga->anggota)->whereIn('id_rtm', ['0', null]);

            if ($anggota->count() > 1) {
                $keluarga = $anggota->map(static fn ($item, $key): array => [
                    'no'       => $key + 1,
                    'id'       => $item->id,
                    'nik'      => $item->nik,
                    'nama'     => $item->nama,
                    'kk_level' => SHDKEnum::valueOf($item->kk_level),
                ])->values();
            }

            return json([
                'data' => $keluarga,
            ]);
        }

        show_404();
    }

    public function edit_anggota($id_rtm = 0, $id = 0): void
    {
        isCan('u');
        $data['hubungan']    = HubunganRTMEnum::all();
        $data['main']        = Penduduk::findOrFail($id) ?? show_404();
        $data['form_action'] = ci_route($this->controller . ".update_anggota.{$id_rtm}", $id);

        view('admin.penduduk.rtm.ajax_edit_anggota_rtm', $data);
    }

    public function kartu_rtm($id = 0): void
    {
        $data['id_kk']     = $id;
        $data['hubungan']  = HubunganRTMEnum::all();
        $rtm               = RtmModel::with(['kepalaKeluarga', 'anggota'])->findOrFail($id);
        $data['main']      = $rtm->anggota->toArray();
        $data['kepala_kk'] = array_merge(['bdt' => $rtm->bdt, 'no_kk' => $rtm->no_kk], $rtm->kepalaKeluarga->toArray());

        view('admin.penduduk.rtm.kartu_rtm', $data);
    }

    public function cetak_kk($id = 0): void
    {
        $data['id_kk']     = $id;
        $data['hubungan']  = HubunganRTMEnum::all();
        $rtm               = RtmModel::with(['kepalaKeluarga', 'anggota'])->findOrFail($id);
        $data['main']      = $rtm->anggota->toArray();
        $data['kepala_kk'] = array_merge(['bdt' => $rtm->bdt, 'no_kk' => $rtm->no_kk], $rtm->kepalaKeluarga->toArray());

        view('admin.penduduk.rtm.cetak_rtm', $data);
    }

    public function add_anggota($id = 0): void
    {
        isCan('u');
        $data = $this->input->post('id_cb');
        $nik  = $this->input->post('nik');
        if (! $data && ! $nik) {
            redirect_with('error', 'Tidak ada anggota yang dipilih', ci_route('rtm.anggota', $id) );
        }

        try {
            // TODO :: Gunakan id pada tabel tweb_rtm agar memudahkan relasi
            $temp['id_rtm']     = RtmModel::findOrFail($id)->no_kk;
            $temp['rtm_level']  = HubunganRTMEnum::ANGGOTA;
            $temp['updated_at'] = date('Y-m-d H:i:s');
            $temp['updated_by'] = ci_auth()->id;

            if ($data) {
                Penduduk::whereIn('id', $data)->update($temp);
            } else {
                Penduduk::where('id', $nik)->update($temp);
            }

            redirect_with('success', 'Anggota berhasil ditambahkan', ci_route('rtm.anggota', $id) );
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota gagal ditambahkan', ci_route('rtm.anggota', $id) );
        }

    }

    public function update_anggota($id_rtm = 0, $id = 0): void
    {
        isCan('u');
        // Krn penduduk_hidup menggunakan no_kk(no_rtm) bukan id sebagai id_rtm, jd perlu dicari dlu
        $rtm = RtmModel::findOrFail($id_rtm);

        $rtm_level = (string) $this->input->post('rtm_level');

        $data = [
            'rtm_level'  => $rtm_level,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => ci_auth()->id,
        ];

        if ($rtm_level === '1') {
            // Ganti semua level penduduk dgn id_rtm yg sma -> rtm_level = 2 (Anggota)
            Penduduk::where(['id_rtm' => $rtm->no_kk])->update(['rtm_level' => HubunganRTMEnum::ANGGOTA]);
            // nik_kepala = id_penduduk pd table tweb_penduduk
            // field no_kk pada tweb_rtm maksudnya adalah no_rtm
            $rtm->nik_kepala = $id;
            $rtm->save();
        }

        Penduduk::where(['id' => $id])->update($data);

        redirect_with('success', 'Anggota berhasil diupdate', ci_route($this->controller . '.anggota', $id_rtm));
    }

    public function delete_anggota($kk = 0, $id = 0): void
    {
        isCan('h');
        $rtm = RtmModel::withCount('anggota')->findOrFail($kk);

        $this->delete_single_anggota($id);

        if ($rtm->anggota_count <= 1) {
            redirect_with('success', 'Anggota terakhir telah dihapus. Rumah tangga ini sekarang kosong.', ci_route($this->controller . '.index'));
        } else {
            redirect_with('success', 'Anggota berhasil dihapus', ci_route($this->controller . '.anggota', $kk));
        }
    }

    public function delete_all_anggota($kk = 0): void
    {
        isCan('h');
        $id_cb = $_POST['id_cb'];

        if (empty($id_cb)) {
            redirect_with('error', 'Tidak ada anggota yang dipilih', ci_route($this->controller . '.anggota', $kk));
        }

        // Hitung jumlah anggota sebelum penghapusan
        $rtm = RtmModel::withCount('anggota')->findOrFail($kk);
        $jumlahAwalAnggota = $rtm->anggota_count;
        $jumlahDihapus = count($id_cb);

        foreach ($id_cb as $id) {
            $this->delete_single_anggota($id);
        }

        // Jika semua anggota dihapus, redirect ke index
        if ($jumlahDihapus >= $jumlahAwalAnggota) {
            redirect_with('success', 'Semua anggota telah dihapus. Rumah tangga ini sekarang kosong.', ci_route($this->controller));
        } else {
            redirect_with('success', 'Anggota berhasil dihapus', ci_route($this->controller . '.anggota', $kk));
        }
    }

    public function list_anggota_kk($id_pend = null)
    {
        if ($this->input->is_ajax_request()) {
            $penduduk = Penduduk::with('keluarga')->find($id_pend);

            if (empty($penduduk->keluarga->anggota)) {
                return json(['data' => []]);
            }

            // Anggota keluarga dari penduduk yang dipilih, yg belum masuk RTM
            $anggota = collect($penduduk->keluarga->anggota)
                ->whereIn('id_rtm', ['0', null])
                ->where('id', '!=', $id_pend)
                ->map(static fn ($item, $key) => [
                    'no'       => $key + 1,
                    'id'       => $item->id,
                    'nik'      => $item->nik,
                    'nama'     => $item->nama,
                    'hubungan' => SHDKEnum::valueOf($item->kk_level),
                ])->values();

            return json(['data' => $anggota]);
        }

        show_404();
    }

    public function statistik($tipe = '0', $nomor = 0, $sex = null): void
    {
        if ($sex == 0) {
            $sex = null;
        }

        switch ($tipe) {
            case 'bdt':
                $kategori = 'KLASIFIKASI BDT :';
                break;

            case 'dtsen':
                $kategori = 'KLASIFIKASI DTSEN :';
                break;

            case $tipe > 50:
                $program_id                     = preg_replace('/^50/', '', $tipe);
                $this->session->program_bantuan = $program_id;

                // TODO: Sederhanakan query ini, pindahkan ke model
                $nama = Bantuan::find($program_id)->nama;

                if (! in_array($nomor, [BELUM_MENGISI, TOTAL])) {
                    $this->session->status_dasar = null; // tampilkan semua peserta walaupun bukan hidup/aktif
                    $nomor                       = $program_id;
                }
                $kategori = $nama . ' : ';
                $tipe     = 'penerima_bantuan';
                break;
        }

        $judul = (new RtmModel())->judulStatistik($tipe, $nomor, $sex);
        if ($judul['nama']) {
            $this->judulStatistik = $kategori . $judul['nama'];
        }
        $this->filterColumn = ['sex' => $sex, 'status' => StatusRTMEnum::YA, 'tipe' => $tipe];

        $this->index();
    }

    protected function sumberData()
    {
        $status    = $this->input->get('status') ?? null;
        $sex       = $this->input->get('jenis_kelamin') ?? null;
        $namaDusun = $this->input->get('dusun') ?? null;
        $rw        = $this->input->get('rw') ?? null;
        $rt        = $this->input->get('rt') ?? null;
        $bdt       = $this->input->get('bdt') ?? null;
        $dtsen     = $this->input->get('dtsen') ?? null;
        $idCluster = $rt ? [$rt] : [];

        if (empty($idCluster) && ! empty($rw)) {
            [$namaDusun, $namaRw] = explode('__', $rw);
            $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
        }

        if (empty($idCluster) && ! empty($namaDusun)) {
            $idCluster = Wilayah::whereDusun($namaDusun)->select(['id'])->get()->pluck('id')->toArray();
        }

        return RtmModel::withCount('anggota')
            ->withOnly([
                'anggota'        => static fn ($a) => $a->select(['id', 'id_rtm', 'nama', 'nik', 'id_kk']),
                'kepalaKeluarga' => static fn ($q) => $q->select(['id', 'nama', 'nik', 'sex', 'foto', 'id_kk', 'status_dasar'])
                    ->without('rtm')
                    ->withOnly([
                        'keluarga' => static fn ($qq) => $qq->select(['id', 'id_cluster', 'alamat'])
                            ->withOnly([
                                'wilayah' => static fn ($w) => $w->select(['id', 'dusun', 'rw', 'rt']),
                            ]),
                    ]),
            ])
            ->when($status != null, static function ($q) use ($status) {
                if ($status == StatusRTMEnum::YA) { // Aktif
                    $q->whereHas('kepalaKeluarga', static function ($r) use ($status) {
                        $r->whereStatusDasar($status)->where('rtm_level', HubunganRTMEnum::KEPALA_RUMAH_TANGGA);
                    })->has('anggota');
                } elseif ($status == StatusRTMEnum::TANPA_KEPALA_KELUARGA) { // Tanpa Kepala Keluarga
                    $q->where(static function ($query) {
                        $query->doesntHave('kepalaKeluarga')->orDoesntHave('anggota');
                    });
                } elseif ($status == StatusRTMEnum::TIDAK) { // Tidak Aktif
                    $q->whereHas(
                        'kepalaKeluarga',
                        static fn ($r) => $r->where('status_dasar', '!=', StatusRTMEnum::YA)
                    );
                }
            })
            ->when($sex, static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($r) => $r->whereSex($sex)->where('rtm_level', HubunganRTMEnum::KEPALA_RUMAH_TANGGA)))
            ->when(in_array($bdt, [BELUM_MENGISI, JUMLAH]), static fn ($q) => $bdt == BELUM_MENGISI ? $q->whereNull('bdt') : $q->whereNotNull('bdt'))
            ->when(in_array($dtsen, [BELUM_MENGISI, JUMLAH]), static fn ($q) => $dtsen == BELUM_MENGISI ? $q->where('terdaftar_dtks', 0) : $q->where('terdaftar_dtks', 1))
            ->when($idCluster, static fn ($q) => $q->whereHas('kepalaKeluarga.keluarga', static fn ($r) => $r->whereIn('id_cluster', $idCluster)));

    }

    private function validasiNoRtm($no_rtm)
    {
        // Hanya izinkan huruf & angka
        if (! preg_match('/^[A-Za-z0-9]+$/', $no_rtm)) {
            redirect_with('error', 'Nomor Rumah Tangga hanya boleh berisi huruf dan angka');
        }

        // Wajib mengandung minimal 1 digit angka
        if (! preg_match('/\d/', $no_rtm)) {
            redirect_with('error', 'Nomor Rumah Tangga harus mengandung angka. Tidak boleh berisi huruf semua.');
        }

        // HARUS diakhiri angka
        if (! preg_match('/\d$/', $no_rtm)) {
            redirect_with('error', 'Nomor Rumah Tangga harus diakhiri dengan angka. Tidak boleh diakhiri huruf.');
        }

        return true;
    }

    private function delete_single_anggota($id): void
    {
        isCan('h');
        $pend = Penduduk::findOrFail($id);

        if ($pend->rtm_level == HubunganRTMEnum::KEPALA_RUMAH_TANGGA) {
            $rtm = RtmModel::where('no_kk', $pend->id_rtm)->first();
            if ($rtm) {
                // Clear the nik_kepala for this RTM, marking it as headless
                $rtm->nik_kepala = null;
                $rtm->save();
            }
            // Also detach the former head from the RTM
            $temp['id_rtm']     = 0;
            $temp['rtm_level']  = 0;
            $temp['updated_at'] = date('Y-m-d H:i:s');
            $pend->update($temp);
        } else {
            // If the resident is not a KEPALA_RUMAH_TANGGA, just detach them from the RTM
            $temp['id_rtm']     = 0;
            $temp['rtm_level']  = 0;
            $temp['updated_at'] = date('Y-m-d H:i:s');
            $pend->update($temp);
        }
    }
}
