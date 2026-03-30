<?php

namespace Orchestra\Workbench\Actions;

/**
 * @api
 */
class DumpComposerAutoloads
{
    /**
     * Construct a new action.
     */
    public function __construct(
        protected string $workingPath
    ) {}

    /**
     * Handle the action.
     */
    public function handle(): void
    {
        app('workbench.composer')
            ->setWorkingPath($this->workingPath)
            ->dumpAutoloads();
    }
}
