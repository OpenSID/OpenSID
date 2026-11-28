<?php

namespace App\Services;

use App\Repositories\DesaRepository;

class DesaIdentityService
{
    public function __construct(
        protected DesaRepository $desa,
    ) {}

    public function identitas(): ?object
    {
        return $this->desa->identitas();
    }

    public function isInstalled(): bool
    {
        return file_exists(base_path('desa'));
    }

    public function validAppKey(): bool
    {
        $appKey = get_app_key();
        $db     = $this->desa->appKey();

        if ($this->desa->count() === 0) {
            return false;
        }

        if (! empty($db->app_key) && $appKey !== $db->app_key) {
            return false;
        }

        return true;
    }

    public function hasKodeDesa(): bool
    {
        return $this->desa->hasKodeDesa();
    }

    public function isMultiDesa(): bool
    {
        return $this->desa->count() > 1;
    }
}
