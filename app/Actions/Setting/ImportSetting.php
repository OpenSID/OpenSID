<?php

namespace App\Actions\Setting;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;

class ImportSetting
{
    /**
     * Import setting aplikasi dari file JSON
     * (update semua kolom kecuali value).
     */
    public function handle(?string $path = null): bool
    {
        $path ??= storage_path('app/template/impor/default_setting_aplikasi.json');

        if (! file_exists($path)) {
            return false;
        }

        $data = json_decode(file_get_contents($path), true);

        if (! is_array($data) || empty($data)) {
            return false;
        }

        DB::transaction(function () use ($data) {
            foreach ($data as $item) {
                unset($item['id'], $item['config_id']);

                $key = $item['key'] ?? null;
                if (! $key) {
                    continue;
                }

                $existing = SettingAplikasi::where('key', $key)->first();

                if ($existing) {
                    unset($item['value']);

                    $existing->update($item);
                } else {
                    SettingAplikasi::create($item);
                }
            }
        });

        cache()->flush();

        return true;
    }
}
