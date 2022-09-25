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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\StatusEnum;
use App\Models\AnjunganMenu as Menu;
use App\Models\Artikel;
use App\Models\Bantuan;
use App\Models\Kategori;
use App\Models\Kelompok;
use App\Models\Suplemen;

defined('BASEPATH') || exit('No direct script access allowed');

class Anjungan_menu extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 312;
        $this->sub_modul_ini = 348;
    }

    public function index()
    {
        return view('admin.anjungan_menu.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(Menu::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url('anjungan_menu/urut/' . $row->id . '/1') . '" class="btn bg-olive btn-sm"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a> ';
                        $aksi .= '<a href="' . site_url('anjungan_menu/urut/' . $row->id . '/-1') . '" class="btn bg-olive btn-sm"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a> ';
                        $aksi .= '<a href="' . route('anjungan_menu.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if (! $row->status) {
                            $aksi .= '<a href="' . site_url("anjungan_menu/kunci/{$row->id}/1") . '" class="btn bg-navy btn-sm" title="Aktifkan Anjungan"><i class="fa fa-lock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("anjungan_menu/kunci/{$row->id}/0") . '" class="btn bg-navy btn-sm" title="Nonaktifkan Anjungan"><i class="fa fa-unlock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('anjungan_menu.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        $this->redirect_hak_akses('u');
        $tipe_link = $this->referensi_model->list_ref(LINK_TIPE);
        array_pop($tipe_link);

        $data['link_tipe']                  = $tipe_link;
        $data['artikel_statis']             = Artikel::where('id_kategori', 999)->get();
        $data['kategori_artikel']           = Kategori::where('enabled', 1)->get();
        $data['statistik_penduduk']         = $this->referensi_model->list_ref(STAT_PENDUDUK);
        $data['statistik_keluarga']         = $this->referensi_model->list_ref(STAT_KELUARGA);
        $data['statistik_kategori_bantuan'] = $this->referensi_model->list_ref(STAT_BANTUAN);
        $data['statistik_program_bantuan']  = Bantuan::get();
        $data['kelompok']                   = Kelompok::tipe('kelompok')->get();
        $data['lembaga']                    = Kelompok::tipe('lembaga')->get();
        $data['suplemen']                   = Suplemen::get();
        $data['statis_lainnya']             = $this->referensi_model->list_ref(STAT_LAINNYA);
        $data['artikel_keuangan']           = Artikel::where('id_kategori', 1001)->get();

        if ($id) {
            $data['action']      = 'Ubah';
            $data['form_action'] = route('anjungan_menu.update', $id);
            $data['menu']        = Menu::find($id) ?? show_404();
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = route('anjungan_menu.insert');
            $data['menu']        = null;
        }

        return view('admin.anjungan_menu.form', $data);
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');

        if (! empty($this->request['surat'])) {
            $this->surat_master_model->upload($this->request['url_surat']);
        }

        if (Menu::insert(static::validated($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null)
    {
        $this->redirect_hak_akses('u');

        $data = Menu::find($id) ?? show_404();

        if ($data->update(static::validated($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null)
    {
        $this->redirect_hak_akses('h');

        $file = LOKASI_ICON_MENU_ANJUNGAN . Menu::find($id)->icon;
        if (is_file($file)) {
            unlink($file);
        }

        if (Menu::destroy($id ?? $this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function kunci($id = null, $val = 0)
    {
        $this->redirect_hak_akses('u');

        $favorit = Menu::find($id) ?? show_404();
        $favorit->update(['status' => ($val == 1) ? StatusEnum::TIDAK : StatusEnum::YA]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function urut($id, $urut)
    {
        $menu = Menu::findOrFail($id);
        if ($urut == -1 && Menu::min('urut') == $menu->urut) {
            return redirect_with('error', 'Menu sudah berada di urutan pertama');
        }
        if ($urut == 1 && Menu::max('urut') == $menu->urut) {
            return redirect_with('error', 'Menu sudah berada di urutan terakhir');
        }
        $perubahan = $menu->urut + $urut;
        Menu::where('urut', $perubahan)->update(['urut' => $menu->urut]);
        $menu->update(['urut' => $perubahan]);

        return redirect_with('success', 'Berhasil Ubah Data');
    }

    protected static function validated($request = [], $id = null)
    {
        if (! $id) {
            $urut = Menu::max('urut') + 1;
        } else {
            $urut = Menu::find($id)->urut;
        }

        $validated = [
            'nama'      => htmlentities($request['nama']),
            'link'      => $request['link'],
            'icon'      => static::unggah('icon') ?? $request['old_icon'],
            'link_tipe' => $request['link_tipe'],
            'urut'      => $urut,
            'status'    => 1,
        ];

        if ($id) {
            $validated['created_by'] = $validated['updated_by'] = auth()->id;
            $file                    = LOKASI_ICON_MENU_ANJUNGAN . Menu::find($id)->icon;
            if (is_file($file) && $request['icon']) {
                unlink($file);
            }
        } else {
            $validated['created_by'] = auth()->id;
        }

        return $validated;
    }

    protected static function unggah($jenis = '')
    {
        $CI = &get_instance();
        $CI->load->library('upload');
        folder(LOKASI_ICON_MENU_ANJUNGAN);

        $CI->uploadConfig = [
            'upload_path'   => LOKASI_ICON_MENU_ANJUNGAN,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => max_upload() * 1024,
        ];
        // Adakah berkas yang disertakan?
        if (empty($_FILES[$jenis]['name'])) {
            return null;
        }
        // Tes tidak berisi script PHP
        if (isPHP($_FILES[$jenis]['tmp_name'], $_FILES[$jenis]['name'])) {
            redirect_with('error', 'Jenis file ini tidak diperbolehkan');
        }
        $uploadData = null;
        // Inisialisasi library 'upload'
        $CI->upload->initialize($CI->uploadConfig);
        // Upload sukses
        if ($CI->upload->do_upload($jenis)) {
            $uploadData = $CI->upload->data();
            $tipe_file  = TipeFile($_FILES['icon']);
            resizeImage(LOKASI_ICON_MENU_ANJUNGAN . $uploadData['file_name'], $tipe_file, ['width' => 100, 'height' => 100]);

            return $uploadData['file_name'];
        }
        redirect_with('error', $CI->upload->display_errors(null, null));

        return null;
    }
}
