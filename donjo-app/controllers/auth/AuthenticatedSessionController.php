<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\Auth\Traits\LoginRequest;

class AuthenticatedSessionController extends MY_Controller
{
    use LoginRequest;

    /**
     * Attempt to get the guard.
     */
    protected $guard = 'admin';

    public function __construct()
    {
        parent::__construct();

        $this->latar_login = default_file(LATAR_LOGIN . $this->setting->latar_login, DEFAULT_LATAR_SITEMAN);
        $this->header      = collect(identitas())->toArray();
    }

    /**
     * Display the login view.
     */
    public function create()
    {
        // Kalau sehabis periksa data, paksa harus login lagi
        if ($this->session->userdata('periksa_data') == 1) {
            return $this->destroy();
        }

        if (Auth::guard($this->guard)->check()) {
            redirect('main');
        }

        return view('admin.auth.login', [
            'header'      => $this->header,
            'form_action' => site_url('siteman/auth'),
            'logo_bsre'   => default_file(LOGO_BSRE, false),
            'latar_login' => $this->latar_login,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store()
    {
        // Check demo mode
        $isDemoMode      = config_item('demo_mode');
        $demoUser        = config_item('demo_user');
        $requestUsername = request('username');
        $requestPassword = request('password');

        if ($isDemoMode && $requestUsername == $demoUser['username'] && $requestPassword == $demoUser['password']) {
            // Log in as the first admin user
            $user = User::superAdmin()->first();
            Auth::guard($this->guard)->login($user);
        } else {
            $this->authenticate(['active' => 1]);
        }

        $this->session->sess_regenerate();

        // Validate password conditions
        if (!$this->syaratSandi($requestPassword) && !($isDemoMode || ENVIRONMENT === 'development')) {
            // Password doesn't meet the criteria except in demo mode or development environment
            $this->session->force_change_password = true;
            return redirect('pengguna#sandi');
        }

        // Kalau sehabis periksa data, paksa harus login lagi
        if ($this->session->userdata('periksa_data') == 1) {
            return $this->destroy();
        }

        return redirect($this->session->intended ?? 'main');
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy()
    {
        Auth::guard($this->guard)->logout();

        $this->session->sess_destroy();

        return redirect('siteman');
    }

    //Harus 8 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil dan satu karakter khusus
    protected function syaratSandi($password)
    {
        return (bool) (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/', $password));
    }

    protected function rules()
    {
        $captcha = [];

        if ($this->setting->google_recaptcha) {
            $captcha = [
                'g-recaptcha-response' => 'required|captcha'
            ];
        }
    
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            ...$captcha
        ];
    }
}
