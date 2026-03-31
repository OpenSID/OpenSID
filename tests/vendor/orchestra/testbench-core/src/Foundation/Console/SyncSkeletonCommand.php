<?php

namespace Orchestra\Testbench\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\Contracts\Config as ConfigContract;
use Orchestra\Testbench\Foundation\Console\Concerns\CopyTestbenchFiles;
use Orchestra\Testbench\Workbench\Actions\AddAssetSymlinkFolders;
use Symfony\Component\Console\Attribute\AsCommand;

use function Orchestra\Testbench\package_path;

/**
 * @codeCoverageIgnore
 */
#[AsCommand(name: 'package:sync-skeleton', description: 'Sync skeleton folder to be served externally')]
class SyncSkeletonCommand extends Command
{
    use CopyTestbenchFiles;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:sync-skeleton';

    /** {@inheritDoc} */
    #[\Override]
    protected function configure()
    {
        parent::configure();

        TerminatingConsole::flush();
    }

    /**
     * Execute the console command.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @param  \Orchestra\Testbench\Contracts\Config  $config
     * @return int
     */
    public function handle(Filesystem $filesystem, ConfigContract $config)
    {
        $this->copyTestbenchConfigurationFile(
            $this->laravel, $filesystem, package_path(), backupExistingFile: false, resetOnTerminating: false
        );

        $this->copyTestbenchDotEnvFile(
            $this->laravel, $filesystem, package_path(), backupExistingFile: false, resetOnTerminating: false
        );

        (new AddAssetSymlinkFolders($filesystem, $config))->handle();

        return self::SUCCESS;
    }
}
