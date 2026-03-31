<?php

namespace Orchestra\Workbench\Actions;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Workbench\Workbench;

/**
 * @api
 */
class ReplaceNamespaces
{
    /**
     * Construct a new action.
     */
    public function __construct(
        protected Filesystem $filesystem
    ) {}

    /**
     * Handle the action.
     */
    public function handle(string $filename): void
    {
        if (! $this->filesystem->isFile($filename)) {
            return;
        }

        $workbenchAppNamespacePrefix = rtrim(Workbench::detectNamespace('app') ?? 'Workbench\App\\', '\\');
        $workbenchFactoriesNamespacePrefix = rtrim(Workbench::detectNamespace('database/factories') ?? 'Workbench\Database\Factories\\', '\\');
        $workbenchSeederNamespacePrefix = rtrim(Workbench::detectNamespace('database/seeders') ?? 'Workbench\Database\Seeders\\', '\\');

        $serviceProvider = \sprintf('%s\Providers\WorkbenchServiceProvider', $workbenchAppNamespacePrefix);
        $databaseSeeder = \sprintf('%s\DatabaseSeeder', $workbenchSeederNamespacePrefix);
        $userModel = \sprintf('%s\Models\User', $workbenchAppNamespacePrefix);
        $userFactory = \sprintf('%s\UserFactory', $workbenchFactoriesNamespacePrefix);

        $keywords = [
            'Workbench\App' => $workbenchAppNamespacePrefix,
            'Workbench\Database\Factories' => $workbenchFactoriesNamespacePrefix,
            'Workbench\Database\Seeders' => $workbenchSeederNamespacePrefix,

            '{{WorkbenchAppNamespace}}' => $workbenchAppNamespacePrefix,
            '{{ WorkbenchAppNamespace }}' => $workbenchAppNamespacePrefix,
            '{{WorkbenchFactoryNamespace}}' => $workbenchFactoriesNamespacePrefix,
            '{{ WorkbenchFactoryNamespace }}' => $workbenchFactoriesNamespacePrefix,
            '{{WorkbenchSeederNamespace}}' => $workbenchSeederNamespacePrefix,
            '{{ WorkbenchSeederNamespace }}' => $workbenchSeederNamespacePrefix,

            '{{WorkbenchServiceProvider}}' => $serviceProvider,
            '{{ WorkbenchServiceProvider }}' => $serviceProvider,

            '{{WorkbenchDatabaseSeeder}}' => $databaseSeeder,
            '{{ WorkbenchDatabaseSeeder }}' => $databaseSeeder,

            '{{WorkbenchUserModel}}' => $userModel,
            '{{ WorkbenchUserModel }}' => $userModel,

            '{{WorkbenchUserFactory}}' => $userFactory,
            '{{ WorkbenchUserFactory }}' => $userFactory,
            'Orchestra\Testbench\Factories\UserFactory' => $userFactory,
        ];

        $this->filesystem->replaceInFile(array_keys($keywords), array_values($keywords), $filename);
    }
}
