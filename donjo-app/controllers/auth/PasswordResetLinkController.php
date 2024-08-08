<?php

use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->latar_login = default_file(LATAR_LOGIN . $this->setting->latar_login, DEFAULT_LATAR_SITEMAN);
        $this->header      = collect(identitas())->toArray();
    }

    /**
     * Display the password reset link request view.
     */
    public function create()
    {
        return view('admin.auth.forgot-password', [
            'header'      => $this->header,
            'logo_bsre'   => default_file(LOGO_BSRE, false),
            'latar_login' => $this->latar_login,
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $request = request();

        // Periksa isian captcha
        $captcha = new App\Libraries\Captcha();
        if (! $captcha->check($request->post('captcha_code'))) {
            set_session('notif', 'Kode captcha anda salah. Silakan ulangi lagi.');

            redirect('siteman/lupa_sandi');
        }

        $this->validated($request, [
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        set_session('notif', __($status));

        return redirect('siteman/lupa_sandi');
    }
}
