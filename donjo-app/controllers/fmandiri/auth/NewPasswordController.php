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

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class NewPasswordController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->latar_login = default_file(LATAR_LOGIN . setting('latar_login'), DEFAULT_LATAR_SITEMAN);
        $this->header      = collect(identitas())->toArray();
    }

    /**
     * Display the password reset view.
     *
     * @param mixed $token
     */
    public function create($token)
    {
        $request = request();

        return view('layanan_mandiri.auth.reset-password', [
            'header'             => $this->header,
            'latar_login'        => $this->latar_login,
            'logo_bsre'          => default_file(LOGO_BSRE, false),
            $this->via($request) => $request->{$this->via($request)},
            'token'              => $token,
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $request = request();

        $this->validated($request, [
            'token'              => ['required'],
            $this->via($request) => ['required'],
            'pin'                => ['required', 'digits:6', 'regex:/^\d{6}$/', 'confirmed'],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::broker('pendudukMandiri')->reset(
            $request->only($this->via($request), 'token'),
            static function ($user) use ($request) {
                $user->forceFill([
                    'pin'            => Hash::driver('md5')->make($request->pin),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        set_session('notif', __($status));

        return $status == Password::PASSWORD_RESET
            ? redirect('layanan-mandiri/masuk')
            : redirect("layanan-mandiri/reset-password/{$request->token}?{$this->via($request)}={$request->{$this->via($request)}}");
    }

    protected function via(Request $reques)
    {
        return $reques->telegram ? 'telegram' : 'email';
    }
}
