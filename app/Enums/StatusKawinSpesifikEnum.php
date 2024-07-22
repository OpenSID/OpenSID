<?php

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class StatusKawinSpesifikEnum extends BaseEnum
{
    public const BELUM_KAWIN = 1;
    public const KAWIN_TERCATAT = 2;
    public const KAWIN_BELUM_TERCATAT = 21;
    public const CERAIHIDUP = 3;
    public const CERAIMATI  = 4;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::BELUM_KAWIN => 'BELUM KAWIN',
            self::KAWIN_TERCATAT => 'KAWIN TERCATAT',
            self::KAWIN_BELUM_TERCATAT => 'KAWIN BELUM TERCATAT',
            self::CERAIHIDUP => 'CERAI HIDUP',
            self::CERAIMATI  => 'CERAI MATI',
        ];
    }
}
