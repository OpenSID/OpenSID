<?php

namespace App\Enums;

enum PeruntukanTanahKasEnum: int
{
    case SEWA                                      = 1;
    case PINJAM_PAKAI                              = 2;
    case KERJASAMA_PEMANFAATAN                     = 3;
    case BANGUN_GUNA_SERAH_ATAU_BANGUN_SERAH_GUNA = 4;

    public function label(): string
    {
        return match ($this) {
            self::SEWA                                     => 'Sewa',
            self::PINJAM_PAKAI                             => 'Pinjam Pakai',
            self::KERJASAMA_PEMANFAATAN                    => 'Kerjasama Pemanfaatan',
            self::BANGUN_GUNA_SERAH_ATAU_BANGUN_SERAH_GUNA => 'Bangun Guna Serah atau Bangun Serah Guna',
        };
    }

    public static function labels(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}