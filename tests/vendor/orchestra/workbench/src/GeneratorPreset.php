<?php

namespace Orchestra\Workbench;

use BadMethodCallException;
use Orchestra\Canvas\Core\Presets\Preset;

use function Orchestra\Sidekick\join_paths;

class GeneratorPreset extends Preset
{
    /**
     * Preset name.
     *
     * @return string
     */
    public function name()
    {
        return 'workbench';
    }

    /**
     * Get the path to the base working directory.
     *
     * @return string
     */
    public function basePath()
    {
        return rtrim(Workbench::path(), DIRECTORY_SEPARATOR);
    }

    /**
     * Get the path to the source directory.
     *
     * @return string
     */
    public function sourcePath()
    {
        return rtrim(Workbench::path('app'), DIRECTORY_SEPARATOR);
    }

    /**
     * Get the path to the testing directory.
     *
     * @throws \BadMethodCallException
     */
    public function testingPath()
    {
        throw new BadMethodCallException('Generating test is not supported for [workbench] preset');
    }

    /**
     * Get the path to the resource directory.
     *
     * @return string
     */
    public function resourcePath()
    {
        return rtrim(Workbench::path('resources'), DIRECTORY_SEPARATOR);
    }

    /**
     * Get the path to the view directory.
     *
     * @return string
     */
    public function viewPath()
    {
        return rtrim(Workbench::path(join_paths('resources', 'views')), DIRECTORY_SEPARATOR);
    }

    /**
     * Get the path to the factory directory.
     *
     * @return string
     */
    public function factoryPath()
    {
        return rtrim(Workbench::path(join_paths('database', 'factories')), DIRECTORY_SEPARATOR);
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    public function migrationPath()
    {
        return rtrim(Workbench::path(join_paths('database', 'migrations')), DIRECTORY_SEPARATOR);
    }

    /**
     * Get the path to the seeder directory.
     */
    public function seederPath(): string
    {
        return rtrim(Workbench::path(join_paths('database', 'seeders')), DIRECTORY_SEPARATOR);
    }

    /**
     * Preset namespace.
     *
     * @return string
     */
    public function rootNamespace()
    {
        return Workbench::detectNamespace('app') ?? "Workbench\App\\";
    }

    /**
     * Command namespace.
     *
     * @return string
     */
    public function commandNamespace()
    {
        return "{$this->rootNamespace()}Console\\";
    }

    /**
     * Model namespace.
     *
     * @return string
     */
    public function modelNamespace()
    {
        return "{$this->rootNamespace()}Models\\";
    }

    /**
     * Provider namespace.
     *
     * @return string
     */
    public function providerNamespace()
    {
        return "{$this->rootNamespace()}Providers\\";
    }

    /**
     * Database factory namespace.
     *
     * @return string
     */
    public function factoryNamespace()
    {
        return Workbench::detectNamespace('database/factories') ?? "Workbench\Database\Factories\\";
    }

    /**
     * Database seeder namespace.
     *
     * @return string
     */
    public function seederNamespace()
    {
        return Workbench::detectNamespace('database/seeders') ?? "Workbench\Database\Seeders\\";
    }

    /**
     * Testing namespace.
     *
     * @throws \BadMethodCallException
     */
    public function testingNamespace()
    {
        throw new BadMethodCallException('Generating test is not supported for [workbench] preset');
    }

    /**
     * Preset has custom stub path.
     *
     * @return bool
     */
    public function hasCustomStubPath()
    {
        return false;
    }
}
