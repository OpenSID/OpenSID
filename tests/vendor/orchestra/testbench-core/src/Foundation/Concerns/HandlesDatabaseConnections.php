<?php

namespace Orchestra\Testbench\Foundation\Concerns;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Orchestra\Sidekick\Env;

trait HandlesDatabaseConnections
{
    /**
     * Allow to use database connections environment variables.
     */
    final protected function usesDatabaseConnectionsEnvironmentVariables(Repository $config, string $driver, string $keyword): void
    {
        $keyword = Str::upper($keyword);

        /** @var array<string, array{env: array<int, string>|string, rules?: (\Closure(mixed):(bool))|null}> $options */
        $options = [
            'url' => ['env' => 'URL'],
            'host' => ['env' => 'HOST'],
            'port' => ['env' => 'PORT', 'rules' => static fn ($value) => ! empty($value) && \is_int($value)],
            'database' => ['env' => ['DB', 'DATABASE']],
            'username' => ['env' => ['USER', 'USERNAME']],
            'password' => ['env' => 'PASSWORD', 'rules' => static fn ($value) => \is_null($value) || \is_string($value)],
            'collation' => ['env' => 'COLLATION', 'rules' => static fn ($value) => \is_null($value) || \is_string($value)],
        ];

        $config->set(
            (new Collection($options))
                ->when($driver === 'pgsql', static fn ($options) => $options->put('schema', ['env' => 'SCHEMA']))
                ->mapWithKeys(static function ($options, $key) use ($driver, $keyword, $config) {
                    $name = "database.connections.{$driver}.{$key}";

                    /** @var mixed $configuration */
                    $configuration = (new Collection(Arr::wrap($options['env'])))
                        ->transform(static fn ($value) => Env::get("{$keyword}_{$value}"))
                        ->first(
                            $options['rules'] ?? static fn ($value) => ! empty($value) && $value !== false && \is_string($value) // @phpstan-ignore notIdentical.alwaysTrue
                        ) ?? $config->get($name);

                    return [
                        "{$name}" => $configuration,
                    ];
                })->all()
        );
    }
}
