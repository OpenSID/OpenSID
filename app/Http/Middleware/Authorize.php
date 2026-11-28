<?php

namespace App\Http\Middleware;

use Closure;

class Authorize extends \Illuminate\Auth\Middleware\Authorize
{
    /**
     * {@inheritDoc}
     */
    public function handle($request, Closure $next, $ability, ...$models)
    {
        if ($request->wantsJson()) {
            return parent::handle($request, $next, $ability, ...$models);
        }

        if ($this->gate->check($ability, $this->getGateArguments($request, $models))) {
            return $next($request);
        }

        $request->session()->flash('error', 'Anda tidak memiliki akses untuk halaman tersebut!');

        return redirect()->to('beranda');
    }
}
