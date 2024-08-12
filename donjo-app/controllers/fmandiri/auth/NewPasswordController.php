<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->latar_login = default_file(LATAR_LOGIN . $this->setting->latar_login, DEFAULT_LATAR_SITEMAN);
        $this->header      = collect(identitas())->toArray();
    }

    /**
     * Display the password reset view.
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
     * @throws \Illuminate\Validation\ValidationException
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
            function ($user) use ($request) {
                $user->forceFill([
                    'pin' => Hash::driver('md5')->make($request->pin),
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
