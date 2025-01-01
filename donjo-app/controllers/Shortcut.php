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

use App\Models\Shortcut as ShortcutModel;

defined('BASEPATH') || exit('No direct script access allowed');

class Shortcut extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'shortcut';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        return view('admin.shortcut.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status') ?? false;
            $order  = $this->input->get('order') ?? false;
            $query  = ShortcutModel::when(! $order, static fn ($q) => $q->orderBy('urut', 'asc'))->when(in_array($status, ['0', '1']), static fn ($q) => $q->where('status', $status));

            return datatables()->of($query)
                ->addColumn('drag-handle', static fn (): string => '<i class="fa fa-sort-alpha-desc"></i>')
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("shortcut/form/{$row->id}") . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if ($row->status == 1) {
                            $aksi .= '<a href="' . site_url("shortcut/lock/{$row->id}") . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("shortcut/lock/{$row->id}") . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . site_url("shortcut/delete/{$row->id}") . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('judul', static fn ($row) => SebutanDesa($row->judul))
                ->editColumn('icon', static fn ($row): string => '<i class="fa ' . $row->icon . ' fa-lg"></i>')
                ->editColumn('warna', static fn ($row): string => '<div style="background-color:' . $row->warna . '; width: auto; height: 10px;"></div>')
                ->editColumn('status', static fn ($row): string => ($row->status == 1) ? '<span class="label label-success">Aktif</span>' : '<span class="label label-danger">Tidak Aktif</span>')
                ->rawColumns(['drag-handle', 'ceklist', 'aksi', 'icon', 'warna', 'status'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        isCan('u');
        if ($id) {
            $action      = 'Ubah';
            $form_action = ci_route('shortcut.update', $id);
            $shortcut    = ShortcutModel::findOrFail($id);
        } else {
            $action      = 'Tambah';
            $form_action = ci_route('shortcut.insert');
            $shortcut    = null;
        }
        $icons   = ShortcutModel::listIcon();
        $modules = ShortcutModel::querys()['modules'];

        return view('admin.shortcut.form', ['action' => $action, 'form_action' => $form_action, 'shortcut' => $shortcut, 'icons' => $icons, 'moduls' => $moduls, 'modules' => $modules]);
    }

    public function insert(): void
    {
        isCan('u');

        if (ShortcutModel::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = ''): void
    {
        isCan('u');

        $data = ShortcutModel::findOrFail($id);

        if ($data->update(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = ''): void
    {
        isCan('h');

        $data = ShortcutModel::findOrFail($id);

        if ($data->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function deleteAll(): void
    {
        isCan('h');

        foreach ($this->request['id_cb'] as $id) {
            $this->delete($id);
        }
    }

    public function lock($id = 0): void
    {
        isCan('u');

        if (ShortcutModel::gantiStatus($id)) {
            redirect_with('success', 'Berhasil Ubah Status');
        }

        redirect_with('error', 'Gagal Ubah Status');
    }

    public function tukar()
    {
        isCan('u');
        $shortcut = $this->input->post('data');

        ShortcutModel::setNewOrder($shortcut);

        shortcut_cache();

        return json(['status' => 1]);
    }

    protected static function validate($request = [])
    {
        return [
            'judul'     => $request['judul'],
            'raw_query' => $request['raw_query'],
            'icon'      => $request['icon'],
            'warna'     => $request['warna'] ?? '#00c0ef',
            'status'    => $request['status'] ?? 0,
        ];
    }
}
