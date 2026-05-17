<?php

namespace Orchestra\Canvas\Core\Contracts;

interface GeneratesCode
{
    /**
     * Handle generating code.
     */
    public function generatingCode(string $stub, string $className): string;

    /**
     * Code already exists.
     */
    public function codeAlreadyExists(string $className, string $path): bool;

    /**
     * Code successfully generated.
     */
    public function codeHasBeenGenerated(string $className, string $path): bool;

    /**
     * Run after code successfully generated.
     */
    public function afterCodeHasBeenGenerated(string $className, string $path): void;
}
