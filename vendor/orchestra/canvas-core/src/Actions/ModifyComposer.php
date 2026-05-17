<?php

namespace Orchestra\Canvas\Core\Actions;

use RuntimeException;

use function Orchestra\Sidekick\Filesystem\join_paths;

/**
 * @api
 */
class ModifyComposer
{
    /**
     * Construct a new action.
     */
    public function __construct(
        protected string $workingPath
    ) {}

    /**
     * Handle the action.
     *
     * @param  callable(array):array  $callback
     *
     * @throws \RuntimeException
     */
    public function handle(callable $callback): void
    {
        $composerFile = join_paths($this->workingPath, 'composer.json');

        if (! file_exists($composerFile)) {
            throw new RuntimeException("Unable to locate `composer.json` file at [{$this->workingPath}].");
        }

        $composer = json_decode((string) file_get_contents($composerFile), true, 512, JSON_THROW_ON_ERROR);

        $composer = \call_user_func($callback, $composer);

        file_put_contents(
            $composerFile,
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
        );
    }
}
