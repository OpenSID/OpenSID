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

use App\Models\Kategori as KategoriModel;

defined('BASEPATH') || exit('No direct script access allowed');

class Kategori extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'kategori';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $parent = $this->input->get('parent') ?? 0;
        $data   = [
            'status'   => [KategoriModel::UNLOCK => 'Aktif', KategoriModel::LOCK => 'Tidak Aktif'],
            'subtitle' => $parent > 0 ? '<a href="' . ci_route('kategori.index') . '?parent=0">MENU UTAMA </a> / ' . strtoupper(KategoriModel::find($parent)->kategori ?? '') : '',
            'parent'   => $parent,
        ];

        view('admin.web.kategori.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status    = $this->input->get('status') ?? null;
            $parent    = (int) ($this->input->get('parent') ?? 0);
            $canDelete = can('h');
            $canUpdate = can('u');

            return datatables()->of(KategoriModel::child($parent)->with(['parent', 'artikel'])->when($status != null, static fn ($q) => $q->whereEnabled($status))->orderBy('urut', 'asc'))
                ->addColumn('drag-handle', static fn () => '<i class="fa fa-sort-alpha-desc"></i>')
                ->addColumn('ceklist', static function ($row) use ($canDelete) {
                    if ($canDelete) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($parent, $canUpdate, $canDelete): string {
                    $aksi  = '';
                    $judul = $parent > 0 ? 'Subkategori' : 'Kategori';
                    if ($canUpdate) {
                        if (! $parent) {
                            $aksi .= '<a href="' . ci_route('kategori.index') . '?parent=' . $row->id . '" class="btn bg-purple btn-sm"><i class="fa fa-bars"></i></a> ';
                        }
                        $aksi .= '<a href="' . ci_route('kategori.ajax_form', implode('/', [$row->parent->id ?? $parent, $row->id])) . '" class="btn bg-orange btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah ' . $judul . '" title="Ubah ' . $judul . '"><i class="fa fa-edit"></i></a> ';

                        if ($row->isActive()) {
                            $aksi .= '<a href="' . ci_route('kategori.lock', implode('/', [$row->parent->id ?? $parent, $row->id])) . '" class="btn bg-navy btn-sm" title="Non Aktifkan"><i class="fa fa-unlock">&nbsp;</i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('kategori.lock', implode('/', [$row->parent->id ?? $parent, $row->id])) . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if ($canDelete) {
                        if ($row->artikel->count() == 0) {
                            $aksi .= '<a href="#" data-href="' . ci_route('kategori.delete', implode('/', [$row->parent->id ?? $parent, $row->id])) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                        }
                    }

                    return $aksi;
                })
                ->editColumn('kategori', static fn ($row) => html_entity_decode($row->kategori))
                ->rawColumns(['drag-handle', 'aksi', 'ceklist', 'link'])
                ->make();
        }

        return show_404();
    }

    public function ajax_form($parent, $id = ''): void
    {
        isCan('u');

        if ($id) {
            $data['kategori']    = KategoriModel::find($id);
            $data['form_action'] = ci_route("kategori.update.{$parent}.{$id}");
        } else {
            $data['kategori']    = null;
            $data['form_action'] = ci_route("kategori.insert.{$parent}");
        }
        view('admin.web.kategori.ajax_form', $data);
    }

    public function insert($parent): void
    {
        isCan('u');
        $data            = $this->validasi($this->input->post());
        $data['enabled'] = 1;
        $data['parrent'] = $parent;
        // periksa apakah sudah ada kategori yang sama
        $sudahAda = KategoriModel::isUniqueKategori($data['kategori'], $data['config_id']);

        if ($sudahAda) {
            redirect_with('error', 'Kategori ' . $data['kategori'] . ' sudah ada', ci_route('kategori.index') . '?parent=' . $parent);
        }

        try {
            KategoriModel::create($data);
            redirect_with('success', 'Kategori berhasil disimpan', ci_route('kategori.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Kategori gagal disimpan', ci_route('kategori.index') . '?parent=' . $parent);
        }
    }

    public function update($parent, $id): void
    {
        isCan('u');
        $data = $this->validasi($this->input->post());
        // periksa apakah sudah ada kategori yang sama
        $sudahAda = KategoriModel::isUniqueKategori($data['kategori'], $data['config_id'], $id);

        if ($sudahAda) {
            redirect_with('error', 'Kategori ' . $data['kategori'] . ' sudah ada', ci_route('kategori.index') . '?parent=' . $parent);
        }

        try {
            $obj = KategoriModel::findOrFail($id);
            $obj->update($data);
            redirect_with('success', 'Kategori berhasil disimpan', ci_route('kategori.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Kategori gagal disimpan', ci_route('kategori.index') . '?parent=' . $parent);
        }
    }

    public function delete($parent, $id = null): void
    {
        isCan('h');

        if (KategoriModel::whereIn('id', $this->request['id_cb'] ?? [$id] )->whereHas('artikel')->count()) {
            redirect_with('error', 'Kategori tidak dapat dihapus karena masih memiliki artikel');
        }

        if (KategoriModel::whereIn('id', $this->request['id_cb'] ?? [$id] )->whereHas('children')->count()) {
            redirect_with('error', 'Kategori tidak dapat dihapus karena masih memiliki subkategori');
        }

        try {
            KategoriModel::destroy($this->request['id_cb'] ?? $id);
            redirect_with('success', 'Kategori berhasil dihapus', ci_route('kategori.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Kategori gagal dihapus', ci_route('kategori.index') . '?parent=' . $parent);
        }
    }

    public function lock($parent, $id): void
    {
        isCan('h');

        try {
            KategoriModel::gantiStatus($id, 'enabled');
            redirect_with('success', 'Berhasil ubah status', ci_route('kategori.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal ubah status', ci_route('kategori.index') . '?parent=' . $parent);
        }
    }

    public function tukar()
    {
        isCan('u');
        $kategori = $this->input->post('data');
        KategoriModel::setNewOrder($kategori);

        return json(['status' => 1]);
    }

    private function validasi($post)
    {
        $kategori = htmlentities($post['kategori']);

        return [
            'config_id' => identitas('id'),
            'kategori'  => $kategori,
            'slug'      => url_title($kategori, '-', true),
        ];
    }
}
