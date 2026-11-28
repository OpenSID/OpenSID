<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Session\Middleware\AuthenticateSession as BaseAuthenticateSession;

class AuthenticateSession extends BaseAuthenticateSession
{
    /**
     * {@inheritDoc}
     */
    protected function logout($request): void
    {
        match ($this->auth->getDefaultDriver()) {
            'admin'    => $this->handleAdminPasswordChange($request),
            'penduduk' => $this->handlePendudukPasswordChange($request),
            default    => parent::logout($request),
        };
    }

    /**
     * Tandai session admin untuk memaksa ganti password.
     */
    private function handleAdminPasswordChange(Request $request): void
    {
        $request->session()->regenerate();

        $request->session()->put('change_password', true);
        $request->session()->put('force_change_password', false);
    }

    /**
     * Tandai session penduduk untuk memaksa login ulang akibat perubahan PIN.
     */
    private function handlePendudukPasswordChange(Request $request): void
    {
        $request->session()->regenerate();

        $request->session()->put('force_change_password_penduduk', [
            'pesan' => 'Pin Anda telah berubah. Silakan login kembali.',
            'aksi'  => site_url('layanan-mandiri/keluar'),
        ]);
    }
    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        if (str_contains($request->getRequestUri(), 'koneksi_database') || session()->has('db_error')) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}