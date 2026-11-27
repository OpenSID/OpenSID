<?php

namespace App\Http\Middleware;

use App\Actions\Anjungan\ResolveCurrentAction;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareAnjungan
{
    public function __construct(
        protected ResolveCurrentAction $resolveAnjungan,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        View::share('cek_anjungan', $this->resolveAnjungan->execute(
            $request->session()->get('mac_address'),
            $request->session()->get('anjungan_uuid'),
        ));

        return $next($request);
    }
}
