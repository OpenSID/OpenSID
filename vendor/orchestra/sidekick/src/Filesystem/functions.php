<?php

namespace Orchestra\Sidekick\Filesystem;

use ReflectionClass;

if (! \function_exists('Orchestra\Sidekick\Filesystem\filename_from_classname')) {
    /**
     * Resolve filename from classname.
     *
     * @api
     *
     * @param  class-string  $className
     */
    function filename_from_classname(string $className): string|false
    {
        if (! class_exists($className, false)) {
            return false;
        }

        $classFileName = (new ReflectionClass($className))->getFileName();

        if (
            $classFileName === false
            || (! is_file($classFileName) && ! str_ends_with(strtolower($classFileName), '.php'))
        ) {
            return false;
        }

        return realpath($classFileName);
    }
}

if (! \function_exists('Orchestra\Sidekick\Filesystem\is_symlink')) {
    /**
     * Determine if the path is a symlink for both Unix and Windows environments.
     *
     * @api
     */
    function is_symlink(string $path): bool
    {
        if (\Orchestra\Sidekick\windows_os() && is_dir($path) && readlink($path) !== $path) {
            return true;
        } elseif (is_link($path)) {
            return true;
        }

        return false;
    }
}

if (! \function_exists('Orchestra\Sidekick\Filesystem\join_paths')) {
    /**
     * Join the given paths together.
     *
     * @api
     */
    function join_paths(?string $basePath, string ...$paths): string
    {
        foreach ($paths as $index => $path) {
            if (empty($path) && $path !== '0') {
                unset($paths[$index]);
            } else {
                $paths[$index] = DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
            }
        }

        return $basePath.implode('', $paths);
    }
}
