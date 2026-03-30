<?php

namespace Orchestra\Testbench\Foundation\Console\Concerns;

use Orchestra\Testbench\Foundation\Console\TerminatingConsole;

/**
 * @deprecated
 *
 * @codeCoverageIgnore
 */
trait HandleTerminatingConsole
{
    /**
     * Register a callback to be run before terminating the command.
     *
     * @param  callable():void  $callback
     * @return void
     *
     * @deprecated Use `Orchestra\Testbench\Foundation\Console\TerminatingConsole::before()` instead.
     */
    protected function beforeTerminating(callable $callback): void
    {
        TerminatingConsole::before($callback);
    }

    /**
     * Handle terminating console.
     *
     * @return void
     *
     * @deprecated Use `Orchestra\Testbench\Foundation\Console\TerminatingConsole::handle()` instead.
     */
    protected function handleTerminatingConsole(): void
    {
        TerminatingConsole::handle();
    }
}
