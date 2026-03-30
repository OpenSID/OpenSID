<?php

namespace Orchestra\Canvas\Presets;

use InvalidArgumentException;

use function Illuminate\Filesystem\join_paths;

class Package extends Preset
{
    /**
     * Preset name.
     */
    public function name(): string
    {
        return 'package';
    }

    /**
     * Get the path to the source directory.
     */
    public function sourcePath(): string
    {
        return join_paths(
            $this->basePath(),
            $this->config('paths.src', 'src')
        );
    }

    /**
     * Preset namespace.
     */
    public function rootNamespace(): string
    {
        $namespace = trim($this->config['namespace'] ?? '');

        if (empty($namespace)) {
            throw new InvalidArgumentException("Please configure namespace configuration under 'canvas.yaml'");
        }

        return $namespace;
    }

    /**
     * Command namespace.
     *
     * @return string
     */
    public function commandNamespace(): string
    {
        return $this->config('console.namespace', $this->rootNamespace().'\Console');
    }

    /**
     * Model namespace.
     */
    public function modelNamespace(): string
    {
        return $this->config('model.namespace', $this->rootNamespace());
    }

    /**
     * Provider namespace.
     */
    public function providerNamespace(): string
    {
        return $this->config('provider.namespace', $this->rootNamespace());
    }

    /**
     * Testing namespace.
     */
    public function testingNamespace(): string
    {
        return $this->config('testing.namespace', $this->rootNamespace().'\Tests');
    }

    /**
     * Get custom stub path.
     */
    public function getCustomStubPath(): ?string
    {
        return null;
    }
}
