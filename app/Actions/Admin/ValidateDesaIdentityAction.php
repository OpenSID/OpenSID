<?php

namespace App\Actions\Admin;

use App\Repositories\SettingAplikasiRepository;
use App\Services\DesaIdentityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Session\Store as SessionStore;

class ValidateDesaIdentityAction
{
    public function __construct(
        protected DesaIdentityService $desa,
        protected SettingAplikasiRepository $settings,
        protected SessionStore $session,
        protected Redirector $redirector,
    ) {}

    public function execute(string $currentController): ?RedirectResponse
    {
        if ($this->desa->hasKodeDesa()) {
            return null;
        }

        if ($currentController === 'identitas_desa') {
            return null;
        }

        $sebutanDesa = $this->settings->resolveSetting()->sebutan_desa ?? 'Desa';
        $this->session->flash('error', 'Identitas ' . ucwords($sebutanDesa) . ' masih kosong, silakan isi terlebih dahulu');

        return $this->redirector->to('identitas_desa');
    }
}
