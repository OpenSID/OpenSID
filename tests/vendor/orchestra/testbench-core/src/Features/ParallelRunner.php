<?php

namespace Orchestra\Testbench\Features;

use Orchestra\Sidekick\Env;

use function Orchestra\Testbench\container;

/**
 * @internal
 *
 * @codeCoverageIgnore
 */
class ParallelRunner extends \Illuminate\Testing\ParallelRunner
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    #[\Override]
    protected function createApplication()
    {
        if (! \defined('TESTBENCH_WORKING_PATH')) {
            \define('TESTBENCH_WORKING_PATH', Env::get('TESTBENCH_WORKING_PATH'));
        }

        if (! isset($_ENV['TESTBENCH_APP_BASE_PATH'])) {
            $_ENV['TESTBENCH_APP_BASE_PATH'] = Env::get('TESTBENCH_APP_BASE_PATH');
        }

        $applicationResolver = static::$applicationResolver ?: static fn () => container()->createApplication();

        return $applicationResolver();
    }
}
