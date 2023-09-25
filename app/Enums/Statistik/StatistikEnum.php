<?php

namespace App\Enums\Statistik;

use App\Enums\BaseEnum;

defined('BASEPATH') || exit('No direct script access allowed');

class StatistikEnum extends BaseEnum
{
    public const PENDUDUK = 'penduduk';
    public const KELUARGA = 'keluarga';


    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::PENDUDUK     => 'Penduduk',
            self::KELUARGA     => 'Keluarga / KK',
        ];
    }

    /**
     * Get all statistik
     */
    public static function allStatistik(): array
    {
        return [
            self::PENDUDUK => StatistikPendudukEnum::$data,
            self::KELUARGA => StatistikKeluargaEnum::$data,
        ];
    }

    /**
     * Get all statistik merge
     */
    public static function allStatistikMerge(): array
    {
        return collect(self::allStatistik())->collapse()->pluck('slug', 'key')->toArray();
    }

    /**
     * Get slug from key
     */
    public static function slugFromKey($key): ?string
    {
        return self::allStatistikMerge()[$key] ?? null;
    }

    /**
     * Get key form slug
     */
    public static function keyFromSlug($slug): ?string
    {
        return array_search($slug, self::allStatistikMerge());
    }

    /**
     * Get label from slug
     */
    public static function labelFromSlug($slug): ?string
    {
        $all = collect(self::allStatistik())->collapse()->pluck('label', 'slug')->toArray();

        return $all[$slug] ?? null;
    }
}
