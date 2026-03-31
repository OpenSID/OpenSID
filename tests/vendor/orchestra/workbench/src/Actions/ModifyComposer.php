<?php

namespace Orchestra\Workbench\Actions;

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
     */
    public function handle(callable $callback): void
    {
        app('workbench.composer')
            ->setWorkingPath($this->workingPath)
            ->modify($callback);
    }
}
