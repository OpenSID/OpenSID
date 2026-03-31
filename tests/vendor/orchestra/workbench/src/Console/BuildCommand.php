<?php

namespace Orchestra\Workbench\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Collection;
use Orchestra\Workbench\BuildParser;
use Orchestra\Workbench\Contracts\RecipeManager;
use Orchestra\Workbench\Recipes\Command as CommandRecipe;
use Orchestra\Workbench\Workbench;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * @phpstan-import-type TWorkbenchConfig from \Orchestra\Testbench\Foundation\Config
 *
 * @codeCoverageIgnore
 */
#[AsCommand(name: 'workbench:build', description: 'Run builds for workbench')]
class BuildCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ConsoleKernel $kernel, RecipeManager $recipes)
    {
        $commands = Collection::make($kernel->all())
            ->keys()
            ->filter(static fn ($command) => \is_string($command))
            ->mapWithKeys(static fn (string $command) => [str_replace(':', '-', $command) => $command]);

        /** @var array<int|string, array<string, mixed>|string> $build */
        $build = Workbench::config('build');

        BuildParser::make($build)
            ->each(function (array $options, string $name) use ($kernel, $recipes, $commands) {
                /** @var array<string, mixed> $options */
                if ($recipes->hasCommand($name)) {
                    tap($recipes->command($name), static function ($recipe) use ($options) {
                        if ($recipe instanceof CommandRecipe) {
                            $recipe->options = $options;
                        }
                    })->handle($kernel, $this->output);

                    return;
                }

                $command = $commands->get($name) ?? $commands->first(static fn ($commandName) => $name === $commandName);

                if (! \is_null($command)) {
                    $recipes->commandUsing($command, $options)->handle($kernel, $this->output);
                }
            });

        return Command::SUCCESS;
    }
}
