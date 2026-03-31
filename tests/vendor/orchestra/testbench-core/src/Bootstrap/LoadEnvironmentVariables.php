<?php

namespace Orchestra\Testbench\Bootstrap;

use Dotenv\Dotenv;
use Orchestra\Sidekick\Env;

use function Orchestra\Sidekick\join_paths;

/**
 * @internal
 */
final class LoadEnvironmentVariables extends \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables
{
    /** {@inheritDoc} */
    #[\Override]
    protected function createDotenv($app)
    {
        /** @phpstan-ignore method.notFound, method.notFound */
        if (! is_file(join_paths($app->environmentPath(), $app->environmentFile()))) {
            return Dotenv::create(
                Env::getRepository(), (string) realpath(join_paths(__DIR__, 'stubs')), '.env.testbench'
            );
        }

        return parent::createDotenv($app);
    }
}
