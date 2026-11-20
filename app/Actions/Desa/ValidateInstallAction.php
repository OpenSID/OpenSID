<?php

namespace App\Actions\Desa;

use App\Services\DesaIdentityService;

class ValidateInstallAction
{
    public function __construct(
        protected DesaIdentityService $desa,
    ) {}

    public function execute(): ?\Illuminate\Http\RedirectResponse
    {
        if ($this->desa->isInstalled()) {
            return null;
        }

        return redirect('install');
    }
}
