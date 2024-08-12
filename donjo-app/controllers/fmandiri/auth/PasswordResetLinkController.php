<?php

use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['mandiri_model', 'theme_model']);

        if (!$this->setting->tampilkan_pendaftaran) {
            show_404();
        }

        if (auth('penduduk')->check()) {
            redirect('layanan-mandiri/beranda');
        }
    }

    /**
     * Display the password reset link request view.
     */
    public function create()
    {
        return view('layanan_mandiri.auth.forgot-password', [
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

        $this->validated($request, [
            'nik' => ['required', 'digits:16', 'regex:/^\d{16}$/'],
            'via' => ['required', 'in:email,telegram'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::broker('pendudukMandiri')->sendResetLink([
            'nik'   => $request->nik,
            'query' => fn($q) => $q->when(
                $request->via === 'telegram',
                fn($q) => $q->whereRelation('penduduk', 'telegram_tgl_verifikasi', '!=', null),
                fn($q) => $q->whereRelation('penduduk', 'email_tgl_verifikasi', '!=', null)
            ),
        ], $request->via === 'telegram' ? fn ($user, $token) => $user->sendPasswordResetNotification($token, 'telegram') : null);

        set_session('notif', __($status));

        return redirect('layanan-mandiri/lupa-pin');
    }
}
