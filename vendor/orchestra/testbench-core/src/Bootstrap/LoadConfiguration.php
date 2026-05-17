<?php

namespace Orchestra\Testbench\Bootstrap;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Orchestra\Sidekick\Env;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

use function Orchestra\Testbench\default_skeleton_path;

/**
 * @internal
 *
 * @phpstan-type TLaravel \Illuminate\Contracts\Foundation\Application
 */
class LoadConfiguration
{
    /**
     * Bootstrap the given application.
     *
     * @param  TLaravel  $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        $app->instance('config', $config = new Repository([]));

        $this->loadConfigurationFiles($app, $config);

        if (\is_null($config->get('database.connections.testing'))) {
            $config->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'foreign_key_constraints' => Env::get('DB_FOREIGN_KEYS', false),
            ]);
        }

        $this->configureDefaultDatabaseConnection($config);

        mb_internal_encoding('UTF-8');
    }

    /**
     * Load the configuration items from all of the files.
     *
     * @param  TLaravel  $app
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    private function loadConfigurationFiles(Application $app, RepositoryContract $config): void
    {
        $this->extendsLoadedConfiguration(
            (new LazyCollection(function () use ($app) {
                $path = $this->getConfigurationPath($app);

                if (\is_string($path)) {
                    foreach (Finder::create()->files()->name('*.php')->in($path) as $file) {
                        $directory = $this->getNestedDirectory($file, $path);

                        yield $directory.basename($file->getRealPath(), '.php') => $file->getRealPath();
                    }
                }
            }))
                ->collect()
                ->transform(fn ($path, $key) => $this->resolveConfigurationFile($path, $key))
        )->each(static function ($path, $key) use ($config) {
            $config->set($key, require $path);
        });
    }

    /**
     * Get the configuration file nesting path.
     *
     * @param  \SplFileInfo  $file
     * @param  string  $configPath
     * @return string
     */
    protected function getNestedDirectory(SplFileInfo $file, string $configPath): string
    {
        $directory = $file->getPath();

        if ($nested = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested).'.';
        }

        return $nested;
    }

    /**
     * Resolve the configuration file.
     *
     * @param  string  $path
     * @param  string  $key
     * @return string
     */
    protected function resolveConfigurationFile(string $path, string $key): string
    {
        return $path;
    }

    /**
     * Extend the loaded configuration.
     *
     * @param  \Illuminate\Support\Collection  $configurations
     * @return \Illuminate\Support\Collection
     */
    protected function extendsLoadedConfiguration(Collection $configurations): Collection
    {
        return $configurations;
    }

    /**
     * Configure the default database connection.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    protected function configureDefaultDatabaseConnection(RepositoryContract $config): void
    {
        if ($config->get('database.default') === 'sqlite' && ! is_file($config->get('database.connections.sqlite.database'))) {
            $config->set('database.default', 'testing');
        }
    }

    /**
     * Get the application configuration path.
     *
     * @param  TLaravel  $app
     * @return string
     */
    protected function getConfigurationPath(Application $app): string
    {
        return is_dir($app->basePath('config'))
            ? $app->basePath('config')
            : default_skeleton_path('config');
    }
}
