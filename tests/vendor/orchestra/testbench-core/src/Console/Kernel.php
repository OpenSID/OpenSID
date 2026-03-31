<?php

namespace Orchestra\Testbench\Console;

use Orchestra\Testbench\Foundation\Console\Kernel as ConsoleKernel;
use Throwable;

use function Orchestra\Sidekick\join_paths;

/**
 * @codeCoverageIgnore
 */
final class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    #[\Override]
    protected function commands()
    {
        if (is_file($console = base_path(join_paths('routes', 'console.php')))) {
            require $console;
        }
    }

    /**
     * Report the exception to the exception handler.
     *
     * @param  \Throwable  $e
     * @return void
     *
     * @throws \Throwable
     */
    #[\Override]
    protected function reportException(Throwable $e)
    {
        throw $e;
    }
}
