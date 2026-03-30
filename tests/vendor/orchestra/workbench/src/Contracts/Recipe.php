<?php

namespace Orchestra\Workbench\Contracts;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Symfony\Component\Console\Output\OutputInterface;

interface Recipe
{
    /**
     * Run the recipe.
     *
     * @return void
     */
    public function handle(ConsoleKernel $kernel, OutputInterface $output);
}
