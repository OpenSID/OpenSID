<?php

namespace Orchestra\Testbench\Foundation\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * @codeCoverageIgnore
 */
abstract class Kernel extends HttpKernel
{
    /**
     * Get the bootstrap classes for the application.
     *
     * @return array
     */
    #[\Override]
    protected function bootstrappers()
    {
        return [];
    }
}
