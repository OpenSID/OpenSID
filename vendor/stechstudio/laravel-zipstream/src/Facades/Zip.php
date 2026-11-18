<?php

namespace STS\ZipStream\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \STS\ZipStream\Builder
 */
class Zip extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'zipstream.builder';
    }
}
