<?php

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class SumberDanaEnum extends BaseEnum
{
    public const PAD                = 1;
    public const DANA_DESA          = 2;
    public const PAJAK_DAERAH       = 3;
    public const ALOKASI_DANA_DESA  = 4;
    public const BANTUAN_PROVINSI   = 5;
    public const BANTUAN_KAB_KOTA   = 6;
    public const PENDAPATAN_LAIN    = 7;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::PAD               => 'Pendapatan Asli Desa (PAD)',
            self::DANA_DESA         => 'Pendapatan Transfer (Dana Desa)',
            self::PAJAK_DAERAH      => 'Pendapatan Transfer (Bagian dari Hasil Pajak dan Retribusi Daerah Kabupaten/Kota)',
            self::ALOKASI_DANA_DESA => 'Pendapatan Transfer (Alokasi Dana Desa)',
            self::BANTUAN_PROVINSI  => 'Pendapatan Transfer (Bantuan Keuangan dari APBD Provinsi)',
            self::BANTUAN_KAB_KOTA  => 'Pendapatan Transfer (Bantuan Keuangan APBD Kabupaten/Kota)',
            self::PENDAPATAN_LAIN   => 'Pendapatan Lain',
        ];
    }
}
