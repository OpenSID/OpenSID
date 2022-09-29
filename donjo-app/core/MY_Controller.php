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

use App\Models\Config;
use App\Models\GrupAkses;
use App\Models\LogSurat;
use App\Models\Pamong;
use App\Models\Pesan;
use Illuminate\Support\Facades\Schema;

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
        $this->load->model(['setting_model']);
        $this->controller = strtolower($this->router->fetch_class());
        $this->setting_model->init();
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
    public $cek_anjungan;

    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->header = Schema::hasColumn('tweb_desa_pamong', 'jabatan_id') ? Config::first() : null;

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

        // Untuk anjungan
        $this->load->model('anjungan_model');
        $this->cek_anjungan = $this->anjungan_model->cek_anjungan();

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
        $data['teks_berjalan'] = $this->teks_berjalan_model->list_data(true, 1);
        $data['slide_artikel'] = $this->first_artikel_m->slide_show();
        $data['slider_gambar'] = $this->first_artikel_m->slider_gambar();
        $data['w_cos']         = $this->web_widget_model->get_widget_aktif();
        $data['cek_anjungan']  = $this->cek_anjungan;

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
        $pamong_kades = Pamong::ttd('a.n')->first();

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
    public $is_login;

    public function __construct()
    {
        parent::__construct();
        $this->is_login = $this->session->is_login;
        $this->header   = Schema::hasColumn('tweb_desa_pamong', 'jabatan_id') ? Config::first() : null;

        if ($this->setting->layanan_mandiri == 0 && ! $this->cek_anjungan) {
            show_404();
        }

        if ($this->session->mandiri != 1) {
            if (! $this->session->login_ektp) {
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

class Premium extends MY_Controller
{
    // Hanya domain terdaftar yg bisa melewati validasi premium dengan mode demo
    protected $domain = [
        'beta.opendesa.id',
        'beta2.opensid.or.id',
        'berputar.opendesa.id',
    ];
    protected $versi_setara;

    /**
     * TODO :
     * Controller main exted ke CI_Controller bukan ke Admin_controller tp masih berpengaruh pada validasi pengguna premium
     * Sehingga akan error saat login di awal, namun setelah di refresh akan kembali normal
     */
    protected $kecuali = [
        'hom_sid', 'identitas_desa', 'pelanggan', 'pendaftaran_kerjasama', 'setting', 'notif', 'user_setting', 'main', 'info_sistem',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('header_model');
        $this->header = $this->header_model->get_data();
    }

    /**
     * Validasi akses.
     *
     * @return mixed
     */
    public function validasi()
    {
        // Jangan jalankan validasi akses untuk spesifik controller.
        if (in_array($this->router->class, $this->kecuali) || (config_item('demo_mode') && (in_array(get_domain(APP_URL), $this->domain)))) {
            return;
        }

        // Validasi akses
        if (! $this->validasi_akses()) {
            redirect('peringatan');
        }

        $this->session->unset_userdata(['error_premium', 'error_premium_pesan']);
    }

    /**
     * Validasi akses fitur.
     *
     * @return bool
     */
    protected function validasi_akses()
    {
        $this->session->unset_userdata('error_premium');

        if (empty($this->header['desa']['kode_desa'])) {
            $this->session->set_userdata('error_premium', 'Kode desa diperlukan.');

            return false;
        }

        if (empty($token = $this->setting->layanan_opendesa_token)) {
            $this->session->set_userdata('error_premium', 'Token pelanggan kosong / tidak valid.');

            return false;
        }

        $tokenParts   = explode('.', $token);
        $tokenPayload = base64_decode($tokenParts[1], true);
        $jwtPayload   = json_decode($tokenPayload);
        $date         = new DateTime('20' . str_replace('.', '-', currentVersion()) . '-01');
        $version      = $date->format('Y-m-d');

        if (version_compare($jwtPayload->desa_id, kode_wilayah($this->header['desa']['kode_desa']), '!=')) {
            $this->session->set_userdata('error_premium', ucwords($this->setting->sebutan_desa . ' ' . $this->header['desa']['nama_desa']) . ' tidak terdaftar di ' . config_item('server_layanan'));

            $this->daftarHitam();

            return false;
        }

        $berakhir   = $jwtPayload->tanggal_berlangganan->akhir;
        $disarankan = 'v' . str_replace('-', '.', substr($berakhir, 2, 5)) . '-premium';

        if ($version > $berakhir) {
            // Versi premium setara dengan umum adalah 6 bulan setelahnya + 1 bulan untuk versi pembaharuan
            // Misalnya 21.05-premium setara dengan 21.12-umum, notifikasi tampil jika ada umum di atas 21.12-umum
            $this->versi_setara = date('Y-m-d', strtotime('+7 month', strtotime($berakhir)));
            $this->versi_setara = str_replace('-', '.', substr($this->versi_setara, 2, 5));
            $this->session->set_userdata('error_premium', 'Masa aktif berlangganan fitur premium sudah berakhir.');
            $this->session->set_userdata('error_premium_pesan', "Hanya diperbolehkan menggunakan {$disarankan} (maupun versi revisinya) atau menggunakan versi rilis {$this->versi_setara} umum.");

            return false;
        }

        if (isLocalIPAddress($_SERVER['REMOTE_ADDR'])) {
            return true;
        }

        if (get_domain($jwtPayload->domain) != get_domain(APP_URL)) {
            $this->session->set_userdata('error_premium', 'Domain ' . get_domain(APP_URL) . ' tidak terdaftar di ' . config_item('server_layanan'));

            $this->daftarHitam();

            return false;
        }

        return true;
    }

    private function daftarHitam()
    {
        $this->load->library('user_agent');
        if ($this->agent->is_browser()) {
            $browser = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $browser = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $browser = $this->agent->mobile();
        } else {
            $browser = 'Unidentified User Agent';
        }

        $os = $this->agent->platform();

        try {
            $client = new \GuzzleHttp\Client();
            $client->post(config_item('server_layanan') . '/api/v1/pelanggan/daftarhitam', [
                'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
                'form_params' => [
                    'kode_desa'  => kode_wilayah($this->header['desa']['kode_desa']),
                    'ip_address' => $this->input->ip_address(),
                    'token'      => $this->setting->layanan_opendesa_token,
                    'waktu'      => date('Y-m-d h:i:sa'),
                    'browser'    => $browser,
                    'os'         => $os,
                    'domain'     => get_domain(APP_URL),
                ],
            ])
                ->getBody();
        } catch (Exception $e) {
            log_message('error', $e);
        }
    }
}

class Admin_Controller extends Premium
{
    public $grup;
    public $CI;
    public $pengumuman;

    public function __construct()
    {
        parent::__construct();
        $this->validasi();
        $this->CI = CI_Controller::get_instance();
        $this->load->model(['user_model', 'notif_model', 'referensi_model']);

        // Kalau sehabis periksa data, paksa harus login lagi
        if ($this->session->periksa_data == 1) {
            $this->user_model->logout();
        }

        $this->grup = $this->user_model->sesi_grup($_SESSION['sesi']);
        $this->load->model('modul_model');
        if (! $this->modul_model->modul_aktif($this->controller)) {
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
        $this->header['notif_permohonan_surat'] = $this->notif_model->permohonan_surat_baru();
        $this->header['notif_inbox']            = $this->notif_model->inbox_baru();
        $this->header['notif_komentar']         = $this->notif_model->komentar_baru();
        $this->header['notif_langganan']        = $this->notif_model->status_langganan();
        $this->header['notif_pesan_opendk']     = $cek_kotak_pesan ? Pesan::where('sudah_dibaca', '=', 0)->where('diarsipkan', '=', 0)->count() : 0;
        $this->header['notif_pengumuman']       = $this->cek_pengumuman();
        $isAdmin                                = $this->session->isAdmin->pamong;
        $this->header['notif_permohonan']       = 0;
        if ($this->db->field_exists('verifikasi_operator', 'log_surat')) {
            $this->header['notif_permohonan'] = LogSurat::when($isAdmin->jabatan_id == '1', static function ($q) {
                return $q->when(setting('tte') == 1, static function ($tte) {
                    return $tte->where('verifikasi_kades', '=', 0)->orWhere('tte', '=', 0);
                })->when(setting('tte') == 0, static function ($tte) {
                    return $tte->where('verifikasi_kades', '=', 0);
                });
            })
                ->when($isAdmin->jabatan_id == '2', static function ($q) {
                    return $q->where('verifikasi_sekdes', '=', '0');
                })
                ->when($isAdmin == null || ! in_array($isAdmin->jabatan_id, ['1', '2']), static function ($q) {
                    return $q->where('verifikasi_operator', '=', '0')->orWhere('verifikasi_operator', '=', '-1');
                })
                ->count();
        }
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

    protected function redirect_hak_akses($akses, $redirect = '', $controller = '', $admin_only = false)
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }

        if (($admin_only && $this->grup != GrupAkses::ADMINISTRATOR) || ! $this->user_model->hak_akses($this->grup, $controller, $akses)) {
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

    public function modal_penandatangan()
    {
        $this->load->model('pamong_model');

        return [
            'pamong'         => $this->pamong_model->list_data(),
            'pamong_ttd'     => Pamong::ttd('a.n')->first(),
            'pamong_ketahui' => Pamong::kepalaDesa()->first(),
        ];
    }
}
