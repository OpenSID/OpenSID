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

use App\Enums\StatusEnum;
use App\Models\Artikel;
use App\Models\Bantuan;
use App\Models\Kategori;
use App\Models\Kelompok;
use App\Models\Suplemen;
use Modules\Anjungan\Models\AnjunganMenu as Menu;

defined('BASEPATH') || exit('No direct script access allowed');

class Anjungan_menu extends AnjunganModulController
{
    public $modul_ini     = 'anjungan';
    public $sub_modul_ini = 'anjungan-menu';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        return view('admin.anjungan_menu.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $order = $this->input->get('order') ?? false;

            return datatables()->of(Menu::when(! $order, static fn ($q) => $q->orderBy('urut')))
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
                        $aksi .= '<a href="' . ci_route('anjungan_menu.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if ($row->status == StatusEnum::YA) {
                            $aksi .= '<a href="' . ci_route('anjungan_menu.lock', $row->id) . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('anjungan_menu.lock', $row->id) . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('anjungan_menu.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['drag-handle', 'ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');
        $tipe_link = $this->referensi_model->list_ref(LINK_TIPE);

        $data['link_tipe']                  = $tipe_link;
        $data['artikel_statis']             = Artikel::statis()->get();
        $data['kategori_artikel']           = Kategori::where('enabled', 1)->get();
        $data['statistik_penduduk']         = $this->referensi_model->list_ref(STAT_PENDUDUK);
        $data['statistik_keluarga']         = $this->referensi_model->list_ref(STAT_KELUARGA);
        $data['statistik_kategori_bantuan'] = $this->referensi_model->list_ref(STAT_BANTUAN);
        $data['statistik_program_bantuan']  = Bantuan::get();
        $data['kelompok']                   = Kelompok::tipe('kelompok')->get();
        $data['lembaga']                    = Kelompok::tipe('lembaga')->get();
        $data['suplemen']                   = Suplemen::get();
        $data['statis_lainnya']             = $this->referensi_model->list_ref(STAT_LAINNYA);
        $data['artikel_keuangan']           = Artikel::keuangan()->get();

        if ($id) {
            $data['action']      = 'Ubah';
            $data['form_action'] = ci_route('anjungan_menu.update', $id);
            $data['menu']        = Menu::findOrFail($id);
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('anjungan_menu.insert');
            $data['menu']        = null;
        }

        return view('admin.anjungan_menu.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (Menu::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = Menu::findOrFail($id);

        if ($data->update(static::validate($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (Menu::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function lock($id = 0): void
    {
        isCan('u');

        if (Menu::gantiStatus($id, 'status')) {
            redirect_with('success', 'Berhasil Ubah Status');
        }

        redirect_with('error', 'Gagal Ubah Status');
    }

    public function tukar()
    {
        isCan('u');

        $menu = $this->input->post('data');
        Menu::setNewOrder($menu);

        return json(['status' => 1]);
    }

    protected static function validate(array $request = [], $id = null): array
    {
        $urut = $id ? Menu::find($id)->urut : Menu::max('urut') + 1;

        return [
            'nama'      => htmlentities($request['nama']),
            'link'      => $request['link'],
            'icon'      => static::unggah('icon'),
            'link_tipe' => $request['link_tipe'],
            'urut'      => $urut,
            'status'    => 1,
        ];
    }

    protected static function unggah($jenis = '')
    {
        $CI = &get_instance();
        $CI->load->library('MY_Upload', null, 'upload');
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
