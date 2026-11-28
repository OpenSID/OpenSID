<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;

class NamaWilayahCast implements CastsAttributes
{
    /**
     * {@inheritDoc}
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return Str::FormatNamaWilayah($value);
    }

    /**
     * {@inheritDoc}
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
