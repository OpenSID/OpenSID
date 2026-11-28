<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;
use Symfony\Component\Process\Process;

class ServeCommand extends BaseServeCommand
{
    /**
     * {@inheritdoc}
     */
    protected function startProcess($hasEnvironment)
    {
        $process = new Process(
            $this->serverCommand(),
            base_path(),
            collect($_ENV)->mapWithKeys(function ($value, $key) use ($hasEnvironment) {
                if ($this->option('no-reload') || ! $hasEnvironment) {
                    return [$key => $value];
                }

                return in_array($key, static::$passthroughVariables)
                    ? [$key => $value]
                    : [$key => false];
            })->all()
        );

        $this->trap(fn () => [SIGTERM, SIGINT, SIGHUP, SIGUSR1, SIGUSR2, SIGQUIT], function ($signal) use ($process) {
            if ($process->isRunning()) {
                $process->stop(10, $signal);
            }

            exit;
        });

        $process->start($this->handleProcessOutput());

        return $process;
    }
}
