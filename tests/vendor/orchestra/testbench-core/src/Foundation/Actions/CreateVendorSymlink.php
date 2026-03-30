<?php

namespace Orchestra\Testbench\Foundation\Actions;

use ErrorException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;

use function Orchestra\Testbench\laravel_vendor_exists;

/**
 * @internal
 */
final class CreateVendorSymlink
{
    /**
     * Construct a new action.
     *
     * @param  string  $workingPath
     */
    public function __construct(
        protected string $workingPath
    ) {}

    /**
     * Execute the command.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function handle(Application $app): void
    {
        $filesystem = new Filesystem;

        $appVendorPath = $app->basePath('vendor');

        $vendorLinkCreated = false;

        if (! laravel_vendor_exists($app, $this->workingPath)) {
            (new DeleteVendorSymlink)->handle($app);

            try {
                $filesystem->link($this->workingPath, $appVendorPath);

                (new RefreshPackageDiscovery)->handle($app);

                $vendorLinkCreated = true;
            } catch (ErrorException $e) {
                //
            }
        }

        $app->flush();

        $app->instance('TESTBENCH_VENDOR_SYMLINK', $vendorLinkCreated);
    }
}
