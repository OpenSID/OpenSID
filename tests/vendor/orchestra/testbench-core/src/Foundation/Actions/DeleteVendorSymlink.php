<?php

namespace Orchestra\Testbench\Foundation\Actions;

use Illuminate\Contracts\Foundation\Application;

use function Orchestra\Sidekick\is_symlink;

/**
 * @internal
 */
final class DeleteVendorSymlink
{
    /**
     * Execute the command.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function handle(Application $app): void
    {
        tap($app->basePath('vendor'), static function ($appVendorPath) {
            if (is_symlink($appVendorPath)) {
                windows_os() ? @rmdir($appVendorPath) : @unlink($appVendorPath);
            }

            clearstatcache(false, \dirname($appVendorPath));
        });
    }
}
