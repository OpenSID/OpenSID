<?php

namespace Orchestra\Canvas\Core\Concerns;

use Illuminate\Support\Str;
use Orchestra\Canvas\Core\PresetManager;
use Orchestra\Canvas\Core\Presets\Preset;
use Symfony\Component\Console\Input\InputOption;

trait CreatesUsingGeneratorPreset
{
    /**
     * Add the standard command options for generating with preset.
     *
     * @return void
     */
    protected function addGeneratorPresetOptions()
    {
        $message = 'to running the command';

        if (property_exists($this, 'type') && ! empty($this->type)) {
            $message = 'when generating '.Str::lower($this->type);
        }

        $this->getDefinition()->addOption(new InputOption(
            'preset',
            null,
            InputOption::VALUE_OPTIONAL,
            sprintf('Preset used %s', $message),
            null,
        ));
    }

    /**
     * Resolve the generator preset.
     */
    protected function generatorPreset(): Preset
    {
        return $this->laravel->make(PresetManager::class)->driver($this->option('preset'));
    }

    /**
     * Get the generator preset source path.
     */
    protected function getGeneratorSourcePath(): string
    {
        return $this->generatorPreset()->sourcePath();
    }
}
