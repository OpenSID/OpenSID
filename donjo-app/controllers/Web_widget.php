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
use App\Models\Widget;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Web_widget extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'widget';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        // Jika offline_mode dalam level yang menyembunyikan website,
        // tidak perlu menampilkan halaman website
        if (setting('offline_mode') >= 2) {
            redirect('beranda');
        }

        $this->load->model(['web_widget_model']);
    }

    public function index()
    {
        return view('admin.web.widget.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status') ?? null;

            return datatables()->of(Widget::orderBy('urut')->when($status, static fn ($q) => $q->where('enabled', $status)))
                ->addColumn('drag-handle', static fn (): string => '<i class="fa fa-sort-alpha-desc"></i>')
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u') && $row->jenis_widget != 1) {
                        $aksi .= '<a href="' . ci_route('web_widget.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }
                    if ($row->form_admin) {
                        $aksi .= '<a href="' . ci_route($row->form_admin) . '" class="btn btn-info btn-sm"  title="Form Admin"><i class="fa fa-sliders"></i></a> ';
                    }
                    if (can('u')) {
                        if ($row->enabled == StatusEnum::YA) {
                            $aksi .= '<a href="' . ci_route('web_widget.lock') . '/' . $row->id . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('web_widget.lock') . '/' . $row->id . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }
                    if (can('h') && $row->jenis_widget != 1) {
                        $aksi .= '<a href="#" data-href="' . ci_route('web_widget.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('DT_RowAttr', static function ($row): array {
                    $style = '';
                    if ($row->jenis_widget != 1) {
                        $style = 'background-color: #f8deb5;';
                    }

                    return ['style' => $style];
                })
                ->editColumn('isi', static function ($row): string {
                    if ($row->jenis_widget == Widget::WIDGET_DINAMIS) {
                        return Str::limit($row->isi, 200, '...');
                    }

                    return $row->isi;
                })
                ->addColumn('jenis_widget', static fn ($row): string => $row->jenis_widget == '1' ? 'Sistem' : ($row->jenis_widget == '2' ? 'Statis' : 'Dinamis'))
                ->rawColumns(['drag-handle', 'ceklist', 'aksi', 'jenis_widget'])
                ->make();
        }

        return show_404();
    }

    public function tukar()
    {
        isCan('u');
        $widget = $this->input->post('data');

        Widget::setNewOrder($widget);

        return json(['status' => 1]);
    }

    public function form($id = '')
    {
        isCan('u');

        if ($id) {
            $data['aksi']        = 'Ubah';
            $data['widget']      = Widget::GetWidget($id);
            $data['form_action'] = ci_route('web_widget.update', $id);
        } else {
            $data['aksi']        = 'Tambah';
            $data['widget']      = null;
            $data['form_action'] = ci_route('web_widget.insert');
        }

        $data['list_widget'] = Widget::listWidgetBaru();

        return view('admin.web.widget.form', $data);
    }

    public function admin($widget)
    {
        $data['form_action'] = ci_route('web_widget.update_setting', $widget);
        $data['settings']    = Widget::getSetting($widget);
        if ($widget == 'aparatur_desa') {
            $data['pemerintah'] = ucwords((string) setting('sebutan_pemerintah_desa'));

            return view('admin.web.widget.form_admin.admin_' . $widget, $data);
        }
        if ($widget == 'sinergi_program') {
            redirect($widget);
        }
    }

    public function update_setting($widget): void
    {
        isCan('u');

        $this->cek_tidy();
        $setting = $this->input->post('setting');
        $this->web_widget_model->update_setting($widget, $setting);

        redirect("{$this->controller}/admin/{$widget}");
    }

    public function insert(): void
    {
        isCan('u');

        $this->cek_tidy();

        if (Widget::create($this->validasi($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    private function upload_gambar(string $jenis, int $id)
    {
        // Inisialisasi library 'upload'
        $CI = &get_instance();
        $CI->load->library('upload');
        $uploadConfig = [
            'upload_path'   => LOKASI_GAMBAR_WIDGET,
            'allowed_types' => 'jpg|jpeg|png|gif',
            'max_size'      => 1024, // 1 MB
        ];
        $CI->upload->initialize($uploadConfig);

        $uploadData = null;
        // Adakah berkas yang disertakan?
        $adaBerkas = ! empty($_FILES[$jenis]['name']);
        if (! $adaBerkas) {
            $berkas = Widget::find($id)->foto;

            // Jika hapus (ceklis)
            if (isset($_POST['hapus_foto'])) {
                unlink(LOKASI_GAMBAR_WIDGET . $berkas);

                return null;
            }

            return $berkas;
        }

        // Upload sukses
        if ($CI->upload->do_upload($jenis)) {
            $uploadData = $this->upload->data();
            // Buat nama file unik agar url file susah ditebak dari browser
            $namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
            // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
            $fileRenamed = rename(
                $uploadConfig['upload_path'] . $uploadData['file_name'],
                $uploadConfig['upload_path'] . $namaFileUnik
            );
            // Ganti nama di array upload jika file berhasil di-rename --
            // jika rename gagal, fallback ke nama asli
            $uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
        }
        // Upload gagal
        else {
            session_error($CI->upload->display_errors(null, null));

            return redirect('web_widget');
        }

        return (empty($uploadData)) ? null : $uploadData['file_name'];
    }

    public function update($id = ''): void
    {
        isCan('u');

        $this->cek_tidy();
        if (Widget::findOrFail($id)->update($this->validasi($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = ''): void
    {
        isCan('h');
        $web = Widget::where('jenis_widget', '!=', Widget::WIDGET_SISTEM)->find($id) ?? show_404();
        if ($web->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function delete_all(): void
    {
        isCan('h');
        if (Widget::whereIn('id', $this->request['id_cb'])->where('jenis_widget', '!=', Widget::WIDGET_SISTEM)->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function lock($id = 0): void
    {
        isCan('u');

        if (Widget::gantiStatus($id, 'enabled')) {
            redirect_with('success', 'Berhasil Ubah Status');
        }

        redirect_with('error', 'Gagal Ubah Status');
    }

    private function cek_tidy(): void
    {
        if (! in_array('tidy', get_loaded_extensions())) {
            $pesan = '<br/>Ektensi <code>tidy</code> tidak aktif. Silahkan cek <a href="' . ci_route('info_sistem') . '"><b>Pengaturan > Info Sistem > Kebutuhan Sistem.</a></b>';

            redirect_with('error', $pesan);
        }
    }

    private function validasi(array $post, int $id = 0)
    {
        $data['judul']        = judul($post['judul']);
        $data['jenis_widget'] = (int) $post['jenis_widget'];
        $data['foto']         = $this->upload_gambar('foto', $id);
        if ($data['jenis_widget'] == 2) {
            $data['isi'] = bersihkan_xss($post['isi-statis']);
        } elseif ($data['jenis_widget'] == 3) {
            $data['isi'] = $this->bersihkan_html($post['isi-dinamis']);
        }

        return $data;
    }

    private function bersihkan_html($isi): string
    {
        // Konfigurasi tidy
        $config = [
            'indent'         => true,
            'output-xhtml'   => true,
            'show-body-only' => true,
            'clean'          => true,
            'coerce-endtags' => true,
            'drop-empty-elements' => false,
            'preserve-entities'   => true,
        ];
        
        $tidy = new tidy();
        $tidy->parseString($isi, $config, 'utf8');
        $tidy->cleanRepair();

        $output = tidy_get_output($tidy);

        return $output;
    }
}
