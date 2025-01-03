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

use App\Enums\StatusEnum;
use App\Models\MediaSosial;

defined('BASEPATH') || exit('No direct script access allowed');

class Sosmed extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'media-sosial';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('web_sosmed_model');
    }

    public function index()
    {
        return view('admin.sosmed.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(MediaSosial::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("sosmed/form/{$row->id}") . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if ($row->enabled == StatusEnum::YA) {
                            $aksi .= '<a href="' . site_url("sosmed/lock/{$row->id}") . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("sosmed/lock/{$row->id}") . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . site_url("sosmed/delete/{$row->id}") . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('url_icon', static fn ($row): string => '<a href="' . $row->new_link . '" target="_blank"><img src="' . $row->url_icon . '" class="img-thumbnail" width="50" height="50"></a>')
                ->editColumn('enabled', static fn ($row): string => ($row->enabled == StatusEnum::YA) ? '<span class="label label-success">Aktif</span>' : '<span class="label label-danger">Tidak Aktif</span>')
                ->rawColumns(['ceklist', 'aksi', 'url_icon', 'enabled'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');

        if ($id) {
            $data['action']      = 'Ubah';
            $data['form_action'] = site_url("sosmed/update/{$id}");
            $data['sosmed']      = MediaSosial::findOrFail($id);
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = site_url('sosmed/insert');
            $data['sosmed']      = null;
        }

        return view('admin.sosmed.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (MediaSosial::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = MediaSosial::findOrFail($id);

        if ($data->update(static::validate($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (MediaSosial::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function lock($id = 0): void
    {
        isCan('h');

        if (MediaSosial::gantiStatus($id, 'enabled')) {
            redirect_with('success', 'Berhasil Ubah Status');
        }

        redirect_with('error', 'Gagal Ubah Status');
    }

    protected static function validate(array $request = [], $id = null): array
    {
        $data = [
            'link'    => $request['link'],
            'nama'    => htmlentities((string) $request['nama']),
            'tipe'    => 1,
            'enabled' => $request['enabled'] ?? 0,
        ];

        if (! empty($id) && empty($request['gambar'])) {
            unset($data['gambar']);
        } else {
            $data['gambar'] = static::unggah('gambar');
        }

        return $data;
    }

    protected static function unggah($jenis = '')
    {
        $CI = &get_instance();
        $CI->load->library('upload');
        folder(LOKASI_ICON_SOSMED);

        $CI->uploadConfig = [
            'upload_path'   => LOKASI_ICON_SOSMED,
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
            $tipe_file  = TipeFile($_FILES['gambar']);
            resizeImage(LOKASI_ICON_SOSMED . $uploadData['file_name'], $tipe_file, ['width' => 100, 'height' => 100]);

            return $uploadData['file_name'];
        }

        redirect_with('error', $CI->upload->display_errors(null, null));
    }
}
