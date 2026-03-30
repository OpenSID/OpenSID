<?php

namespace Orchestra\Testbench\Foundation\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * @deprecated
 *
 * @codeCoverageIgnore
 */
#[AsCommand(name: 'package:devtool', description: 'Setup Workbench for package development (deprecated)')]
class DevToolCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:devtool {--force : Overwrite any existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Workbench for package development (deprecated)';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->call('workbench:install', ['--force' => $this->option('force')]);
    }
}
