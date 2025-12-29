<?php

use OpenSID\MiddlewareInterface;

class AuthenticateSession implements MiddlewareInterface
{
    /**
     * CodeIgniter instance
     */
    protected $ci;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ci = &get_instance();
    }

    /**
     * {@inheritdoc}
     */
    public function run($args)
    {
        $request = request();

        // Jika user tidak login, lanjutkan
        if (! $request->user()) {
            return;
        }

        // Simpan password hash di session jika belum ada
        $authDriver = $this->getAuthDriver();
        if (! $this->ci->session->has_userdata("password_hash_{$authDriver}")) {
            $this->storePasswordHashInSession($request);
        }

        // Validasi password hash dari session
        if ($this->ci->session->userdata("password_hash_{$authDriver}") !== $request->user()->getAuthPassword()) {
            $this->logout();
        }
    }

    /**
     * Simpan password hash user di session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function storePasswordHashInSession($request)
    {
        if (! $request->user()) {
            return;
        }

        $authDriver = $this->getAuthDriver();
        $this->ci->session->set_userdata([
            "password_hash_{$authDriver}" => $request->user()->getAuthPassword(),
        ]);
    }

    /**
     * Logout user dari aplikasi.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function logout()
    {
        $this->ci->session->change_password       = true;
        $this->ci->session->force_change_password = false;
    }

    /**
     * Dapatkan guard instance.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard()
    {
        return auth();
    }

    /**
     * Dapatkan auth driver default.
     *
     * @return string
     */
    protected function getAuthDriver()
    {
        return config('auth.defaults.guard', 'admin');
    }
}
