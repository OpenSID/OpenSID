<?php

namespace Orchestra\Canvas;

use Orchestra\Canvas\Core\Presets\Preset;

use function Illuminate\Filesystem\join_paths;

class GeneratorPreset extends Preset
{
    /**
     * Preset name.
     *
     * @return string
     */
    public function name()
    {
        return 'canvas';
    }

    /**
     * Get the path to the base working directory.
     *
     * @return string
     */
    public function basePath()
    {
        return $this->canvas()->basePath();
    }

    /**
     * Get the path to the source directory.
     *
     * @return string
     */
    public function sourcePath()
    {
        return $this->canvas()->sourcePath();
    }

    /**
     * Get the path to the testing directory.
     *
     * @return string
     */
    public function testingPath()
    {
        return $this->canvas()->testingPath();
    }

    /**
     * Get the path to the resource directory.
     *
     * @return string
     */
    public function resourcePath()
    {
        return $this->canvas()->resourcePath();
    }

    /**
     * Get the path to the view directory.
     *
     * @return string
     */
    public function viewPath()
    {
        return join_paths($this->resourcePath(), 'views');
    }

    /**
     * Get the path to the factory directory.
     *
     * @return string
     */
    public function factoryPath()
    {
        return $this->canvas()->factoryPath();
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    public function migrationPath()
    {
        return $this->canvas()->migrationPath();
    }

    /**
     * Get the path to the seeder directory.
     *
     * @return string
     */
    public function seederPath()
    {
        return $this->canvas()->seederPath();
    }

    /**
     * Preset namespace.
     *
     * @return string
     */
    public function rootNamespace()
    {
        return $this->canvas()->rootNamespace().'\\';
    }

    /**
     * Command namespace.
     *
     * @return string
     */
    public function commandNamespace()
    {
        return $this->canvas()->commandNamespace().'\\';
    }

    /**
     * Model namespace.
     *
     * @return string
     */
    public function modelNamespace()
    {
        return $this->canvas()->modelNamespace().'\\';
    }

    /**
     * Provider namespace.
     *
     * @return string
     */
    public function providerNamespace()
    {
        return $this->canvas()->providerNamespace().'\\';
    }

    /**
     * Database factory namespace.
     *
     * @return string
     */
    public function factoryNamespace()
    {
        return $this->canvas()->factoryNamespace().'\\';
    }

    /**
     * Database seeder namespace.
     *
     * @return string
     */
    public function seederNamespace()
    {
        return $this->canvas()->seederNamespace().'\\';
    }

    /**
     * Testing namespace.
     *
     * @return string
     */
    public function testingNamespace()
    {
        return $this->canvas()->testingNamespace().'\\';
    }

    /**
     * Preset has custom stub path.
     *
     * @return bool
     */
    public function hasCustomStubPath()
    {
        return ! \is_null($this->canvas()->getCustomStubPath());
    }

    /**
     * Get the model for the default guard's user provider.
     *
     * @param  string|null  $guard
     * @return string|null
     */
    public function userProviderModel($guard = null)
    {
        if (\is_null($guard) || $guard === $this->app->make('config')->get('auth.defaults.guard')) {
            return $this->canvas()->config('user-auth-model')
                ?? $this->canvas()->config('user-auth-provider')
                ?? parent::userProviderModel($guard);
        }

        return parent::userProviderModel($guard);
    }

    /**
     * Get canvas preset.
     *
     * @return \Orchestra\Canvas\Presets\Preset
     */
    public function canvas(): Presets\Preset
    {
        return $this->app->make('orchestra.canvas');
    }
}
