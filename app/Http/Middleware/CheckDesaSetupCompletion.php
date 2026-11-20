<?php

namespace App\Http\Middleware;

use App\Actions\Admin\ForceChangePasswordAction;
use App\Actions\Admin\HandleSetupWarningsAction;
use App\Actions\Admin\ValidateDesaIdentityAction;
use App\Services\Penjaga\PenjagaPermintaan;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDesaSetupCompletion
{
    public function __construct(
        protected ValidateDesaIdentityAction $identity,
        protected PenjagaPermintaan $penjaga,
        protected ForceChangePasswordAction $forcePassword,
        protected HandleSetupWarningsAction $warnings,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $currentController = explode('.', $request->route()?->getName() ?? '')[0];

        if ($redirect = $this->identity->execute($currentController)) {
            return $redirect;
        }

        if (! $this->penjaga->izinkanPermintaan()) {
            return redirect('peringatan');
        }
        $validasiPassed = true;

        if ($redirect = $this->forcePassword->execute($currentController, $validasiPassed)) {
            return $redirect;
        }

        if ($request->session()->get('change_password') && $currentController !== 'pengguna') {
            return redirect('pengguna');
        }

        $isProduction = app()->environment('production') && ! config('opensid.demo_mode');
        $adminUser    = auth('admin')->user();

        $this->warnings->execute($currentController, $isProduction, $adminUser);

        return $next($request);
    }
}
