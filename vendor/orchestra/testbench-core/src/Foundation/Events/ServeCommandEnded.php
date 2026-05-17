<?php

namespace Orchestra\Testbench\Foundation\Events;

use Illuminate\Console\View\Components\Factory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @api
 *
 * @codeCoverageIgnore
 */
class ServeCommandEnded
{
    /**
     * Construct a new event.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  \Illuminate\Console\View\Components\Factory  $components
     * @param  int  $exitCode
     */
    public function __construct(
        public InputInterface $input,
        public OutputInterface $output,
        public Factory $components,
        public int $exitCode
    ) {}
}
