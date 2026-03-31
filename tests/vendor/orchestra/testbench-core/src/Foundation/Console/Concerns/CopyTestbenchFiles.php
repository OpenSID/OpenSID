<?php

namespace Orchestra\Testbench\Foundation\Console\Concerns;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\LazyCollection;
use Orchestra\Sidekick\Env;
use Orchestra\Testbench\Foundation\Console\TerminatingConsole;

use function Orchestra\Sidekick\join_paths;

/**
 * @codeCoverageIgnore
 */
trait CopyTestbenchFiles
{
    /**
     * Copy the "testbench.yaml" file.
     *
     * @internal
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @param  string  $workingPath
     * @param  bool  $resetOnTerminating
     * @return void
     */
    protected function copyTestbenchConfigurationFile(
        Application $app,
        Filesystem $filesystem,
        string $workingPath,
        bool $backupExistingFile = true,
        bool $resetOnTerminating = true
    ): void {
        $configurationFile = (new LazyCollection(static function () {
            yield 'testbench.yaml';
            yield 'testbench.yaml.example';
            yield 'testbench.yaml.dist';
        }))->map(static fn ($file) => join_paths($workingPath, $file))
            ->filter(static fn ($file) => $filesystem->isFile($file))
            ->first();

        $testbenchFile = $app->basePath(join_paths('bootstrap', 'cache', 'testbench.yaml'));

        if ($backupExistingFile === true && $filesystem->isFile($testbenchFile)) {
            $filesystem->copy($testbenchFile, "{$testbenchFile}.backup");

            TerminatingConsole::beforeWhen($resetOnTerminating, static function () use ($filesystem, $testbenchFile) {
                if ($filesystem->isFile("{$testbenchFile}.backup")) {
                    $filesystem->move("{$testbenchFile}.backup", $testbenchFile);
                }
            });
        }

        if (! \is_null($configurationFile)) {
            $filesystem->copy($configurationFile, $testbenchFile);

            TerminatingConsole::beforeWhen($resetOnTerminating, static function () use ($filesystem, $testbenchFile) {
                if ($filesystem->isFile($testbenchFile)) {
                    $filesystem->delete($testbenchFile);
                }
            });
        }
    }

    /**
     * Copy the ".env" file.
     *
     * @internal
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @param  string  $workingPath
     * @param  bool  $resetOnTerminating
     * @return void
     */
    protected function copyTestbenchDotEnvFile(
        Application $app,
        Filesystem $filesystem,
        string $workingPath,
        bool $backupExistingFile = true,
        bool $resetOnTerminating = true
    ): void {
        $workingPath = $filesystem->isDirectory(join_paths($workingPath, 'workbench'))
            ? join_paths($workingPath, 'workbench')
            : $workingPath;

        $testbenchEnvFilename = $this->testbenchEnvironmentFile();

        $configurationFile = (new LazyCollection(static function () use ($testbenchEnvFilename) {
            $defaultTestbenchEnvFilename = '.env';

            yield $testbenchEnvFilename;
            yield "{$testbenchEnvFilename}.example";
            yield "{$testbenchEnvFilename}.dist";

            yield $defaultTestbenchEnvFilename;
            yield "{$defaultTestbenchEnvFilename}.example";
            yield "{$defaultTestbenchEnvFilename}.dist";
        }))->unique()
            ->map(static fn ($file) => join_paths($workingPath, $file))
            ->filter(static fn ($file) => $filesystem->isFile($file))
            ->first();

        if (\is_null($configurationFile) && $filesystem->isFile($app->basePath('.env.example'))) {
            $configurationFile = $app->basePath('.env.example');
        }

        $environmentFile = $app->basePath('.env');

        if ($backupExistingFile === true && $filesystem->isFile($environmentFile)) {
            $filesystem->copy($environmentFile, "{$environmentFile}.backup");

            TerminatingConsole::beforeWhen($resetOnTerminating, static function () use ($filesystem, $environmentFile) {
                $filesystem->move("{$environmentFile}.backup", $environmentFile);
            });
        }

        if (! \is_null($configurationFile)) {
            $filesystem->copy($configurationFile, $environmentFile);

            TerminatingConsole::beforeWhen($resetOnTerminating, static function () use ($filesystem, $environmentFile) {
                $filesystem->delete($environmentFile);
            });
        }
    }

    /**
     * Determine the Testbench's environment file.
     *
     * @internal
     *
     * @return string
     */
    protected function testbenchEnvironmentFile(): string
    {
        return match (true) {
            property_exists($this, 'environmentFile') => $this->environmentFile,
            Env::has('TESTBENCH_ENVIRONMENT_FILENAME') => Env::get('TESTBENCH_ENVIRONMENT_FILENAME'),
            default => '.env',
        };
    }
}
