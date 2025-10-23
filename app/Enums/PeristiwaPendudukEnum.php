<?php

namespace App\Enums;

enum PeristiwaPendudukEnum: int
{
    case BARU_LAHIR        = 1;
    case MATI              = 2;
    case PINDAH_KELUAR     = 3;
    case HILANG            = 4;
    case BARU_PINDAH_MASUK = 5;
    case TIDAK_TETAP_PERGI = 6;

    public function label(): string
    {
        return match ($this) {
            self::BARU_LAHIR        => 'Baru Lahir',
            self::MATI              => 'Mati',
            self::PINDAH_KELUAR     => 'Pindah Keluar',
            self::HILANG            => 'Hilang',
            self::BARU_PINDAH_MASUK => 'Baru Pindah Masuk',
            self::TIDAK_TETAP_PERGI => 'Tidak Tetap Pergi',
        };
    }

    public static function labels(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}
