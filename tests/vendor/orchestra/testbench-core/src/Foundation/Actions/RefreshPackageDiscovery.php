<?php

namespace Orchestra\Testbench\Foundation\Actions;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;

use function Orchestra\Sidekick\join_paths;

/**
 * @internal
 */
final class RefreshPackageDiscovery
{
    /**
     * Execute the command.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function handle(Application $app): void
    {
        $filesystem = new Filesystem;

        if ($filesystem->exists($app->bootstrapPath(join_paths('cache', 'packages.php')))) {
            $filesystem->delete($app->bootstrapPath(join_paths('cache', 'packages.php')));
        }

        $app->make(PackageManifest::class)->build();
    }
}
