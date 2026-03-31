<?php

namespace Orchestra\Workbench\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * @codeCoverageIgnore
 */
#[AsCommand(name: 'workbench:sync-skeleton', description: 'Sync skeleton folder to be served externally')]
class SyncSkeletonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workbench:sync-skeleton';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->call('package:sync-skeleton');
    }
}
