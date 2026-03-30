<?php

namespace Orchestra\Canvas;

use Illuminate\Support\Arr;

class Canvas
{
    /**
     * Make Preset from configuration.
     *
     * @param  array<string, mixed>  $config
     * @return \Orchestra\Canvas\Presets\Preset
     */
    public static function preset(array $config, string $basePath): Presets\Preset
    {
        /** @var array<string, mixed> $configuration */
        $configuration = Arr::except($config, 'preset');

        $preset = $config['preset'];

        switch ($preset) {
            case 'package':
                return new Presets\Package($configuration, $basePath);
            case 'laravel':
                return new Presets\Laravel($configuration, $basePath);
            default:
                if (class_exists($preset)) {
                    /**
                     * @var class-string<\Orchestra\Canvas\Presets\Preset> $preset
                     *
                     * @return \Orchestra\Canvas\Presets\Preset
                     */
                    return new $preset($configuration, $basePath);
                }

                return new Presets\Laravel($configuration, $basePath);
        }
    }
}
