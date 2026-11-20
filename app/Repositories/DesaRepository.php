<?php

namespace App\Repositories;

use App\Models\Config;

class DesaRepository
{
    public function appKey(): ?Config
    {
        return Config::count() > 1
            ? Config::appKey()->first()
            : Config::first();
    }

    public function count(): int
    {
        return Config::count();
    }

    public function hasKodeDesa(): bool
    {
        return ! empty($this->appKey()->kode_desa);
    }

    public function identitas(): ?Config
    {
        static $desa;

        return $desa ??= Config::appKey()->first();
    }
}
