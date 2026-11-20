<?php

namespace App\Http\Middleware;

use App\Actions\Desa\ValidateInstallAction;
use App\Actions\Desa\ValidateAppKeyAction;
use App\Actions\Database\ReconnectAction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDesaInstalled
{
    public function __construct(
        protected ValidateInstallAction $install,
        protected ValidateAppKeyAction $appKey,
        protected ReconnectAction $reconnect,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($redirect = $this->install->execute()) {
            return $redirect;
        }

        if ($redirect = $this->appKey->execute()) {
            return $redirect;
        }

        $dbError = $request->session()->get('db_error');
        $this->reconnect->execute($dbError, null);

        return $next($request);
    }
}
