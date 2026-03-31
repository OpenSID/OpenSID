<?php

namespace Orchestra\Testbench\Foundation\Console\Concerns;

use function Orchestra\Testbench\package_path;

/**
 * @deprecated
 *
 * @codeCoverageIgnore
 */
trait InteractsWithIO
{
    /**
     * Write a status message to the console.
     *
     * @param  string  $from
     * @param  string  $to
     * @param  string  $type
     * @param  string|null  $workingPath
     * @return void
     */
    protected function copyTaskCompleted(string $from, string $to, string $type, ?string $workingPath = null): void
    {
        $workingPath = $workingPath ?? package_path();

        $from = str_replace($workingPath.'/', '', (string) realpath($from));

        $to = str_replace($workingPath.'/', '', (string) realpath($to));

        $this->components->task(\sprintf(
            'Copying %s [%s] to [%s]',
            $type,
            $from,
            $to,
        ));
    }
}
