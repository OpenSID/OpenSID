<?php

namespace Orchestra\Testbench\Foundation\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Testbench\Foundation\Actions\CreateVendorSymlink as CreateVendorSymlinkAction;

/**
 * @api
 */
final class CreateVendorSymlink
{
    /**
     * Construct a new Create Vendor Symlink bootstrapper.
     *
     * @param  string  $workingPath
     */
    public function __construct(
        public string $workingPath
    ) {}

    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        (new CreateVendorSymlinkAction($this->workingPath))->handle($app);
    }
}
