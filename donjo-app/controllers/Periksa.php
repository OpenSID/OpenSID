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

use App\Models\Config;
use App\Models\User;
use App\Models\UserGrup;
use App\Services\Auth\Traits\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Periksa extends CI_Controller
{
    use LoginRequest;

    protected $guard = 'admin_periksa';
    public $setting;
    public $header;
    public $latar_login;

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model(['setting_model', 'periksa_model']);

        if ($this->session->db_error['code'] === 1049) {
            redirect('koneksi-database');
        }

        $this->setting_model->init();

        $this->header      = Config::appKey()->first();
        $this->latar_login = default_file(LATAR_LOGIN . $this->setting->latar_login, DEFAULT_LATAR_SITEMAN);
    }

    public function index()
    {
        $this->cek_user();

        if ($this->session->message_query || $this->session->message_exception) {
            log_message('error', $this->session->message_query);
            log_message('error', $this->session->message_exception);
        }

        return view('periksa.index', array_merge($this->periksa_model->periksa, ['header' => $this->header]));
    }

    private function cek_user(): void
    {
        if (! Auth::guard($this->guard)->check()) {
            redirect('periksa/login');
        }
    }

    public function perbaiki(): void
    {
        $this->cek_user();
        $this->periksa_model->perbaiki();
        $this->session->unset_userdata(['db_error', 'message', 'message_query', 'heading', 'message_exception']);

        redirect('/');
    }

    public function perbaiki_sebagian($masalah): void
    {
        $this->cek_user();
        $this->periksa_model->perbaiki_sebagian($masalah);
        $this->session->unset_userdata(['db_error', 'message', 'message_query', 'heading', 'message_exception']);

        redirect('/');
    }

    // Login khusus untuk periksa
    public function login()
    {
        if (Auth::guard($this->guard)->check()) {
            redirect('periksa');
        }

        return view('admin.auth.login', [
            'header'      => $this->header,
            'form_action' => site_url('periksa/auth'),
            'logo_bsre'   => default_file(LOGO_BSRE, false),
            'latar_login' => $this->latar_login,
        ]);
    }

    // Login khusus untuk periksa
    public function auth(): void
    {
        // Check demo mode
        $isDemoMode      = config_item('demo_mode');
        $demoUser        = config_item('demo_user');
        $requestUsername = request('username');
        $requestPassword = request('password');

        if ($isDemoMode && $requestUsername == $demoUser['username'] && $requestPassword == $demoUser['password']) {
            $this->validated(request(), $this->rules());

            // Log in as the first admin user
            $user = User::superAdmin()->first();
            Auth::guard($this->guard)->login($user);
        } else {
            $this->authenticate(['active' => 1, 'id_grup' => UserGrup::getGrupId(UserGrup::ADMINISTRATOR)]);
        }

        $this->session->sess_regenerate();

        redirect('periksa');
    }

    protected function rules()
    {
        $captcha = [];

        if ($this->setting->google_recaptcha) {
            $captcha = [
                'g-recaptcha-response' => 'required|captcha',
            ];
        }

        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            ...$captcha,
        ];
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey()
    {
        return Str::transliterate(Str::lower(request('username')) . '|' . request()->ip());
    }
}
