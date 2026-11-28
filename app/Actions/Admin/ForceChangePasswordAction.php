<?php

namespace App\Actions\Admin;

use App\Repositories\SettingAplikasiRepository;
use App\Services\DesaIdentityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Session\Store as SessionStore;

class ForceChangePasswordAction
{
    public function __construct(
        protected SessionStore $session,
        protected DesaIdentityService $desa,
        protected SettingAplikasiRepository $settings,
    ) {}

    public function execute(string $currentController, bool $validasiPassed): ?RedirectResponse
    {
        $force = $this->session->get('force_change_password');

        if (! $force) {
            return null;
        }

        if (! $validasiPassed) {
            return null;
        }

        if (! $this->desa->hasKodeDesa()) {
            return null;
        }

        if ($currentController === 'pengguna') {
            return null;
        }

        return redirect('pengguna#sandi');
    }
}
