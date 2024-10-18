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

use App\Enums\OfflineModeEnum;
use App\Models\Modul as ModulModel;
use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

// TODO:: Ganti cara hapus cache yang gunakan prefix dimodul menu ("{$grupId}_admin_menu")

class Modul extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'modul';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['modul_model']);
    }

    public function index(?int $parent = 0): void
    {
        isCan('b');

        $data = [
            'utama'      => ! $parent,
            'status'     => [ModulModel::UNLOCK => 'Aktif', ModulModel::LOCK => 'Tidak Aktif'],
            'parentName' => $parent ? ModulModel::findOrFail($parent)->modul ?? '' : null,
            'parent'     => $parent,
        ];

        view('admin.pengaturan.modul.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $parent     = $this->input->get('parent') ?? 0;
            $order      = $this->input->get('order') ?? false;
            $canUpdate  = can('u');
            $lockParent = false;
            if ($parent) {
                $lockParent = ModulModel::find($parent)->isLock();
            }

            return datatables()->of(ModulModel::with(['children'])->whereParent($parent)->whereNotIn('modul', ModulModel::SELALU_AKTIF)
                ->when(! $order, static fn ($q) => $q->orderBy('urut', 'asc')))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($parent, $canUpdate, $lockParent): string {
                    $aksi = '';
                    if ($canUpdate) {
                        $aksi .= '<a href="' . ci_route('modul.form', $row->id) . '" class="btn bg-orange btn-sm" title="Ubah Data" ><i class="fa fa-edit"></i></a> ';
                        if (! $lockParent && $row->isLock()) {
                            $aksi .= '<a href="' . ci_route('modul.unlock', $row->id) . '" class="btn bg-navy btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a> ';
                        }

                        if (! $row->isLock()) {
                            $aksi .= '<a href="' . ci_route('modul.lock', $row->id) . '" class="btn bg-navy btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a> ';
                        }
                    }
                    if (! $parent && $row->children->count()) {
                        $aksi .= '<a href="' . ci_route('modul.index', $row->id) . '" class="btn bg-olive btn-sm" title="Lihat Sub Modul" ><i class="fa fa-list"></i></a>';
                    }

                    return $aksi;
                })->editColumn('modul', static fn ($row) => SebutanDesa($row->modul))
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id): void
    {
        isCan('u');
        $modul             = ModulModel::findOrFail($id);
        $data['list_icon'] = ModulModel::listIcon();

        $data['item']        = $modul->toArray();
        $data['utama']       = ! (bool) $modul->parent;
        $data['form_action'] = ci_route('modul.update', $id);

        view('admin.pengaturan.modul.form', $data);
    }

    public function update($id): void
    {
        isCan('u');
        $data          = $this->input->post();
        $data['modul'] = strip_tags($data['modul']);
        $data['ikon']  = strip_tags($data['ikon']);
        $obj           = ModulModel::findOrFail($id);
        $parent        = $obj->parent;

        try {
            // pakai query builder, karena ada cast untuk field aktif di modelnya, tidak diubah kuatirnya dipakai di tempat lain
            ModulModel::where(['id' => $id])->update($data);
            // update juga children
            $obj->children()->update(['aktif' => $data['aktif']]);
            cache()->flush();
            redirect_with('success', 'Modul berhasil disimpan', ci_route('modul.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Modul gagal disimpan', ci_route('modul.index', $parent));
        }
    }

    public function lock($id): void
    {
        isCan('u');
        $obj    = ModulModel::findOrFail($id);
        $parent = $obj->parent;

        try {
            ModulModel::where(['id' => $id])->orWhere(['parent' => $id])->update(['aktif' => ModulModel::LOCK]);
            cache()->flush();
            redirect_with('success', 'Modul berhasil dinonaktifkan', ci_route('modul.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Modul gagal dinonaktifkan', ci_route('modul.index', $parent));
        }
    }

    public function unlock($id): void
    {
        isCan('u');
        $obj    = ModulModel::findOrFail($id);
        $parent = $obj->parent;

        try {
            ModulModel::where(['id' => $id])->orWhere(['parent' => $id])->update(['aktif' => ModulModel::UNLOCK]);
            cache()->flush();
            redirect_with('success', 'Modul berhasil dinonaktifkan', ci_route('modul.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Modul gagal dinonaktifkan', ci_route('modul.index', $parent));
        }
    }

    public function ubah_server(): void
    {
        isCan('u');

        try {
            $mode                        = $this->input->post('offline_mode_saja');
            $this->setting->offline_mode = ($mode === '0' || $mode) ? $mode : $this->input->post('offline_mode');
            SettingAplikasi::where('key', 'offline_mode')->update(['value' => $this->setting->offline_mode]);
            $penggunaan_server                = $this->input->post('server_mana') ?: $this->input->post('jenis_server');
            $this->setting->penggunaan_server = $penggunaan_server;
            SettingAplikasi::where('key', 'penggunaan_server')->update(['value' => $penggunaan_server]);
            $this->cache->hapus_cache_untuk_semua('setting_aplikasi');
            redirect_with('success', 'Berhasil menyimpan pengaturan aplikasi');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal menyimpan pengaturan aplikasi');
        }
    }

    public function default_server(): void
    {
        isCan('u');
        /*
        Setting modul yang diaktifkan sesuai dengan setting penggunaan_server,
        dan setting online_mode

        penggunaan_server:
        1 - offline saja di kantor desa
        2 - online saja di hosting
        3 - [lebih spesifik di 5 dan 6]
        4 - offline dan online di kantor desa
        5 - offline di kantor desa, dan ada online di hosting
        6 - online di hosting, dan ada offline di kantor desa
    */

        switch ($this->setting->penggunaan_server) {
            case '1':
            case '5':
                ModulModel::whereNotNull('id')->update(['aktif' => ModulModel::UNLOCK]);
                // Kalau web tidak diaktifkan sama sekali, non-aktifkan modul Admin Web
                if ($this->setting->offline_mode == OfflineModeEnum::NONAKTIF) {
                    $modul_web = 13;
                    ModulModel::where(['id' => $modul_web])->orWhere(['parent' => $modul_web])->update(['aktif' => ModulModel::LOCK]);
                }
                break;

            case '6':
                // Online digunakan hanya untuk publikasi web; admin penduduk dan lain-lain
                // dilakukan offline di kantor desa. Yaitu, hanya modul Admin Web yang aktif
                // Kecuali Pengaturan selalu aktif
                $modul_pengaturan = 11;
                ModulModel::where('id', '!=', $modul_pengaturan)->orWhere('parent', '!=', $modul_pengaturan)->update(['aktif' => ModulModel::LOCK]);
                $modul_web = 13;
                ModulModel::where(['id' => $modul_web])->orWhere(['parent' => $modul_web])->update(['aktif' => ModulModel::UNLOCK]);
                break;

            default:
                // semua modul aktif
                ModulModel::whereNotNull('id')->update(['aktif' => ModulModel::UNLOCK]);
                break;
        }
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        // redirect ke beranda, karena ada kemungkinan perubahan hak akses modul
        redirect_with('success', 'Pengaturan modul default berhasil disimpan');
    }
}
