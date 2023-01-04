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

use App\Models\Pesan;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property CI_Benchmark        $benchmark
 * @property CI_Config           $config
 * @property CI_DB_query_builder $db
 * @property CI_Input            $input
 * @property CI_Lang             $lang
 * @property CI_Loader           $loader
 * @property CI_log              $log
 * @property CI_Output           $output
 * @property CI_Router           $router
 * @property CI_Security         $security
 * @property CI_Session          $session
 * @property CI_URI              $uri
 * @property CI_Utf8             $utf8
 */
class MY_Controller extends CI_Controller
{
    // Common data
    public $user;
    public $settings;
    public $includes;
    public $current_uri;
    public $theme;
    public $template;
    public $error;
    public $header;

    // Constructor
    public function __construct()
    {
        parent::__construct();
        $error = $this->session->db_error;
        if ($error['code'] == 1049 && ! $this->db) {
            return;
        }
        /*
        | Tambahkan model yg akan diautoload di sini.
        | donjo-app/config/autoload.php digunakan untuk autoload model untuk mengisi data awal
        | pada waktu install, di mana database masih kosong
        */
        $this->load->model(['config_model', 'setting_model']);
        $this->controller = strtolower($this->router->fetch_class());
        $this->setting_model->init();
        $this->header  = $this->config_model->get_data();
        $this->request = $this->input->post();
    }

    // Bersihkan session cluster wilayah
    public function clear_cluster_session()
    {
        $cluster_session = ['dusun', 'rw', 'rt'];

        foreach ($cluster_session as $session) {
            $this->session->unset_userdata($session);
        }
    }
}

class Web_Controller extends MY_Controller
{
    // Constructor
    public function __construct()
    {
        parent::__construct();
        if ($this->setting->offline_mode == 2) {
            $this->view_maintenance();
        } elseif ($this->setting->offline_mode == 1) {
            $this->load->model('user_model');
            $grup = $this->user_model->sesi_grup($this->session->sesi);
            if (! $this->user_model->hak_akses($grup, 'web', 'b')) {
                $this->view_maintenance();
            }
        }

        $this->load->model('theme_model');
        $this->theme        = $this->theme_model->tema;
        $this->theme_folder = $this->theme_model->folder;

        // Variabel untuk tema
        $this->set_template();
        $this->includes['folder_themes'] = "../../{$this->theme_folder}/{$this->theme}";

        $this->load->model('web_menu_model');
    }

    /**
     * set_template function
     *
     * @param string $template_file
     *
     * @return void
     */
    public function set_template($template_file = 'template')
    {
        $this->template = "../../{$this->theme_folder}/{$this->theme}/{$template_file}";
    }

    public function _get_common_data(&$data)
    {
        $this->load->library('statistik_pengunjung');

        $this->load->model('first_menu_m');
        $this->load->model('teks_berjalan_model');
        $this->load->model('first_artikel_m');
        $this->load->model('web_widget_model');
        $this->load->model('anjungan_model');
        $this->load->model('keuangan_grafik_manual_model');
        $this->load->model('keuangan_grafik_model');
        $this->load->model('pengaduan_model');

        // Counter statistik pengunjung
        $this->statistik_pengunjung->counter_visitor();

        // Data statistik pengunjung
        $data['statistik_pengunjung'] = $this->statistik_pengunjung->get_statistik();

        $data['latar_website'] = $this->theme_model->latar_website();
        $data['desa']          = $this->header;
        $data['menu_atas']     = $this->first_menu_m->list_menu_atas();
        $data['menu_kiri']     = $this->first_menu_m->list_menu_kiri();
        $data['teks_berjalan'] = $this->teks_berjalan_model->list_data(true);
        $data['slide_artikel'] = $this->first_artikel_m->slide_show();
        $data['slider_gambar'] = $this->first_artikel_m->slider_gambar();
        $data['w_cos']         = $this->web_widget_model->get_widget_aktif();
        $data['cek_anjungan']  = $this->anjungan_model->cek_anjungan();

        $this->web_widget_model->get_widget_data($data);
        $data['data_config'] = $this->header;
        if ($this->setting->apbdes_footer && $this->setting->apbdes_footer_all) {
            $data['transparansi'] = $this->setting->apbdes_manual_input
                ? $this->keuangan_grafik_manual_model->grafik_keuangan_tema()
                : $this->keuangan_grafik_model->grafik_keuangan_tema();
        }
        // Pembersihan tidak dilakukan global, karena artikel yang dibuat oleh
        // petugas terpecaya diperbolehkan menampilkan <iframe> dsbnya..
        $list_kolom = [
            'arsip',
            'w_cos',
        ];

        foreach ($list_kolom as $kolom) {
            $data[$kolom] = $this->security->xss_clean($data[$kolom]);
        }
    }

    private function view_maintenance()
    {
        $this->load->model('pamong_model');

        $main         = $this->header;
        $pamong_kades = $this->pamong_model->get_ttd();

        // TODO : Gunakan view blade
        if (file_exists(DESAPATH . 'offline_mode.php')) {
            include DESAPATH . 'offline_mode.php';
        } else {
            include VIEWPATH . 'offline_mode.php';
        }

        exit();
    }
}

