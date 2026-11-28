<?php

namespace App\Actions\Anjungan;

use App\Services\AnjunganService;

class ResolveCurrentAction
{
    public function __construct(
        protected AnjunganService $anjungan,
    ) {}

    public function execute(?string $macAddress, ?string $uuid): array
    {
        $restoreFromCookie = false;

        if (! $uuid && isset($_COOKIE['anjungan_uuid'])) {
            if ($this->anjungan->isValidUuid($_COOKIE['anjungan_uuid'])) {
                $uuid              = $_COOKIE['anjungan_uuid'];
                $restoreFromCookie = true;
            }
        }

        if (! $macAddress && ! $uuid) {
            return [];
        }

        $result = $this->anjungan->resolve($macAddress, $uuid);

        if ($result && $restoreFromCookie && session()->isStarted()) {
            session()->put('anjungan_uuid', $uuid);
        }

        return $result;
    }
}
