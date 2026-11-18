<?php

namespace Spatie\LaravelPackageTools\Concerns\PackageServiceProvider;

trait ProcessConfigs
{
    public function registerPackageConfigs(): self
    {
        if (empty($this->package->configFileNames)) {
            return $this;
        }

        foreach ($this->package->configFileNames as $configFileName) {
            $vendorConfig = $this->package->basePath("/../config/{$configFileName}.php");

            // Only mergeConfigFile if a .php file and not if a stub file
            if (! is_file($vendorConfig)) {
                continue;
            }

            $this->mergeConfigFrom($vendorConfig, $configFileName);
        }

        return $this;
    }

    protected function bootPackageConfigs(): self
    {
        if (empty($this->package->configFileNames) || ! $this->app->runningInConsole()) {
            return $this;
        }

        foreach ($this->package->configFileNames as $configFileName) {
            $vendorConfig ;
            if (
                ! is_file($vendorConfig = $this->package->basePath("/../config/{$configFileName}.php"))
                &&
                ! is_file($vendorConfig = $this->package->basePath("/../config/{$configFileName}.php.stub"))
            ) {
                continue;
            }

            $this->publishes(
                [$vendorConfig => config_path("{$configFileName}.php")],
                "{$this->package->shortName()}-config"
            );
        }

        return $this;
    }
}
