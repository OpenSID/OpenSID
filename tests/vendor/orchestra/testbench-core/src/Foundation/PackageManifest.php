<?php

namespace Orchestra\Testbench\Foundation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest as IlluminatePackageManifest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use function Orchestra\Sidekick\is_testbench_cli;
use function Orchestra\Testbench\package_path;

/**
 * @api
 */
class PackageManifest extends IlluminatePackageManifest
{
    /**
     * Testbench Class.
     *
     * @var \Orchestra\Testbench\Contracts\TestCase|object|null
     */
    protected $testbench;

    /**
     * List of required packages.
     *
     * @var array<int, string>
     */
    protected $requiredPackages = [
        'laravel/dusk',
        'spatie/laravel-ray',
    ];

    /**
     * {@inheritDoc}
     *
     * @param  \Orchestra\Testbench\Contracts\TestCase|object|null  $testbench
     */
    public function __construct(Filesystem $files, $basePath, $manifestPath, $testbench = null)
    {
        parent::__construct($files, $basePath, $manifestPath);

        $this->setTestbench($testbench);
    }

    /**
     * Create a new package manifest instance from base.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @param  object|null  $testbench
     * @return void
     */
    public static function swap($app, $testbench = null)
    {
        /** @var \Illuminate\Foundation\PackageManifest $base */
        $base = $app->make(IlluminatePackageManifest::class);

        $app->instance(
            IlluminatePackageManifest::class,
            new static(
                $base->files, $base->basePath, $base->manifestPath, $testbench
            )
        );
    }

    /**
     * Set Testbench instance.
     *
     * @param  object|null  $testbench
     * @return void
     */
    public function setTestbench($testbench): void
    {
        $this->testbench = \is_object($testbench) ? $testbench : null;
    }

    /**
     * Requires packages.
     *
     * @param  string[]  $packages
     * @return $this
     */
    public function requires(...$packages)
    {
        $this->requiredPackages = array_merge($this->requiredPackages, Arr::wrap($packages));

        return $this;
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function getManifest()
    {
        $ignore = ! \is_null($this->testbench) && method_exists($this->testbench, 'ignorePackageDiscoveriesFrom')
                ? ($this->testbench->ignorePackageDiscoveriesFrom() ?? [])
                : [];

        $ignoreAll = \in_array('*', $ignore);

        $requires = $this->requiredPackages;

        return (new Collection(parent::getManifest()))
            ->reject(static fn ($configuration, $package) => ($ignoreAll && ! \in_array($package, $requires)) || \in_array($package, $ignore))
            ->map(static function ($configuration, $package) {
                foreach ($configuration['providers'] ?? [] as $provider) {
                    if (! class_exists($provider)) {
                        return null;
                    }
                }

                return $configuration;
            })->filter()->all();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function packagesToIgnore()
    {
        return [];
    }

    /**
     * Get all of the package names from root.
     *
     * @return array
     */
    protected function providersFromRoot()
    {
        $package = $this->providersFromTestbench();

        return \is_array($package) ? [
            $this->format($package['name']) => $package['extra']['laravel'] ?? [],
        ] : [];
    }

    /**
     * Get testbench root composer file.
     *
     * @return array{name: string, extra?: array{laravel?: array}}|null
     */
    protected function providersFromTestbench()
    {
        if (is_testbench_cli() && is_file($composerFile = package_path('composer.json'))) {
            /** @var array{name: string, extra?: array{laravel?: array}} $composer */
            $composer = $this->files->json($composerFile);

            return $composer;
        }

        return null;
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function write(array $manifest)
    {
        parent::write(
            (new Collection($manifest))->merge($this->providersFromRoot())->filter()->all()
        );
    }
}
