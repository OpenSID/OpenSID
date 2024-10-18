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

use App\Models\Keluarga;
use App\Models\Pamong;
use App\Models\Penduduk;
use App\Models\Wilayah as WilayahModel;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Wilayah extends Admin_Controller
{
    public $modul_ini              = 'info-desa';
    public $sub_modul_ini          = 'wilayah-administratif';
    private array $subordinatLevel = ['dusun' => 'rw', 'rw' => 'rt'];
    private int $parent;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index($parent = '', $level = 'dusun'): void
    {
        $level   = $this->input->get('level') ?? 'dusun';
        $parent  = $this->input->get('parent') ?? '';
        $title   = '';
        $backUrl = ci_route('wilayah.index');
        $wilayah = $parent ? WilayahModel::find($parent) : collect();

        switch ($level) {
            case 'rt':
                $title   .= 'RW ' . ($wilayah->rw ?? '') . ' / Dusun ' . ($wilayah->dusun ?? '');
                $backUrl .= '?parent=' . WilayahModel::where(['dusun' => $wilayah->dusun])->dusun()->first()->id . '&level=rw';
                $adaUrutKosong = WilayahModel::rt()->whereRw($wilayah->rw)->where('rt', '!=', '-')->whereDusun($wilayah->dusun)->whereNull('urut')->count();
                break;

            case 'rw':
                $title .= 'Dusun ' . $wilayah->dusun ?? '';
                $adaUrutKosong = WilayahModel::rw()->whereDusun($wilayah->dusun)->whereNull('urut')->count();
                break;

            default:
                $adaUrutKosong = WilayahModel::dusun()->whereNotNull('urut')->count();
        }
        $data = [
            'parent'       => $parent,
            'wilayah'      => $level == 'dusun' ? ucwords(setting('sebutan_dusun')) : strtoupper($level),
            'jabatan'      => $level == 'dusun' ? 'Kepala' : 'Ketua',
            'level'        => $level,
            'title'        => $title,
            'backUrl'      => $backUrl,
            'refreshOrder' => $adaUrutKosong,
        ];

        view('admin.wilayah.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $parent = $this->input->get('parent');
            $level  = $this->input->get('level');

            $subOrdinat = $this->subordinatLevel[$level] ?? '';

            switch ($level) {
                case 'rw':
                    $mapKantor       = 'ajax_kantor_rw_maps';
                    $mapWilayah      = 'ajax_wilayah_rw_maps';
                    $wilayah         = WilayahModel::find($parent);
                    $cek_lokasi_peta = cek_lokasi_peta($wilayah->toArray());
                    $model           = WilayahModel::rw()->whereDusun($wilayah->dusun)->with(['kepala'])->orderBy('urut')
                        ->withCount(['rts' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_0.rw = tweb_wil_clusterdesa.rw')), 'keluargaAktif' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_1.rw = tweb_wil_clusterdesa.rw')), 'pendudukPria' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_2.rw = tweb_wil_clusterdesa.rw')), 'pendudukWanita' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_3.rw = tweb_wil_clusterdesa.rw'))]);
                    break;

                case 'rt':
                    $mapKantor  = 'ajax_kantor_rt_maps';
                    $mapWilayah = 'ajax_wilayah_rt_maps';
                    $wilayah    = WilayahModel::find($parent);
                    $wilayahRw  = $wilayah->toArray();
                    if ($wilayah->rw == '-') {
                        $wilayahRw = WilayahModel::dusun()->whereDusun($wilayah->dusun)->first()->toArray();
                    }
                    $cek_lokasi_peta = cek_lokasi_peta($wilayahRw);
                    $model           = WilayahModel::rt()->whereRw($wilayah->rw)->where('rt', '!=', '-')->whereDusun($wilayah->dusun)->with(['kepala'])->orderBy('urut')
                        ->withCount(['keluargaAktif' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_0.rw = tweb_wil_clusterdesa.rw and laravel_reserved_0.rt = tweb_wil_clusterdesa.rt')), 'pendudukPria' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_1.rw = tweb_wil_clusterdesa.rw and laravel_reserved_1.rt = tweb_wil_clusterdesa.rt')), 'pendudukWanita' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_2.rw = tweb_wil_clusterdesa.rw and laravel_reserved_2.rt = tweb_wil_clusterdesa.rt'))]);
                    break;

                default:
                    $model           = WilayahModel::dusun()->with(['kepala'])->orderBy('urut')->withCount('rts', 'rws', 'keluargaAktif', 'pendudukPria', 'pendudukWanita');
                    $cek_lokasi_peta = cek_lokasi_peta(collect(identitas())->toArray());
                    $mapKantor       = 'ajax_kantor_dusun_maps';
                    $mapWilayah      = 'ajax_wilayah_dusun_maps';
            }

            return datatables()->of($model)
                ->addIndexColumn()
                ->addColumn('drag-handle', static fn (): string => '<i class="fa fa-sort-alpha-desc"></i>')
                ->addColumn('aksi', static function ($row) use ($parent, $mapKantor, $mapWilayah, $level, $subOrdinat, $cek_lokasi_peta): string {
                    $aksi = '';
                    if ($level != 'rt') {
                        $aksi .= '<a href="' . ci_route('wilayah.index') . '?parent=' . $row->id . '&level=' . $subOrdinat . '" class="btn bg-purple btn-sm" title="Rincian Sub Wilayah"><i class="fa fa-list"></i></a> ';
                    }
                    if (can('u')) {
                        if ($level == 'rw') {
                            if ($row->rw != '-') {
                                $aksi .= '<a href="' . ci_route('wilayah.form_' . $level, "{$parent}/{$row->id}") . '" class="btn bg-orange btn-sm" title="Ubah"><i class="fa fa-edit"></i></a> ';
                            }
                        } else {
                            $aksi .= '<a href="' . ci_route('wilayah.form_' . $level, "{$parent}/{$row->id}") . '" class="btn bg-orange btn-sm" title="Ubah"><i class="fa fa-edit"></i></a> ';
                        }
                    }
                    if (can('h')) {
                        $disabled = '';
                        if (($row->penduduk_pria_count + $row->penduduk_wanita_count) > 0) {
                            $disabled = 'disabled';
                        }

                        if ($row->keluarga_aktif_count > 0) {
                            $disabled = 'disabled';
                        }

                        if ($level == 'rw') {
                            if ($row->rw != '-') {
                                $aksi .= '<a href="#" data-href="' . ci_route('wilayah.delete', "{$level}/{$row->id}") . '" class="btn bg-maroon btn-sm ' . $disabled . '" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                            }
                        } else {
                            $aksi .= '<a href="#" data-href="' . ci_route('wilayah.delete', "{$level}/{$row->id}") . '" class="btn bg-maroon btn-sm ' . $disabled . '" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                        }
                    }
                    if ($level == 'dusun' && $row->dusun == '-') {
                        $cek_lokasi_peta = false;
                    }
                    if ($cek_lokasi_peta && can('u')) {
                        $wilayah = $level == 'dusun' ? ucwords(setting('sebutan_dusun')) : strtoupper($level);
                        if (! ($level == 'rw' && $row->rw == '-')) {
                            $aksi .= '<div class="btn-group">
                                <button type="button" class="btn btn-social btn-info btn-sm" data-toggle="dropdown"><i class="fa fa-arrow-circle-down"></i> Peta</button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="' . ci_route('wilayah.' . $mapKantor, "{$row->id}/{$parent}") . '" class="btn btn-social btn-block btn-sm"><i class="fa fa-map-marker"></i> Lokasi Kantor ' . $wilayah . '</a>
                                    </li>
                                    <li>
                                        <a href="' . ci_route('wilayah.' . $mapWilayah, "{$row->id}/{$parent}") . '" class="btn btn-social btn-block btn-sm"><i class="fa fa-map"></i> Peta Wilayah ' . $wilayah . '</a>
                                    </li>
                                </ul>
                            </div>';
                        }
                    }

                    return $aksi;
                })
                ->addColumn('penduduk_count', static fn ($row): string => $level == 'dusun' ? '<a href="' . ci_route('wilayah.warga', $row->id) . '">' . ($row->penduduk_pria_count + $row->penduduk_wanita_count) . '</a>' : '<span>' . ($row->penduduk_pria_count + $row->penduduk_wanita_count) . '</span>')
                ->editColumn('rts_count', static fn ($row): string => $level == 'rw' ? '<a href="' . ci_route('wilayah.index') . '?parent=' . $row->id . '&level=' . $subOrdinat . '" title="Rincian Sub Wilayah">' . ($row->rts_count ?? '') . '</a>' : '<span>' . ($row->rts_count ?? '') . '</span>')
                ->editColumn('rws_count', static fn ($row): string => $level == 'dusun' ? '<a href="' . ci_route('wilayah.index') . '?parent=' . $row->id . '&level=' . $subOrdinat . '" title="Rincian Sub Wilayah">' . ($row->rws_count ?? '') . '</a>' : '<span>' . ($row->rws_count ?? '') . '</span>')
                ->editColumn('keluarga_aktif_count', static fn ($row): string => $level == 'dusun' ? '<a href="' . ci_route('wilayah.warga_kk', $row->id) . '">' . ($row->keluarga_aktif_count ?? '') . '</a>' : '<span>' . ($row->keluarga_aktif_count ?? '') . '</span>')
                ->editColumn('penduduk_pria_count', static fn ($row): string => $level == 'dusun' ? '<a href="' . ci_route('wilayah.warga_l', $row->id) . '">' . ($row->penduduk_pria_count ?? '') . '</a>' : '<span>' . ($row->penduduk_pria_count ?? '') . '</span>')
                ->editColumn('penduduk_wanita_count', static fn ($row): string => $level == 'dusun' ? '<a href="' . ci_route('wilayah.warga_p', $row->id) . '">' . ($row->penduduk_wanita_count ?? '') . '</a>' : '<span>' . ($row->penduduk_wanita_count ?? '') . '</span>')
                ->editColumn('kepala', static fn ($row): string => '<strong>' . $row->kepala->nama . '</strong>' ?? '')
                ->addColumn('nik_kepala', static fn ($row): string => $row->kepala->nik ?? '')
                ->rawColumns(['drag-handle', 'aksi', 'ceklist', 'kepala', 'penduduk_count', 'rts_count', 'rws_count', 'keluarga_aktif_count', 'penduduk_wanita_count', 'penduduk_pria_count'])
                ->make();
        }

        return show_404();
    }

    public function tukar()
    {
        $wilayah = $this->input->post('data');
        if ($wilayah) {
            WilayahModel::setNewOrder($wilayah);
            // setiap ada perubahan urutan maka harus diupdate lagi, karena berimbas ke urutan cetak
            // WilayahModel::updateUrutan();
        }

        return json(['status' => 1]);
    }

    // $aksi = cetak/unduh
    public function dialog($aksi = 'cetak'): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = $aksi;
        $data['form_action'] = ci_route("{$this->controller}.daftar.{$aksi}");
        view('admin.layouts.components.ttd_pamong', $data);
    }

    // $aksi = cetak/unduh
    public function daftar($aksi = 'cetak'): void
    {
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ketahui')])->first()->toArray();
        $data['desa']           = $this->header['desa'];
        $data['dusuns']         = WilayahModel::dusun()->with([
            'kepala', 'rws' => static fn ($q) => $q->orderBy('urut')->with([
                'kepala', 'rts' => static fn ($q) => $q->orderBy('urut')->with('kepala')->withCount([
                    'keluargaAktif' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_9.rw = tweb_wil_clusterdesa.rw and laravel_reserved_9.rt = tweb_wil_clusterdesa.rt')), 'pendudukPria' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_10.rw = tweb_wil_clusterdesa.rw and laravel_reserved_10.rt = tweb_wil_clusterdesa.rt')), 'pendudukWanita' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_11.rw = tweb_wil_clusterdesa.rw and laravel_reserved_11.rt = tweb_wil_clusterdesa.rt')),
                ]),
            ])->withCount([
                'rts' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_5.rw = tweb_wil_clusterdesa.rw')), 'keluargaAktif' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_6.rw = tweb_wil_clusterdesa.rw')), 'pendudukPria' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_7.rw = tweb_wil_clusterdesa.rw')), 'pendudukWanita' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_8.rw = tweb_wil_clusterdesa.rw')),
            ]),
        ])->orderBy('urut')->withCount('rts', 'rws', 'keluargaAktif', 'pendudukPria', 'pendudukWanita')->get();
        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=wilayah_' . date('Y-m-d') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        view('admin.wilayah.wilayah_cetak', $data);
    }

    private function form(string $level, $id = ''): void
    {
        isCan('u');
        $parent = $this->parent ?? null;
        $data   = [
            'wilayah'      => null,
            'form_action'  => ci_route("{$this->controller}.insert.{$level}.{$parent}"),
            'wilayahLabel' => $level == 'dusun' ? ucwords(setting('sebutan_dusun')) : strtoupper($level),
            'level'        => $level,
        ];
        if ($id) {
            $data['wilayah']     = WilayahModel::with('kepala')->find($id) ?? show_404();
            $data['form_action'] = ci_route("{$this->controller}.update.{$level}.{$id}.{$parent}");
        }

        view('admin.wilayah.form', $data);
    }

    public function form_dusun(?int $id = null): void
    {
        $this->form('dusun', $id);
    }

    public function form_rw(int $parent, $id = ''): void
    {
        $this->parent = $parent;
        $this->form('rw', $id);
    }

    public function form_rt(int $parent, $id = ''): void
    {
        $this->parent = $parent;
        $this->form('rt', $id);
    }

    public function apipendudukwilayah()
    {
        if ($this->input->is_ajax_request()) {
            $cari     = $this->input->get('q');
            $kepala   = WilayahModel::pluck('id_kepala')->filter(static fn ($value): bool => null !== $value);
            $penduduk = Penduduk::select(['id', 'nik', 'nama', 'id_cluster'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->whereNotIn('id', $kepala)
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->id,
                        'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun') . ' ' . $item->wilayah->dusun),
                    ]),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    private function bersihkan_data($data)
    {
        if ((int) $data['id_kepala'] === 0) {
            unset($data['id_kepala']);
        }

        $data['dusun'] = nama_terbatas(trim(str_ireplace('DUSUN', '', $data['dusun'])));
        $data['rw']    = bilangan($data['rw']) ?: 0;
        $data['rt']    = bilangan($data['rt']) ?: 0;

        return $data;
    }

    public function insert(string $level, ?int $parent = null): void
    {
        isCan('u');

        try {
            $data      = $this->bersihkan_data($this->request);
            $parentObj = $parent ? WilayahModel::find($parent) : null;

            switch ($level) {
                case 'dusun':
                    WilayahModel::create($data);
                    // insert rw
                    $data['rw'] = '-';
                    WilayahModel::create($data);
                    // insert rt
                    $data['rt'] = '-';
                    WilayahModel::create($data);
                    break;

                case 'rw':
                    $data['dusun'] = $parentObj->dusun;
                    $sudahAda      = WilayahModel::where(['dusun' => $data['dusun'], 'rw' => $data['rw']])->count();
                    if ($sudahAda) {
                        redirect_with('error', 'Data wilayah RW tersebut sudah ada', ci_route('wilayah.index') . '?level=' . $level . '&parent=' . $parent);
                    }
                    WilayahModel::create($data);
                    // insert rt
                    $data['rt'] = '-';
                    WilayahModel::create($data);
                    break;

                case 'rt':
                    $data['dusun'] = $parentObj->dusun;
                    $data['rw']    = $parentObj->rw;
                    $sudahAda      = WilayahModel::where(['dusun' => $data['dusun'], 'rw' => $data['rw'], 'rt' => $data['rt']])->count();
                    if ($sudahAda) {
                        redirect_with('error', 'Data wilayah RT tersebut sudah ada', ci_route('wilayah.index') . '?level=' . $level . '&parent=' . $parent);
                    }
                    WilayahModel::create($data);
                    break;
            }

            redirect_with('success', 'Data wilayah berhasil disimpan', ci_route('wilayah.index') . '?level=' . $level . '&parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data wilayah gagal disimpan', ci_route('wilayah.index') . '?level=' . $level . '&parent=' . $parent);
        }
    }

    public function update(string $level, $id = '', ?int $parent = null): void
    {
        try {
            $data = $this->bersihkan_data($this->request);
            $obj  = WilayahModel::find($id);

            // update nama wilayah yang dibawahnya, karena hubungan parent - child diidentifikasi berdasarkan nama
            switch ($level) {
                case 'dusun':
                    // update rw dan rt dibawahnya
                    WilayahModel::whereDusun($obj->dusun)->update(['dusun' => $data['dusun']]);
                    unset($data['rt'], $data['rw']);

                    $obj->update($data);
                    break;

                case 'rw':
                    // update rt dibawahnya
                    WilayahModel::whereDusun($obj->dusun)->whereRw($obj->rw)->update(['rw' => $data['rw']]);
                    unset($data['dusun'], $data['rt']);

                    $obj->update($data);
                    break;

                default:
                    unset($data['dusun'], $data['rw']);

                    $obj->update($data);
            }

            redirect_with('success', 'Data wilayah berhasil disimpan', ci_route('wilayah.index') . '?level=' . $level . '&parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data wilayah gagal disimpan', ci_route('wilayah.index') . '?level=' . $level . '&parent=' . $parent);
        }
    }

    //Delete dusun/rw/rt tergantung tipe
    public function delete(string $level, int $id): void
    {
        isCan('h');
        // Perlu hapus berdasarkan nama, supaya baris RW dan RT juga terhapus
        $wilayah = WilayahModel::find($id) ?? show_404();

        switch ($level) {
            case 'dusun':
                $id_cluster = WilayahModel::where('dusun', $wilayah->dusun)->pluck('id')->toArray();
                $nama       = setting('sebutan_dusun') . ' ' . $wilayah->dusun;
                break;

            case 'rw':
                $id_cluster        = WilayahModel::where('rw', '!=', '-')->where('rw', $wilayah->rw)->where('dusun', $wilayah->dusun)->pluck('id')->toArray();
                $nama              = 'RW ' . $wilayah->rw . ' ' . setting('sebutan_dusun') . ' ' . $wilayah->dusun;
                $this->session->rw = $wilayah->rw;
                break;

            default:
                $id_cluster        = [$id];
                $nama              = 'RT ' . $wilayah->rw . ' ' . 'RW ' . $wilayah->rw . ' ' . setting('sebutan_dusun') . ' ' . $wilayah->dusun;
                $this->session->rt = $wilayah->rt;
                $this->session->rw = $wilayah->rw;
                break;
        }
        $penduduk = Penduduk::whereIn('id_cluster', $id_cluster)->count();
        $keluarga = Keluarga::whereIn('id_cluster', $id_cluster)->count();

        $this->session->dusun = $wilayah->dusun;
        $url_penduduk         = ci_route('penduduk.index');
        $url_keluarga         = ci_route('keluarga.index');
        if ($penduduk + $keluarga != 0) {
            redirect_with('error', $nama . ' tidak dapat dihapus karena hal berikut: <ol><li>Terdapat penduduk dengan status mati, pindah, hilang, pergi dan tidak valid </li><li>Terdapat kelurga dengan status KK Hilang/Pindah/Mati dan KK Kosong</li></ol>Silakan hapus data <a href="' . $url_penduduk . '" target="_blank">Penduduk</a> atau <a href="' . $url_keluarga . '" target="_blank">Keluarga</a> terlebih dahulu pada setiap status tersebut.', '', true);
        }

        WilayahModel::whereIn('id', $id_cluster)->delete();
        redirect_with('success', $nama . ' berhasil dihapus');
    }

    public function cetak_rw(int $id): void
    {
        $dusun         = WilayahModel::find($id);
        $data['dusun'] = $dusun->dusun;
        $data['rws']   = WilayahModel::rw()->whereDusun($dusun->dusun)->with(['kepala'])->orderBy('urut')
            ->withCount(['rts' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_0.rw = tweb_wil_clusterdesa.rw')), 'keluargaAktif' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_1.rw = tweb_wil_clusterdesa.rw')), 'pendudukPria' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_2.rw = tweb_wil_clusterdesa.rw')), 'pendudukWanita' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_3.rw = tweb_wil_clusterdesa.rw'))])
            ->get();

        view('admin.wilayah.wilayah_rw_cetak', $data);
    }

    public function unduh_rw(int $id): void
    {
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename=wilayah_rw_' . date('Y-m-d') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        $this->cetak_rw($id);
    }

    public function cetak_rt(int $id): void
    {
        $rw            = WilayahModel::find($id);
        $data['dusun'] = $rw->dusun;
        $data['rts']   = WilayahModel::rt()->whereRw($rw->rw)->where('rt', '!=', '-')->whereDusun($rw->dusun)->with(['kepala'])->orderBy('urut')
            ->withCount(['keluargaAktif' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_0.rw = tweb_wil_clusterdesa.rw and laravel_reserved_0.rt = tweb_wil_clusterdesa.rt')), 'pendudukPria' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_1.rw = tweb_wil_clusterdesa.rw and laravel_reserved_1.rt = tweb_wil_clusterdesa.rt')), 'pendudukWanita' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_2.rw = tweb_wil_clusterdesa.rw and laravel_reserved_2.rt = tweb_wil_clusterdesa.rt'))])
            ->get();

        view('admin.wilayah.wilayah_rt_cetak', $data);
    }

    public function unduh_rt(int $id): void
    {
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename=wilayah_rt_' . date('Y-m-d') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        $this->cetak_rt($id);
    }

    public function warga($id = ''): void
    {
        $temp  = WilayahModel::find($id)->toArray();
        $dusun = $temp['dusun'];

        $_SESSION['per_page'] = 100;
        $_SESSION['dusun']    = $dusun;
        redirect('penduduk/index/1/0');
    }

    public function warga_kk($id = ''): void
    {
        $temp                 = WilayahModel::find($id)->toArray();
        $dusun                = $temp['dusun'];
        $_SESSION['per_page'] = 50;
        $_SESSION['dusun']    = $dusun;
        redirect('keluarga/index/1/0');
    }

    public function warga_l($id = ''): void
    {
        $temp  = WilayahModel::find($id)->toArray();
        $dusun = $temp['dusun'];

        $_SESSION['per_page'] = 100;
        $_SESSION['dusun']    = $dusun;
        $_SESSION['sex']      = 1;
        redirect('penduduk/index/1/0');
    }

    public function warga_p($id = ''): void
    {
        $temp  = WilayahModel::find($id)->toArray();
        $dusun = $temp['dusun'];

        $_SESSION['per_page'] = 100;
        $_SESSION['dusun']    = $dusun;
        $_SESSION['sex']      = 2;
        redirect('penduduk/index/1/0');
    }

    public function ajax_kantor_dusun_maps(int $id): void
    {
        $data['wil_atas'] = $this->header['desa'];
        $data['desa']     = $this->header['desa'];
        $sebutan_desa     = ucwords(setting('sebutan_desa'));
        $namadesa         = $data['wil_atas']['nama_desa'];

        $this->ubah_lokasi_peta($data['wil_atas'], 'index', "Lokasi Kantor {$sebutan_desa} {$namadesa} Belum Dilengkapi");

        $data['poly']    = 'multi';
        $data['wil_ini'] = WilayahModel::find($id)->toArray();

        $data['dusun_gis']    = WilayahModel::dusun()->get()->toArray();
        $data['rw_gis']       = WilayahModel::rw()->get()->toArray();
        $data['rt_gis']       = WilayahModel::rt()->get()->toArray();
        $data['nama_wilayah'] = ucwords(setting('sebutan_dusun') . ' ' . $data['wil_ini']['dusun'] . ' ' . $sebutan_desa . ' ' . $data['wil_atas']['nama_desa']);
        $data['wilayah']      = ucwords(setting('sebutan_dusun'));
        $data['breadcrumb']   = [
            ['link' => ci_route('wilayah'), 'judul' => 'Daftar ' . $data['wilayah']],
        ];
        $data['form_action'] = ci_route("{$this->controller}.update_kantor_map", "dusun/{$id}");
        $data['logo']        = $this->header['desa'];

        view('admin.wilayah.maps_kantor', $data);
    }

    public function ajax_wilayah_dusun_maps(int $id): void
    {
        $data['wil_atas'] = $this->header['desa'];
        $data['desa']     = $this->header['desa'];
        $sebutan_desa     = ucwords(setting('sebutan_desa'));
        $namadesa         = $data['wil_atas']['nama_desa'];
        $this->ubah_lokasi_peta($data['wil_atas'], 'index', "Peta Wilayah {$sebutan_desa} {$namadesa} Belum Dilengkapi");

        $data['poly']         = 'multi';
        $data['wil_ini']      = WilayahModel::find($id)->toArray();
        $data['dusun_gis']    = WilayahModel::dusun()->get()->toArray();
        $data['rw_gis']       = WilayahModel::rw()->get()->toArray();
        $data['rt_gis']       = WilayahModel::rt()->get()->toArray();
        $data['nama_wilayah'] = ucwords(setting('sebutan_dusun') . ' ' . $data['wil_ini']['dusun'] . ' ' . $sebutan_desa . ' ' . $data['wil_atas']['nama_desa']);
        $data['wilayah']      = ucwords(setting('sebutan_dusun'));
        $data['breadcrumb']   = [
            ['link' => ci_route('wilayah'), 'judul' => 'Daftar ' . $data['wilayah']],
        ];
        $data['form_action']     = ci_route("{$this->controller}.update_wilayah_map", "dusun/{$id}");
        $data['logo']            = $this->header['desa'];
        $data['route_kosongkan'] = ci_route('wilayah.kosongkan', $id);
        view('admin.wilayah.maps_wilayah', $data);
    }

    public function ajax_kantor_rw_maps(int $id, int $id_dusun): void
    {
        $data['desa']     = $this->header['desa'];
        $data['wil_atas'] = WilayahModel::find($id_dusun)->toArray();
        $sebutan_dusun    = ucwords(setting('sebutan_dusun'));
        $dusun            = $data['wil_atas']['dusun'];
        $this->ubah_lokasi_peta($data['wil_atas'], "index?level=rw&parent={$id_dusun}", "Lokasi Kantor {$sebutan_dusun} {$dusun} Belum Dilengkapi");

        $data['wil_ini']   = WilayahModel::find($id)->toArray();
        $data['dusun_gis'] = WilayahModel::dusun()->get()->toArray();
        $data['rw_gis']    = WilayahModel::rw()->get()->toArray();
        $data['rt_gis']    = WilayahModel::rt()->get()->toArray();

        $data['nama_wilayah'] = 'RW ' . $data['wil_ini']['rw'] . ' ' . $sebutan_dusun . ' ' . $dusun;
        $data['breadcrumb']   = [
            ['link' => ci_route('wilayah'), 'judul' => 'Daftar ' . $sebutan_dusun],
            ['link' => ci_route("{$this->controller}.index?level=rw&parent={$id_dusun}"), 'judul' => 'Daftar RW'],
        ];
        $data['wilayah']     = 'RW';
        $data['form_action'] = ci_route("{$this->controller}.update_kantor_map", "rw/{$id}/{$id_dusun}");
        $data['logo']        = $this->header['desa'];

        view('admin.wilayah.maps_kantor', $data);
    }

    public function ajax_wilayah_rw_maps(int $id, int $id_dusun): void
    {
        $data['desa']     = $this->header['desa'];
        $data['wil_atas'] = WilayahModel::find($id_dusun)->toArray();
        $sebutan_dusun    = ucwords(setting('sebutan_dusun'));
        $dusun            = $data['wil_atas']['dusun'];
        $this->ubah_lokasi_peta($data['wil_atas'], "index?level=rw&parent={$id_dusun}", "Peta Wilayah {$sebutan_dusun} {$dusun} Belum Dilengkapi");

        $data['wil_ini']      = WilayahModel::find($id)->toArray();
        $data['dusun_gis']    = WilayahModel::dusun()->get()->toArray();
        $data['rw_gis']       = WilayahModel::rw()->get()->toArray();
        $data['rt_gis']       = WilayahModel::rt()->get()->toArray();
        $dusun                = $data['wil_atas']['dusun'];
        $data['nama_wilayah'] = 'RW ' . $data['wil_ini']['rw'] . ' ' . $sebutan_dusun . ' ' . $dusun;
        $data['breadcrumb']   = [
            ['link' => ci_route('wilayah'), 'judul' => 'Daftar ' . $sebutan_dusun],
            ['link' => ci_route("{$this->controller}.index?level=rw&parent={$id_dusun}"), 'judul' => 'Daftar RW'],
        ];
        $data['wilayah']         = 'RW';
        $data['form_action']     = ci_route("{$this->controller}.update_wilayah_map", "rw/{$id}/{$id_dusun}");
        $data['logo']            = $this->header['desa'];
        $data['route_kosongkan'] = ci_route('wilayah.kosongkan', $id);
        view('admin.wilayah.maps_wilayah', $data);
    }

    public function ajax_kantor_rt_maps(int $id, int $id_rw): void
    {
        $dataRW           = WilayahModel::find($id_rw)->toArray();
        $data['desa']     = $this->header['desa'];
        $data['wil_atas'] = $dataRW;
        $id_dusun         = WilayahModel::dusun()->whereDusun($dataRW['dusun'])->first()->id;
        if ($dataRW['rw'] == '-') {
            $data['wil_atas'] = WilayahModel::find($id_dusun)->toArray();
        }
        $sebutan_dusun = ucwords(setting('sebutan_dusun'));
        $dusun         = $data['wil_atas']['dusun'];
        $this->ubah_lokasi_peta($data['wil_atas'], "index?level=rt&parent={$id_rw}", "Lokasi Kantor {$sebutan_dusun} {$dusun} Belum Dilengkapi");

        $data['wil_ini']      = WilayahModel::find($id)->toArray();
        $data['dusun_gis']    = WilayahModel::dusun()->get()->toArray();
        $data['rw_gis']       = WilayahModel::rw()->get()->toArray();
        $data['rt_gis']       = WilayahModel::rt()->get()->toArray();
        $data['nama_wilayah'] = 'RT ' . $data['wil_ini']['rt'] . ' RW ' . $data['wil_ini']['rw'] . ' ' . ucwords($sebutan_dusun . ' ' . $data['wil_ini']['dusun']);
        $data['breadcrumb']   = [
            ['link' => ci_route("{$this->controller}"), 'judul' => 'Daftar ' . $sebutan_dusun],
            ['link' => ci_route("{$this->controller}.index?level=rw&parent={$id_dusun}"), 'judul' => 'Daftar RW'],
            ['link' => ci_route("{$this->controller}.index?level=rt&parent={$id_rw}"), 'judul' => 'Daftar RT'],
        ];
        $data['wilayah']     = 'RT';
        $data['form_action'] = ci_route("{$this->controller}.update_wilayah_map", "rt/{$id}/{$id_rw}");
        $data['logo']        = $this->header['desa'];

        view('admin.wilayah.maps_kantor', $data);
    }

    public function ajax_wilayah_rt_maps(int $id, int $id_rw): void
    {
        $dataRW           = WilayahModel::find($id_rw)->toArray();
        $id_dusun         = WilayahModel::dusun()->whereDusun($dataRW['dusun'])->first()->id;
        $data['desa']     = $this->header['desa'];
        $data['wil_atas'] = $dataRW;
        if ($dataRW['rw'] == '-') {
            $data['wil_atas'] = WilayahModel::find($id_dusun)->toArray();
        }

        $sebutan_dusun = ucwords(setting('sebutan_dusun'));
        $dusun         = $data['wil_atas']['dusun'];
        $this->ubah_lokasi_peta($data['wil_atas'], "index?level=rt&parent={$id_rw}", "Peta Wilayah {$sebutan_dusun} {$dusun} Belum Dilengkapi");

        $data['wil_ini']      = WilayahModel::find($id)->toArray();
        $data['dusun_gis']    = WilayahModel::dusun()->get()->toArray();
        $data['rw_gis']       = WilayahModel::rw()->get()->toArray();
        $data['rt_gis']       = WilayahModel::rt()->get()->toArray();
        $data['nama_wilayah'] = 'RT ' . $data['wil_ini']['rt'] . ' RW ' . $data['wil_ini']['rw'] . ' ' . ucwords($sebutan_dusun . ' ' . $data['wil_ini']['dusun']);
        $data['breadcrumb']   = [
            ['link' => ci_route("{$this->controller}"), 'judul' => 'Daftar ' . $sebutan_dusun],
            ['link' => ci_route("{$this->controller}.index?level=rw&parent={$id_dusun}"), 'judul' => 'Daftar RW'],
            ['link' => ci_route("{$this->controller}.index?level=rt&parent={$id_rw}"), 'judul' => 'Daftar RT'],
        ];
        $data['wilayah']         = 'RT';
        $data['form_action']     = ci_route("{$this->controller}.update_wilayah_map", "rt/{$id}/{$id_rw}");
        $data['logo']            = $this->header['desa'];
        $data['route_kosongkan'] = ci_route('wilayah.kosongkan', $id);
        view('admin.wilayah.maps_wilayah', $data);
    }

    public function update_kantor_map(string $level, int $id, ?int $parent = null): void
    {
        isCan('u');
        WilayahModel::whereId($id)->update($this->validasi_koordinat($this->request));
        redirect_with('success', 'Lokasi kantor berhasil disimpan', ci_route('wilayah.index') . '?level=' . $level . '&parent=' . $parent);
    }

    public function update_wilayah_map(string $level, int $id, ?int $parent = null): void
    {
        isCan('u');
        WilayahModel::whereId($id)->update($this->validasi_wilayah($this->request));
        redirect_with('success', 'Peta berhasil disimpan', ci_route('wilayah.index') . '?level=' . $level . '&parent=' . $parent);
    }

    public function kosongkan($id = ''): void
    {
        isCan('u');
        $wilayah       = WilayahModel::findOrFail($id);
        $wilayah->path = null;
        $wilayah->save();

        if ($wilayah->isDusun()) {
            redirect($this->controller);
        }

        if ($wilayah->isRw()) {
            $parent = WilayahModel::dusun()->whereDusun($wilayah->dusun)->first();
            redirect($this->controller . '/index?parent=' . $parent->id . '&level=rw');
        }

        if ($wilayah->isRt()) {
            $parent = WilayahModel::rw()->where(['dusun' => $wilayah->dusun, 'rw' => $wilayah->rw])->first();
            redirect($this->controller . '/index?parent=' . $parent->id . '&level=rt');
        }
    }

    public function list_rw($dusun = ''): void
    {
        $dusun   = urldecode($dusun);
        $list_rw = WilayahModel::rw()
            ->when($dusun, static fn ($q) => $q->whereDusun($dusun))
            ->get()
            ->toArray();

        echo json_encode($list_rw, JSON_THROW_ON_ERROR);
    }

    public function list_rt($dusun = '', $rw = '-'): void
    {
        $dusun   = urldecode($dusun);
        $list_rt = WilayahModel::rt()
            ->when($dusun, static fn ($q) => $q->whereDusun($dusun))
            ->when($rw, static fn ($q) => $q->whereRw($rw))
            ->get()
            ->toArray();

        echo json_encode($list_rt, JSON_THROW_ON_ERROR);
    }

    public function ubah_lokasi_peta($wilayah, $to = 'index', $msg = ''): void
    {
        isCan('u');

        if (! cek_lokasi_peta($wilayah)) {
            session_error($msg);

            redirect("{$this->controller}.{$to}");
        }
    }

    private function validasi_koordinat($post)
    {
        return [
            'zoom'     => $post['zoom'] ?: null,
            'map_tipe' => $post['map_tipe'],
            'lat'      => koordinat($post['lat']),
            'lng'      => koordinat($post['lng']),
            'warna'    => warna($post['warna']),
            'border'   => warna($post['border']),
        ];
    }

    private function validasi_wilayah($post)
    {
        return [
            'path'   => $post['path'],
            'zoom'   => $post['zoom'] ?: null,
            'warna'  => warna($post['warna']),
            'border' => warna($post['border']),
        ];
    }
}