class Mandiri_Controller extends MY_Controller
{
    public $cek_anjungan;
    public $is_login;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('anjungan_model');
        $this->cek_anjungan = $this->anjungan_model->cek_anjungan();
        $this->is_login     = $this->session->is_login;

        if ($this->setting->layanan_mandiri == 0 && !$this->cek_anjungan) {
            show_404();
        }

        if ($this->session->mandiri != 1) {
            if (!$this->session->login_ektp) {
                redirect('layanan-mandiri/masuk');
            } else {
                redirect('layanan-mandiri/masuk-ektp');
            }
        }
    }

    public function render($view, ?array $data = null)
    {
        $data['desa']         = $this->header;
        $data['cek_anjungan'] = $this->cek_anjungan;
        $data['konten']       = $view;
        $this->load->view(MANDIRI . '/template', $data);
    }
}

// Untuk API read-only, seperti Api_informasi_publik
class Api_Controller extends MY_Controller
{
    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    protected function log_request()
    {
        $message = 'API Request ' . $this->input->server('REQUEST_URI') . ' dari ' . $this->input->ip_address();
        log_message('error', $message);
    }
}

class Admin_Controller extends MY_Controller
{
    public $grup;
    public $CI;
    public $pengumuman;

    public function __construct()
    {
        parent::__construct();
        $this->CI = CI_Controller::get_instance();
        $this->load->model(['header_model', 'user_model', 'notif_model', 'referensi_model']);

        // Kalau sehabis periksa data, paksa harus login lagi
        if ($this->session->periksa_data == 1) {
            $this->user_model->logout();
        }

        $this->grup = $this->user_model->sesi_grup($_SESSION['sesi']);
        $this->load->model('modul_model');
        if (!$this->modul_model->modul_aktif($this->controller)) {
            session_error('Fitur ini tidak aktif');
            redirect($_SERVER['HTTP_REFERER']);
        }

        if (! $this->user_model->hak_akses($this->grup, $this->controller, 'b')) {
            if (empty($this->grup)) {
                $_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
                redirect('siteman');
            } else {
                session_error('Anda tidak mempunyai akses pada fitur itu');
                unset($_SESSION['request_uri']);
                redirect('main');
            }
        }
        $cek_kotak_pesan                        = $this->db->table_exists('pesan') && $this->db->table_exists('pesan_detail');
        $this->header                           = $this->header_model->get_data();
        $this->header['notif_permohonan_surat'] = $this->notif_model->permohonan_surat_baru();
        $this->header['notif_inbox']            = $this->notif_model->inbox_baru();
        $this->header['notif_komentar']         = $this->notif_model->komentar_baru();
        $this->header['notif_langganan']        = $this->notif_model->status_langganan();
        $this->header['notif_pesan_opendk']     = $cek_kotak_pesan ? Pesan::where('sudah_dibaca', '=', 0)->where('diarsipkan', '=', 0)->count() : 0;
        $this->header['notif_pengumuman']       = $this->cek_pengumuman();
    }

    private function cek_pengumuman()
    {
        if (config_item('demo_mode') || ENVIRONMENT === 'development') {
            return null;
        }

        // Hanya untuk user administrator
        if ($this->grup == 1) {
            $notifikasi = $this->notif_model->get_semua_notif();
            foreach ($notifikasi as $notif) {
                $pengumuman = $this->notif_model->notifikasi($notif);
                if ($notif['jenis'] == 'persetujuan') {
                    break;
                }
            }
        }

        return $pengumuman;
    }

    // Untuk kasus di mana method controller berbeda hak_akses. Misalnya 'setting_qrcode' readonly, tetapi 'setting/analisis' boleh ubah
    protected function redirect_hak_akses_url($akses, $redirect = '', $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }
        if (! $this->user_model->hak_akses_url($this->grup, $controller, $akses)) {
            session_error('Anda tidak mempunyai akses pada fitur ini');
            if (empty($this->grup)) {
                redirect('siteman');
            }
            empty($redirect) ? redirect($_SERVER['HTTP_REFERER']) : redirect($redirect);
        }
    }

    protected function redirect_hak_akses($akses, $redirect = '', $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }
        if (! $this->user_model->hak_akses($this->grup, $controller, $akses)) {
            session_error('Anda tidak mempunyai akses pada fitur ini');
            if (empty($this->grup)) {
                redirect('siteman');
            }
            empty($redirect) ? redirect($_SERVER['HTTP_REFERER']) : redirect($redirect);
        }
    }

    // Untuk kasus di mana method controller berbeda hak_akses. Misalnya 'setting_qrcode' readonly, tetapi 'setting/analisis' boleh ubah
    public function cek_hak_akses_url($akses, $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }

        return $this->user_model->hak_akses_url($this->grup, $controller, $akses);
    }

    public function cek_hak_akses($akses, $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }

        return $this->user_model->hak_akses($this->grup, $controller, $akses);
    }

    public function redirect_tidak_valid($valid)
    {
        if ($valid) {
            return;
        }

        session_error('Aksi ini tidak diperbolehkan');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function render($view, ?array $data = null)
    {
        $this->load->view('header', $this->header);
        $this->load->view('nav');
        $this->load->view($view, $data);
        $this->load->view('footer');
    }
}
