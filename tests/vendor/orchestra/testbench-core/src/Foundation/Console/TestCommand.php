<?php

namespace Orchestra\Testbench\Foundation\Console;

use Illuminate\Support\Collection;
use NunoMaduro\Collision\Adapters\Laravel\Commands\TestCommand as Command;
use Orchestra\Sidekick\Env;
use Orchestra\Testbench\Features\ParallelRunner;

use function Orchestra\Sidekick\is_testbench_cli;
use function Orchestra\Testbench\defined_environment_variables;
use function Orchestra\Testbench\package_path;

/**
 * @codeCoverageIgnore
 */
class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:test
        {--without-tty : Disable output to TTY}
        {--compact : Indicates whether the compact printer should be used}
        {--configuration= : Read configuration from XML file}
        {--coverage : Indicates whether the coverage information should be collected}
        {--min= : Indicates the minimum threshold enforcement for coverage}
        {--p|parallel : Indicates if the tests should run in parallel}
        {--profile : Lists top 10 slowest tests}
        {--recreate-databases : Indicates if the test databases should be re-created}
        {--drop-databases : Indicates if the test databases should be dropped}
        {--without-databases : Indicates if database configuration should be performed}
        {--c|--custom-argument : Add custom env variables}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the package tests';

    /** {@inheritDoc} */
    #[\Override]
    public function configure()
    {
        parent::configure();

        if (! is_testbench_cli()) {
            $this->setHidden(true);
        }
    }

    /** {@inheritDoc} */
    #[\Override]
    public function handle()
    {
        Env::enablePutenv();

        return parent::handle();
    }

    /**
     * Get the PHPUnit configuration file path.
     *
     * @return string
     */
    public function phpUnitConfigurationFile()
    {
        $configurationFile = str_replace('./', '', $this->option('configuration') ?? 'phpunit.xml');

        return (new Collection([
            package_path($configurationFile),
            package_path("{$configurationFile}.dist"),
        ]))->transform(static fn ($path) => DIRECTORY_SEPARATOR.$path)
            ->filter(static fn ($path) => is_file($path))
            ->first() ?? './';
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function phpunitArguments($options)
    {
        $file = $this->phpUnitConfigurationFile();

        return (new Collection(parent::phpunitArguments($options)))
            ->reject(static fn ($option) => str_starts_with($option, '--configuration='))
            ->merge(["--configuration={$file}"])
            ->all();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function paratestArguments($options)
    {
        $file = $this->phpUnitConfigurationFile();

        return (new Collection(parent::paratestArguments($options)))
            ->reject(static fn (string $option) => str_starts_with($option, '--configuration=') || str_starts_with($option, '--runner='))
            ->merge([
                \sprintf('--configuration=%s', $file),
                \sprintf('--runner=%s', ParallelRunner::class),
            ])->all();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function phpunitEnvironmentVariables()
    {
        return (new Collection(defined_environment_variables()))
            ->merge([
                'APP_ENV' => 'testing',
                'TESTBENCH_PACKAGE_TESTER' => '(true)',
                'TESTBENCH_WORKING_PATH' => package_path(),
                'TESTBENCH_APP_BASE_PATH' => $this->laravel->basePath(),
            ])->merge(parent::phpunitEnvironmentVariables())
            ->all();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function paratestEnvironmentVariables()
    {
        return (new Collection(defined_environment_variables()))
            ->merge([
                'APP_ENV' => 'testing',
                'TESTBENCH_PACKAGE_TESTER' => '(true)',
                'TESTBENCH_WORKING_PATH' => package_path(),
                'TESTBENCH_APP_BASE_PATH' => $this->laravel->basePath(),
            ])->merge(parent::paratestEnvironmentVariables())
            ->all();
    }

    /**
     * Get the configuration file.
     *
     * @return string
     */
    protected function getConfigurationFile()
    {
        return $this->phpUnitConfigurationFile();
    }
}
