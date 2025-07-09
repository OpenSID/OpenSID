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

use App\Enums\AgamaEnum;
use App\Enums\AsuransiEnum;
use App\Enums\BahasaEnum;
use App\Enums\CacatEnum;
use App\Enums\CaraKBEnum;
use App\Enums\GolonganDarahEnum;
use App\Enums\HamilEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PekerjaanEnum;
use App\Enums\PendidikanKKEnum;
use App\Enums\PendidikanSedangEnum;
use App\Enums\SakitMenahunEnum;
use App\Enums\SasaranEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusDasarKKEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusKTPEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\SukuEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\Bantuan;
use App\Models\KelasSosial;
use App\Models\Keluarga as KeluargaModel;
use App\Models\LogPenduduk;
use App\Models\Penduduk;
use App\Models\PendudukHidup;
use App\Models\Wilayah;
use App\Traits\GenerateRtf;
use Illuminate\Support\Facades\DB;

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
        if ($this->input->get('dusun')) {
            $this->filterColumn['dusun'] = $this->input->get('dusun');
        }

        $data = [
            'status'          => StatusDasarKKEnum::all(),
            'jenis_kelamin'   => JenisKelaminEnum::all(),
            'disableFilter'   => in_array($this->uri->segment(2), ['statistik']),
            'wilayah'         => Wilayah::treeAccess(),
            'judul_statistik' => $this->judulStatistik,
            'filterColumn'    => $this->filterColumn,
            'statistikFilter' => $this->statistikFilter,
            'defaultStatus'   => $this->defaultStatus,
        ];

        view('admin.penduduk.keluarga.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $canDelete = can('h');
            $canUpdate = can('u');

            return datatables()->of($this->sumberData())
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
                    $aksi      = '<a href="' . ci_route('keluarga.anggota', $row->id) . '" class="btn bg-purple btn-sm" title="Rincian Anggota Keluarga (KK)"><i class="fa fa-list-ol"></i></a> ';
                    $canDelete = $canDelete && $row->bolehHapus();
                    if ($canUpdate && $row->kepalaKeluarga->status_dasar == StatusDasarEnum::HIDUP) {
                        $aksi .= '<div class="btn-group btn-group-vertical">
                            <a class="btn btn-success btn-sm " data-toggle="dropdown" title="Tambah Anggota Keluarga" ><i class="fa fa-plus"></i> </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="' . ci_route('keluarga.form_peristiwa.1', $row->id) . '" class="btn btn-social btn-block btn-sm" title="Anggota Keluarga Lahir"><i class="fa fa-plus"></i> Anggota Keluarga Lahir</a>
                                </li>
                                <li>
                                    <a href="' . ci_route('keluarga.form_peristiwa.5', $row->id) . '" class="btn btn-social btn-block btn-sm" title="Anggota Keluarga Masuk"><i class="fa fa-plus"></i> Anggota Keluarga Masuk</a>
                                </li>
                                <li>
                                    <a href="' . ci_route('keluarga.ajax_add_anggota', $row->id) . '" class="btn btn-social btn-block btn-sm" title="Tambah Anggota Dari Penduduk Yang Sudah Ada" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Anggota Keluarga"><i class="fa fa-plus"></i> Dari Penduduk Sudah Ada</a>
                                </li>
                            </ul>
                        </div> ';
                    }
                    if ($canUpdate) {
                        if ($row->kepalaKeluarga->status_dasar == StatusDasarEnum::HIDUP) {
                            $aksi .= '<a href="' . ci_route('keluarga.edit_nokk', $row->id) . '" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data KK" class="btn bg-orange btn-sm"><i class="fa fa-edit"></i></a> ';
                            $aksi .= ' <a href="' . ci_route('penduduk.ajax_penduduk_maps.' . $row->kepalaKeluarga->id, 0) . '" class="btn btn-success btn-sm" title="Lokasi Tempat Tinggal"><i class="fa fa-map-marker"></i></a> ';
                        } else {
                            if ($row->anggota->count() > 0) {
                                $aksi .= '<a href="' . ci_route('keluarga.form_pecah_semua', $row->id) . '" title="Pecah semua anggota ke keluarga baru" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pecah menjadi keluarga baru" class="btn bg-purple btn-sm"><i class="fa fa-cut"></i></a> ';
                            }
                            if ($row->kepalaKeluarga) {
                                $aksi .= '<a href="' . ci_route('keluarga.edit_nokk', $row->id) . '" title="Lihat Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Data KK" class="btn bg-info btn-sm"><i class="fa fa-eye"></i></a> ';
                            }
                        }
                    }
                    if ($canDelete) {
                        $aksi .= '<a href="#" data-href="' . ci_route('keluarga.delete', $row->id) . '" class="btn bg-maroon btn-sm" title="Hapus/Keluar Dari Daftar Keluarga" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                    }

                    return $aksi;
                })->editColumn('tgl_daftar', static fn ($q) => tgl_indo($q->tgl_daftar))
                ->editColumn('tgl_cetak_kk', static fn ($q) => tgl_indo($q->tgl_cetak_kk))
                ->addColumn('jenis_kelamin', static fn ($q) => JenisKelaminEnum::valueOf($q->kepalaKeluarga->sex))
                ->rawColumns(['aksi', 'ceklist', 'foto'])
                ->make();
        }

        return show_404();
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

        return KeluargaModel::when($status != null, static fn ($q) => $q->whereHas('kepalaKeluarga', static function ($r) use ($status) {
                switch($status) {
                    case 1:
                        return $r->whereStatusDasar($status);

                    case 2:
                        return $r->where('status_dasar', '!=', 1);

                    case 3:
                        return $r->where(static fn ($s) => $s->whereNull('status_dasar')->orwhere('kk_level', '!=', SHDKEnum::KEPALA_KELUARGA));
                }
            }))->when($status == 3, static fn ($q) => $q->orWhereNull('nik_kepala'))
            ->when($sex, static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($r) => $r->whereSex($sex)))
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
            })->orderBy(DB::raw("CASE
                WHEN CHAR_LENGTH(no_kk) < 16 THEN 1
                WHEN no_kk LIKE '0%' AND CHAR_LENGTH(no_kk) = 16 THEN 2
                ELSE 3
            END"))
            ->with(['kepalaKeluarga' => static fn ($q) => $q->withOnly(['keluarga'])])->withCount('anggota');
    }

    public function cetak($aksi = '', $privasi_kk = 0): void
    {
        $query = datatables($this->sumberData())
            ->filter(function ($query) {
                $query->when($this->input->post('id_cb'), static function ($query, $id) {
                    $query->whereIn('id', $id);
                });
            });

        $data = [
            'main'  => $query->prepareQuery()->results(),
            'start' => app('datatables.request')->start(),
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
        $data['ktp_el']             = array_flip(unserialize(KTP_EL));
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
        $data['jenis_peristiwa']    = LogPenduduk::BARU_PINDAH_MASUK;
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

        $lokasi_file = $_FILES['foto']['tmp_name'];
        $tipe_file   = $_FILES['foto']['type'];
        $nama_file   = $_FILES['foto']['name'];
        $nama_file   = str_replace(' ', '-', $nama_file);      // normalkan nama file
        $old_foto    = '';
        if (! empty($lokasi_file)) {
            if ($tipe_file != 'image/jpeg' && $tipe_file != 'image/pjpeg' && $tipe_file != 'image/png') {
                unset($data['foto']);
            } else {
                UploadFoto($nama_file, $old_foto);
                $data['foto'] = $nama_file;
            }
        } else {
            unset($data['foto']);
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
        if ($data['id_cluster'] != $keluarga->id_cluster) {
            $keluarga->anggota()->update(['id_cluster' => $data['id_cluster']]);
            $keluarga->anggota->each(static function ($item) {
                $item->log()->create([
                    'kode_peristiwa' => LogPenduduk::TIDAK_TETAP_PERGI, // kode 6
                    'tgl_peristiwa'  => date('d-m-y'),
                ]);
            });
        }

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
        $data['id_kk']       = $id;
        $keluarga            = KeluargaModel::with(['anggota' => static fn ($q) => $q->orderBy('kk_level'), 'kepalaKeluarga'])->find($id);
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
            $berkas_kk[] = $this->buat_berkas_kk($data);
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

            case $tipe > 50:
                $program_id = preg_replace('/^50/', '', $tipe);
                $nama       = Bantuan::find($program_id)->nama ?? '-';

                if (! in_array($nomor, [BELUM_MENGISI, TOTAL])) {
                    $this->defaultStatus = null;
                    $nomor               = $program_id;
                }
                $kategori = $nama . ' : ';
                $tipe     = 'bantuan_keluarga';
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

    public function ajax_cetak($aksi = ''): void
    {
        $data['aksi']   = $aksi;
        $data['action'] = ci_route('keluarga.cetak', $aksi);

        view('admin.penduduk.ajax_cetak_bersama', $data);
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
}
