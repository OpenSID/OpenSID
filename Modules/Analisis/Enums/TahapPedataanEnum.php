<?php

namespace Modules\Analisis\Enums;

use App\Enums\BaseEnum;

class TahapPedataanEnum extends BaseEnum
{
    public const BELUM_ENTRI   = 1;
    public const SEDANG_ENTRI  = 2;
    public const SELESAI_ENTRI = 3;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::BELUM_ENTRI => 'Belum Entri / Pedataan',
            self::SEDANG_ENTRI => 'Sedang Dalam Pendataan',
            self::SELESAI_ENTRI => 'Selesai Entri / Pedataan',
        ];
    }
}
