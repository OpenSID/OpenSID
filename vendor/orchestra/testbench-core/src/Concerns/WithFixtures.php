<?php

namespace Orchestra\Testbench\Concerns;

use Illuminate\Support\Str;
use Orchestra\Sidekick\Env;

use function Orchestra\Sidekick\Filesystem\filename_from_classname;

/**
 * @api
 *
 * @codeCoverageIgnore
 */
trait WithFixtures
{
    use InteractsWithPest;

    /**
     * Setup test case to include fixture file using ".fixtures.php" suffix if it's available.
     *
     * @return void
     */
    protected static function setupWithFixturesForTestingEnvironment(): void
    {
        $classFileName = static::isRunningViaPestPrinter(static::class)
            ? static::$__filename
            : filename_from_classname(static::class);

        if ($classFileName === false) {
            return;
        }

        if (! is_file($fixtureFileName = Str::replaceLast('.php', '.fixtures.php', $classFileName))) {
            return;
        }

        if (Env::has('TEST_TOKEN')) {
            require $fixtureFileName;
        } else {
            require_once $fixtureFileName;
        }
    }
}
