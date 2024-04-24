<?php

namespace App\Traits;

use App\Observers\ClearCacheObserver;

defined('BASEPATH') || exit('No direct script access allowed');

trait ShortcutCache
{
    public static function bootShortcutCache()
    {
        static::observe(ClearCacheObserver::class);
    }
}
