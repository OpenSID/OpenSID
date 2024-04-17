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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\JenisKelaminEnum;
use App\Enums\ListSasaranEnum;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Suplemen as ModelsSuplemen;
use App\Models\SuplemenTerdata;
use App\Models\Wilayah;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Reader\Common\Creator\ReaderEntityFactory;
use OpenSpout\Writer\Common\Creator\Style\BorderBuilder;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

defined('BASEPATH') || exit('No direct script access allowed');

class Suplemen extends Admin_Controller
{
    public $modul_ini     = 'kependudukan';
    public $sub_modul_ini = 'data-suplemen';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['pamong_model']);
    }

    public function index()
    {
        $list_sasaran = unserialize(SASARAN);

        return view('admin.suplemen.index', ['list_sasaran' => $list_sasaran]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $sasaran = $this->input->get('sasaran');

            return datatables()->of(ModelsSuplemen::with('terdata')
                ->filter($sasaran))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi     = '';
                    $disabled = $row->terdata()->count() > 0 ? 'disabled' : 'data-target="#confirm-delete"';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('suplemen.rincian', $row->id) . '" class="btn bg-purple btn-sm" title="Rincian Data"><i class="fa fa-list-ol"></i></a> ';
                        $aksi .= '<a href="' . ci_route('suplemen.impor_data', $row->id) . '" class="btn bg-navy btn-sm btn-import" title="Impor Data"><i class="fa fa-upload"></i></a> ';
                        $aksi .= '<a href="' . ci_route('suplemen.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Tanggapi Pengaduan"><i class="fa fa-pencil"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('suplemen.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal"' . $disabled . '><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('terdata', static fn ($row) => $row->terdata()->count())
                ->editColumn('sasaran', static fn ($row) => unserialize(SASARAN)[$row->sasaran])
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        isCan('u');

        if ($id) {
            $action      = 'Ubah';
            $form_action = ci_route('suplemen.update', $id);
            $suplemen    = ModelsSuplemen::with('terdata')->findOrFail($id);
        } else {
            $action      = 'Tambah';
            $form_action = ci_route('suplemen.create');
            $suplemen    = null;
        }

        $list_sasaran = unserialize(SASARAN);

        return view('admin.suplemen.form', ['action' => $action, 'form_action' => $form_action, 'suplemen' => $suplemen, 'list_sasaran' => $list_sasaran]);
    }

    public function create(): void
    {
        isCan('u');

        try {
            ModelsSuplemen::create(static::validated($this->request));
            redirect_with('success', 'Berhasil Tambah Data');
        } catch (Exception $e) {
            redirect_with('error', 'Gagal Tambah Data ' . $e->getMessage());
        }
    }

    public function update($id = ''): void
    {
        isCan('u');

        $update = ModelsSuplemen::findOrFail($id);

        try {
            $data = static::validated($this->request);
            $data['sasaran'] ??= $update->sasaran;
            $update->update($data);
            redirect_with('success', 'Berhasil Ubah Data');
        } catch (Exception $e) {
            redirect_with('error', 'Gagal Ubah Data ' . $e->getMessage());
        }
    }

    public function delete($id): void
    {
        isCan('h');

        $suplemen = ModelsSuplemen::findOrFail($id);
        if ($suplemen->terdata()->count() > 0) {
            redirect_with('error', 'Gagal Hapus Data');
        }

        if ($suplemen->destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    protected static function validated($request = [])
    {
        return [
            'sasaran'    => $request['sasaran'],
            'nama'       => nomor_surat_keputusan($request['nama']),
            'keterangan' => strip_tags($request['keterangan']),
        ];
    }

    public function rincian($id)
    {
        $sasaran  = unserialize(SASARAN);
        $suplemen = ModelsSuplemen::findOrFail($id);
        $dusun    = Wilayah::dusun()->pluck('dusun');

        return view('admin.suplemen.detail', ['sasaran' => $sasaran, 'suplemen' => $suplemen, 'dusun' => $dusun]);
    }

    public function datatables_terdata()
    {
        if ($this->input->is_ajax_request()) {
            $id      = $this->input->get('id');
            $sasaran = $this->input->get('sasaran');
            $filters = [
                'sex'   => $this->input->get('sex'),
                'dusun' => $this->input->get('dusun'),
                'rw'    => $this->input->get('rw'),
                'rt'    => $this->input->get('rt'),
            ];

            return datatables()->of(SuplemenTerdata::anggota($sasaran, $id)->filter($filters))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("suplemen/form_terdata/{$row->id_suplemen}/0/{$row->id_terdata}") . '" class="btn btn-warning btn-sm"  title="Tanggapi Pengaduan"><i class="fa fa-pencil"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('suplemen.delete_terdata', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('tanggallahir', static fn ($row) => tgl_indo($row->tanggallahir))
                ->editColumn('sex', static fn ($row) => JenisKelaminEnum::valueOf($row->sex))
                ->editColumn('alamat', static fn ($row): string => 'RT/RW ' . $row->rt . '/' . $row->rw . ' - ' . strtoupper($row->dusun))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form_terdata($id_suplemen, $aksi = 1, $id = '')
    {
        isCan('u');

        $suplemen      = ModelsSuplemen::findOrFail($id_suplemen);
        $sasaran       = unserialize(SASARAN);
        $judul_sasaran = ListSasaranEnum::valueOf($suplemen->sasaran);
        $individu      = isset($_POST['id_terdata']) ? Penduduk::findOrFail($_POST['id_terdata']) : null;

        if ($id) {
            $action      = 'Ubah';
            $form_action = ci_route('suplemen.update_terdata', $id);
            $terdata     = SuplemenTerdata::anggota($suplemen->sasaran, $suplemen->id)->where('id_terdata', $id)->first();
        } else {
            $action      = 'Tambah';
            $form_action = ci_route('suplemen.create_terdata', $aksi);
            $terdata     = null;
        }

        return view('admin.suplemen.form_terdata', ['action' => $action, 'form_action' => $form_action, 'suplemen' => $suplemen, 'terdata' => $terdata, 'sasaran' => $sasaran, 'judul_sasaran' => $judul_sasaran, 'individu' => $individu]);
    }

    public function create_terdata($aksi): void
    {
        isCan('u');
        if (SuplemenTerdata::create(static::validated_terdata($this->request))) {
            if ($aksi == 2) {
                redirect_with('success', 'Berhasil Tambah Data', 'suplemen/form_terdata/' . $this->request['id_suplemen'] . '/2');
            }
            redirect_with('success', 'Berhasil Tambah Data', 'suplemen/rincian/' . $this->request['id_suplemen']);
        }

        redirect_with('error', 'Gagal Tambah Data', 'suplemen/rincian/' . $this->request['id_suplemen']);
    }

    public function update_terdata($id = ''): void
    {
        isCan('u');

        $update = SuplemenTerdata::where('id_suplemen', $this->request['id_suplemen'])->where('id_terdata', $id)->first();

        if ($update->update(['keterangan' => substr(htmlentities($this->request['keterangan']), 0, 100)])) {
            redirect_with('success', 'Berhasil Ubah Data', 'suplemen/rincian/' . $this->request['id_suplemen']);
        }

        redirect_with('error', 'Gagal Ubah Data', 'suplemen/rincian/' . $this->request['id_suplemen']);
    }

    public function delete_terdata($id): void
    {
        isCan('h');

        $id_suplemen = substr($_SERVER['HTTP_REFERER'], -1);

        if (SuplemenTerdata::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data', 'suplemen/rincian/' . $id_suplemen);
        }

        redirect_with('error', 'Gagal Hapus Data', 'suplemen/rincian/' . $id_suplemen);
    }

    public function delete_all_terdata(): void
    {
        isCan('h');

        $id_suplemen = substr($_SERVER['HTTP_REFERER'], -1);

        if (SuplemenTerdata::destroy($this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data', 'suplemen/rincian/' . $id_suplemen);
        }

        redirect_with('error', 'Gagal Hapus Data', 'suplemen/rincian/' . $id_suplemen);
    }

    protected static function validated_terdata($request = [])
    {
        return [
            'id_suplemen' => $request['id_suplemen'],
            'id_terdata'  => $request['id_terdata'],
            'sasaran'     => $request['sasaran'],
            'keterangan'  => substr(htmlentities($request['keterangan']), 0, 100),
        ];
    }

    public function apipenduduksuplemen()
    {
        if ($this->input->is_ajax_request()) {
            $cari     = $this->input->get('q');
            $suplemen = $this->input->get('suplemen');
            $sasaran  = $this->input->get('sasaran');
            $terdata  = SuplemenTerdata::where('id_suplemen', $suplemen)->pluck('id_terdata');

            switch ($sasaran) {
                case 1:
                    $this->get_pilihan_penduduk($cari, $terdata);
                    break;

                case 2:
                    $this->get_pilihan_kk($cari, $terdata);
                    break;

                default:
            }
        }

        return show_404();
    }

    private function get_pilihan_penduduk($cari, $terdata)
    {
        $penduduk = Penduduk::select(['id', 'nik', 'nama', 'id_cluster', 'kk_level'])
            ->when($cari, static function ($query) use ($cari): void {
                $query->orWhere('nik', 'like', "%{$cari}%")
                    ->orWhere('nama', 'like', "%{$cari}%");
            })
            ->whereNotIn('id', $terdata)
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static fn ($item): array => [
                    'id'   => $item->id,
                    'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                ]),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    private function get_pilihan_kk($cari, $terdata)
    {
        $penduduk = Penduduk::with('pendudukHubungan')
            ->select(['tweb_penduduk.id', 'tweb_penduduk.nik', 'keluarga_aktif.no_kk', 'tweb_penduduk.kk_level', 'tweb_penduduk.nama', 'tweb_penduduk.id_cluster'])
            ->leftJoin('tweb_penduduk_hubungan', static function ($join): void {
                $join->on('tweb_penduduk.kk_level', '=', 'tweb_penduduk_hubungan.id');
            })
            ->leftJoin('keluarga_aktif', static function ($join): void {
                $join->on('tweb_penduduk.id_kk', '=', 'keluarga_aktif.id');
            })
            ->when($cari, static function ($query) use ($cari): void {
                $query->where(static function ($q) use ($cari): void {
                    $q->where('tweb_penduduk.nik', 'like', "%{$cari}%")
                        ->orWhere('keluarga_aktif.no_kk', 'like', "%{$cari}%")
                        ->orWhere('tweb_penduduk.nama', 'like', "%{$cari}%");
                });
            })
            ->whereIn('tweb_penduduk.kk_level', ['1'])
            // ->whereIn('tweb_penduduk.kk_level', ['1', '2', '3', '4'])
            ->whereNotIn('tweb_penduduk.id_kk', $terdata)
            ->orderBy('tweb_penduduk.id_kk')
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static fn ($item): array => [
                    'id'   => $item->id,
                    'text' => 'No KK : ' . $item->no_kk . ' - ' . $item->pendudukHubungan->nama . '- NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                ]),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    // $aksi = cetak/unduh
    public function dialog_daftar($id = 0, $aksi = '')
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = $aksi;
        $data['form_action'] = site_url("{$this->controller}/daftar/{$id}/{$aksi}");

        return view('admin.layouts.components.ttd_pamong', $data);
    }

    // $aksi = cetak/unduh
    public function daftar($id = 0, $aksi = '')
    {
        if ($id > 0) {
            $data['suplemen']       = ModelsSuplemen::findOrFail($id)->toArray();
            $data['terdata']        = SuplemenTerdata::anggota($data['suplemen']['sasaran'], $data['suplemen']['id'])->get()->toArray();
            $data['sasaran']        = unserialize(SASARAN);
            $data['config']         = $this->header['desa'];
            $data['pamong_ttd']     = $this->pamong_model->get_data($this->request['pamong_ttd']);
            $data['pamong_ketahui'] = $this->pamong_model->get_data($this->request['pamong_ketahui']);
            $data['aksi']           = $aksi;

            //pengaturan data untuk format cetak/ unduh
            $data['file']      = 'Laporan Suplemen ' . $data['suplemen']['nama'];
            $data['isi']       = 'admin.suplemen.cetak';
            $data['letak_ttd'] = ['2', '2', '3'];

            return view('admin.layouts.components.format_cetak', $data);
        }
    }

    public function impor_data($id)
    {
        $suplemen    = ModelsSuplemen::findOrFail($id);
        $form_action = ci_route('suplemen.impor');

        return view('admin.suplemen.impor', ['suplemen' => $suplemen, 'form_action' => $form_action]);
    }

    public function impor()
    {
        isCan('u');
        $suplemen_id = $this->input->post('id_suplemen');

        $config = [
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'xls|xlsx|xlsm',
        ];

        $this->load->library('MY_Upload', null, 'upload');
        $this->upload->initialize($config);

        if (! $this->upload->do_upload('userfile')) {
            return session_error($this->upload->display_errors(null, null));
        }

        $upload = $this->upload->data();

        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($upload['full_path']);

        $data_peserta      = [];
        $terdaftar_peserta = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            $baris_pertama = true;
            $no_baris      = 0;
            $no_gagal      = 0;
            $no_sukses     = 0;
            $pesan         = '';

            $field = ['id', 'nama', 'sasaran', 'keterangan'];

            // Sheet Program
            if ($sheet->getName() == 'Peserta') {
                $suplemen_record = $this->get_suplemen($suplemen_id);
                $sasaran         = $suplemen_record['sasaran'];
                $ambil_peserta   = SuplemenTerdata::where('id_suplemen', $suplemen_id)->pluck('id_terdata');

                if ($sasaran == '1') {
                    $terdaftar_peserta = Penduduk::whereIn('id', $ambil_peserta)->pluck('nik')->toArray();
                } elseif ($sasaran == '2') {
                    $terdaftar_peserta = Keluarga::whereIn('id', $ambil_peserta)->pluck('no_kk')->toArray();
                }

                foreach ($sheet->getRowIterator() as $row) {
                    $cells   = $row->getCells();
                    $peserta = trim((string) $cells[0]); // NIK atau No_kk sesuai sasaran

                    // Data terakhir
                    if ($peserta == '###') {
                        break;
                    }

                    // Abaikan baris pertama / judul
                    if ($baris_pertama) {
                        $baris_pertama = false;

                        continue;
                    }

                    $no_baris++;

                    // Cek valid data peserta sesuai sasaran
                    $cek_peserta = $this->cek_peserta($peserta, $sasaran);
                    if (! in_array($peserta, $cek_peserta['valid'])) {
                        $no_gagal++;
                        $pesan .= '- Data peserta baris <b> Ke-' . ($no_baris) . ' / ' . $cek_peserta['sasaran_peserta'] . ' = ' . $peserta . '</b> tidak ditemukan <br>';

                        continue;
                    }
                    $penduduk_sasaran = $this->cek_penduduk($sasaran, $peserta);
                    if (! $penduduk_sasaran['id_terdata']) {
                        $no_gagal++;
                        $pesan .= '- Data peserta baris <b> Ke-' . ($no_baris) . ' / ' . $penduduk_sasaran['id_sasaran'] . ' = ' . $peserta . '</b> yang terdaftar tidak ditemukan <br>';

                        continue;
                    }
                    $id_terdata = $penduduk_sasaran['id_terdata'];

                    // Cek data peserta yg akan dimpor dan yg sudah ada
                    if (in_array($peserta, $terdaftar_peserta)) {
                        $no_gagal++;
                        $pesan .= '- Data peserta baris <b> Ke-' . ($no_baris) . '</b> sudah ada <br>';

                        continue;
                    }

                    $terdaftar_peserta[] = $peserta;

                    // Simpan data peserta yg diimpor dalam bentuk array
                    $simpan = [
                        'config_id'   => identitas('id'),
                        'id_suplemen' => $suplemen_id,
                        'id_terdata'  => $id_terdata,
                        'sasaran'     => $sasaran, // Duplikasi
                        'keterangan'  => (string) $cells[1],
                    ];

                    $data_peserta[] = $simpan;
                    $no_sukses++;
                }

                // Proses impor peserta
                if ($no_baris <= 0) {
                    $pesan .= '- Data peserta tidak tersedia<br>';
                } else {
                    $this->impor_peserta($data_peserta);
                }
            }
        }

        $reader->close();

        $notif = [
            'gagal'  => $no_gagal,
            'sukses' => $no_sukses,
            'pesan'  => $pesan,
        ];

        set_session('notif', $notif);

        redirect("{$this->controller}/impor_data/{$suplemen_id}");
    }

    public function get_suplemen($id)
    {
        return ModelsSuplemen::withCount('terdata as jml')
            ->find($id)
            ->toArray();
    }

    private function cek_peserta(string $peserta = '', $sasaran = 1)
    {
        if (in_array($peserta, [null, '-', ' ', '0'])) {
            return false;
        }

        switch ($sasaran) {
            case 1:
                // Penduduk
                $sasaran_peserta = 'NIK';

                $data = Penduduk::select(['id', 'nik as no'])->where('nik', $peserta)->get()->toArray();
                break;

            case 2:
                // Keluarga
                $sasaran_peserta = 'No. KK';

                $data = Keluarga::select(['id', 'no_kk as no'])->where('no_kk', $peserta)->get()->toArray();
                break;

            default:
                // Lainnya
                break;
        }

        return [
            'id'              => $data[0]['id'], // untuk nik, no_kk, no_rtm, kode konversi menjadi id issue #3417
            'sasaran_peserta' => $sasaran_peserta,
            'valid'           => array_column($data, 'no'), // untuk daftar valid anggota keluarga
        ];
    }

    private function cek_penduduk($sasaran, string $peserta)
    {
        $terdata = [];
        if ($sasaran == '1') {
            $terdata['id_sasaran'] = 'NIK';
            $cek_penduduk          = Penduduk::where('nik', $peserta)->first()->toArray();
            if ($cek_penduduk['id']) {
                $terdata['id_terdata'] = $cek_penduduk['id'];
            }
        } elseif ($sasaran == '2') {
            $terdata['id_sasaran'] = 'KK';
            $keluarga              = Keluarga::with('kepalaKeluarga')->where('no_kk', $peserta)->first();
            $kepala_kk             = $keluarga->kepalaKeluarga->toArray();
            if ($kepala_kk['nik']) {
                $terdata['id_terdata'] = $kepala_kk['id_kk'];
            }
        }

        return $terdata;
    }

    private function impor_peserta(array $data_peserta = []): void
    {
        $this->session->success = 1;

        if ($data_peserta) {
            $outp = SuplemenTerdata::insert($data_peserta);
        }

        status_sukses($outp, true);
    }

    public function ekspor($id = 0): void
    {
        $data_suplemen['suplemen'] = ModelsSuplemen::findOrFail($id)->toArray();
        $data_suplemen['terdata']  = SuplemenTerdata::anggota($data_suplemen['suplemen']['sasaran'], $id)->get()->toArray();

        $writer    = WriterEntityFactory::createXLSXWriter();
        $file_name = namafile($data_suplemen['suplemen']['nama']) . '.xlsx';
        $writer->openToBrowser($file_name);

        // Ubah Nama Sheet
        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Peserta');

        // Deklarasi Style
        $border = (new BorderBuilder())
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->build();

        $borderStyle = (new StyleBuilder())
            ->setBorder($border)
            ->build();

        $yellowBackgroundStyle = (new StyleBuilder())
            ->setBackgroundColor(Color::YELLOW)
            ->setFontBold()
            ->setBorder($border)
            ->build();

        $greenBackgroundStyle = (new StyleBuilder())
            ->setBackgroundColor(Color::LIGHT_GREEN)
            ->build();

        // Cetak Header Tabel
        $values        = ['Peserta', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'Keterangan'];
        $rowFromValues = WriterEntityFactory::createRowFromArray($values, $yellowBackgroundStyle);
        $writer->addRow($rowFromValues);

        // Cetak Data Anggota Suplemen
        $data_anggota = $data_suplemen['terdata'];

        foreach ($data_anggota as $data) {
            $cells = [
                WriterEntityFactory::createCell($data['nik']),
                WriterEntityFactory::createCell(strtoupper($data['nama'])),
                WriterEntityFactory::createCell($data['tempatlahir']),
                WriterEntityFactory::createCell(tgl_indo_out($data['tanggallahir'])),
                WriterEntityFactory::createCell(strtoupper($data['alamat'] . ' RT ' . $data['rt'] . ' / RW ' . $data['rw'] . ' ' . $this->setting->sebutan_dusun . ' ' . $data['dusun'])),
                WriterEntityFactory::createCell(empty($data['keterangan']) ? '-' : $data['keterangan']),
            ];

            $singleRow = WriterEntityFactory::createRow($cells);
            $singleRow->setStyle($borderStyle);
            $writer->addRow($singleRow);
        }

        $cells = [
            '###', '', '', '', '', '',
        ];
        $singleRow = WriterEntityFactory::createRowFromArray($cells);
        $writer->addRow($singleRow);

        // Cetak Catatan
        $array_catatan = [
            [
                'Catatan:', '', '', '', '', '',
            ],
            [
                '1. Sesuaikan kolom peserta (A) berdasarkan sasaran : - penduduk = nik, - keluarga = no. kk', '', '', '', '', '',
            ],
            [
                '2. Kolom Peserta (A)  wajib di isi', '', '', '', '', '',
            ],
            [
                '3. Kolom (B, C, D, E) diambil dari database kependudukan', '', '', '', '', '',
            ],
            [
                '4. Kolom (F) opsional', '', '', '', '', '',
            ],
        ];

        $rows_catatan = [];

        foreach ($array_catatan as $catatan) {
            $rows_catatan[] = WriterEntityFactory::createRowFromArray($catatan, $greenBackgroundStyle);
        }
        $writer->addRows($rows_catatan);

        $writer->close();
    }
}
