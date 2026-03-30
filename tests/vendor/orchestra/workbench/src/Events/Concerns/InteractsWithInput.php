<?php

namespace Orchestra\Workbench\Events\Concerns;

trait InteractsWithInput
{
    /**
     * Determine if event executed with `--basic` option.
     */
    public function isBasicInstallation(): bool
    {
        return $this->input->hasOption('basic') && $this->input->getOption('basic') === true;
    }
}
