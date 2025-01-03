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
use App\Enums\AktifEnum;
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
use App\Enums\PindahEnum;
use App\Enums\SakitMenahunEnum;
use App\Enums\SasaranEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusKawinSpesifikEnum;
use App\Enums\StatusKTPEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\SukuEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\Bantuan;
use App\Models\Dokumen;
use App\Models\DokumenHidup;
use App\Models\LogKeluarga;
use App\Models\LogPenduduk;
use App\Models\Penduduk as PendudukModel;
use App\Models\PendudukMap;
use App\Models\PendudukSaja;
use App\Models\RentangUmur;
use App\Models\StatusKtp;
use App\Models\SyaratSurat;
use App\Models\UserGrup;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

defined('BASEPATH') || exit('No direct script access allowed');

class Penduduk extends Admin_Controller
{
    public $modul_ini           = 'kependudukan';
    public $sub_modul_ini       = 'penduduk';
    public $kategori_pengaturan = 'Data Lengkap';
    private $judulStatistik;
    private $filterColumn    = [];
    private $advanceSearch   = [];
    private $statistikFilter = [];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['impor_model']);
    }

    public function index(): void
    {
        if ($this->input->get('status_dasar')) {
            $this->filterColumn['status_dasar'] = $this->input->get('status_dasar');
        }
        if ($this->input->get('dusun')) {
            $this->filterColumn['dusun'] = $this->input->get('dusun');
        }
        if ($this->input->get('rw')) {
            $this->filterColumn['rw'] = $this->input->get('rw');
        }
        if ($this->input->get('rt')) {
            $this->filterColumn['rt'] = $this->input->get('rt');
        }
        if ($this->input->get('sex')) {
            $this->filterColumn['sex'] = $this->input->get('sex');
        }
        $data['disableFilter']        = in_array($this->uri->segment(2), ['statistik', 'lap_statistik']);
        $data['wilayah']              = Wilayah::treeAccess();
        $data['list_status_dasar']    = StatusDasarEnum::all();
        $data['list_status_penduduk'] = StatusPendudukEnum::all();
        $data['list_jenis_kelamin']   = JenisKelaminEnum::all();
        $data['filterColumn']         = $this->filterColumn;
        $data['defaultStatusDasar']   = $this->filterColumn['status_dasar'] ?? StatusDasarEnum::HIDUP;
        $data['advanceSearch']        = $this->advanceSearch;
        $data['statistikFilter']      = $this->statistikFilter;
        $data['judul_statistik']      = $this->judulStatistik;
        $data['pesan_hapus']          = 'Hanya lakukan hapus penduduk hanya jika ada kesalahan saat pengisian data atau penduduk tersebut tidak akan ditambahkan kembali. Apakah Anda yakin ingin menghapus data ini?';
        $data['akses']                = UserGrup::getGrupId(UserGrup::ADMINISTRATOR);

        view('admin.penduduk.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $canDelete = can('h');
            $canUpdate = can('u');

            return datatables()->of($this->sumberData())
                ->addColumn('ceklist', static function ($row) use ($canDelete) {
                    if ($canDelete) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })->addColumn('valid_kk', static function ($row) {
                    $result = '';
                    if (strlen($row->nik) < 16) {
                        $result = 'warning';
                    } elseif (get_nik($row->nik) == 0) {
                        $result = 'danger';
                    }

                    return $result;
                })
                ->addColumn('foto', static fn ($row) => '<img class="penduduk_kecil" src="' . AmbilFoto($row->foto, '', $row->sex) . '" alt="Foto Penduduk" />')->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate, $canDelete): string {
                    $aksi = '<div class="btn-group">
                        <button type="button" class="btn btn-social btn-info btn-sm" data-toggle="dropdown"><i class="fa fa-arrow-circle-down"></i> Pilih Aksi</button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="' . ci_route('penduduk.detail', $row->id) . '" class="btn btn-social btn-block btn-sm"><i class="fa fa-list-ol"></i> Lihat Detail Biodata Penduduk</a>
                            </li>';
                    if ($row->status_dasar == StatusDasarEnum::TIDAK_VALID && $canUpdate) {
                        $aksi .= '<li>
                                    <a href="#" data-href="' . ci_route('penduduk.kembalikan_status', $row->id) . '" class="btn btn-social btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#confirm-status" data-body="Apakah Anda yakin ingin mengembalikan status data penduduk ini?<br> Perubahan ini akan mempengaruhi laporan penduduk bulanan."><i class="fa fa-undo"></i> Kembalikan ke Status HIDUP</a>
                                </li>';
                    }
                    if ($row->status_dasar == StatusDasarEnum::HIDUP) {
                        if ($canUpdate) {
                            $aksi .= '<li>
                                        <a href="' . ci_route('penduduk.form', $row->id) . '" class="btn btn-social btn-block btn-sm"><i class="fa fa-edit"></i> Ubah Biodata Penduduk</a>
                                    </li>
                                    <li>
                                        <a href="' . ci_route('penduduk.ajax_penduduk_maps.' . $row->id, 0) . '" class="btn btn-social btn-block btn-sm"><i class="fa fa-map-marker"></i> Lihat Lokasi Tempat Tinggal</a>
                                    </li>';
                            if (data_lengkap()) {
                                $aksi .= '<li>
                                            <a href="' . ci_route('penduduk.edit_status_dasar', $row->id) . '" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Status Dasar" class="btn btn-social btn-block btn-sm"><i class="fa fa-sign-out"></i> Ubah Status Dasar</a>
                                        </li>';
                            }
                        }
                        $aksi .= '<li>
                                            <a href="' . ci_route('penduduk.dokumen', $row->id) . '" class="btn btn-social btn-block btn-sm"><i class="fa fa-upload"></i> Upload Dokumen Penduduk</a>
                                        </li>
                                        <li>
                                            <a href="' . ci_route('penduduk.cetak_biodata', $row->id) . '" target="_blank" class="btn btn-social btn-block btn-sm"><i class="fa fa-print"></i> Cetak Biodata Penduduk</a>
                                        </li>';
                        if ($canDelete && ! data_lengkap()) {
                            $aksi .= '<li>
                                        <a href="#" data-href="' . ci_route('penduduk.delete', $row->id) . '" class="btn btn-social btn-block btn-sm" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i> Hapus</a>
                                    </li>';
                        }
                    }
                    $aksi .= '
                        </ul>
                    </div>';

                    return $aksi;
                })->editColumn('tgl_peristiwa', static fn ($q) => $q->log_latest ? tgl_indo($q->log_latest->tgl_peristiwa) : tgl_indo($q->created_at))
                ->editColumn('created_at', static fn ($q) => tgl_indo($q->created_at))
                ->editColumn('nama', static fn ($q) => strtoupper($q->nama))
                ->addColumn('umur', static fn ($q) => $q->umur)
                ->addColumn('status_perkawinan', static fn ($q) => $q->statusPerkawinan)
                ->rawColumns(['aksi', 'ceklist', 'foto'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        $statusDasar     = $this->input->get('status_dasar') ?? null;
        $statusPenduduk  = $this->input->get('status_penduduk') ?? null;
        $sex             = $this->input->get('jenis_kelamin') ?? null;
        $dusun           = $this->input->get('dusun') ?? null;
        $rw              = $this->input->get('rw') ?? null;
        $rt              = $this->input->get('rt') ?? null;
        $nikSementara    = $this->input->get('nik_sementara') ?? null;
        $kumpulanNIK     = $this->input->get('kumpulan_nik') ?? null;
        $bantuan         = $this->input->get('bantuan') ?? null;
        $statistikFilter = $this->input->get('statistikfilter') ?? null;
        $advanceSearch   = $this->input->get('advancesearch') ?? null;

        $idCluster = $rt ? [$rt] : [];
        if (! empty($kumpulanNIK)) {
            $bantuan = $nikSementara = $rw = $dusun = $rt = $idCluster = $statusDasar = $statusPenduduk = $sex = $kelasSosial = null;
        }

        if ($bantuan) {
            $kumpulanNIK = $nikSementara = $rw = $dusun = $rt = $idCluster = $statusDasar = $statusPenduduk = $sex = $kelasSosial = null;
        }

        if ($nikSementara) {
            $bantuan = $kumpulanNIK = $rw = $dusun = $rt = $idCluster = $statusDasar = $statusPenduduk = $sex = $kelasSosial = null;
        }

        if ($statistikFilter) {
            $advanceSearch = $bantuan = $kumpulanNIK = $rw = $dusun = $rt = $idCluster = $statusDasar = $statusPenduduk = $sex = $kelasSosial = null;
            if (isset($statistikFilter['bantuan_penduduk'])) {
                $bantuan = $statistikFilter['bantuan_penduduk'];
                $sex     = $statistikFilter['sex'];
                unset($statistikFilter);
            }

            $dusun     = $statistikFilter['dusun'] ?? null;
            $rw        = $statistikFilter['rw'] ?? null;
            $rt        = $statistikFilter['rt'] ?? null;
            $clusterId = $statistikFilter['idCluster'] ?? null;

            if ($rt) {
                [$namaDusun, $namaRw] = explode('__', $rw);
                $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->whereRt($rt)->select(['id'])->get()->pluck('id')->toArray();
            }
        }

        if (empty($idCluster) && ! empty($rw)) {
            [$namaDusun, $namaRw] = explode('__', $rw);
            $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
        }

        if (empty($idCluster) && ! empty($dusun)) {
            $idCluster = Wilayah::whereDusun($dusun)->select(['id'])->get()->pluck('id')->toArray();
        }

        if ($clusterId) $idCluster = $clusterId;

        return PendudukModel::with(['log_latest'])
            ->select('tweb_penduduk.*')
            ->when($idCluster, static fn ($q) => $q->whereIn('tweb_penduduk.id_cluster', $idCluster))
            ->when($statusDasar, static fn ($q) => $q->whereStatusDasar($statusDasar))
            ->when($statusPenduduk, static fn ($q) => $q->whereStatus($statusPenduduk))
            ->when($nikSementara, static fn ($q) => $q->where('nik', 'like', '0%'))
            ->when($sex, static fn ($q) => $q->whereSex($sex))
            ->when($kumpulanNIK, static fn ($q) => $q->whereIn('nik', $kumpulanNIK))
            ->when($statistikFilter, static function ($q) use ($statistikFilter) {
                if (isset($statistikFilter['umurx'])) {
                    if ($statistikFilter['umurx'] == BELUM_MENGISI) {
                        $statistikFilter['umur_min'] = -1;
                        $statistikFilter['umur_max'] = -1;
                    } else {
                        $rentangUmur                 = RentangUmur::find($statistikFilter['umurx']);
                        $statistikFilter['umur_min'] = $rentangUmur->dari;
                        $statistikFilter['umur_max'] = $rentangUmur->sampai;
                    }
                }

                $umurMin           = $statistikFilter['umur_min'];
                $umurMax           = $statistikFilter['umur_max'];
                $umurObj['satuan'] = 'tahun';
                if (null !== $umurMin) {
                    $umurObj['min'] = $umurMin;
                }
                if (null !== $umurMax) {
                    $umurObj['max'] = $umurMax;
                }

                $map = [
                    'pekerjaan_id'              => 'pekerjaan_id',
                    'status_kawin'              => 'status_kawin',
                    'agama'                     => 'agama_id',
                    'pendidikan_sedang_id'      => 'pendidikan_sedang_id',
                    'pendidikan_kk_id'          => 'pendidikan_kk_id',
                    'status_penduduk'           => 'status',
                    'sex'                       => 'sex',
                    'status_dasar'              => 'status_dasar',
                    'cara_kb_id'                => 'cara_kb_id',
                    'status_ktp'                => 'ktp_el',
                    'id_asuransi'               => 'id_asuransi',
                    'warganegara'               => 'warganegara_id',
                    'golongan_darah'            => 'golongan_darah_id',
                    'menahun'                   => 'sakit_menahun_id',
                    'cacat'                     => 'cacat_id',
                    'suku'                      => 'suku',
                    'hubungan'                  => 'kk_level',
                    'akta_kelahiran'            => 'akta_lahir',
                    'bpjs_ketenagakerjaan'      => 'bpjs_ketenagakerjaan',
                    'status_asuransi_kesehatan' => 'status_asuransi',
                    'hamil'                     => 'hamil',
                    'buku-nikah'                => 'akta_perkawinan',
                    'kia'                       => 'kia',
                    'id_cluster'                => 'id_cluster',
                ];

                info($statistikFilter);

                foreach ($statistikFilter as $key => $val) {
                    if ($val != '') {
                        if (isset($map[$key])) {
                            if ($map[$key] == 'ktp_el') {
                                $q->wajibKtp();
                                if ($val == BELUM_MENGISI) {
                                    $q->where(static fn ($r) => $r->whereNull('ktp_el')->orWhere('ktp_el', 0)->orWhere('status_rekam', 0)->orWhereNull('status_rekam'));
                                } else {
                                    if ($val == JUMLAH) {
                                        $q->where(static fn ($r) => $r->whereNotNull('ktp_el')->whereNotIn('ktp_el', [0, 3]));
                                    } else {
                                        if ($val != TOTAL) {
                                            $statusKTP = StatusKtp::find($val);
                                            $q->where('ktp_el', '!=', 3)->where('status_rekam', $statusKTP->status_rekam);
                                        }
                                    }
                                }
                            } elseif ($map[$key] == 'kia') {
                                $umurObj['min'] = 0;
                                $umurObj['max'] = 17;
                                if ($val == BELUM_MENGISI) {
                                    $q->where(static fn ($r) => $r->whereNull('ktp_el')->orWhere('ktp_el', 0)->orWhere('status_rekam', 0)->orWhereNull('status_rekam'));
                                } else {
                                    if ($val == JUMLAH) {
                                        $q->where('ktp_el', 3);
                                    } else {
                                        if ($val != TOTAL) {
                                            $statusKTP = StatusKtp::find($val);
                                            $q->where('ktp_el', 3)->where('status_rekam', $statusKTP->status_rekam);
                                        }
                                    }
                                }
                            } elseif ($map[$key] == 'akta_perkawinan') {
                                $q->where('status_kawin', '!=', StatusKawinEnum::BELUMKAWIN);
                                if ($val == BELUM_MENGISI) {
                                    $q->where(static fn ($r) => $r->where('akta_perkawinan', '=', '')->orWhereNull('akta_perkawinan'));
                                } elseif ($val == JUMLAH || $val == 2) {
                                    $q->where(static fn ($r) => $r->where('akta_perkawinan', '!=', '')->whereNotNull('akta_perkawinan'));
                                }
                            } elseif ($map[$key] == 'cacat_id') {
                                if ($val == CacatEnum::TIDAK_CACAT) {
                                    $q->where(static fn ($r) => $r->where('cacat_id', '=', CacatEnum::TIDAK_CACAT)->orWhereNull('cacat_id'));
                                } else {
                                    if ($val == JUMLAH) {
                                        $q->where(static fn ($r) => $r->where('cacat_id', '!=', CacatEnum::TIDAK_CACAT)->whereNotNull('cacat_id'));
                                    } else {
                                        $q->where($map[$key], $val);
                                    }
                                }
                            } elseif ($map[$key] == 'sakit_menahun_id') {
                                if (is_array($val)) {
                                    $q->whereIn($map[$key], $val);
                                } else {
                                    $q->where($map[$key], $val);
                                }
                            } elseif ($map[$key] == 'status_asuransi') {
                                if ($val == BELUM_MENGISI) {
                                    $q->where(static fn ($r) => $r->whereNull('status_asuransi'));
                                } elseif ($val == JUMLAH || $val == 0) {
                                    $q->where(static fn ($r) => $r->whereIn('status_asuransi', AktifEnum::keys()));
                                } else {
                                    $q->where('status_asuransi', $val);
                                }
                            } else {
                                if ($val == BELUM_MENGISI) {
                                    $q->where(static fn ($r) => $r->whereNull($map[$key])->orWhere($map[$key], ''));
                                } else {
                                    if ($val == JUMLAH) {
                                        $q->whereNotNull($map[$key])->where($map[$key], '!=', '');
                                    } else {
                                        $q->where($map[$key], $val);
                                    }
                                }
                            }
                        }
                    }
                }

                return $q->batasiUmur(date('d-m-Y'), $umurObj);
            })
            ->when($advanceSearch, static function ($q) use ($advanceSearch) {
                $umurMin           = $advanceSearch['umur_min'];
                $umurMax           = $advanceSearch['umur_max'];
                $umurObj['satuan'] = $advanceSearch['umur'];
                if ($umurMin !== null) {
                    $umurObj['min'] = $umurMin;
                }
                if ($umurMax !== null) {
                    $umurObj['max'] = $umurMax;
                }

                // maping field yang memiliki relasi dengan tabel lain
                $map = [
                    'pekerjaan_id'         => 'pekerjaan_id',
                    'status'               => 'status',
                    'agama'                => 'agama_id',
                    'pendidikan_sedang_id' => 'pendidikan_sedang_id',
                    'pendidikan_kk_id'     => 'pendidikan_kk_id',
                    'status_penduduk'      => 'status',
                    'sex'                  => 'sex',
                    'status_dasar'         => 'status_dasar',
                    'cara_kb_id'           => 'cara_kb_id',
                    'status_ktp'           => 'status_rekam',
                    'id_asuransi'          => 'id_asuransi',
                    'warganegara'          => 'warganegara_id',
                    'golongan_darah'       => 'golongan_darah_id',
                    'menahun'              => 'sakit_menahun_id',
                    'cacat'                => 'cacat_id',
                ];
                $resultMap = [];

                foreach ($advanceSearch as $key => $val) {
                    if ($val != '') {
                        if (isset($map[$key])) {
                            $resultMap[$map[$key]] = $val;
                        }
                    }
                }

                $statusKawin = $advanceSearch['status_kawin'];
                if (in_array($statusKawin, StatusKawinSpesifikEnum::keys())) {
                    if ($statusKawin == StatusKawinSpesifikEnum::KAWIN_TERCATAT) {
                        $q->where('status_kawin', StatusKawinEnum::KAWIN)
                            ->where('akta_perkawinan', '!=', '')
                            ->whereNotNull('tanggalperkawinan');
                    } elseif ($statusKawin == StatusKawinSpesifikEnum::KAWIN_BELUM_TERCATAT) {
                        $q->where('status_kawin', StatusKawinEnum::KAWIN)
                            ->where('akta_perkawinan', '')
                            ->whereNull('tanggalperkawinan');
                    } elseif ($statusKawin == StatusKawinSpesifikEnum::CERAIHIDUP_TERCATAT) {
                        $q->where('status_kawin', StatusKawinEnum::CERAIHIDUP)
                            ->where('akta_perceraian', '!=', '')
                            ->whereNotNull('tanggalperceraian');
                    } elseif ($statusKawin == StatusKawinSpesifikEnum::CERAIHIDUP_BELUM_TERCATAT) {
                        $q->where('status_kawin', StatusKawinEnum::CERAIHIDUP)
                            ->where('akta_perceraian', '')
                            ->whereNull('tanggalperceraian');
                    } else {
                        $q->where('status_kawin', $statusKawin);
                    }
                }

                if (in_array($advanceSearch['tag_id_card'], StatusEnum::keys())) {
                    if ($advanceSearch['tag_id_card']) {
                        $q->whereNotNull('tag_id_card');
                    } else {
                        $q->whereNull('tag_id_card');
                    }
                }

                if (in_array($advanceSearch['id_kk'], StatusEnum::keys())) {
                    if ($advanceSearch['id_kk']) {
                        $q->whereNotNull('id_kk');
                    } else {
                        $q->whereNull('id_kk');
                    }
                }

                return $q->batasiUmur(date('d-m-Y'), $umurObj)->where($resultMap);
            })
            ->when($bantuan, static function ($q) use ($bantuan) {
                switch ($bantuan) {
                    case BELUM_MENGISI:
                        return $q->whereDoesntHave('bantuan');

                    case JUMLAH:
                        return $q->whereHas('bantuan');

                    default:
                        return $q->whereHas('bantuan', static fn ($r) => $r->where('program.id', $bantuan));
                }
            })
            ->orderBy(DB::raw("CASE
                WHEN CHAR_LENGTH(nik) < 16 THEN 1
                WHEN nik LIKE '0%' AND CHAR_LENGTH(nik) = 16 THEN 2
                ELSE 3
            END"));
    }
    /*
        Ajax url query data:
        q -- kata pencarian
        page -- nomor paginasi
    */

    public function list_nik_ajax()
    {
        if ($this->input->is_ajax_request()) {
            $cari     = $this->input->get('q');
            $penduduk = PendudukModel::select(['id', 'nik'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->where('nik', 'like', "%{$cari}%");
                })
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->nik,
                        'text' => $item->nik,
                    ]),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    public function ambil_foto(): void
    {
        $foto = $this->input->get('foto');
        $sex  = $this->input->get('sex');
        if (empty($foto) || ! file_exists(FCPATH . LOKASI_USER_PICT . $foto)) {
            $foto = ($sex == 1) ? 'kuser.png' : 'wuser.png';
            ambilBerkas($foto, $this->controller, null, 'assets/images/pengguna/', $tampil = true);
        } else {
            ambilBerkas($foto, $this->controller, null, LOKASI_USER_PICT, $tampil = true);
        }
    }

    public function form_peristiwa($peristiwa = ''): void
    {
        isCan('u');
        $this->form(null, $peristiwa);
    }

    public function form($id = null, $peristiwa = null): void
    {
        isCan('u');
        $penduduk = new PendudukModel();
        if ($id) {
            $data['id'] = $id;
            // Validasi dilakukan di penduduk_model sewaktu insert dan update
            $penduduk                         = PendudukModel::with('log_latest')->findOrFail($id);
            $data['penduduk']                 = $penduduk->toArray();
            $data['penduduk']['no_kk']        = $penduduk->keluarga->no_kk;
            $data['penduduk']['alamat']       = $penduduk->keluarga->alamat ?? $penduduk->alamat;
            $data['penduduk']['tgl_lapor']    = $penduduk->log_latest->tgl_lapor;
            $data['penduduk']['tanggallahir'] = $penduduk->tanggallahir?->format('d-m-Y');
            $data['penduduk']['id_status']    = $penduduk->status;
            $data['penduduk']['id_sex']       = $penduduk->sex;
            $data['penduduk']['status_kawin'] = $penduduk->status_kawin;
            $wilayah                          = $penduduk->wilayah;
            $data['penduduk']['wilayah']      = ['dusun' => $wilayah->dusun, 'rw' => $wilayah->rw, 'rt' => $wilayah->rt];
            $data['form_action']              = ci_route('penduduk.update', $id);
            if ($penduduk->log_latest->kode_peristiwa == LogPenduduk::BARU_PINDAH_MASUK) {
                $data['penduduk']['maksud_tujuan_kedatangan'] = $penduduk->log_latest->maksud_tujuan_kedatangan;
            } else {
                $data['penduduk']['maksud_tujuan_kedatangan'] = null;
            }
        } else {
            // Validasi dilakukan di penduduk_model sewaktu insert
            $data['penduduk']    = $penduduk->toArray();
            $data['form_action'] = ci_route('penduduk.insert', $peristiwa);
        }

        $data['wilayah']            = Wilayah::treeAccess();
        $data['agama']              = AgamaEnum::all();
        $data['pendidikan_sedang']  = PendidikanSedangEnum::all();
        $data['pendidikan_kk']      = PendidikanKKEnum::all();
        $data['pekerjaan']          = PekerjaanEnum::all();
        $data['warganegara']        = WargaNegaraEnum::all();
        $data['hubungan']           = SHDKEnum::all();
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
        $data['suku_penduduk']      = PendudukModel::distinct()->select('suku')->whereNotNull('suku')->whereRaw('LENGTH(suku) > 0')->pluck('suku', 'suku');
        $data['nik_sementara']      = PendudukModel::nikSementara();
        $data['status_penduduk']    = StatusPendudukEnum::all();
        $data['keluarga']           = $penduduk->keluarga;
        $data['cek_nik']            = get_nik($penduduk->nik);

        $data['jenis_peristiwa'] = $peristiwa;
        $data['controller']      = 'penduduk';
        $originalInput           = session('old_input');
        if ($originalInput) {
            $data['penduduk'] = $originalInput;
            if (isset($originalInput['id_cluster'])) {
                $wilayah                     = Wilayah::find((int) ($originalInput['id_cluster']));
                $data['penduduk']['wilayah'] = ['dusun' => $wilayah->dusun, 'rw' => $wilayah->rw, 'rt' => $wilayah->rt];
            }
            $data['penduduk']['id_sex']    = $originalInput['sex'];
            $data['penduduk']['id_status'] = $originalInput['status'];
        }
        $data['pesan_hapus']  = 'Apakah Anda yakin ingin mengembalikan foto menggunakan foto bawaan?';
        $data['tombol_hapus'] = 'Kembalikan';
        $data['icon_hapus']   = 'fa fa-undo';

        view('admin.penduduk.form', $data);
    }

    public function detail($id): void
    {
        $penduduk             = PendudukModel::findOrFail($id);
        $data['list_dokumen'] = $penduduk->dokumen;
        $data['penduduk']     = $penduduk;
        $data['program']      = $penduduk->pesertaBantuan;
        view('admin.penduduk.detail', $data);
    }

    public function dokumen(int $id)
    {
        $data = ['penduduk' => PendudukModel::select('id', 'nik', 'nama', 'id_cluster')->find($id) ?? show_404()];

        return view('admin.penduduk.dokumen.index', $data);
    }

    public function dokumen_datatables()
    {
        if ($this->input->is_ajax_request()) {
            $idPend = $this->input->get('id_pend');

            return datatables()->of(Dokumen::with(['jenisDokumen'])->whereIdPend($idPend))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($idPend): string {
                    $aksi = '';

                    if (! $row->hidden) {
                        if (can('u')) {
                            $aksi .= '<a href="' . ci_route('penduduk.dokumen_form', "{$idPend}/{$row->id}") . '" class="btn bg-orange btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data" title="Ubah Data" title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                        }
                        if (can('u')) {
                            $aksi .= '<a href="#" data-href="' . ci_route('penduduk.delete_dokumen', "{$idPend}/{$row->id}") . '" class="btn bg-maroon btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                        }
                    }

                    return $aksi . ('<a href="' . ci_route('penduduk.unduh_berkas', $row->id) . '" class="btn bg-purple btn-sm" title="Unduh Dokumen"><i class="fa fa-download"></i></a>');
                })
                ->editColumn('jenis_dokumen', static fn ($row) => $row->jenisDokumen->ref_syarat_nama ?? '')
                ->editColumn('tgl_upload', static fn ($row) => tgl_indo2($row->tgl_upload))
                ->rawColumns(['aksi', 'ceklist'])
                ->make();
        }

        return show_404();
    }

    public function dokumen_form(int $id, $id_dokumen = 0)
    {
        isCan('u');
        $penduduk                   = PendudukModel::with(['keluarga', 'dokumen' => static fn ($q) => $q->whereId($id_dokumen)])->find($id) ?? show_404();
        $data['penduduk']           = ['id' => $id, 'nik' => $penduduk->nik];
        $data['jenis_syarat_surat'] = SyaratSurat::get();

        if ($penduduk->isKepalaKeluarga()) { //Jika Kepala Keluarga
            $data['kk'] = $penduduk->keluarga->anggota->keyBy('id')->toArray();
        }

        if ($id_dokumen) {
            $dokumenTerpilih = $penduduk->dokumen->first();
            $data['dokumen'] = $dokumenTerpilih->toArray();

            // Ambil data anggota KK
            if ($penduduk->isKepalaKeluarga()) { //Jika Kepala Keluarga
                $dokumenAnggota = $dokumenTerpilih->children;

                if ($dokumenAnggota) {
                    $data['dokumen_anggota'] = $dokumenAnggota->toArray();

                    foreach ($dokumenAnggota as $item) {
                        $data['kk'][$item->id_pend]['checked'] = 'checked';
                    }
                }
            }

            $data['form_action'] = ci_route("{$this->controller}/dokumen_update/{$id_dokumen}");
        } else {
            $data['dokumen']     = null;
            $data['form_action'] = ci_route("{$this->controller}/dokumen_insert");
        }

        return view('admin.penduduk.dokumen.form', $data);
    }

    public function dokumen_list($id = 0): void
    {
        $data['list_dokumen'] = DokumenHidup::where(['id_pend' => $id])->get();
        view('admin.penduduk.dokumen_ajax', $data);
    }

    public function dokumen_insert(): void
    {
        isCan('u');

        try {
            $dataInsert               = Dokumen::validasi($this->input->post());
            $id_pend                  = $dataInsert['id_pend'];
            $dataInsert['satuan']     = $this->upload_dokumen();
            $dataInsert['updated_by'] = $this->session->user;
            $dataInsert['created_by'] = $this->session->user;
            $dokumen                  = Dokumen::create($dataInsert);

            if ($dataInsert['anggota_kk']) {
                foreach ($dataInsert['anggota_kk'] as $anggota) {
                    $dataInsert['id_parent'] = $dokumen->id;
                    $dataInsert['id_pend']   = $anggota;
                    Dokumen::create($dataInsert);
                }
            }
            redirect_with('success', 'Dokumen berhasil disimpan', ci_route('penduduk.dokumen', $id_pend));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Dokumen gagal disimpan', ci_route('penduduk.dokumen', $id_pend));
        }
    }

    public function dokumen_update(int $id): void
    {
        isCan('u');

        try {
            $dataUpdate               = Dokumen::validasi($this->input->post());
            $dataUpdate['updated_by'] = $this->session->user;
            if (isset($_FILES['satuan']) && $_FILES['satuan']['error'] == UPLOAD_ERR_OK) {
                $dataUpdate['satuan'] = $this->upload_dokumen();
            }
            $anggotaKK = $dataUpdate['anggota_kk'] ?? [];
            unset($dataUpdate['anggota_kk'], $dataUpdate['id_pend']);

            $dokumen = Dokumen::find($id);
            $dokumen->update($dataUpdate);

            $id_pend = $dokumen->id_pend;

            $dokumenLain      = $dokumen->children;
            $anggotaLain      = $dokumenLain ? $dokumenLain->pluck('id_pend')->all() : [];
            $intersectAnggota = array_intersect($anggotaKK, $anggotaLain);

            foreach ($intersectAnggota as $value) {
                $dokumen->children->firstWhere('id_pend', $value)->update($dataUpdate);
            }

            $diffDeleteAnggota = array_diff($anggotaLain, $anggotaKK);

            foreach ($diffDeleteAnggota as $value) {
                $dokumen->children->firstWhere('id_pend', $value)->delete();
            }

            $diffInsertAnggota = array_diff($anggotaKK, $anggotaLain);

            foreach ($diffInsertAnggota as $value) {
                $dataUpdate['id_parent'] = $dokumen->id;
                $dataUpdate['id_pend']   = $value;
                $dataUpdate['satuan']    = $dokumen->satuan;
                Dokumen::create($dataUpdate);
            }

            redirect_with('success', 'Dokumen berhasil disimpan', ci_route('penduduk.dokumen', $id_pend));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Dokumen gagal disimpan', ci_route('penduduk.dokumen', $id_pend));
        }
    }

    public function delete_dokumen($id_pend, $id = null): void
    {
        isCan('h');

        try {
            Dokumen::whereIdPend($id_pend)->whereIn('id_parent', $this->request['id_cb'] ?? [$id])->delete();
            Dokumen::destroy($this->request['id_cb'] ?? $id);
            redirect_with('success', 'Area berhasil dihapus', ci_route('penduduk.dokumen', $id_pend));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Area gagal dihapus', ci_route('penduduk.dokumen', $id_pend));
        }
    }

    private function upload_dokumen()
    {
        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['file_name']     = namafile($this->input->post('nama', true));

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (! $this->upload->do_upload('satuan')) {
            session_error($this->upload->display_errors(null, null));

            return false;
        }

        return $this->upload->data()['file_name'];
    }

    public function cetak_biodata($id = ''): void
    {
        $data['penduduk'] = PendudukModel::findOrFail($id);
        view('admin.penduduk.cetak_biodata', $data);
    }

    public function insert($peristiwa): void
    {
        isCan('u');
        $data                    = $this->input->post();
        $originalInput           = $data;
        $data['tgl_lapor']       = rev_tgl($data['tgl_lapor']);
        $data['tgl_peristiwa']   = $data['tgl_peristiwa'] ? rev_tgl($data['tgl_peristiwa']) : rev_tgl($data['tanggallahir']);
        $data['jenis_peristiwa'] = $peristiwa;
        $validasiPenduduk        = PendudukModel::validasi($data);
        if (! $validasiPenduduk['status']) {
            set_session('old_input', $originalInput);
            redirect_with('error', $validasiPenduduk['messages'], ci_route('penduduk.form_peristiwa', $data['jenis_peristiwa']));
        }
        unset($data['file_foto'], $data['old_foto'], $data['nik_lama'], $data['dusun'], $data['rw']);

        DB::beginTransaction();

        try {
            $penduduk = PendudukModel::baru($data);
            DB::commit();
            redirect_with('success', 'Penduduk baru berhasil ditambahkan', ci_route('penduduk.detail', $penduduk->id));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            DB::rollBack();
            set_session('old_input', $originalInput);
            redirect_with('error', 'Penduduk baru gagal ditambahkan', ci_route('penduduk.form_peristiwa.' . $data['jenis_peristiwa']));
        }
    }

    public function update($id = ''): void
    {
        isCan('u');
        $data                  = $this->input->post();
        $originalInput         = $data;
        $data['tgl_lapor']     = rev_tgl($data['tgl_lapor']);
        $data['tgl_peristiwa'] = $data['tgl_peristiwa'] ? rev_tgl($data['tgl_peristiwa']) : rev_tgl($data['tanggallahir']);
        $validasiPenduduk      = PendudukModel::validasi($data, $id);
        $penduduk              = PendudukModel::findOrFail($id);
        if ($penduduk->status_dasar != StatusDasarEnum::HIDUP) {
            set_session('old_input', $originalInput);
            redirect_with('error', 'Data penduduk dengan status dasar MATI/HILANG/PINDAH tidak dapat diubah!', ci_route('penduduk.form_peristiwa', $id));
        }
        if (! $validasiPenduduk['status']) {
            set_session('old_input', $originalInput);
            redirect_with('error', $validasiPenduduk['messages'], ci_route('penduduk.form', $id));
        }

        unset($data['file_foto'], $data['old_foto'], $data['nik_lama'], $data['dusun'], $data['rw']);

        DB::beginTransaction();

        try {
            $penduduk->ubah($data);
            DB::commit();
            redirect_with('success', 'Penduduk berhasil diubah', ci_route('penduduk.detail', $penduduk->id));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            DB::rollBack();
            set_session('old_input', $originalInput);
            redirect_with('error', 'Penduduk baru gagal diubah', ci_route('penduduk.form.', $penduduk->id));
        }
    }

    public function delete($id = ''): void
    {
        isCan('h');
        if (data_lengkap()) {
            redirect_with('error', 'Data tidak dapat proses karena sudah dinyatakan lengkap');
        }
        akun_demo($id);
        $penduduk = PendudukModel::findOrFail($id);

        if ($penduduk->pamongUser()->exists()) {
            redirect_with('error', 'Tidak dapat menghapus penduduk karena sudah terdaftar sebagai pengguna.');
        }

        if ($bantuan = $penduduk->pesertaBantuan()->get()) {
            $links = $bantuan->map(static fn ($item) => '<li><a href="' . ci_route("peserta_bantuan.detail.{$item->program_id}") . '" target="_blank">' . $item->bantuan->nama . '</a></li>')->implode('');

            $links = "<ul>{$links}</ul>";

            redirect_with('error', "Tidak dapat menghapus penduduk karena sudah terdaftar sebagai peserta bantuan: {$links}", '', true);
        }

        if ($penduduk->logSurat()->exists()) {
            redirect_with('error', 'Tidak dapat menghapus penduduk karena sudah terdaftar di Arsip Layanan Surat.');
        }

        $penduduk->delete();

        redirect_with('success', 'Penduduk berhasil dihapus', ci_route('penduduk'));
    }

    public function delete_all(): void
    {
        isCan('h');

        if (data_lengkap()) {
            redirect_with('error', 'Data tidak dapat proses karena sudah dinyatakan lengkap');
        }
        $ids = $this->request['id_cb'];
        akun_demo($ids[0]);

        foreach (PendudukModel::whereIn('id', $ids)->get() as $penduduk) {
            $penduduk->delete();
        }
        redirect_with('success', 'Penduduk berhasil dihapus', ci_route('penduduk'));
    }

    public function ajax_adv_search(): void
    {
        $listSearch = $this->session->userdata('advance_search');

        foreach ($listSearch as $key => $item) {
            $data[$key] = $item;
        }

        $data['input_umur']           = true;
        $data['list_agama']           = AgamaEnum::all();
        $data['list_pendidikan']      = PendidikanSedangEnum::all();
        $data['list_pendidikan_kk']   = PendidikanKKEnum::all();
        $data['list_pekerjaan']       = PekerjaanEnum::all();
        $data['list_status_kawin']    = StatusKawinSpesifikEnum::all();
        $data['list_status_penduduk'] = StatusPendudukEnum::all();
        $data['list_sex']             = JenisKelaminEnum::all();
        $data['list_status_dasar']    = StatusDasarEnum::all();
        $data['list_cacat']           = CacatEnum::all();
        $data['list_cara_kb']         = CaraKBEnum::all();
        $data['list_status_ktp']      = StatusKTPEnum::all();
        $data['list_asuransi']        = AsuransiEnum::all();
        $data['list_warganegara']     = WargaNegaraEnum::all();
        $data['list_golongan_darah']  = GolonganDarahEnum::all();
        $data['list_sakit_menahun']   = SakitMenahunEnum::all();
        $data['list_tag_id_card']     = StatusEnum::all();
        $data['list_id_kk']           = StatusEnum::all();
        $data['form_action']          = ci_route('penduduk.adv_search_proses');

        view('admin.penduduk.ajax_adv_search_form', $data);
    }

    public function adv_search_proses(): void
    {
        $this->advanceSearch = $this->validasi_pencarian($this->input->post());
        $this->index();
    }

    private function validasi_pencarian($post)
    {
        $data['umur']                 = $post['umur'];
        $data['umur_min']             = bilangan($post['umur_min']);
        $data['umur_max']             = bilangan($post['umur_max']);
        $data['pekerjaan_id']         = $post['pekerjaan_id'];
        $data['status']               = $post['status'];
        $data['status_kawin']         = $post['status_kawin'];
        $data['agama']                = $post['agama'];
        $data['pendidikan_sedang_id'] = $post['pendidikan_sedang_id'];
        $data['pendidikan_kk_id']     = $post['pendidikan_kk_id'];
        $data['status_penduduk']      = $post['status_penduduk'];
        $data['filter']               = $post['status_penduduk'];
        $data['sex']                  = $post['sex'];
        $data['status_dasar']         = $post['status_dasar'];
        $data['cara_kb_id']           = $post['cara_kb_id'];
        $data['status_ktp']           = $post['status_ktp'];
        $data['id_asuransi']          = $post['id_asuransi'];
        $data['warganegara']          = $post['warganegara'];
        $data['golongan_darah']       = $post['golongan_darah'];
        $data['menahun']              = $post['menahun'];
        $data['cacat']                = $post['cacat'];
        $data['tag_id_card']          = $post['tag_id_card'];
        $data['id_kk']                = $post['id_kk'];

        return $data;
    }

    public function ajax_penduduk_maps($id = null, $edit = '1'): void
    {
        isCan('u');
        $penduduk = PendudukModel::withOnly(['keluarga', 'rtm', 'map'])->findOrFail($id);

        if ($penduduk->map === null && $edit !== '2') {
            redirect(ci_route("penduduk.ajax_penduduk_maps.{$id}.2"));
        }

        $data['id']       = $id;
        $data['edit']     = $edit;
        $data['penduduk'] = ['nama' => $penduduk->nama, 'status_dasar' => $penduduk->status_dasar];
        if ($penduduk->lokasi) {
            $data['penduduk'] = array_merge($penduduk->lokasi->toArray(), $data['penduduk']);
        } elseif ($penduduk->map) {
            $data['penduduk'] = array_merge($penduduk->map->toArray(), $data['penduduk']);
        }
        $data['wil_atas']    = $this->header['desa'];
        $data['dusun_gis']   = Wilayah::dusun()->get()->toArray();
        $data['rw_gis']      = Wilayah::rw()->get()->toArray();
        $data['rt_gis']      = Wilayah::rt()->get()->toArray();
        $data['form_action'] = ci_route("penduduk.update_maps.{$id}.{$data['edit']}");

        view('admin.penduduk.ajax_penduduk_maps', $data);
    }

    public function update_maps($id = '', $edit = ''): void
    {
        isCan('u');

        $penduduk = PendudukSaja::findOrFail($id);

        if ($penduduk->status_dasar != StatusDasarEnum::HIDUP) {
            redirect_with('error', 'Data penduduk dengan status dasar MATI/HILANG/PINDAH tidak dapat diubah!', ci_route("penduduk.ajax_penduduk_maps.{$id}.{$edit}"));
        }
        $data = $_POST;
        unset($data['zoom'], $data['map_tipe']);
        $map      = $penduduk->map ?? new PendudukMap(['id' => $penduduk->id]);
        $map->lat = $data['lat'];
        $map->lng = $data['lng'];
        $map->save();

        // jika penduduk adalah kepala keluarga maka ubah anggota keluarga lainnya
        if ($penduduk->isKepalaKeluarga()) {
            $anggotaKeluarga = PendudukSaja::with(['map'])->status(StatusDasarEnum::HIDUP)->where('id_kk', $penduduk->id_kk)->where('id', '!=', $penduduk->id)->get();
            if (! $anggotaKeluarga->isEmpty()) {
                foreach ($anggotaKeluarga as $anggota) {
                    $mapAnggota      = $anggota->map ?? new PendudukMap(['id' => $anggota->id]);
                    $mapAnggota->lat = $data['lat'];
                    $mapAnggota->lng = $data['lng'];
                    $mapAnggota->save();
                }
            }
        }

        set_session('success', 'Data berhasil disimpan');

        if ($edit == 1) {
            redirect(ci_route("penduduk.form.{$id}"));
        } else {
            redirect(ci_route('penduduk'));
        }
    }

    public function edit_status_dasar($id = 0): void
    {
        isCan('u');
        if (! data_lengkap()) {
            session_error('Data tidak dapat proses karena sudah dinyatakan lengkap');

            redirect(ci_route('penduduk'));
        }

        $data['nik']             = PendudukModel::with('keluarga.anggota')->findOrFail($id);
        $data['form_action']     = ci_route('penduduk.update_status_dasar', $id);
        $data['list_ref_pindah'] = PindahEnum::all();
        $data['sebab']           = unserialize(SEBAB);
        $data['penolong_mati']   = unserialize(PENOLONG_MATI);

        // pengecualian kk level kepala keluarga
        $excludeStatusMati = $data['nik']['kk_level'] == SHDKEnum::KEPALA_KELUARGA
            && $data['nik']?->keluarga?->anggota?->count() > 1
            ? StatusDasarEnum::MATI
            : null;

        // pengecualian status dasar: Penduduk Tetap => ('TIDAK VALID', 'HIDUP', 'PERGI') , Penduduk Tidak Tetap => ('TIDAK VALID', 'HIDUP')
        $excludeStatus = $data['nik']['status'] == StatusPendudukEnum::TETAP
            ? [StatusDasarEnum::TIDAK_VALID, StatusDasarEnum::HIDUP, StatusDasarEnum::PERGI, $excludeStatusMati]
            : [StatusDasarEnum::TIDAK_VALID, StatusDasarEnum::HIDUP, $excludeStatusMati];

        $data['list_status_dasar'] = collect(StatusDasarEnum::all())->filter(static fn ($key, $item) => ! in_array($item, $excludeStatus))->all();

        view('admin.penduduk.ajax_edit_status_dasar', $data);
    }

    public function update_status_dasar($id = ''): void
    {
        isCan('u');
        if (! data_lengkap()) {
            redirect_with('error', 'Data tidak dapat proses karena sudah dinyatakan lengkap', ci_route('penduduk'));
        }
        akun_demo($id);

        $data['kelahiran_anak_ke'] = (int) $this->input->post('anak_ke');
        $data['status_dasar']      = $this->input->post('status_dasar');
        $penduduk                  = PendudukModel::findOrFail($id);
        $penduduk->status_dasar    = $data['status_dasar'];
        $penduduk->save();
        // Tulis log_penduduk
        $log = [
            'config_id'      => identitas('id'),
            'id_pend'        => $id,
            'no_kk'          => $penduduk->keluarga->no_kk ?? '',
            'nama_kk'        => $penduduk->keluarga->kepalaKeluarga->nama ?? '',
            'tgl_peristiwa'  => rev_tgl($this->input->post('tgl_peristiwa')),
            'tgl_lapor'      => rev_tgl($this->input->post('tgl_lapor')),
            'kode_peristiwa' => $data['status_dasar'],
            'catatan'        => alfanumerik_spasi($this->input->post('catatan')),
            'meninggal_di'   => alfanumerik_spasi($this->input->post('meninggal_di')),
            'jam_mati'       => $this->input->post('jam_mati'),
            'sebab'          => (int) ($this->input->post('sebab')),
            'penolong_mati'  => (int) ($this->input->post('penolong_mati')),
            'akta_mati'      => $this->input->post('akta_mati'),
            'created_by'     => ci_auth()->id,
        ];

        if ($log['kode_peristiwa'] == 2 && ! empty($_FILES['nama_file']['name'])) {
            $log['file_akta_mati'] = $this->upload_akta_mati($id);
        }

        if ($log['kode_peristiwa'] == 3) {
            $pindah               = $this->input->post('ref_pindah');
            $log['ref_pindah']    = empty($pindah) ? 1 : $pindah;
            $log['alamat_tujuan'] = $this->input->post('alamat_tujuan');
        }
        $penduduk->log()->upsert($log, ['config_id', 'kode_peristiwa', 'tgl_peristiwa', 'id_pend']);

        // Tulis log_keluarga jika penduduk adalah kepala keluarga
        if ($penduduk->kk_level == SHDKEnum::KEPALA_KELUARGA && $penduduk->id_kk) {
            $id_peristiwa = $penduduk->status_dasar; // lihat kode di keluarga_model
            $log_keluarga = [
                'id_kk'           => $penduduk->id_kk,
                'id_peristiwa'    => $id_peristiwa,
                'tgl_peristiwa'   => date('Y-m-d H:i:s'),
                'id_pend'         => null,
                'id_log_penduduk' => LogPenduduk::where(['kode_peristiwa' => $log['kode_peristiwa'], 'id_pend' => $penduduk->id, 'tgl_peristiwa' => $log['tgl_peristiwa']])->first()->id ?? null,
                'updated_by'      => ci_auth()->id,
            ];
            LogKeluarga::create($log_keluarga);
        }

        redirect("{$this->controller}");
    }

    private function upload_akta_mati($id)
    {
        $this->load->library('upload');

        $config = [
            'upload_path'   => LOKASI_DOKUMEN,
            'allowed_types' => 'jpg|jpeg|png|pdf',
            'max_size'      => 1024 * 10,
            'file_name'     => 'akta_mati_' . $id . '_' . time(),
        ];

        $this->upload->initialize($config);

        if (! $this->upload->do_upload('nama_file')) {
            session_error($this->upload->display_errors());
            redirect($this->controller);
        }

        $uploadData = $this->upload->data();

        return $uploadData['file_name'];
    }

    public function kembalikan_status($id = ''): void
    {
        isCan('u');
        $penduduk               = PendudukModel::findOrFail($id);
        $penduduk->status_dasar = StatusDasarEnum::HIDUP;
        $penduduk->save();

        // Jika peristiwa lahir akan mengambil data dari field tanggal lahir
        $x = [
            'tgl_peristiwa'  => Carbon::now(),
            'kode_peristiwa' => LogPenduduk::BARU_PINDAH_MASUK,
            'tgl_lapor'      => Carbon::now(),
            'created_by'     => ci_auth()->id,
        ];

        $penduduk->log()->create($x);
        redirect('penduduk');
    }

    public function cetak($aksi = 'cetak', $privasi_nik = 0): void
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
            'judul' => $this->input->post('judul'),
        ];
        if ($privasi_nik == 1) {
            $data['privasi_nik'] = true;
        }
        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=Penduduk_' . date('Ymd') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        view('admin.penduduk.cetak', $data);
    }

    public function statistik($tipe = '0', $nomor = 0, $sex = null): void
    {
        $this->statistikFilter['status_dasar'] = StatusDasarEnum::HIDUP;
        $dusun                                 = $this->input->get('dusun') ?? null;
        $rw                                    = $this->input->get('rw') ?? null;
        $rt                                    = $this->input->get('rt') ?? null;
        $idCluster                             = $this->input->get('idCluster') ?? null;

        if (! empty($dusun)) {
            $this->statistikFilter['dusun'] = $dusun;
        }
        if (! empty($rw)) {
            $this->statistikFilter['rw'] = $dusun . '__' . $rw;
        }
        if (! empty($rt)) {
            $this->statistikFilter['rt'] = $rt;
        }
        if (! empty($sex)) {
            $this->statistikFilter['sex'] = $sex;
        }

        switch ($tipe) {
            case '0':
                $session  = 'pendidikan_kk_id';
                $kategori = 'PENDIDIKAN DALAM KK : ';
                break;

            case 1:
                $session  = 'pekerjaan_id';
                $kategori = 'PEKERJAAN : ';
                break;

            case 2:
                $session  = 'status_kawin';
                $kategori = 'STATUS PERKAWINAN : ';
                break;

            case 3:
                $session  = 'agama';
                $kategori = 'AGAMA : ';
                break;

            case 4:
                $session  = 'sex';
                $kategori = 'JENIS KELAMIN : ';
                break;

            case 5:
                $session  = 'warganegara';
                $kategori = 'WARGANEGARA : ';
                break;

            case 6:
                $session  = 'status_penduduk';
                $kategori = 'STATUS PENDUDUK : ';
                break;

            case 7:
                $session  = 'golongan_darah';
                $kategori = 'GOLONGAN DARAH : ';
                break;

            case 9:
                $session  = 'cacat';
                $kategori = 'CACAT : ';
                break;

            case 10:
                $session  = 'menahun';
                $kategori = 'SAKIT MENAHUN : ';
                break;

            case 13:
                $session  = 'umurx';
                $kategori = 'UMUR (RENTANG) : ';
                break;

            case 14:
                $session  = 'pendidikan_sedang_id';
                $kategori = 'PENDIDIKAN SEDANG DITEMPUH : ';
                break;

            case 15:
                $session  = 'umurx';
                $kategori = 'UMUR (KATEGORI) : ';
                break;

            case 16:
                $session  = 'cara_kb_id';
                $kategori = 'CARA KB : ';
                break;

            case 17:
                $session  = 'akta_kelahiran';
                $kategori = 'AKTA KELAHIRAN : UMUR ';
                break;

            case 19:
                $session  = 'id_asuransi';
                $kategori = 'ASURANSI KESEHATAN : ';
                break;

            case 'bpjs-tenagakerja':
                // $session  = ($nomor == BELUM_MENGISI || $nomor == JUMLAH) ? 'bpjs_ketenagakerjaan' : 'pekerjaan_id';
                $session  = 'bpjs_ketenagakerjaan';
                $kategori = 'BPJS Ketenagakerjaan : ';
                // $this->session->bpjs_ketenagakerjaan = $nomor != TOTAL;
                break;

            case 'status-asuransi-kesehatan':
                $session  = 'status_asuransi_kesehatan';
                $kategori = 'Status Kepersertaan Asuransi Kesehatan : ';
                break;

            case 'hubungan_kk':
                $session  = 'hubungan';
                $kategori = 'HUBUNGAN DALAM KK : ';
                break;

            case 'covid':
                $session  = 'status_covid';
                $kategori = 'STATUS COVID : ';
                break;

            case 'bantuan_penduduk':
                if (! in_array($nomor, [BELUM_MENGISI, TOTAL])) {
                    $this->statistikFilter['status_dasar'] = null;
                } // tampilkan semua peserta walaupun bukan hidup/aktif
                $session  = 'bantuan_penduduk';
                $kategori = 'PENERIMA BANTUAN PENDUDUK : ';
                break;

            case 18:
                if ($sex == null) {
                    $this->statistikFilter['status_ktp'] = 0;
                    $this->statistikFilter['sex']        = ($nomor == 0) ? null : $nomor;
                    $sex                                 = $this->statistikFilter['sex'];
                    unset($nomor);
                } else {
                    $this->statistikFilter['status_ktp'] = $nomor;
                }

                $kategori = 'KEPEMILIKAN WAJIB KTP : ';
                break;

            case 'suku':
                $session  = 'suku';
                $kategori = 'Suku : ';
                break;

            case 'hamil':
                $session  = 'hamil';
                $kategori = 'STATUS KEHAMILAN : ';
                break;

            case 'buku-nikah':
                $session  = 'buku-nikah';
                $kategori = 'STATUS PERKAWINAN : ';
                break;

            case 'kia':
                $session  = 'kia';
                $kategori = 'KEPEMILIKAN KIA : ';
                break;

            case $tipe > 50:
                $program_id = preg_replace('/^50/', '', $tipe);

                $this->statistikFilter['program_bantuan'] = $program_id;

                // TODO: Sederhanakan query ini, pindahkan ke model
                $nama = Bantuan::find($program_id)->nama ?? '-';
                if (! in_array($nomor, [BELUM_MENGISI, TOTAL])) {
                    $nomor = $program_id;
                }
                $kategori = $nama . ' : ';
                $session  = 'bantuan_penduduk';
                $tipe     = 'bantuan_penduduk';
                break;
        }

        // Filter berdasarkan kategori tdk dilakukan jika $nomer = TOTAL (888)
        if ($tipe != 18 && $nomor != TOTAL) {
            $this->statistikFilter[$session] = rawurldecode($nomor);
        }
        // pengecualian untuk kia dan 18
        if (in_array($tipe, ['18', 'kia', 'buku-nikah'])) {
            $this->statistikFilter[$session] = rawurldecode($nomor);
        }

        $judul = $this->get_judul_statistik($tipe, $nomor, $sex);
        // Laporan wajib KTP berbeda - menampilkan sebagian dari penduduk, jadi selalu perlu judul
        if ($judul['nama'] || $tipe = 18) {
            $this->judulStatistik = $kategori . $judul['nama'];
        }

        $this->index();
    }

    public function lap_statistik($id_cluster = 0, $tipe = 0, $nomor = 0): void
    {
        $this->statistikFilter['id_cluster'] = $id_cluster;
        if ($nomor) {
            $this->statistikFilter['sex'] = $nomor;
        }

        switch ($tipe) {
            case 1:
                $this->statistikFilter['sex'] = '1';
                $pre                          = 'JENIS KELAMIN LAKI-LAKI  ';
                break;

            case 2:
                $this->statistikFilter['sex'] = '2';
                $pre                          = 'JENIS KELAMIN PEREMPUAN ';
                break;

            case 3:
                $this->statistikFilter['umur_min'] = '0';
                $this->statistikFilter['umur_max'] = '0';
                $pre                               = 'BERUMUR <1 ';
                break;

            case 4:
                $this->statistikFilter['umur_min'] = '1';
                $this->statistikFilter['umur_max'] = '5';
                $pre                               = 'BERUMUR 1-5 ';
                break;

            case 5:
                $this->statistikFilter['umur_min'] = '6';
                $this->statistikFilter['umur_max'] = '12';
                $pre                               = 'BERUMUR 6-12 ';
                break;

            case 6:
                $this->statistikFilter['umur_min'] = '13';
                $this->statistikFilter['umur_max'] = '15';

                $pre = 'BERUMUR 13-16 ';
                break;

            case 7:
                $this->statistikFilter['umur_min'] = '16';
                $this->statistikFilter['umur_max'] = '18';

                $pre = 'BERUMUR 16-18 ';
                break;

            case 8:
                $this->statistikFilter['umur_min'] = '61';
                $this->statistikFilter['umur_max'] = '9999';
                $pre                               = 'BERUMUR >60';
                break;

            case 91:
            case 92:
            case 93:
            case 94:
            case 95:
            case 96:
            case 97:
                $kode_cacat                     = $tipe - 90;
                $this->statistikFilter['cacat'] = $kode_cacat;

                $stat = $this->get_judul_statistik(9, $kode_cacat, null);
                $pre  = $stat['nama'];
                break;

            case 10:
                $this->statistikFilter['menahun'] = array_diff(SakitMenahunEnum::keys(), [SakitMenahunEnum::TIDAK_ADA_TIDAK_SAKIT]);
                $this->statistikFilter['sex']     = '1';
                $pre                              = 'SAKIT MENAHUN LAKI-LAKI ';
                break;

            case 11:
                $this->statistikFilter['menahun'] = array_diff(SakitMenahunEnum::keys(), [SakitMenahunEnum::TIDAK_ADA_TIDAK_SAKIT]);
                $this->statistikFilter['sex']     = '2';
                $pre                              = 'SAKIT MENAHUN PEREMPUAN ';
                break;

            case 12:
                $this->statistikFilter['hamil'] = '1';
                $pre                            = 'HAMIL ';
                break;
        }

        if ($pre) {
            $this->judulStatistik = $pre;
        }

        $this->index();
    }

    public function search_kumpulan_nik(): void
    {
        view('admin.penduduk.modal.kumpulan_nik');
    }

    public function ajax_cetak(string $aksi = 'cetak'): void
    {
        $data['aksi']   = $aksi;
        $data['action'] = ci_route('penduduk.cetak', $aksi);

        view('admin.penduduk.ajax_cetak_bersama', $data);
    }

    public function program_bantuan(): void
    {
        $data = [
            'program_bantuan' => Bantuan::whereSasaran(SasaranEnum::PENDUDUK)->get(),
        ];

        view('admin.penduduk.modal.program_bantuan', $data);
    }

    /**
     * Unduh berkas berdasarkan kolom dokumen.id
     *
     * @param int   $id_dokumen Id berkas pada koloam dokumen.id
     * @param mixed $tampil
     */
    public function unduh_berkas($id_dokumen = 0, $tampil = false): void
    {
        // Ambil nama berkas dari database
        $data = DokumenHidup::findOrFail($id_dokumen);
        ambilBerkas($data['satuan'], $this->controller . '/dokumen/' . $data['id_pend'], null, LOKASI_DOKUMEN, $tampil);
    }

    public function impor()
    {
        if (config_item('demo_mode') || data_lengkap()) {
            $msg = 'Tidak dapat melakukan impor pada mode demo atau data sudah dinyatakan lengkap';
            redirect_with('error', $msg);
        }

        isCan('u');

        $data = [
            'form_action'          => ci_route('penduduk.proses_impor'),
            'boleh_hapus_penduduk' => $this->impor_model->boleh_hapus_penduduk(),
            'formatImpor'          => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-impor-excel.xlsm')),
        ];

        return view('admin.penduduk.impor', $data);
    }

    public function proses_impor(): void
    {
        if (config_item('demo_mode') || data_lengkap()) {
            redirect($this->controller);
        }

        isCan('u');
        $hapus = isset($_POST['hapus_data']);
        $this->impor_model->impor_excel($hapus);
        shortcut_cache();
        redirect('penduduk/impor');
    }

    public function impor_bip()
    {
        if (config_item('demo_mode') || setting('multi_desa') || data_lengkap()) {
            redirect($this->controller);
        }

        isCan('u');

        $data = [
            'form_action'          => ci_route('penduduk.proses_impor_bip'),
            'boleh_hapus_penduduk' => $this->impor_model->boleh_hapus_penduduk(),
            'formatBip2012'        => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-bip-2012.xls')),
            'formatBip2016'        => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-bip-2016.xls')),
            'formatBipEktp'        => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-bip-ektp.xls')),
            'formatBip2016Lutim'   => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-bip-2016-luwutimur.xls')),
            'formatBipSiak'        => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-siak.xls')),
        ];

        return view('admin.penduduk.impor_bip', $data);
    }

    public function proses_impor_bip(): void
    {
        if (config_item('demo_mode') || setting('multi_desa') || data_lengkap()) {
            redirect($this->controller);
        }

        isCan('u');

        // TODO: Sederhanakan query ini, pindahkan ke model
        if (PendudukModel::count() > 0) {
            redirect_with('error', 'Tidak dapat mengimpor BIP ketika data penduduk telah ada', 'penduduk/impor_bip');
        }

        $this->impor_model->impor_bip($this->input->post('hapus_data'));
        shortcut_cache();
        redirect('penduduk/impor_bip');
    }

    public function ekspor($huruf = null): void
    {
        try {
            $daftar_kolom = $this->impor_model->daftar_kolom;

            $writer = new Writer();
            $writer->openToBrowser(namafile('penduduk') . '.xlsx');
            $writer->getCurrentSheet()->setName('Data Penduduk');
            $writer->addRow(Row::fromValues($daftar_kolom));
            //Isi Tabel
            $paramDatatable = json_decode($this->input->get('params'), 1);
            $_GET           = $paramDatatable;
            // harusnya order by no_kk
            $get = $this->sumberData()->leftJoin('tweb_keluarga', 'tweb_keluarga.id', '=', 'tweb_penduduk.id_kk')->with(['map'])->orderBy('tweb_keluarga.no_kk', 'asc')->orderBy('kk_level', 'asc')->get();

            foreach ($get as $row) {
                $penduduk                  = [];
                $row->alamat               = $row->keluarga->alamat ?? $row->alamat;
                $row->dusun                = $row->wilayah->dusun ?? '-';
                $row->rw                   = $row->wilayah->rw ?? '-';
                $row->rt                   = $row->wilayah->rt ?? '-';
                $row->no_kk                = $row->keluarga->no_kk;
                $row->sex                  = $huruf ? JenisKelaminEnum::valueOf($row->sex) : $row->sex;
                $row->tanggallahir_str     = $row->tanggallahir?->format('Y-m-d');
                $row->agama_id             = $huruf ? $row->agama->nama : $row->agama_id;
                $row->pendidikan_kk_id     = $huruf ? $row->pendidikanKK->nama : $row->pendidikan_kk_id;
                $row->pendidikan_sedang_id = $huruf ? $row->pendidikan : $row->pendidikan_sedang_id;
                $row->pekerjaan_id         = $huruf ? $row->pekerjaan->nama : $row->pekerjaan_id;
                $row->status_kawin         = $huruf ? $row->status_perkawinan : $row->status_kawin;
                $row->kk_level             = $huruf ? SHDKEnum::valueOf($row->kk_level) : $row->kk_level;
                $row->warganegara_id       = $huruf ? $row->warganegara->nama : $row->warganegara_id;
                $row->golongan_darah_id    = $huruf ? $row->golonganDarah->nama : $row->golongan_darah_id;
                $row->tanggal_akhir_paspor = $row->tanggal_akhir_paspor ? date_format(date_create($row->tanggal_akhir_paspor), 'Y-m-d') : '';
                $row->tanggalperkawinan    = $row->tanggalperkawinan ? date_format(date_create($row->tanggalperkawinan), 'Y-m-d') : '';
                $row->tanggalperceraian    = $row->tanggalperceraian ? date_format(date_create($row->tanggalperceraian), 'Y-m-d') : '';
                $row->cacat_id             = $huruf ? $row->cacat->nama : $row->cacat_id;
                $row->cara_kb_id           = $huruf ? CaraKBEnum::valueOf($row->cara_kb_id) : $row->cara_kb_id;
                $row->hamil                = $huruf ? HamilEnum::valueOf($row->hamil) : $row->hamil;
                $row->status_rekam         = $huruf ? StatusKTPEnum::valueOf($row->status_rekam) : $row->status_rekam;
                $row->status_dasar         = $huruf ? StatusDasarEnum::valueOf($row->status_dasar) : $row->status_dasar;
                $row->lat                  = $row->map->lat;
                $row->lng                  = $row->map->lng;

                foreach ($daftar_kolom as $kolom) {
                    // $this->bersihkanData($row, $kolom);
                    if ($kolom == 'tanggallahir') {
                        $kolom = 'tanggallahir_str';
                    }
                    $penduduk[] = $this->bersihkanData($row->{$kolom}, $kolom);
                }

                $writer->addRow(Row::fromValues($penduduk));
            }
            $writer->close();
        } catch (Exception $e) {
            log_message('error', $e);

            set_session('notif', 'Tidak berhasil mengekspor data penduduk, harap mencoba kembali.');

            redirect('penduduk');
        }
    }

    private function bersihkanData($str, $key): string
    {
        if (null === $str) $str = '';

        if (strstr($str, '"')) {
            return '"' . str_replace('"', '""', $str) . '"';
        }
        // Kode yang tersimpan sebagai '0' harus '' untuk dibaca oleh Import Excel
        $kecuali = ['nik', 'no_kk'];
        if ($str != '0') {
            return $str;
        }
        if (in_array($key, $kecuali)) {
            return $str;
        }

        return '';
    }

    public function foto_bawaan($id)
    {
        $penduduk = PendudukModel::findOrFail($id);

        // Hapus file foto penduduk yg di hapus di folder desa/upload/user_pict
        $file_foto = LOKASI_USER_PICT . $penduduk->foto;
        if (is_file($file_foto)) {
            unlink($file_foto);
        }
        // Hapus file foto kecil penduduk yg di hapus di folder desa/upload/user_pict
        $file_foto_kecil = LOKASI_USER_PICT . 'kecil_' . $penduduk->foto;
        if (is_file($file_foto_kecil)) {
            unlink($file_foto_kecil);
        }
        $penduduk->foto = null;
        $penduduk->save();
        redirect(ci_route('penduduk.form', $penduduk->id));
    }

    public function get_judul_statistik($tipe = '0', $nomor = 0, $sex = null)
    {
        $filter = ['id' => $nomor];
        if ($nomor == JUMLAH) {
            $judul = ['nama' => 'JUMLAH'];
        } elseif ($nomor == BELUM_MENGISI) {
            $judul = ['nama' => 'BELUM MENGISI'];
        } elseif ($nomor == TOTAL) {
            $judul = ['nama' => 'TOTAL'];
        } else {
            switch ($tipe) {
                case '0':
                    $table = 'tweb_penduduk_pendidikan_kk';
                    break;

                case 1:
                case 'bpjs-tenagakerja':
                    $table = 'tweb_penduduk_pekerjaan';
                    break;

                case 'status-asuransi-kesehatan':
                    $table               = 'tweb_penduduk';
                    $filter['config_id'] = identitas('id');
                    break;
                    break;

                case 2:
                case 'buku-nikah':
                    $table = 'tweb_penduduk_kawin';
                    break;

                case 3:
                    $table = 'tweb_penduduk_agama';
                    break;

                case 4:
                    $table = 'tweb_penduduk_sex';
                    break;

                case 5:
                    $table = 'tweb_penduduk_warganegara';
                    break;

                case 6:
                    $table = 'tweb_penduduk_status';
                    break;

                case 7:
                    $table = 'tweb_golongan_darah';
                    break;

                case 9:
                    $table = 'tweb_cacat';
                    break;

                case 10:
                    $table = 'tweb_sakit_menahun';
                    break;

                case 14:
                    $table = 'tweb_penduduk_pendidikan';
                    break;

                case 16:
                    $table = 'tweb_cara_kb';
                    break;

                case 13: // = 17
                case 15: // = 17
                case 17: // = 17
                case 'akta-kematian': // = 17
                    $table               = 'tweb_penduduk_umur';
                    $filter['config_id'] = identitas('id');
                    break;

                case 18:
                case 'kia':
                    $table = 'tweb_status_ktp';
                    break;

                case 19:
                    $table = 'tweb_penduduk_asuransi';
                    break;

                case 'covid':
                    $table = 'ref_status_covid';
                    break;

                case 'bantuan_penduduk':
                    $table               = 'program';
                    $filter['config_id'] = identitas('id');
                    break;

                case 'hubungan_kk':
                    $table = 'tweb_penduduk_hubungan';
                    break;

                case 'suku':
                    $table               = 'tweb_penduduk';
                    $filter['config_id'] = identitas('id');
                    break;

                case 'hamil':
                    $table = 'ref_penduduk_hamil';
                    break;
            }

            if ($tipe == 13 || $tipe == 17) {
                $filter['status'] = 1;
            }
            if ($tipe == 15) {
                $filter['status'] = 0;
            }

            $judul = (array) DB::table($table)->where($filter)->get()->first();

            if ($tipe == 'suku') {
                $judul['nama'] = rawurldecode($nomor);
            }
        }

        if ($sex == 1) {
            $judul['nama'] .= ' - LAKI-LAKI';
        } elseif ($sex == 2) {
            $judul['nama'] .= ' - PEREMPUAN';
        }

        return $judul;
    }
}
