<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use App\Repositories\SettingAplikasiRepository;

class SettingService
{
    public function __construct(
        protected SettingAplikasiRepository $settings,
    ) {}

    public function all(): object
    {
        return $this->settings->resolveSetting();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->settings->resolveSetting()->{$key} ?? $default;
    }

    public function raw(): \Illuminate\Support\Collection
    {
        return $this->settings->resolvedListSetting();
    }

    public function update(string $key, mixed $value): bool
    {
        return $this->settings->updateWithKey($key, $value);
    }

    public function updateBatch(array $data): bool
    {
        $ok = true;

        foreach ($data as $key => $value) {
            $ok = $this->update($key, $value) && $ok;
        }

        return $ok;
    }

    public static function sensitiveKeys(): array
    {
        return SettingAplikasi::$sensitiveKeys;
    }
}
