<?php

namespace Orchestra\Testbench\Foundation\Console;

class Signals extends \Illuminate\Console\Signals
{
    /**
     * Execute the given callback if "signals" should be used and are available.
     *
     * @param  callable  $callback
     * @param  callable|null  $default
     * @return void
     *
     * @codeCoverageIgnore
     */
    #[\Override]
    public static function whenAvailable($callback, $default = null)
    {
        if (\is_null($resolver = static::$availabilityResolver)) {
            return;
        }

        if ($resolver()) {
            $callback();
        } elseif (\is_callable($default)) {
            $default();
        }
    }
}
