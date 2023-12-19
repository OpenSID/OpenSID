<?php

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class TampilanArtikelEnum extends BaseEnum
{
    public const CONTENT_SIDEBAR_RIGHT = 1;
    public const CONTENT_SIDEBAR_LEFT = 2;
    public const CONTENT_FULLWIDHT = 3;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::CONTENT_SIDEBAR_RIGHT => 'Content + Sidebar Right',
            self::CONTENT_SIDEBAR_LEFT => 'Content + Sidebar Left',
            self::CONTENT_FULLWIDHT => 'Content Full Width',
        ];
    }
}
