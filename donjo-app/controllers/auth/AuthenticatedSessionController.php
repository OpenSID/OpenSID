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

use App\Models\User;
use App\Services\Auth\Traits\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        if (auth('admin_periksa')->check()) {
            auth('admin')->logout();
            auth('admin_periksa')->logout();
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
            $this->validated(request(), $this->rules());

            // Log in as the first admin user
            $user = User::superAdmin()->first();
            Auth::guard($this->guard)->login($user);
        } else {
            $this->authenticate(['active' => 1]);
        }

        $this->session->sess_regenerate();

        // Validate password conditions
        if (! $this->syaratSandi($requestPassword) && ! ($isDemoMode || ENVIRONMENT === 'development')) {
            // Password doesn't meet the criteria except in demo mode or development environment
            $this->session->force_change_password = true;

            return redirect('pengguna#sandi');
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
