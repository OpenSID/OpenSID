<?php

namespace Orchestra\Testbench\Contracts;

use ArrayAccess;

/**
 * @phpstan-import-type TExtraConfig from \Orchestra\Testbench\Foundation\Config
 * @phpstan-import-type TPurgeConfig from \Orchestra\Testbench\Foundation\Config
 * @phpstan-import-type TWorkbenchConfig from \Orchestra\Testbench\Foundation\Config
 * @phpstan-import-type TWorkbenchDiscoversConfig from \Orchestra\Testbench\Foundation\Config
 */
interface Config extends ArrayAccess
{
    /**
     * Add additional service providers.
     *
     * @param  array<int, class-string<\Illuminate\Support\ServiceProvider>>  $providers
     * @return $this
     */
    public function addProviders(array $providers);

    /**
     * Get extra attributes.
     *
     * @return array<string, mixed>
     *
     * @phpstan-return TExtraConfig
     */
    public function getExtraAttributes(): array;

    /**
     * Get purge attributes.
     *
     * @return array<string, mixed>
     *
     * @phpstan-return TPurgeConfig
     */
    public function getPurgeAttributes(): array;

    /**
     * Get workbench attributes.
     *
     * @return array<string, mixed>
     *
     * @phpstan-return TWorkbenchConfig
     */
    public function getWorkbenchAttributes(): array;

    /**
     * Get workbench discovers attributes.
     *
     * @return array<string, mixed>
     *
     * @phpstan-return TWorkbenchDiscoversConfig
     */
    public function getWorkbenchDiscoversAttributes(): array;
}
