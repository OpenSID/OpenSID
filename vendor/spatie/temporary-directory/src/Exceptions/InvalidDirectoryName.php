<?php

namespace Spatie\TemporaryDirectory\Exceptions;

class InvalidDirectoryName extends \Exception
{
    public static function create(string $directoryName): static
    {
        return new static("The directory name `{$directoryName}` contains invalid characters.");
    }
}
