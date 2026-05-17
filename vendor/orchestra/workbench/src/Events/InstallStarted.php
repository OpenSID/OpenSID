<?php

namespace Orchestra\Workbench\Events;

use Illuminate\Console\View\Components\Factory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallStarted
{
    use Concerns\InteractsWithInput;

    /**
     * Construct a new event.
     */
    public function __construct(
        public InputInterface $input,
        public OutputInterface $output,
        public Factory $components
    ) {}
}
