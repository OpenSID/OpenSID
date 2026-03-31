<?php

namespace Orchestra\Testbench\Concerns;

use Orchestra\Testbench\Foundation\Application as Testbench;

use function Orchestra\Sidekick\join_paths;
use function Orchestra\Testbench\workbench_path;

trait WithLaravelBootstrapFile
{
    use InteractsWithTestCase;

    /**
     * Get application bootstrap file path (if exists).
     *
     * @internal
     *
     * @param  string  $filename
     * @return string|false
     */
    protected function getApplicationBootstrapFile(string $filename): string|false
    {
        $bootstrapFile = realpath(join_paths($this->getApplicationBasePath(), 'bootstrap', $filename));

        if ($this->usesTestbenchDefaultSkeleton()) {
            if (static::usesTestingConcern(WithWorkbench::class) || $this instanceof Testbench) {
                return is_file($workbenchFile = workbench_path('bootstrap', $filename)) ? (string) realpath($workbenchFile) : false;
            }

            return false;
        }

        return $bootstrapFile;
    }

    /**
     * Determine if application is bootstrapped using Testbench's default skeleton.
     *
     * @return bool
     */
    protected function usesTestbenchDefaultSkeleton(): bool
    {
        return realpath(join_paths($this->getApplicationBasePath(), 'bootstrap', '.testbench-default-skeleton')) !== false;
    }

    /**
     * Get the application's base path.
     *
     * @api
     *
     * @return string
     */
    abstract protected function getApplicationBasePath();
}
