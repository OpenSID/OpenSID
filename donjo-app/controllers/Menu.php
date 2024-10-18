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

use App\Models\Artikel;
use App\Models\Bantuan;
use App\Models\Kategori;
use App\Models\Kelompok;
use App\Models\Menu as MenuModel;
use App\Models\Suplemen;

defined('BASEPATH') || exit('No direct script access allowed');

// TODO:: Ganti cara hapus cache yang gunakan prefix dimodul menu ("{$grupId}_admin_menu")
class Menu extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'menu';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $parent = $this->input->get('parent') ?? 0;
        $data   = [
            'status'   => [MenuModel::UNLOCK => 'Aktif', MenuModel::LOCK => 'Non Aktif'],
            'subtitle' => $parent > 0 ? '<a href="' . ci_route('menu.index') . '?parent=0">MENU UTAMA </a> / ' . MenuModel::find($parent)->getSelfParents()->reverse()->map(static fn ($item) => $parent == $item['id'] ? strtoupper($item['nama']) : '<a href="' . ci_route('menu.index') . '?parent=' . $item['id'] . '">' . strtoupper($item['nama']) . '</a>')->join(' / ') : '',
            'parent'   => $parent,
        ];

        view('admin.web.menu.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $parent    = (int) ($this->input->get('parent') ?? 0);
            $status    = $this->input->get('status') ?? null;
            $canDelete = can('h');
            $canUpdate = can('u');

            return datatables()->of(MenuModel::child($parent)->with(['parent'])->orderBy('urut', 'asc')->when(in_array($status, ['0', '1']), static fn ($q) => $q->where('enabled', $status)))
                ->addColumn('drag-handle', static fn () => '<i class="fa fa-sort-alpha-desc"></i>')
                ->addColumn('ceklist', static function ($row) use ($canDelete) {
                    if ($canDelete) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($parent, $canUpdate, $canDelete): string {
                    $aksi  = '';
                    $judul = $parent > 0 ? 'Submenu' : 'Menu';
                    if ($canUpdate) {

                        $aksi .= '<a href="' . ci_route('menu.index') . '?parent=' . $row->id . '" class="btn bg-purple btn-sm"><i class="fa fa-bars"></i></a> ';

                        $aksi .= '<a href="' . ci_route('menu.ajax_menu', implode('/', [$row->parent->id ?? $parent, $row->id])) . '" class="btn bg-orange btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah ' . $judul . '" title="Ubah ' . $judul . '"><i class="fa fa-edit"></i></a> ';
                        if ($row->isActive()) {
                            $aksi .= '<a href="' . ci_route('menu.lock', implode('/', [$row->parent->id ?? $parent, $row->id])) . '" class="btn bg-navy btn-sm" title="Non Aktifkan"><i class="fa fa-unlock">&nbsp;</i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('menu.lock', implode('/', [$row->parent->id ?? $parent, $row->id])) . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if ($canDelete) {
                        $aksi .= '<a href="#" data-href="' . ci_route('menu.delete', implode('/', [$row->parent->id ?? $parent, $row->id])) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })->editColumn('link', static fn ($row) => '<a href="' . $row->linkUrl . '" target="_blank">' . $row->linkUrl . '</a>' )
                ->rawColumns(['drag-handle', 'aksi', 'ceklist', 'link'])
                ->make();
        }

        return show_404();
    }

    public function ajax_menu($parent, $id = ''): void
    {
        isCan('u');
        $menu                               = new MenuModel();
        $data['link_tipe']                  = unserialize(LINK_TIPE);
        $data['artikel_statis']             = Artikel::select(['id', 'judul'])->statis()->get()->toArray();
        $data['kategori_artikel']           = Kategori::select(['slug', 'kategori'])->orderBy('urut')->get()->toArray();
        $data['statistik_penduduk']         = unserialize(STAT_PENDUDUK);
        $data['statistik_keluarga']         = unserialize(STAT_KELUARGA);
        $data['statistik_kategori_bantuan'] = unserialize(STAT_BANTUAN);
        $data['statistik_program_bantuan']  = Bantuan::select(['id', 'nama', 'slug'])->get()->toArray();
        $data['kelompok']                   = Kelompok::tipe('kelompok')->get()->toArray();
        $data['lembaga']                    = Kelompok::tipe('lembaga')->get()->toArray();
        $data['suplemen']                   = Suplemen::select(['id', 'nama', 'slug'])->get()->toArray();
        $data['statis_lainnya']             = unserialize(STAT_LAINNYA);
        $data['artikel_keuangan']           = Artikel::select(['id', 'judul'])->keuangan()->get()->toArray();

        if ($id) {
            $data['menu']        = MenuModel::findOrFail($id)->toArray();
            $data['form_action'] = ci_route("menu.update.{$parent}.{$id}");
            $data['menu_utama']  = $menu->buildArray($menu->tree());
        } else {
            $data['menu']        = null;
            $data['form_action'] = ci_route("menu.insert.{$parent}");
        }
        view('admin.web.menu.ajax_form', $data);
    }

    public function insert($parent): void
    {
        isCan('u');
        $data            = $this->validasi($this->input->post());
        $data['parrent'] = $parent;

        try {
            MenuModel::create($data);
            // TODO:: hapus cache hanya prefix *_admin_menu
            cache()->flush();
            redirect_with('success', 'Menu berhasil disimpan', ci_route('menu.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Menu gagal disimpan', ci_route('menu.index') . '?parent=' . $parent);
        }
    }

    public function update($parent, $id): void
    {
        isCan('u');
        $data = $this->validasi($this->input->post());

        try {
            $obj = MenuModel::findOrFail($id);
            $obj->update($data);
            cache()->flush();
            redirect_with('success', 'Menu berhasil disimpan', ci_route('menu.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Menu gagal disimpan', ci_route('menu.index') . '?parent=' . $parent);
        }
    }

    public function delete($parent, $id = null): void
    {
        isCan('h');

        if (MenuModel::whereIn('id', $this->request['id_cb'] ?? [$id] )->whereHas('children')->count()) {
            redirect_with('error', 'Menu tidak dapat dihapus karena masih memiliki submenu');
        }

        try {
            MenuModel::destroy($this->request['id_cb'] ?? $id);
            cache()->flush();
            redirect_with('success', 'Menu berhasil dihapus', ci_route('menu.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Menu gagal dihapus', ci_route('menu.index') . '?parent=' . $parent);
        }
    }

    public function lock($parent, $id): void
    {
        isCan('h');

        try {
            MenuModel::gantiStatus($id, 'enabled');
            cache()->flush();
            redirect_with('success', 'Berhasil ubah status', ci_route('menu.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal ubah status', ci_route('menu.index') . '?parent=' . $parent);
        }
    }

    public function tukar()
    {
        $menu = $this->input->post('data');
        MenuModel::setNewOrder($menu);
        cache()->flush();

        return json(['status' => 1]);
    }

    private function validasi($post)
    {
        $parrent = bilangan($post['parrent'] ?? 0);

        return [
            'nama'      => htmlentities($post['nama']),
            'link'      => $post['link'],
            'parrent'   => $parrent,
            'link_tipe' => $post['link_tipe'],
            'enabled'   => 1,
        ];
    }
}
