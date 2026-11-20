<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Endpoint SSO OpenKab -> OpenSID: request lintas-situs sebelum login,
        // proteksi dilakukan lewat validasi Origin/Referer (lihat SsoLoginService).
        'admin/sso-login',
    ];
}
