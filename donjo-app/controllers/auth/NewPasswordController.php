<?php

use Illuminate\Auth\Events\PasswordReset;
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

        return view('admin.auth.reset-password', [
            'header'      => $this->header,
            'latar_login' => $this->latar_login,
            'logo_bsre'   => default_file(LOGO_BSRE, false),
            'email'       => $request->email,
            'token'       => $token,
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
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
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
            ? redirect('siteman')
            : redirect("siteman/reset_kata_sandi/{$request->token}?email={$request->email}");
    }
}
