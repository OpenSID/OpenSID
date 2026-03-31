<?php

namespace Orchestra\Workbench\Recipes;

use Illuminate\Support\Collection;
use Orchestra\Workbench\Workbench;

class AssetPublishCommand extends Command
{
    /**
     * Contruct a new recipe.
     */
    public function __construct()
    {
        parent::__construct('vendor:publish', []);
    }

    /**
     * Get the command options.
     *
     * @return array<string, mixed>
     */
    protected function commandOptions(): array
    {
        /** @var array<int, string> $assets */
        $assets = Workbench::config('assets');

        $tags = Collection::make($assets)
            ->push('laravel-assets')
            ->unique()
            ->all();

        return array_merge($this->options, [
            '--tag' => $tags,
            '--force' => true,
        ]);
    }
}
