<?php

namespace App\Enums\Statistik;

use App\Enums\BaseEnum;

defined('BASEPATH') || exit('No direct script access allowed');

class StatistikKeluargaEnum extends BaseEnum
{
    public const KELAS_SOSIAL = [
        'key'   => 'kelas_sosial',
        'slug'  => 'kelas-sosial',
        'label' => 'Kelas Sosial',
        'url'   => 'statistik/kelas-sosial',
    ];

    public static $data = [
        self::KELAS_SOSIAL,
    ];

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return collect(self::$data)->pluck('label', 'slug')->toArray();
    }

    /**
     * Get slug from key
     */
    public static function slugFromKey($key): ?string
    {
        $item = collect(self::$data)->firstWhere('key', $key);
        return $item ? $item['slug'] : null;
    }

    /**
     * Get key form slug
     */
    public static function keyFromSlug($slug): ?string
    {
        $item = collect(self::$data)->firstWhere('slug', $slug);
        return $item ? $item['key'] : null;
    }
}
