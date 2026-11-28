<?php

namespace OpenSID\LaravelFilemanager\Exceptions;

use RuntimeException;

class UnsafePathException extends RuntimeException
{
    public static function forPath(string $path): self
    {
        return new self("Unsafe or invalid path: {$path}");
    }
}
