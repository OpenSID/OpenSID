<?php

namespace Orchestra\Workbench\Recipes;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Orchestra\Workbench\Contracts\Recipe;
use Symfony\Component\Console\Output\OutputInterface;

class Command implements Recipe
{
    /**
     * After completion callback.
     *
     * @var (callable(\Symfony\Component\Console\Output\OutputInterface):(void))|null
     */
    public $callback;

    /**
     * Construct a new recipe.
     *
     * @param  array<string, mixed>  $options
     * @param  (callable(\Symfony\Component\Console\Output\OutputInterface):(void))|null  $callback
     */
    public function __construct(
        public string $command,
        public array $options = [],
        ?callable $callback = null
    ) {
        $this->callback = $callback;
    }

    /**
     * Run the recipe.
     *
     * @return void
     */
    public function handle(ConsoleKernel $kernel, OutputInterface $output)
    {
        $kernel->call(
            $this->commandName(), $this->commandOptions(), $output
        );

        if (\is_callable($this->callback)) {
            \call_user_func($this->callback, $output);
        }
    }

    /**
     * Get the command name.
     */
    protected function commandName(): string
    {
        return $this->command;
    }

    /**
     * Get the command options.
     *
     * @return array<string, mixed>
     */
    protected function commandOptions(): array
    {
        return $this->options;
    }
}
