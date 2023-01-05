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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Config;

defined('BASEPATH') || exit('No direct script access allowed');

class Siteman extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        siteman_timeout();
        $this->load->model('user_model');
        $this->load->model('theme_model');
        $this->lang->load('passwords');
        $this->load->library('Reset/Password', '', 'password');
        $this->latar_login = to_base64(default_file(LATAR_SITEMAN, DEFAULT_LATAR_SITEMAN));
    }

    public function index()
    {
        // Kalau sehabis periksa data, paksa harus login lagi
        if ($this->session->periksa_data == 1) {
            $this->user_model->logout();

            redirect('siteman');
        }

        if (isset($_SESSION['siteman']) && $_SESSION['siteman'] == 1) {
            redirect('main');
        }
        unset($_SESSION['balik_ke']);
        $data['header'] = Config::first();

        $data['form_action'] = site_url('siteman/auth');
        $data['logo_bsre']   = to_base64(default_file(LOGO_BSRE, false));
        $data['latar_login'] = $this->latar_login;
        //Initialize Session ------------
        if (! isset($_SESSION['siteman'])) {
            // Belum ada session variable
            $this->session->set_userdata('siteman', 0);
            $this->session->set_userdata('siteman_try', 4);
            $this->session->set_userdata('siteman_wait', 0);
        }
        session_error_clear();
        $_SESSION['per_page']   = 10;
        $_SESSION['cari']       = '';
        $_SESSION['pengumuman'] = 0;
        $_SESSION['sesi']       = 'kosong';
        //-------------------------------

        $this->load->view('siteman', $data);
    }

    public function auth()
    {
        $method       = $this->input->method(true);
        $allow_method = ['POST'];
        if (! in_array($method, $allow_method)) {
            redirect('siteman');
        }
        $this->user_model->siteman();

        if ($_SESSION['siteman'] != 1) {
            // Gagal otentifikasi
            redirect('siteman');
        }

        if (! $this->user_model->syarat_sandi() && ! ($this->session->user == 1 && (config_item('demo_mode') || ENVIRONMENT === 'development'))) {
            // Password tidak memenuhi syarat kecuali di website demo
            redirect('user_setting/change_pwd');
        }

        $_SESSION['dari_login'] = '1';
        // Notif bisa dipanggil sewaktu-waktu dan tidak digunakan untuk redirect
        if (isset($_SESSION['request_uri']) && strpos($_SESSION['request_uri'], 'notif/') === false) {
            // Lengkapi url supaya tidak diubah oleh redirect
            $request_awal = $_SERVER['HTTP_ORIGIN'] . $_SESSION['request_uri'];
            unset($_SESSION['request_uri']);
            redirect($request_awal);
        } else {
            unset($_SESSION['request_uri']);
            redirect('main');
        }
    }

    public function logout()
    {
        $this->user_model->logout();

        redirect('siteman');
    }

    public function lupa_sandi()
    {
        $data['header']      = Config::first();
        $data['latar_login'] = $this->latar_login;

        $this->load->view('lupa_sandi', $data);
    }

    public function kirim_lupa_sandi()
    {
        // Periksa isian captcha
        include FCPATH . 'securimage/securimage.php';
        $securimage = new Securimage();

        if (! $securimage->check($this->input->post('captcha_code'))) {
            set_session('notif', 'Kode captcha anda salah. Silakan ulangi lagi.');

            redirect('siteman/lupa_sandi');
        }

        try {
            $status = $this->password->driver('email')->sendResetLink([
                'email' => $this->input->post('email'),
            ]);
        } catch (\Exception $e) {
            log_message('error', $e);

            set_session('notif', 'Tidak berhasil mengirim email, harap mencoba kembali.');

            redirect('siteman/lupa_sandi');
        }

        set_session('notif', lang($status));

        redirect('siteman/lupa_sandi');
    }

    public function reset_kata_sandi($token = null)
    {
        if (! $token) {
            redirect('siteman');
        }

        $data['header']      = Config::first();
        $data['email']       = $this->input->get('email', true);
        $data['token']       = $token;
        $data['latar_login'] = $this->latar_login;

        $this->load->view('reset_kata_sandi', $data);
    }

    public function verifikasi_sandi()
    {
        $request = (object) $this->input->post();

        if ($request->password !== $request->konfirmasi_password) {
            set_session('notif', 'Bidang konfirmasi password tidak cocok dengan bidang password.');

            redirect("siteman/reset_kata_sandi/{$request->token}?email={$request->email}");
        }

        try {
            $status = $this->password->driver('email')->reset(
                ['email' => $request->email, 'token' => $request->token, 'password' => $request->password],
                function ($user, $password) {
                    $this->db->where('id', $user->id)->update('user', ['password' => $this->generatePasswordHash($password)]);
                }
            );
        } catch (\Exception $e) {
            log_message('error', $e);

            set_session('notif', 'Tidak berhasil memverifikasi kata sandi, silahkan coba kembali.');

            redirect("siteman/reset_kata_sandi/{$request->token}?email={$request->email}");
        }

        set_session('notif', lang($status));

        if ($status === 'reset') {
            redirect('siteman');
        } else {
            redirect("siteman/reset_kata_sandi/{$request->token}?email={$request->email}");
        }
    }

    protected function generatePasswordHash($string)
    {
        // Pastikan inputnya adalah string
        $string = is_string($string) ? $string : (string) $string;
        // Buat hash password
        $pwHash = password_hash($string, PASSWORD_BCRYPT);
        // Cek kekuatan hash, regenerate jika masih lemah
        if (password_needs_rehash($pwHash, PASSWORD_BCRYPT)) {
            $pwHash = password_hash($string, PASSWORD_BCRYPT);
        }

        return $pwHash;
    }
}
