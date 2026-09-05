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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\User;
use App\Rules\CaptchaRule;
use App\Rules\SecretCodeRule;
use App\Services\Auth\Traits\LoginRequest;
use App\Services\OtpService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends MY_Controller
{
    use LoginRequest;

    protected $guard = 'admin';
    protected $otpService;

    public function __construct()
    {
        parent::__construct();

        $this->latar_login = default_file(LATAR_LOGIN . setting('latar_login'), DEFAULT_LATAR_SITEMAN);
        $this->header      = collect(identitas())->toArray();

        $this->otpService = new OtpService();

        view()->share('list_setting', $this->list_setting);
    }

    public function create()
    {
        $this->handleCaptchaSession();

        if (auth('admin_periksa')->check()) {
            auth('admin')->logout();
            auth('admin_periksa')->logout();
        }
        if (Auth::guard($this->guard)->check()) {
            redirect('main');
        }

        $this->session->unset_userdata('otp_activation');
        $this->session->unset_userdata('otp_login');

        return view('admin.auth.login', [
            'header'      => $this->header,
            'form_action' => site_url('siteman/auth'),
            'logo_bsre'   => default_file(LOGO_BSRE, false),
            'latar_login' => $this->latar_login,
        ]);
    }

    public function form_login_otp()
    {
        if (! setting('login_otp')) {
            $this->session->unset_userdata('otp_login');
            redirect_with('notif', 'Login dengan OTP tidak aktif.', 'siteman');
        }

        if (auth('admin_periksa')->check()) {
            auth('admin')->logout();
            auth('admin_periksa')->logout();
        }
        if (Auth::guard($this->guard)->check()) {
            redirect('main');
        }

        // Hapus localStorage untuk timer OTP login setiap kali halaman permintaan OTP dimuat.
        $this->session->set_flashdata('clear_otp_timer', true);

        return view('admin.pengaturan.otp.login', [
            'header'      => $this->header,
            'form_action' => ci_route('siteman.otp.request_login'),
            'logo_bsre'   => default_file(LOGO_BSRE, false),
            'latar_login' => $this->latar_login,
        ]);
    }

    /**
     * Request OTP for login
     */
    public function request_login()
    {
        if (! setting('login_otp')) {
            $this->session->unset_userdata('otp_login');
            redirect_with('notif', 'Login dengan OTP tidak aktif.', 'siteman');
        }

        $request = $this->input->post();

        $validator = Validator::make($request, [
            'identifier' => 'required|string',
        ]);

        $this->validated(request(), $validator->getRules());

        // Find user by email or username
        $user = User::where('email', $request['identifier'])
            ->orWhere('username', $request['identifier'])
            ->where('active', 1)
            ->first();

        if (! $user) {
            redirect_with('notif', 'Pengguna tidak ditemukan atau tidak aktif.', ci_route('siteman.otp.form_login_otp'));
        }

        if (! $user->otp_enabled) {
            redirect_with('notif', 'OTP belum di aktivasi di halaman profile > Pengaturan Aktivasi OTP. Silakan aktivasi terlebih dahulu atau login dengan password.', ci_route('siteman.otp.form_login_otp'));
        }

        // Periksa apakah saluran notifikasi yang digunakan pengguna aktif
        if ($user->otp_channel === 'email' && ! setting('email_notifikasi')) {
            redirect_with('notif', 'Notifikasi email tidak aktif. Silakan hubungi Admin atau login dengan password', 'siteman');
        }
        if ($user->otp_channel === 'telegram' && ! setting('telegram_notifikasi')) {
            redirect_with('notif', 'Notifikasi Telegram tidak aktif. Silakan hubungi Admin atau login dengan password', 'siteman');
        }

        // Generate and send OTP
        $result = $this->otpService->generateAndSend(
            $user,
            $user->otp_channel,
            $user->otp_identifier,
            'login'
        );

        if (! $result['sent']) {
            redirect_with('notif', 'Gagal mengirim kode OTP. Silakan coba lagi.', ci_route('siteman.otp.form_login_otp'));
        }

        // Store login attempt in session
        $this->session->set_userdata([
            'otp_login' => [
                'user_id' => $user->id,
                'sent_at' => Carbon::now()->timestamp,
            ],
        ]);

        redirect_with('success', 'Kode OTP telah dikirim ke ' . ($user->otp_channel === 'email' ? 'email' : 'Telegram') . ' Anda.', ci_route('siteman.otp.verify_login'));
    }

    public function verify_login()
    {
        if (! setting('login_otp')) {
            $this->session->unset_userdata('otp_login');
            redirect_with('notif', 'Login dengan OTP tidak aktif.', 'siteman');
        }

        if (! $this->session->userdata('otp_login')) {
            redirect_with('notif', 'Silakan minta kode OTP terlebih dahulu.', ci_route('siteman.otp.form_login_otp'));
        }

        return view('admin.pengaturan.otp.verify-login', [
            'header'      => $this->header,
            'form_action' => ci_route('siteman.otp.verify_login'),
            'logo_bsre'   => default_file(LOGO_BSRE, false),
            'latar_login' => $this->latar_login,
        ]);
    }

    /**
     * Verify OTP and login
     */
    public function login_otp()
    {
        if (! setting('login_otp')) {
            $this->session->unset_userdata('otp_login');
            redirect_with('notif', 'Login dengan OTP tidak aktif.', 'siteman');
        }

        $request = $this->input->post();

        $validator = Validator::make($request, [
            'otp' => 'required|numeric|digits:6',
        ]);

        $this->validated(request(), $validator->getRules());

        if (! $this->session->userdata('otp_login')) {
            redirect_with('success', 'Sesi login tidak ditemukan. Silakan mulai lagi.', ci_route('siteman.otp.form_login_otp'));
        }

        $loginData = $this->session->userdata('otp_login');
        $user      = User::find($loginData['user_id']);

        if (! $user) {
            $this->session->unset_userdata('otp_login');
            redirect_with('notif', 'Pengguna tidak ditemukan.', ci_route('siteman.otp.form_login_otp'));
        }

        // Verify OTP
        $result = $this->otpService->verify($user, $request['otp'], 'login');

        if (! $result['success']) {
            // Jika gagal karena maksimal percobaan, hapus sesi aktivasi
            if (isset($result['reason']) && $result['reason'] === 'max_attempts') {
                $this->session->unset_userdata('otp_login');
                redirect_with('notif', $result['message'], 'siteman');
            }
            redirect_with('notif', $result['message'], ci_route('siteman.otp.verify_login'));
        }

        // Simpan URL tujuan sebelum login, karena listener akan menghapus session 'intended'
        $redirectUrl = $this->session->intended ?? 'beranda';

        // Login user
        Auth::guard($this->guard)->login($user);

        // Clear session
        $this->session->unset_userdata('otp_login');

        return redirect($redirectUrl);
    }

    /**
     * Resend OTP
     */
    public function resend_otp()
    {
        if (! setting('login_otp')) {
            return json(['success' => false, 'message' => 'Login dengan OTP tidak aktif.'], 400);
        }

        $request = $this->input->post();

        $purpose = $request['purpose'] ?? 'login'; // default to 'login'

        $result = $this->otpService->resend($purpose, $this->session);

        if ($result['success']) {
            return json(['success' => true, 'message' => $result['message']]);
        }

        return json(['success' => false, 'message' => $result['message']], 400);
    }

    public function store()
    {
        $isDemoMode      = config_item('demo_mode');
        $demoUser        = config_item('demo_user');
        $requestUsername = request('username');
        $requestPassword = request('password');

        if ($isDemoMode && $requestUsername == $demoUser['username'] && $requestPassword == $demoUser['password']) {
            $this->validated(request(), $this->rules());

            $user = User::superAdmin()->first();
            Auth::guard($this->guard)->login($user);
        } else {
            $this->authenticate(['active' => 1]);
        }

        $this->session->sess_regenerate();

        $user = Auth::guard($this->guard)->user();

        if ($user->two_factor_enabled) {
            return $this->startTwoFactorAuthProcess($user);
        }

        if (! $this->syaratSandi($requestPassword) && ! ($isDemoMode || ENVIRONMENT === 'development')) {
            $this->session->force_change_password = true;

            return redirect('pengguna#sandi');
        }

        return redirect($this->session->intended ?? 'main');
    }

    public function destroy()
    {
        Auth::guard($this->guard)->logout();

        $this->session->sess_destroy();

        return redirect('siteman');
    }

    public function matikanCaptcha()
    {
        $this->session->set_userdata('recaptcha', true);

        return json('Captcha dinonaktifkan');
    }

    protected function syaratSandi($password)
    {
        return (bool) (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/', $password));
    }

    protected function rules()
    {
        $secretCode = request('secret_code');
        $rules      = [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];

        if (app()->isProduction() && $this->shouldUseCaptcha()) {
            $rules['g-recaptcha-response'] = ['required', 'captcha'];
            $this->session->unset_userdata('recaptcha');
        } elseif (app()->isProduction()) {
            $rules['captcha_code'] = ['required', new CaptchaRule()];
        }

        if ($secretCode) {
            $username             = request('username');
            $passwordDatabase     = User::where('username', $username)->first()->password ?? '';
            $rules['secret_code'] = ['required', 'string', 'min:10', new SecretCodeRule($passwordDatabase)];

            // CAPTCHA tidak dibutuhkan jika pakai secret code
            unset($rules['g-recaptcha-response'], $rules['captcha_code']);
        }

        return $rules;
    }

    protected function throttleKey()
    {
        return Str::transliterate(Str::lower(request('username')) . '|' . request()->ip());
    }

    private function handleCaptchaSession()
    {
        if ($this->session->userdata('recaptcha')) {
            setting('google_recaptcha', 0);
        }
    }

    private function shouldUseCaptcha()
    {
        return setting('google_recaptcha') && ! $this->session->userdata('recaptcha');
    }

    private function startTwoFactorAuthProcess(User $user)
    {
        try {
            Auth::guard($this->guard)->logout();
            $user->sendOneTimePassword();
            $this->session->set_userdata('two-factor:user', $user);

            return redirect_with('notif', 'Kode autentikasi dua faktor telah dikirim ke email Anda. Silakan masukkan kode tersebut untuk melanjutkan.', 'siteman/two-factor-auth');
        } catch (Exception $e) {
            logger()->error($e);

            return redirect_with('notif', 'Gagal mengirim kode autentikasi dua faktor. Silakan coba lagi atau hubungi administrator.', 'siteman');
        }
    }
}
