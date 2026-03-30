<?php

namespace Orchestra\Testbench\Foundation\Console;

use Illuminate\Console\Command;
use RuntimeException;
use Symfony\Component\Process\Exception\ProcessSignaledException;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\confirm;
use function Orchestra\Sidekick\is_testbench_cli;
use function Orchestra\Sidekick\phpunit_version_compare;
use function Orchestra\Testbench\package_path;
use function Orchestra\Testbench\php_binary;

/**
 * @codeCoverageIgnore
 */
class TestFallbackCommand extends Command
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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! confirm('Running tests requires "nunomaduro/collision". Do you wish to install it as a dev dependency?')) {
            return 1;
        }

        $this->installCollisionDependencies();

        return 0;
    }

    /**
     * Install testing needed dependencies.
     *
     * @return void
     */
    protected function installCollisionDependencies()
    {
        $version = match (true) {
            phpunit_version_compare('10.3', '>=') => '7.8',
            phpunit_version_compare('10', '>=') => '7.4',
            default => '6.4',
        };

        $command = \sprintf('%s require "nunomaduro/collision:^%s" --dev', $this->findComposer(), $version);

        $process = Process::fromShellCommandline($command, null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('Warning: '.$e->getMessage());
            }
        }

        try {
            $process->run(function ($type, $line) {
                $this->output->write($line);
            });
        } catch (ProcessSignaledException $e) {
            if (\extension_loaded('pcntl') && $e->getSignal() !== SIGINT) {
                throw $e;
            }
        }
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        $composerPath = package_path('composer.phar');

        if (is_file($composerPath)) {
            return implode(' ', [php_binary(true), $composerPath]);
        }

        return 'composer';
    }
}
