<?php

namespace App\Repositories;

use App\Enums\StatusEnum;
use Illuminate\Support\Facades\DB;

class AnjunganRepository
{
    public function findByMacAddress(string $mac, int $configId): ?object
    {
        return DB::table('anjungan')
            ->where('mac_address', $mac)
            ->where('status', StatusEnum::YA)
            ->where('config_id', $configId)
            ->orderBy('tipe')
            ->first();
    }

    public function findByUuid(string $uuid, int $configId): ?object
    {
        return DB::table('anjungan')
            ->where('uuid', $uuid)
            ->where('status', StatusEnum::YA)
            ->where('config_id', $configId)
            ->orderBy('tipe')
            ->first();
    }

    public function findByMacOrUuid(?string $mac, ?string $uuid, int $configId): ?object
    {
        return DB::table('anjungan')
            ->where(fn ($q) => $q
                ->when($mac, fn ($q) => $q->orWhere('mac_address', $mac))
                ->when($uuid, fn ($q) => $q->orWhere('uuid', $uuid))
            )
            ->where('status', StatusEnum::YA)
            ->where('config_id', $configId)
            ->orderBy('tipe')
            ->first();
    }
}
