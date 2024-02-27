<?php

use App\Models\Modul;
use App\Models\Shortcut as ShortcutModel;

defined('BASEPATH') || exit('No direct script access allowed');

class Shortcut extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 'pengaturan';
        $this->sub_modul_ini = 'shortcut';
    }

    public function index()
    {
        return view("admin.shortcut.index");
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(ShortcutModel::query()->orderBy('urutan', 'asc'))
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
                            $aksi .= '<a href="' . site_url("shortcut/lock/{$row->id}/1") . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("shortcut/lock/{$row->id}/0") . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . site_url("shortcut/delete/{$row->id}") . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('judul', static function ($row) {
                    return SebutanDesa($row->judul);
                })
                ->editColumn('icon', static function ($row) {
                    return '<i class="fa ' . $row->icon . ' fa-lg"></i>';
                })
                ->editColumn('warna', static function ($row) {
                    return '<div style="background-color:' . $row->warna . '; width: auto; height: 10px;"></div>';
                })
                ->editColumn('status', static fn ($row): string => ($row->status == 1) ? '<span class="label label-success">Aktif</span>' : '<span class="label label-danger">Tidak Aktif</span>')
                ->rawColumns(['ceklist', 'aksi', 'icon', 'warna', 'status'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $action      = 'Ubah';
            $form_action = route('shortcut.update', $id);
            $shortcut    = ShortcutModel::findOrFail($id);
        } else {
            $action      = 'Tambah';
            $form_action = route('shortcut.insert');
            $shortcut    = null;
        }

        $icons  = ShortcutModel::listIcon();
        $moduls = Modul::where('slug', '!=', 'home')->where('hidden', '!=', 2)->get()->pluck('modul', 'slug')->toArray();
        $querys = array_keys(ShortcutModel::querys());

        return view("admin.shortcut.form", compact('action', 'form_action', 'shortcut', 'icons', 'moduls', 'querys'));
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');

        if (ShortcutModel::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = '')
    {
        $this->redirect_hak_akses('u');

        $data = ShortcutModel::findOrFail($id);

        if ($data->update(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = '')
    {
        $this->redirect_hak_akses('h');

        $data = ShortcutModel::findOrFail($id);

        if ($data->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function deleteAll()
    {
        $this->redirect_hak_akses('h');

        foreach ($this->request['id_cb'] as $id) {
            $this->delete($id);
        }
    }

    public function lock($id = 0, $val = 0)
    {
        $this->redirect_hak_akses('h');

        $data = ShortcutModel::find($id) ?? show_404();

        if ($data->update(['status' => $val == 0 ? 1 : 0])) {
            redirect_with('success', 'Berhasil Ubah Status');
        }

        redirect_with('error', 'Gagal Ubah Status');
    }

    protected static function validate($request = [])
    {
        return [
            'judul'       => $request['judul'],
            'akses'       => $request['akses'],
            'link'        => $request['link'],
            'jenis_query' => $request['jenis_query'] ?? 0,
            'raw_query'   => $request['jenis_query'] === 1 ? $request['query_manual'] : $request['query_otomatis'],
            'icon'        => $request['icon'],
            'warna'       => $request['warna'] ?? '#00c0ef',
            'urutan'      => $request['urutan'] ?? 0,
            'status'      => $request['status'] ?? 0,
        ];
    }
}
