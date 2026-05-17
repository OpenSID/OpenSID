<?php

namespace Orchestra\Testbench\Foundation\Process;

use Illuminate\Support\Traits\ForwardsCalls;
use Symfony\Component\Process\Process;

/**
 * @internal
 *
 * @mixin \Symfony\Component\Process\Process
 */
final class ProcessDecorator
{
    use ForwardsCalls;

    /**
     * Create a new process decorator instance.
     *
     * @param  \Symfony\Component\Process\Process  $process
     * @param  array<int, string>|string  $command
     */
    public function __construct(
        protected Process $process,
        protected array|string $command,
    ) {}

    /**
     * Handle dynamic calls to the process instance.
     *
     * @param  string  $method
     * @param  array<int, mixed>  $parameters
     * @return $this|\Orchestra\Testbench\Foundation\Process\ProcessResult
     */
    public function __call($method, $parameters)
    {
        $response = $this->forwardDecoratedCallTo($this->process, $method, $parameters);

        if ($response instanceof self && $response->isTerminated()) {
            return new ProcessResult($this->process, $this->command);
        }

        return $response;
    }
}
