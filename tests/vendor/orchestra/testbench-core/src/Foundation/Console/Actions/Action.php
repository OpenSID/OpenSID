<?php

namespace Orchestra\Testbench\Foundation\Console\Actions;

use function Orchestra\Testbench\transform_realpath_to_relative;

/**
 * @api
 */
abstract class Action
{
    /**
     * Normalise file location.
     *
     * @param  string  $path
     * @return string
     *
     * @deprecated
     *
     * @codeCoverageIgnore
     */
    protected function pathLocation(string $path): string
    {
        return transform_realpath_to_relative(
            $path, property_exists($this, 'workingPath') ? $this->workingPath : null
        );
    }
}
