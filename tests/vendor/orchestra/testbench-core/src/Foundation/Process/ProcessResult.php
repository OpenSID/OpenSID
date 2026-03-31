<?php

namespace Orchestra\Testbench\Foundation\Process;

use Illuminate\Support\Traits\ForwardsCalls;
use Symfony\Component\Process\Process;

/**
 * @internal
 */
final class ProcessResult extends \Illuminate\Process\ProcessResult
{
    use ForwardsCalls;

    /**
     * The methods that should be returned from process instance.
     *
     * @var array<int, string>
     */
    protected array $passthru = [
        'getCommandLine',
        'getErrorOutput',
        'getExitCode',
        'getOutput',
        'isSuccessful',
    ];

    /**
     * Create a new process result instance.
     *
     * @param  \Symfony\Component\Process\Process  $process
     * @param  array<int, string>|string  $command
     */
    public function __construct(
        Process $process,
        protected array|string $command,
    ) {
        parent::__construct($process);
    }

    /**
     * Handle dynamic calls to the process instance.
     *
     * @param  string  $method
     * @param  array<int, mixed>  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (! \in_array($method, $this->passthru)) {
            self::throwBadMethodCallException($method);
        }

        return $this->forwardDecoratedCallTo($this->process, $method, $parameters);
    }
}
