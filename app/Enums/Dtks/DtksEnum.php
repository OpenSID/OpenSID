<?php

namespace App\Enums\Dtks;

class DtksEnum
{
    const VERSION_CODE = 2;

    const REGSOS_EK2021_RT = 1;
    const REGSOS_EK2022_K  = 2;
    const VERSION_LIST = [
        self::REGSOS_EK2021_RT => 'REGSOS-EK2021.RT',
        self::REGSOS_EK2022_K  => 'REGSOSEK2022.K',
    ];

    public static final function GET_CLEAN_NAME_VERSION($code = self::VERSION_CODE)
    {
        // remove char (-) and (.)
        return strtoupper(str_replace(['-', '.'], '', self::VERSION_LIST[$code]));
    }

}
