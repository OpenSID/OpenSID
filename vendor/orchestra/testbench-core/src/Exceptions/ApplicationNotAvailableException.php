<?php

namespace Orchestra\Testbench\Exceptions;

use RuntimeException;

/**
 * @internal
 */
class ApplicationNotAvailableException extends RuntimeException
{
    /**
     * Make new RuntimeException when application is not available.
     *
     * @param  string|null  $caller
     * @return static
     */
    public static function make(?string $caller)
    {
        return new static(\sprintf('Application is not available to run [%s]', $caller ?? 'N/A'));
    }
}
