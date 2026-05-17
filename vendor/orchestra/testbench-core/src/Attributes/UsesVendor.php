<?php

namespace Orchestra\Testbench\Attributes;

use Attribute;
use Orchestra\Testbench\Contracts\Attributes\AfterEach as AfterEachContract;
use Orchestra\Testbench\Contracts\Attributes\BeforeEach as BeforeEachContract;
use Orchestra\Testbench\Foundation\Actions\CreateVendorSymlink;
use Orchestra\Testbench\Foundation\Actions\DeleteVendorSymlink;

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
        $laravel = clone $app;

        (new CreateVendorSymlink(package_path('vendor')))->handle($laravel);

        $this->vendorSymlinkCreated = $laravel['TESTBENCH_VENDOR_SYMLINK'] ?? false;
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
