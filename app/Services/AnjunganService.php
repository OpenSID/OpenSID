<?php

namespace App\Services;

use App\Repositories\AnjunganRepository;
use App\Repositories\DesaRepository;

class AnjunganService
{
    public function __construct(
        protected AnjunganRepository $anjungan,
        protected DesaRepository $desa,
    ) {}

    public function resolve(?string $macAddress, ?string $uuid): array
    {
        if (! $macAddress && ! $uuid) {
            return [];
        }

        $configId = $this->desa->identitas()?->id;

        if (! $configId) {
            return [];
        }

        $data = $this->anjungan->findByMacOrUuid($macAddress, $uuid, $configId);

        if (! $data) {
            return [];
        }

        $data->tipe = json_decode($data->tipe, true) ?? [];

        return (array) $data;
    }

    public function isValidUuid(string $value): bool
    {
        return (bool) preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            $value,
        );
    }

    public function restoreFromCookie(string $uuid): array
    {
        if (! $this->isValidUuid($uuid)) {
            return [];
        }

        return $this->resolve(null, $uuid);
    }
}
