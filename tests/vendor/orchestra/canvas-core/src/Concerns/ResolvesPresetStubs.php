<?php

namespace Orchestra\Canvas\Core\Concerns;

use function Illuminate\Filesystem\join_paths;

trait ResolvesPresetStubs
{
    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        $preset = $this->generatorPreset();

        return $preset->hasCustomStubPath() && file_exists($customPath = join_paths($preset->basePath(), trim($stub, '/')))
            ? $customPath
            : $this->resolveDefaultStubPath($stub);
    }

    /**
     * Resolve the default fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveDefaultStubPath($stub)
    {
        return $stub;
    }
}
