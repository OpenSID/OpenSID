<?php

namespace Orchestra\Workbench;

use Illuminate\Support\Manager;
use Illuminate\Support\Str;

class RecipeManager extends Manager implements Contracts\RecipeManager
{
    /**
     * Create "asset-publish" driver.
     */
    public function createAssetPublishDriver(): Contracts\Recipe
    {
        return new Recipes\AssetPublishCommand;
    }

    /**
     * Create "create-sqlite-db" driver.
     */
    public function createCreateSqliteDbDriver(): Contracts\Recipe
    {
        return $this->commandUsing('workbench:create-sqlite-db', callback: static function () {
            if (config('database.default') === 'testing') {
                config(['database.default' => 'sqlite']);
            }
        });
    }

    /**
     * Create "drop-sqlite-db" driver.
     */
    public function createDropSqliteDbDriver(): Contracts\Recipe
    {
        return $this->commandUsing('workbench:drop-sqlite-db', callback: static function () {
            if (config('database.default') === 'sqlite') {
                config(['database.default' => 'testing']);
            }
        });
    }

    /**
     * Create "purge-skeleton" driver.
     */
    protected function createPurgeSkeletonDriver(): Contracts\Recipe
    {
        return $this->commandUsing('workbench:purge-skeleton');
    }

    /**
     * Create "sync-skeleton" driver.
     */
    protected function createSyncSkeletonDriver(): Contracts\Recipe
    {
        return $this->commandUsing('workbench:sync-skeleton');
    }

    /**
     * Create anonymous command driver.
     *
     * @param  array<string, mixed>  $options
     */
    public function commandUsing(string $command, array $options = [], ?callable $callback = null): Contracts\Recipe
    {
        return new Recipes\Command(
            command: $command,
            options: $options,
            callback: $callback,
        );
    }

    /**
     * Run the recipe by name.
     */
    public function command(string $driver): Contracts\Recipe
    {
        return $this->driver($driver);
    }

    /**
     * Determine recipe is available by name.
     */
    public function hasCommand(string $driver): bool
    {
        if (isset($this->customCreators[$driver])) {
            return true;
        }

        $method = 'create'.Str::studly($driver).'Driver';

        return method_exists($this, $method);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'asset-publish';
    }
}
