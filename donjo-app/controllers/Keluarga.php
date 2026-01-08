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

use App\Enums\AgamaEnum;
use App\Enums\AsuransiEnum;
use App\Enums\BahasaEnum;
use App\Enums\CacatEnum;
use App\Enums\CaraKBEnum;
use App\Enums\GolonganDarahEnum;
use App\Enums\HamilEnum;
use App\Enums\HubunganRTMEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PekerjaanEnum;
use App\Enums\PendidikanKKEnum;
use App\Enums\PendidikanSedangEnum;
use App\Enums\PeristiwaPendudukEnum;
use App\Enums\SakitMenahunEnum;
use App\Enums\SasaranEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusDasarKKEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusKTPEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\StatusRekamEnum;
use App\Enums\SukuEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\Bantuan;
use App\Models\KelasSosial;
use App\Models\Keluarga as KeluargaModel;
use App\Models\Penduduk;
use App\Models\PendudukHidup;
use App\Models\Rtm;
use App\Models\Wilayah;
use App\Traits\GenerateRtf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Keluarga extends Admin_Controller
{
    use GenerateRtf;

    public $modul_ini           = 'kependudukan';
    public $sub_modul_ini       = 'keluarga';
    public $kategori_pengaturan = 'Data Lengkap';
    private $judulStatistik;
    private $filterColumn    = [];
    private $defaultStatus   = StatusDasarKKEnum::AKTIF;
    private $statistikFilter = [];

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
            'status'          => StatusDasarKKEnum::all(),
            'jenis_kelamin'   => JenisKelaminEnum::all(),
            'disableFilter'   => false,
            'wilayah'         => Wilayah::treeAccess(),
            'judul_statistik' => $this->input->get('judul_statistik') ?? $this->judulStatistik,
            'filterColumn'    => $this->filterColumn,
            'statistikFilter' => $this->statistikFilter,
            'defaultStatus'   => $this->filterColumn['status'] === 'all' ? null : $this->defaultStatus,
        ];

        view('admin.penduduk.keluarga.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $canDelete = can('h');
            $canUpdate = can('u');

            return datatables()->of($this->sumberData())
                ->orderColumn(
                    'kepala_keluarga.nama',
                    static fn ($query, $order) => $query->orderBy('kepala_keluarga.nama', $order)
                )
                ->addColumn('ceklist', static fn ($row) => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>')->addColumn('valid_kk', static function ($row) {
                    $result = '';
                    if (strlen($row->no_kk) < 16) {
                        $result = 'warning';
                    } elseif ( get_nik($row->no_kk) == 0) {
                        $result = 'danger';
                    }

                    return $result;
                })
                ->addColumn('foto', static fn ($row) => '<img class="penduduk_kecil" src="' . AmbilFoto($row->kepalaKeluarga->foto, '', $row->kepalaKeluarga->sex) . '" alt="Foto Penduduk" />')->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate, $canDelete): string {
                    $canDelete = $canDelete && $row->bolehHapus();

                    $list = [
                        // Rincian Anggota Keluarga
                        [
                            'url'    => "keluarga/anggota/{$row->id}",
                            'icon'   => 'fa fa-list-ol',
                            'judul'  => 'Rincian Anggota Keluarga (KK)',
                            'target' => false,
                            'modal'  => false,
                            'can'    => true,
                        ],
                        // Tambah Anggota Keluarga Lahir
                        [
                            'url'    => "keluarga/form_peristiwa/1/{$row->id}",
                            'icon'   => 'fa fa-plus',
                            'judul'  => 'Anggota Keluarga Lahir',
                            'target' => false,
                            'modal'  => false,
                            'can'    => $canUpdate && $row->kepalaKeluarga->status_dasar == StatusDasarEnum::HIDUP,
                        ],
                        // Tambah Anggota Keluarga Masuk
                        [
                            'url'    => "keluarga/form_peristiwa/5/{$row->id}",
                            'icon'   => 'fa fa-plus',
                            'judul'  => 'Anggota Keluarga Masuk',
                            'target' => false,
                            'modal'  => false,
                            'can'    => $canUpdate && $row->kepalaKeluarga->status_dasar == StatusDasarEnum::HIDUP,
                        ],
                        // Tambah Dari Penduduk Yang Sudah Ada
                        [
                            'url'    => "keluarga/ajax_add_anggota/{$row->id}",
                            'icon'   => 'fa fa-plus',
                            'judul'  => 'Dari Penduduk Sudah Ada',
                            'target' => false,
                            'modal'  => true,
                            'can'    => $canUpdate && $row->kepalaKeluarga->status_dasar == StatusDasarEnum::HIDUP,
                            'data'   => [
                                'data-remote' => 'false',
                                'data-toggle' => 'modal',
                                'data-target' => '#modalBox',
                                'data-title'  => 'Tambah Anggota Keluarga',
                            ],
                        ],
                        // Edit Data KK untuk HIDUP
                        [
                            'url'    => "keluarga/edit_nokk/{$row->id}",
                            'icon'   => 'fa fa-edit',
                            'judul'  => 'Ubah Data',
                            'target' => false,
                            'modal'  => true,
                            'can'    => $canUpdate && $row->kepalaKeluarga->status_dasar == StatusDasarEnum::HIDUP,
                            'data'   => [
                                'data-remote' => 'false',
                                'data-toggle' => 'modal',
                                'data-target' => '#modalBox',
                                'data-title'  => 'Ubah Data KK',
                            ],
                        ],
                        // Lokasi Tempat Tinggal untuk HIDUP
                        [
                            'url'    => "penduduk/ajax_penduduk_maps/{$row->kepalaKeluarga->id}/0",
                            'icon'   => 'fa fa-map-marker',
                            'judul'  => 'Lokasi Tempat Tinggal',
                            'target' => false,
                            'modal'  => false,
                            'can'    => $canUpdate && $row->kepalaKeluarga->status_dasar == StatusDasarEnum::HIDUP,
                        ],
                        // Pecah semua anggota untuk TIDAK HIDUP yang memiliki anggota
                        [
                            'url'    => "keluarga/form_pecah_semua/{$row->id}",
                            'icon'   => 'fa fa-cut',
                            'judul'  => 'Pecah menjadi keluarga baru',
                            'target' => false,
                            'modal'  => true,
                            'can'    => $canUpdate && $row->kepalaKeluarga->status_dasar != StatusDasarEnum::HIDUP && $row->anggota->count() > 0,
                            'data'   => [
                                'data-remote' => 'false',
                                'data-toggle' => 'modal',
                                'data-target' => '#modalBox',
                                'data-title'  => 'Pecah menjadi keluarga baru',
                            ],
                        ],
                        // Lihat Data untuk TIDAK HIDUP
                        [
                            'url'    => "keluarga/edit_nokk/{$row->id}",
                            'icon'   => 'fa fa-eye',
                            'judul'  => 'Lihat Data',
                            'target' => false,
                            'modal'  => true,
                            'can'    => $canUpdate && $row->kepalaKeluarga->status_dasar != StatusDasarEnum::HIDUP && $row->kepalaKeluarga,
                            'data'   => [
                                'data-remote' => 'false',
                                'data-toggle' => 'modal',
                                'data-target' => '#modalBox',
                                'data-title'  => 'Data KK',
                            ],
                        ],
                        // Hapus
                        [
                            'url'    => '#',
                            'icon'   => 'fa fa-trash-o',
                            'judul'  => 'Hapus/Keluar Dari Daftar Keluarga',
                            'target' => false,
                            'modal'  => true,
                            'can'    => $canDelete,
                            'data'   => [
                                'data-href'   => "keluarga/delete/{$row->id}",
                                'data-toggle' => 'modal',
                                'data-target' => '#confirm-delete',
                            ],
                        ],
                    ];

                    return View::make('admin.layouts.components.buttons.split', [
                        'type'  => 'btn-info',
                        'icon'  => 'fa fa-arrow-circle-down',
                        'judul' => 'Pilih Aksi',
                        'list'  => $list,
                    ])->render();
                })->editColumn('tgl_daftar', static fn ($q) => tgl_indo($q->tgl_daftar))
                ->editColumn('tgl_cetak_kk', static fn ($q) => tgl_indo($q->tgl_cetak_kk))
                ->addColumn('jenis_kelamin', static fn ($q) => JenisKelaminEnum::valueOf($q->kepalaKeluarga->sex))
                ->rawColumns(['aksi', 'ceklist', 'foto'])
                ->make();
        }

        return show_404();
    }

    public function cetak($aksi = '', $privasi_kk = 0): void
    {
        $query = datatables($this->sumberData())
            ->filter(function ($query) {
                $query->when($this->input->post('id_cb'), static function ($query, $ids) {
                    $query->whereIn('id', json_decode($ids));
                });
            });

        $data = [
            'main'  => $query->prepareQuery()->results(),
            'start' => app('datatables.request')->start(),
            'aksi'  => 'cetak',
        ];

        if ($privasi_kk == 1) {
            $data['privasi_kk'] = true;
        }
        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=keluarga_' . date('Y-m-d') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        view('admin.penduduk.keluarga.cetak', $data);
    }

    // Masukkan KK baru
    public function form(): void
    {
        isCan('u');
        $data['kk_baru']            = true;
        $data['kk']                 = null;
        $data['form_action']        = ci_route('keluarga.insert_new');
        $data['penduduk_lepas']     = PendudukHidup::lepas(true)->get();
        $data['wilayah']            = Wilayah::treeAccess();
        $data['agama']              = AgamaEnum::all();
        $data['pendidikan_sedang']  = PendidikanSedangEnum::all();
        $data['pendidikan_kk']      = PendidikanKKEnum::all();
        $data['pekerjaan']          = PekerjaanEnum::all();
        $data['warganegara']        = WargaNegaraEnum::all();
        $data['hubungan']           = [SHDKEnum::KEPALA_KELUARGA => SHDKEnum::valueOf(SHDKEnum::KEPALA_KELUARGA)];
        $data['kawin']              = StatusKawinEnum::all();
        $data['golongan_darah']     = GolonganDarahEnum::all();
        $data['bahasa']             = BahasaEnum::all();
        $data['cacat']              = CacatEnum::all();
        $data['sakit_menahun']      = SakitMenahunEnum::all();
        $data['cara_kb']            = CaraKBEnum::all();
        $data['ktp_el']             = StatusRekamEnum::all();
        $data['status_rekam']       = StatusKTPEnum::all();
        $data['tempat_dilahirkan']  = array_flip(unserialize(TEMPAT_DILAHIRKAN));
        $data['jenis_kelahiran']    = array_flip(unserialize(JENIS_KELAHIRAN));
        $data['penolong_kelahiran'] = array_flip(unserialize(PENOLONG_KELAHIRAN));
        $data['pilihan_asuransi']   = AsuransiEnum::all();
        $data['kehamilan']          = HamilEnum::all();
        $data['suku']               = SukuEnum::all();
        $data['suku_penduduk']      = Penduduk::distinct()->select('suku')->whereNotNull('suku')->whereRaw('LENGTH(suku) > 0')->pluck('suku', 'suku');
        $data['nik_sementara']      = Penduduk::nikSementara();
        $data['cek_nik']            = 1;
        $data['cek_nokk']           = 1;
        $data['nokk_sementara']     = KeluargaModel::formatNomerKKSementara();
        $data['status_penduduk']    = [StatusPendudukEnum::TETAP => StatusPendudukEnum::valueOf(StatusPendudukEnum::TETAP)];
        $data['jenis_peristiwa']    = PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value;
        $data['controller']         = 'keluarga';
        $originalInput              = session('old_input');
        if ($originalInput) {
            $data['penduduk'] = $originalInput;
            if (isset($originalInput['id_cluster'])) {
                $wilayah                     = Wilayah::find((int) ($originalInput['id_cluster']));
                $data['penduduk']['wilayah'] = ['dusun' => $wilayah->dusun, 'rw' => $wilayah->rw, 'rt' => $wilayah->rt];
            }
            $data['penduduk']['id_sex'] = $originalInput['sex'];
            $data['no_kk']              = $originalInput['no_kk'];

        }
        view('admin.penduduk.keluarga.form', $data);
    }

    public function edit_nokk($id = 0): void
    {
        isCan('u');
        $keluarga                   = KeluargaModel::with(['kepalaKeluarga'])->findOrFail($id);
        $data['kk']                 = $keluarga;
        $data['wilayah']            = Wilayah::treeAccess();
        $data['keluarga_sejahtera'] = KelasSosial::get();
        $data['cek_nokk']           = get_nokk($keluarga->no_kk);
        $data['nokk_sementara']     = KeluargaModel::formatNomerKKSementara();
        $data['form_action']        = ci_route('keluarga.update_nokk', $id);

        view('admin.penduduk.keluarga.modal.ajax_edit_nokk', $data);
    }

    // Tambah KK dari penduduk yg ada
    public function add_exist($id = 0): void
    {
        isCan('u');
        $data['penduduk']       = PendudukHidup::lepas()->get();
        $data['nokk_sementara'] = KeluargaModel::formatNomerKKSementara();
        $data['form_action']    = ci_route("keluarga.insert.{$id}");
        view('admin.penduduk.keluarga.modal.ajax_add_keluarga', $data);
    }

    public function pindah_kolektif(): void
    {
        isCan('u');
        $data['wilayah']     = Wilayah::treeAccess();
        $data['form_action'] = ci_route('keluarga.proses_pindah');

        view('admin.penduduk.keluarga.modal.ajax_pindah_wilayah', $data);
    }

    public function proses_pindah(): void
    {
        isCan('u');
        $data['id_kk']      = $this->request['id_cb'];
        $data['id_cluster'] = $this->request['id_cluster'];

        try {
            KeluargaModel::whereIn('id', $data['id_kk'])->update(['id_cluster' => $data['id_cluster']]);
            Penduduk::whereIn('id_kk', $data['id_kk'])->update(['id_cluster' => $data['id_cluster']]);
            redirect_with('success', 'Pindah kolektif berhasil disimpan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('success', 'Pindah kolektif gagal disimpan');
        }
    }

    // Tambah KK dengan memilih dari penduduk yg sudah ada
    public function insert(): void
    {
        isCan('u');
        $data = $this->input->post();

        $valid = KeluargaModel::validasi_data_keluarga($data);
        if (! $valid['status']) {
            redirect_with('error', $valid['messages']);
        }

        try {
            KeluargaModel::tambahKeluargaDariPenduduk($data);
            redirect_with('success', 'Keluarga baru berhasil ditambahkan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Keluarga baru gagal ditambahkan');
        }
    }

    // Tambah KK dengan mengisi form penduduk kepala keluarga baru pindah datang
    public function insert_new(): void
    {
        isCan('u');
        $data          = $this->input->post();
        $originalInput = $data;

        $valid = KeluargaModel::validasi_data_keluarga($data);
        if (! $valid['status']) {
            set_session('old_input', $originalInput);
            redirect_with('error', $valid['messages'], ci_route('keluarga.form'));
        }

        $validasiPenduduk = Penduduk::validasi($data);
        if (! $validasiPenduduk['status']) {
            set_session('old_input', $originalInput);
            redirect_with('error', $validasiPenduduk['messages'], ci_route('keluarga.form'));
        }

        unset($data['file_foto'], $data['old_foto'], $data['nik_lama'], $data['dusun'], $data['rw']);

        DB::beginTransaction();

        try {
            KeluargaModel::baru($data);
            DB::commit();
            redirect_with('success', 'Keluarga baru berhasil ditambahkan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            DB::rollBack();
            set_session('old_input', $originalInput);
            redirect_with('error', 'Keluarga baru gagal ditambahkan', ci_route('keluarga.form'));
        }
    }

    public function update_nokk($id = 0): void
    {
        isCan('u');
        $keluarga = KeluargaModel::with(['kepalaKeluarga'])->findOrFail($id);
        if ($keluarga->kepalaKeluarga && $keluarga->kepalaKeluarga->status_dasar != 1) {
            show_404();
        }

        $data  = $this->input->post();
        $valid = KeluargaModel::validasi_data_keluarga($data);
        if (! $valid['status']) {
            redirect_with('error', $valid['messages']);
        }

        // Pindah dusun/rw/rt anggota keluarga kalau berubah
        // if ($data['id_cluster'] != $keluarga->id_cluster) {
        //     $keluarga->anggota()->update(['id_cluster' => $data['id_cluster']]);
        //     $keluarga->anggota->each(static function ($item) {
        //         $item->log()->create([
        //             'kode_peristiwa' => PeristiwaPendudukEnum::TIDAK_TETAP_PERGI->value, // kode 6
        //             'tgl_peristiwa'  => date('d-m-y'),
        //         ]);
        //     });
        // }

        $data['tgl_cetak_kk'] = empty($data['tgl_cetak_kk']) ? null : date('Y-m-d H:i:s', strtotime($data['tgl_cetak_kk']));
        if (empty($data['kelas_sosial'])) {
            $data['kelas_sosial'] = null;
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = ci_auth()->id;
        $keluarga->update($data);

        redirect_with('success', 'Keluarga berhasil diubah');
    }

    public function delete($id = 0): void
    {
        isCan('h');

        if (data_lengkap()) {
            redirect_with('error', 'Data tidak dapat proses karena sudah dinyatakan lengkap');
        }

        $keluarga = KeluargaModel::findOrFail($id);
        if (! $keluarga->bolehHapus()) {
            redirect_with('error', "Keluarga ini (id = {$id} ) tidak diperbolehkan dihapus");
        }
        $keluarga->delete();

        redirect_with('success', 'Keluarga berhasil dihapus');
    }

    public function delete_all(): void
    {
        isCan('h');

        if (data_lengkap()) {
            redirect_with('error', 'Data tidak dapat proses karena sudah dinyatakan lengkap');
        }

        DB::beginTransaction();

        try {
            $id_cb    = $this->input->post('id_cb');
            $keluarga = KeluargaModel::whereIn('id', $id_cb)->get();
            $keluarga->each(static function ($item, $key) {
                $item->delete();
            });
            DB::commit();
            redirect_with('success', 'Keluarga berhasil dihapus');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            DB::rollBack();
            redirect_with('error', 'Keluarga gagal dihapus. ' . $e->getMessage());
        }
    }

    /*
        Ajax url query data:
        q -- kata pencarian
        page -- nomor paginasi
    */

    public function list_kk_ajax()
    {
        if ($this->input->is_ajax_request()) {
            $cari     = $this->input->get('q');
            $keluarga = KeluargaModel::select(['id', 'no_kk'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->where('no_kk', 'like', "%{$cari}%");
                })
                ->paginate(10);

            return json([
                'results' => collect($keluarga->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->no_kk,
                        'text' => $item->no_kk,
                    ]),
                'pagination' => [
                    'more' => $keluarga->currentPage() < $keluarga->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    public function kartu_keluarga($id): void
    {
        $data['id_kk'] = $id;
        $keluarga      = KeluargaModel::with([
            'wilayah', // ← Tambahkan relasi wilayah untuk keluarga
            'anggota'        => static fn ($q) => $q->with('wilayah')->without(['keluarga', 'rtm'])->orderBy('kk_level'),
            'kepalaKeluarga' => static fn ($q) => $q->with([
                'wilayah',
                'keluarga' => static fn ($r) => $r->with('wilayah'),
            ])->without(['rtm']),
        ])->find($id);

        $data['main']        = $keluarga->toArray();
        $data['kepala_kk']   = $keluarga->kepalaKeluarga ? $keluarga->kepalaKeluarga->toArray() : null;
        $data['form_action'] = ci_route('keluarga.print');

        view('admin.penduduk.keluarga.kartu_keluarga', $data);
    }

    public function cetak_kk($id = 0): void
    {
        view('admin.penduduk.keluarga.cetak_kk_all', ['all_kk' => KeluargaModel::dataCetak($this->request['id_cb'] ?? [$id])]);
    }

    public function doc_kk($id = 0): void
    {
        $datas = KeluargaModel::dataCetak($this->request['id_cb'] ?? [$id] );

        foreach ($datas as $data) {
            $berkas_kk[] = $this->buat_berkas_kk($data, $this->input->get('format'));
        }
        if (count($datas) > 1) {
            // Masukkan semua berkas ke dalam zip
            $berkas_kk = masukkan_zip($berkas_kk);
            // Unduh berkas zip
            header('Content-disposition: attachment; filename=berkas_kk_' . date('d-m-Y') . '.zip');
            header('Content-type: application/zip');
            readfile($berkas_kk);
        } else {
            // Satu kk
            ambilBerkas(basename($berkas_kk[0]));
        }
    }

    public function statistik($tipe = '0', $nomor = 0, $sex = null): void
    {
        if ($sex == 0) {
            $sex = null;
        }

        $bantuan = Bantuan::whereSlug($tipe)->first();
        if (! $bantuan) {
            if (is_string($nomor)) {
                $bantuan = Bantuan::whereSlug($nomor)->first();
            }
        }

        $nama = $bantuan->nama ?? '-';
        if (! in_array($nomor, [BELUM_MENGISI, TOTAL, JUMLAH]) && $bantuan) {
            $nomor = $bantuan->id;
        }
        $kategori = $nama . ' : ';

        switch (true) {
            case $tipe == 'kelas_sosial':
                $kategori = 'KLASIFIKASI SOSIAL : ';
                break;

            case $tipe == 'bantuan_keluarga':
                if (! in_array($nomor, [BELUM_MENGISI, TOTAL])) {
                    $this->defaultStatus = null;
                } // tampilkan semua peserta walaupun bukan hidup/aktif
                $kategori = 'PENERIMA BANTUAN (KELUARGA) : ';
                break;

            default:
                $kategori = 'PENERIMA BANTUAN (KELUARGA) : ';
                break;
        }
        $judul = (new KeluargaModel())->judulStatistik($tipe, $nomor, $sex);
        if ($judul['nama']) {
            $this->judulStatistik = $kategori . $judul['nama'];
        }

        $this->filterColumn    = ['sex' => $sex];
        $this->statistikFilter = ['sex' => $sex, 'value' => $nomor, 'tipe' => $tipe];

        $this->index();
    }

    public function search_kumpulan_kk(): void
    {
        view('admin.penduduk.keluarga.modal.kumpulan_kk');
    }

    public function ajax_cetak($aksi = '')
    {
        $data['aksi']   = $aksi;
        $data['action'] = ci_route('keluarga.cetak', $aksi);

        return view('admin.layouts.components.ajax-cetak-bersama', $data);
    }

    public function program_bantuan(): void
    {
        $data = [
            'form_action' => ci_route('keluarga.program_bantuan_proses'),
            'bantuan'     => Bantuan::where(['sasaran' => SasaranEnum::KELUARGA])->get(),
        ];
        view('admin.penduduk.keluarga.modal.program_bantuan', $data);
    }

    public function program_bantuan_proses(): void
    {
        $id_program = $this->input->post('program_bantuan');
        $this->statistik('bantuan_keluarga', $id_program, '0');
    }

    public function form_pecah_semua($id = 0): void
    {
        isCan('u');
        $keluarga               = KeluargaModel::with(['kepalaKeluarga', 'anggota'])->findOrFail($id);
        $data['keluarga']       = $keluarga;
        $data['nokk_sementara'] = KeluargaModel::formatNomerKKSementara();
        $data['form_action']    = ci_route("keluarga.pecah_semua.{$id}");

        view('admin.penduduk.keluarga.modal.pecah_semua', $data);
    }

    public function pecah_semua($id = 0): void
    {
        isCan('u');
        DB::beginTransaction();

        try {
            KeluargaModel::pecahKK($id, $this->input->post());
            DB::commit();
            redirect_with('success', 'Pecah keluarga baru berhasil ditambahkan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            DB::rollBack();
            redirect_with('error', 'Pecah keluarga baru gagal ditambahkan');
        }
    }

    public function tambah_rtm_all()
    {
        isCan('u');

        DB::beginTransaction();

        try {
            $no_kk_terakhir = Rtm::max('no_kk') ?? 0;
            $id_cb          = $this->input->post('id_cb');
            log_message('info', 'Tambah RTM kolektif untuk keluarga id: ' . implode(', ', $id_cb));
            $keluarga          = KeluargaModel::whereIn('id', $id_cb)->get();
            $keluarga_dilewati = [];
            $jumlah_diproses   = 0;
            $keluarga->each(static function ($item, $key) use (&$no_kk_terakhir, &$keluarga_dilewati, &$jumlah_diproses) {
                $pend = Penduduk::where('id', $item->nik_kepala)->first();

                // Jika kepala keluarga tidak ditemukan atau sudah terdaftar di RTM, lewati
                if (! $pend || $pend->id_rtm) {
                    $keluarga_dilewati[] = $item->no_kk;

                    return true;
                }

                $jumlah_diproses++;
                $no_kk_terakhir++;

                $rtm = Rtm::create([
                    'nik_kepala'     => $pend->id,
                    'no_kk'          => $no_kk_terakhir,
                    'config_id'      => identitas('id'),
                    'tgl_daftar'     => date('Y-m-d H:i:s'),
                    'terdaftar_dtks' => 0,
                ]);

                $pend->id_rtm    = $rtm->id;
                $pend->rtm_level = HubunganRTMEnum::KEPALA_RUMAH_TANGGA;
                $pend->save();

                // Tambahkan juga anggota keluarga lainnya
                $anggota = Penduduk::where('id_kk', $item->id)->where('kk_level', '!=', SHDKEnum::KEPALA_KELUARGA)->status(StatusDasarEnum::HIDUP)->get();

                foreach ($anggota as $ang) {
                    $ang->id_rtm    = $rtm->no_kk;
                    $ang->rtm_level = HubunganRTMEnum::ANGGOTA;
                    $ang->save();
                }
            });
            DB::commit();
            $pesan = $jumlah_diproses . ' keluarga berhasil ditambahkan ke rumah tangga.';
            if (! empty($keluarga_dilewati)) {
                $pesan .= ' ' . count($keluarga_dilewati) . ' keluarga dilewati karena kepala keluarga sudah terdaftar di RTM lain: ' . implode(', ', $keluarga_dilewati);
            }
            redirect_with($jumlah_diproses == 0 ? 'error' : 'success', $pesan);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            DB::rollBack();
            redirect_with('error', 'Keluarga gagal ditambahkan ke rumah tangga. ' . $e->getMessage());
        }
    }

    private function sumberData()
    {
        $status          = $this->input->get('status') ?? null;
        $sex             = $this->input->get('jenis_kelamin') ?? null;
        $dusun           = $this->input->get('dusun') ?? null;
        $rw              = $this->input->get('rw') ?? null;
        $rt              = $this->input->get('rt') ?? null;
        $kumpulanKK      = $this->input->get('kumpulanKK');
        $bantuan         = $this->input->get('bantuan');
        $kkSementara     = $this->input->get('kk_sementara') ?? null;
        $kelasSosial     = $this->input->get('kelas_sosial') ?? null;
        $statistikFilter = $this->input->get('statistikfilter') ?? null;

        if ($statistikFilter) {
            switch ($statistikFilter['tipe']) {
                case 'kelas_sosial':
                    $kelasSosial = $statistikFilter['value'];
                    break;

                case 'bantuan_keluarga':
                    $bantuan = $statistikFilter['value'];
                    break;
            }
        }
        $idCluster = $rt ? [$rt] : [];

        if (empty($idCluster) && ! empty($rw)) {
            [$namaDusun,$namaRw] = explode('__', $rw);
            $idCluster           = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
        }

        if (empty($idCluster) && ! empty($dusun)) {
            $idCluster = Wilayah::whereDusun($dusun)->select(['id'])->get()->pluck('id')->toArray();
        }

        $query = KeluargaModel::with([
            'wilayah',
            // Eager load base relasi kepalaKeluarga terlebih dahulu
            'kepalaKeluarga',
            // Kemudian eager load nested relasi dari kepalaKeluarga
            'kepalaKeluarga.keluarga',
            'kepalaKeluarga.keluarga.wilayah',
            'kepalaKeluarga.rtm',
        ])
            ->leftJoin('tweb_penduduk as kepala_keluarga', 'tweb_keluarga.nik_kepala', '=', 'kepala_keluarga.id')
            ->withCount('anggota')->when($status != null, static fn ($q) => $q->whereHas('kepalaKeluarga', static function ($r) use ($status) {
                switch($status) {
                    case 1:
                        return $r->whereStatusDasar($status);

                    case 2:
                        return $r->where('status_dasar', '!=', 1);

                    case 3:
                        return $r->where(static fn ($s) => $s->whereNull('status_dasar')->orwhere('kk_level', '!=', SHDKEnum::KEPALA_KELUARGA));
                }
            }))
            ->when($status == 3, static fn ($q) => $q->orWhereNull('nik_kepala'))
            ->when($sex, static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($r) => $r->whereSex($sex)->when($status == 1, static fn ($s) => $s->where('status_dasar', 1))))
            ->when($idCluster, static fn ($q) => $q->whereHas('kepalaKeluarga.keluarga', static fn ($r) => $r->whereIn('id_cluster', $idCluster)))
            ->when($kumpulanKK, static fn ($q) => $q->whereIn('no_kk', $kumpulanKK))
            ->when($kkSementara, static fn ($q) => $q->where('no_kk', 'like', '0%'))
            ->when($kelasSosial, static function ($q) use ($kelasSosial) {
                switch($kelasSosial) {
                    case JUMLAH:
                        return $q->whereNotNull('kelas_sosial');

                    case BELUM_MENGISI:
                        return $q->whereNull('kelas_sosial');

                    case TOTAL:
                        return $q;

                    default:
                        return $q->where('kelas_sosial', $kelasSosial);
                }
            })
            ->when($bantuan, static function ($q) use ($bantuan) {
                switch($bantuan) {
                    case JUMLAH:
                        return $q->whereHas('bantuan');

                    case BELUM_MENGISI:
                        return $q->whereDoesntHave('bantuan');

                    case TOTAL:
                        return $q;

                    default:
                        return $q->whereHas('bantuan', static fn ($r) => $r->where('program_id', $bantuan));
                }
            });

        if (! request()->has('order')) {
            $query->orderBy(DB::raw("CASE
                WHEN CHAR_LENGTH(no_kk) < 16 THEN 1
                WHEN no_kk LIKE '0%' AND CHAR_LENGTH(no_kk) = 16 THEN 2
                ELSE 3
            END"));
        }

        return $query;
    }
}
