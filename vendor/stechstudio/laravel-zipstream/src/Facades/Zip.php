<?php

namespace STS\ZipStream\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void extend(string $fileClass)
 *
 * @mixin \STS\ZipStream\Builder
 */
class Zip extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'zipstream.builder';
    }
}
