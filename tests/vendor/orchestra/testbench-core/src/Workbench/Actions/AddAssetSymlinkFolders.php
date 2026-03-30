<?php

namespace Orchestra\Testbench\Workbench\Actions;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Orchestra\Testbench\Contracts\Config as ConfigContract;

use function Orchestra\Sidekick\is_symlink;
use function Orchestra\Testbench\package_path;

/**
 * @internal
 *
 * @codeCoverageIgnore
 */
final class AddAssetSymlinkFolders
{
    /**
     * Construct a new action.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  \Orchestra\Testbench\Contracts\Config  $config
     */
    public function __construct(
        protected Filesystem $files,
        protected ConfigContract $config
    ) {}

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle(): void
    {
        /** @var array<int, array{from: string, to: string, reverse?: bool}> $sync */
        $sync = $this->config->getWorkbenchAttributes()['sync'] ?? [];

        (new Collection($sync))
            ->map(function ($pair) {
                /** @var bool $reverse */
                $reverse = isset($pair['reverse']) && \is_bool($pair['reverse']) ? $pair['reverse'] : false;

                /** @var string $from */
                $from = $reverse === false ? package_path($pair['from']) : base_path($pair['from']);

                /** @var string $to */
                $to = $reverse === false ? base_path($pair['to']) : package_path($pair['to']);

                return $this->files->isDirectory($from)
                    ? ['from' => $from, 'to' => $to]
                    : null;
            })->filter()
            ->each(function ($pair) {
                /** @var array{from: string, to: string} $pair */

                /** @var string $from */
                $from = $pair['from'];

                /** @var string $to */
                $to = $pair['to'];

                if (is_symlink($to)) {
                    windows_os() ? $this->files->deleteDirectory($to) : $this->files->delete($to);
                } elseif ($this->files->isDirectory($to)) {
                    $this->files->deleteDirectory($to);
                }

                /** @var string $rootDirectory */
                $rootDirectory = Str::beforeLast($to, '/');

                if (! $this->files->isDirectory($rootDirectory)) {
                    $this->files->ensureDirectoryExists($rootDirectory);
                }

                $this->files->link($from, $to);
            });
    }
}
