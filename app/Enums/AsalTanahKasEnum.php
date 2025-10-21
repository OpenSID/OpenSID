<?php

namespace App\Enums;

enum AsalTanahKasEnum: int
{
    case APB_DESA                   = 1;
    case PEROLEHAN_LAINNYA_YANG_SAH = 2;
    case KEKAYAAN_ASLI_DESA         = 3;

    public function label(): string
    {
        return match ($this) {
            self::APB_DESA                   => 'APB Desa',
            self::PEROLEHAN_LAINNYA_YANG_SAH => 'Perolehan Lainnya yang Sah',
            self::KEKAYAAN_ASLI_DESA         => 'Kekayaan Asli Desa',
        };
    }

    public static function labels(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}