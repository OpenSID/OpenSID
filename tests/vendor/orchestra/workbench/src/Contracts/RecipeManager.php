<?php

namespace Orchestra\Workbench\Contracts;

interface RecipeManager
{
    /**
     * Create anonymous command driver.
     *
     * @param  array<string, mixed>  $options
     */
    public function commandUsing(string $command, array $options = []): Recipe;

    /**
     * Run the recipe by name.
     */
    public function command(string $driver): Recipe;

    /**
     * Determine recipe is available by name.
     */
    public function hasCommand(string $driver): bool;
}
