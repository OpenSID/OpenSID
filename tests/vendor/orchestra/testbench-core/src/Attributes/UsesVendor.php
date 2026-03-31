<?php

namespace Orchestra\Testbench\Attributes;

use Attribute;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Orchestra\Testbench\Contracts\Attributes\AfterEach as AfterEachContract;
use Orchestra\Testbench\Contracts\Attributes\BeforeEach as BeforeEachContract;
use Orchestra\Testbench\Foundation\Actions\DeleteVendorSymlink;
use Orchestra\Testbench\Foundation\Application as Testbench;

use function Orchestra\Testbench\package_path;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class UsesVendor implements AfterEachContract, BeforeEachContract
{
    /**
     * Determine if vendor symlink was created via this attribute.
     */
    public bool $vendorSymlinkCreated = false;

    /**
     * Handle the attribute.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function beforeEach($app): void
    {
        $laravel = Testbench::createVendorSymlink(base_path(), package_path('vendor'));

        $this->vendorSymlinkCreated = $laravel['TESTBENCH_VENDOR_SYMLINK'] ?? false;

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);

        Application::setInstance($app);
    }

    /**
     * Handle the attribute.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function afterEach($app): void
    {
        if ($this->vendorSymlinkCreated === true) {
            (new DeleteVendorSymlink)->handle($app);
        }
    }
}
