<?php

namespace Orchestra\Workbench;

use Illuminate\Support\Collection;

/**
 * @internal
 */
class BuildParser
{
    /**
     * List of disallowed commands.
     *
     * @var array
     */
    protected static $disallowedCommands = [
        'workbench:build',
        'workbench:devtool',
        'workbench:install',
    ];

    /**
     * Get Workbench build steps.
     *
     * @param  array<int|string, array<string, mixed>|string>  $config
     * @return \Illuminate\Support\Collection<string, array<string, mixed>>
     */
    public static function make(array $config): Collection
    {
        return Collection::make($config)
            ->map(static function (array|string $build) {
                /** @var string $name */
                $name = match (true) {
                    \is_array($build) => array_key_first($build),
                    \is_string($build) => $build,
                };

                /** @var array<string, mixed> $options */
                $options = match (true) {
                    \is_array($build) => array_shift($build),
                    \is_string($build) => [],
                };

                return [
                    'name' => $name,
                    'options' => Collection::make($options)->mapWithKeys(static fn ($value, $key) => [$key => $value])->all(),
                ];
            })->whereNotIn(
                'name',
                Collection::make(static::$disallowedCommands)
                    ->transform(static fn ($command) => [$command, str_replace(':', '-', $command)])
                    ->flatten(),
            )->mapWithKeys(static fn (array $build) => [
                $build['name'] => $build['options'],
            ]);
    }
}
