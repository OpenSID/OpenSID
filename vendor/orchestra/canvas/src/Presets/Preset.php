<?php

namespace Orchestra\Canvas\Presets;

use Illuminate\Support\Arr;

use function Illuminate\Filesystem\join_paths;

abstract class Preset
{
    /**
     * Construct a new preset.
     *
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected array $config,
        protected string $basePath
    ) {
        //
    }

    /**
     * Check if preset name equal to $name.
     */
    public function is(string $name): bool
    {
        return $this->name() === $name;
    }

    /**
     * Get configuration.
     *
     * @param  mixed|null  $default
     */
    public function config(?string $key = null, $default = null): mixed
    {
        if (\is_null($key)) {
            return $this->config;
        }

        return Arr::get($this->config, $key, $default);
    }

    /**
     * Get the path to the base working directory.
     */
    public function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * Get the path to the testing directory.
     */
    public function testingPath(): string
    {
        return join_paths($this->basePath, 'tests');
    }

    /**
     * Get the path to the resource directory.
     */
    public function resourcePath(): string
    {
        return join_paths(
            $this->basePath(),
            $this->config('paths.resource', 'resources')
        );
    }

    /**
     * Get the path to the factory directory.
     */
    public function factoryPath(): string
    {
        return join_paths(
            $this->basePath(),
            $this->config('factory.path', join_paths('database', 'factories'))
        );
    }

    /**
     * Get the path to the migration directory.
     */
    public function migrationPath(): string
    {
        return join_paths(
            $this->basePath(),
            $this->config('migration.path', join_paths('database', 'migrations'))
        );
    }

    /**
     * Get the path to the seeder directory.
     */
    public function seederPath(): string
    {
        return join_paths(
            $this->basePath(),
            $this->config('seeder.path', join_paths('database', 'seeders'))
        );
    }

    /**
     * Database factory namespace.
     */
    public function factoryNamespace(): string
    {
        return $this->config('factory.namespace', 'Database\Factories');
    }

    /**
     * Database seeder namespace.
     */
    public function seederNamespace(): string
    {
        return $this->config('seeder.namespace', 'Database\Seeders');
    }

    /**
     * Preset name.
     */
    abstract public function name(): string;

    /**
     * Get the path to the source directory.
     */
    abstract public function sourcePath(): string;

    /**
     * Preset namespace.
     */
    abstract public function rootNamespace(): string;

    /**
     * Command namespace.
     */
    abstract public function commandNamespace(): string;

    /**
     * Model namespace.
     */
    abstract public function modelNamespace(): string;

    /**
     * Provider namespace.
     */
    abstract public function providerNamespace(): string;

    /**
     * Testing namespace.
     */
    abstract public function testingNamespace(): string;

    /**
     * Get custom stub path.
     */
    abstract public function getCustomStubPath(): ?string;
}
