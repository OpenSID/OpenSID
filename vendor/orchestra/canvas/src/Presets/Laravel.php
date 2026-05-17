<?php

namespace Orchestra\Canvas\Presets;

use function Illuminate\Filesystem\join_paths;

class Laravel extends Preset
{
    /**
     * Preset name.
     */
    public function name(): string
    {
        return 'laravel';
    }

    /**
     * Get the path to the source directory.
     */
    public function sourcePath(): string
    {
        return join_paths($this->basePath(), $this->config('paths.src', 'app'));
    }

    /**
     * Preset namespace.
     */
    public function rootNamespace(): string
    {
        return $this->config['namespace'] ?? 'App';
    }

    /**
     * Command namespace.
     *
     * @return string
     */
    public function commandNamespace(): string
    {
        return $this->config('console.namespace', $this->rootNamespace().'\Console\Commands');
    }

    /**
     * Model namespace.
     */
    public function modelNamespace(): string
    {
        return $this->config('model.namespace', $this->rootNamespace().'\Models');
    }

    /**
     * Provider namespace.
     */
    public function providerNamespace(): string
    {
        return $this->config('provider.namespace', $this->rootNamespace().'\Providers');
    }

    /**
     * Testing namespace.
     */
    public function testingNamespace(): string
    {
        return $this->config('testing.namespace', 'Tests');
    }

    /**
     * Get custom stub path.
     */
    public function getCustomStubPath(): ?string
    {
        return join_paths($this->basePath(), 'stubs');
    }
}
