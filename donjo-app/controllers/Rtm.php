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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\HubunganRTMEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\SasaranEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusEnum;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\Penduduk;
use App\Models\Rtm as RtmModel;
use App\Models\Wilayah;
use App\Traits\Upload;
use Illuminate\Support\Facades\DB;
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
        $data = [
            'status'          => [StatusEnum::YA => 'Aktif', StatusEnum::TIDAK => 'Tidak Aktif'],
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
            $status    = $this->input->get('status') ?? null;
            $sex       = $this->input->get('jenis_kelamin') ?? null;
            $namaDusun = $this->input->get('dusun') ?? null;
            $rw        = $this->input->get('rw') ?? null;
            $rt        = $this->input->get('rt') ?? null;
            $bdt       = $this->input->get('bdt') ?? null;
            $canDelete = can('h');
            $canUpdate = can('u');
            $idCluster = $rt ? [$rt] : [];

            if (empty($idCluster) && ! empty($rw)) {
                [$namaDusun, $namaRw] = explode('__', $rw);
                $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
            }

            if (empty($idCluster) && ! empty($namaDusun)) {
                $idCluster = Wilayah::whereDusun($namaDusun)->select(['id'])->get()->pluck('id')->toArray();
            }

            return datatables()->of(RtmModel::when($status != null, static function ($q) use ($status) {
                    if ($status == '1') {
                        $q->whereHas('kepalaKeluarga', static fn ($r) => $r->whereStatusDasar($status));
                    } elseif ($status == '0') {
                        $q->whereDoesntHave('kepalaKeluarga')->orWhereHas('kepalaKeluarga', static fn ($r) => $r->where('status_dasar', '!=', 1));
                    }
                })
                ->when($sex, static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($r) => $r->whereSex($sex)))
                ->when(in_array($bdt, [BELUM_MENGISI, JUMLAH]), static fn ($q) => $bdt == BELUM_MENGISI ? $q->whereNull('bdt') : $q->whereNotNull('bdt'))
                ->when($idCluster, static fn ($q) => $q->whereHas('kepalaKeluarga.keluarga', static fn ($r) => $r->whereIn('id_cluster', $idCluster)))
                ->with(['kepalaKeluarga' => static fn ($q) => $q->withOnly(['keluarga'])])->withCount('anggota'))
                ->addColumn('ceklist', static function ($row) use ($canDelete) {
                    if ($canDelete) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })->addColumn('foto', static fn ($row) => '<img class="penduduk_kecil" src="' . AmbilFoto($row->kepalaKeluarga->foto, '', $row->kepalaKeluarga->id_sex) . '" alt="Foto Penduduk" />')->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate, $canDelete): string {
                    $aksi = '<a href="' . ci_route('rtm.anggota', $row->id) . '" class="btn bg-purple btn-sm" title="Rincian Anggota Rumah Tangga"><i class="fa fa-list-ol"></i></a>';

                    if ($canUpdate && $row->kepalaKeluarga->status_dasar == StatusDasarEnum::HIDUP) {
                        $aksi .= ' <a href="' . ci_route('rtm.ajax_add_anggota', $row->id) . '" title="Tambah Anggota Rumah Tangga" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Anggota Rumah Tangga" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>';
                        $aksi .= ' <a href="' . ci_route('rtm.edit_nokk', $row->id) . '" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Rumah Tangga" class="btn bg-orange btn-sm"><i class="fa fa-edit"></i></a>';
                        $aksi .= ' <a href="' . ci_route('penduduk.ajax_penduduk_maps.' . $row->kepalaKeluarga->id, 0) . '" class="btn btn-success btn-sm" title="Lokasi Tempat Tinggal"><i class="fa fa-map-marker"></i></a>';
                    }
                    if ($canDelete) {
                        $aksi .= ' <a href="#" data-href="' . ci_route('rtm.delete', $row->id) . '" class="btn bg-maroon btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                    }
                    if ($row->terdaftar_dtks && can('u', 'dtks')) {
                        $aksi .= ' <a href="' . ci_route('dtks.new', $row->id) . '" onclick="show_confirm(this)" data-remote="false" data-toggle="modal" data-target="#show_confirm_modal" class="btn bg-purple btn-sm" title="DTKS"><i class="fa fa-plus "></i> DTKS</a>';
                    }

                    return $aksi;

                })->editColumn('tgl_daftar', static fn ($q) => tgl_indo($q->tgl_daftar))
                ->editColumn('terdaftar_dtks', static fn ($q) => $q->terdaftar_dtks ? 'Terdaftar' : 'Tidak Terdaftar')
                ->rawColumns(['aksi', 'ceklist', 'foto'])
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
            $data['no_kk']          = bilangan($post['no_kk']);
            $data['bdt']            = empty($post['bdt']) ? null : bilangan($post['bdt']);
            $data['terdaftar_dtks'] = empty($post['terdaftar_dtks']) ? 0 : 1;
            $rtm                    = RtmModel::findOrFail($id);
            if ($data['no_kk']) {
                $adaNoKKLain = RtmModel::where(['no_kk' => $data['no_kk']])->where('id', '!=', $id)->count();
                if ($adaNoKKLain) {
                    redirect_with('error', 'Nomor RTM itu sudah ada. Silakan ganti dengan yang lain.');
                }
                Penduduk::where(['id_rtm' => $rtm->no_kk])->update(['id_rtm' => $data['no_kk']]);
            }
            $rtm->update($data);
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
        $nik  = bilangan($post['nik']);

        try {
            if (empty($post['no_rtm'])) {
                $lastRtm = RtmModel::select(['no_kk'])
                    ->where('config_id', identitas('id'))
                    ->orderBy(DB::raw('length(no_kk)'), 'desc')
                    ->orderBy(DB::raw('no_kk'), 'desc')
                    ->first();

                if ($lastRtm) {
                    $noRtm = $lastRtm->no_kk;
                    if (strlen($noRtm) >= 5) {
                        // Gunakan 5 digit terakhir sebagai nomor urut
                        $kw           = substr($noRtm, 0, strlen($noRtm) - 5);
                        $noUrut       = substr($noRtm, -5);
                        $noUrut       = str_pad($noUrut + 1, 5, '0', STR_PAD_LEFT);
                        $rtm['no_kk'] = $kw . $noUrut;
                    } else {
                        $rtm['no_kk'] = str_pad($noRtm + 1, strlen($noRtm), '0', STR_PAD_LEFT);
                    }
                } else {
                    $kw           = identitas()->kode_desa;
                    $rtm['no_kk'] = $kw . str_pad('1', 5, '0', STR_PAD_LEFT);
                }
            } else {
                $rtm['no_kk'] = $post['no_rtm'];
            }

            $rtm['nik_kepala']     = $nik;
            $rtm['bdt']            = empty($post['bdt']) ? null : bilangan($post['bdt']);
            $rtm['terdaftar_dtks'] = empty($post['terdaftar_dtks']) ? 0 : 1;
            RtmModel::create($rtm);

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
        $data = $this->input->post();

        try {
            $obj = RtmModel::findOrFail($id);
            $obj->update($data);
            redirect_with('success', 'Rumah Tangga berhasil disimpan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Rumah Tangga gagal disimpan');
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

            $penduduk = Penduduk::with('pendudukHubungan')
                ->select(['id', 'nik', 'nama', 'id_cluster', 'kk_level'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->where(static function ($query): void {
                    $query->where('id_rtm', '=', 0)
                        ->orWhere('id_rtm', '=', null);
                })
                ->statusDasar([
                    StatusDasarEnum::HIDUP,
                ])
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->id,
                        'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun') . ' ' . $item->wilayah->dusun . ' - ' . $item->pendudukHubungan->nama),
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
                        $pesan .= "Pesan Gagal : Baris {$nomor_baris} NIK Tidak Boleh Kosong</br>";
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
                        $pesan .= "Pesan Gagal : Baris {$nomor_baris} Data penduduk dengan NIK : {$nik} tidak ditemukan</br>";
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

    public function cetak($aksi = 'cetak', $privasi_nik = 0): void
    {
        if ($privasi_nik == 1) {
            $data['privasi_nik'] = true;
        }
        $data['main'] = RtmModel::with(['kepalaKeluarga' => static fn ($q) => $q->withOnly(['keluarga'])])->withCount('anggota')->get();
        if ($aksi == 'unduh') {
            header('Content-type: application/xls');
            header('Content-Disposition: attachment; filename=rtm_' . date('Y-m-d') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        view('admin.penduduk.rtm.cetak', $data);
    }

    public function ajax_cetak($aksi = ''): void
    {
        $data['aksi']   = $aksi;
        $data['action'] = ci_route('rtm.cetak.' . $aksi);

        view('admin.dpt.ajax_cetak_bersama', $data);
    }

    public function anggota($id = 0): void
    {
        $data['kk']        = $id;
        $rtm               = RtmModel::with(['kepalaKeluarga', 'anggota' => static fn ($q) => $q->orderBy('rtm_level')])->findOrFail($id);
        $data['main']      = $rtm->anggota->toArray();
        $data['kepala_kk'] = array_merge(['bdt' => $rtm->bdt, 'no_kk' => $rtm->no_kk, 'jumlah_kk' => $rtm->jumlah_kk], $rtm->kepalaKeluarga->toArray());
        $data['program']   = ['programkerja' => BantuanPeserta::with(['bantuan'])->whereHas('bantuan', static fn ($q) => $q->whereSasaran(SasaranEnum::RUMAH_TANGGA))->wherePeserta($rtm->no_kk)->get()->toArray()];

        view('admin.penduduk.rtm.anggota', $data);
    }

    public function ajax_add_anggota($id = 0): void
    {
        isCan('u');

        $data['form_action'] = ci_route($this->controller . '.add_anggota', $id);

        view('admin.penduduk.rtm.ajax_add_anggota_rtm_form', $data);
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
        $this->delete_single_anggota($id);
        redirect_with('success', 'Anggota berhasil dihapus', ci_route($this->controller . '.anggota', $kk));
    }

    private function delete_single_anggota($id): void
    {
        isCan('h');
        $pend = Penduduk::findOrFail($id);

        if ($pend->rtm_level == HubunganRTMEnum::KEPALA_RUMAH_TANGGA) {
            RtmModel::where('id', $pend->id_rtm)->update(['nik_kepala' => 0]);
        }
        $temp['id_rtm']     = 0;
        $temp['rtm_level']  = 0;
        $temp['updated_at'] = date('Y-m-d H:i:s');
        $pend->update($temp);
    }

    public function delete_all_anggota($kk = 0): void
    {
        isCan('h');
        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete_single_anggota($id);
        }
        redirect_with('success', 'Anggota berhasil dihapus', ci_route($this->controller . '.anggota', $kk));
    }

    public function statistik($tipe = '0', $nomor = 0, $sex = null): void
    {
        switch ($tipe) {
            case 'bdt':
                $kategori = 'KLASIFIKASI BDT :';
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
        $this->filterColumn = ['sex' => $sex, 'status' => $nomor];
        $this->index();
    }
}
