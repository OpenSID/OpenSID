<?php

namespace Orchestra\Testbench\Foundation\Console\Actions;

use Illuminate\Console\View\Components\Factory as ComponentsFactory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\LazyCollection;

use function Laravel\Prompts\confirm;
use function Orchestra\Testbench\transform_realpath_to_relative;

/**
 * @api
 */
class DeleteDirectories extends Action
{
    /**
     * Construct a new action instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @param  \Illuminate\Console\View\Components\Factory  $components
     * @param  string|null  $workingPath
     * @param  bool  $confirmation
     */
    public function __construct(
        public Filesystem $filesystem,
        public ?ComponentsFactory $components = null,
        public ?string $workingPath = null,
        public bool $confirmation = false
    ) {}

    /**
     * Handle the action.
     *
     * @param  iterable<int, string>  $directories
     * @return void
     */
    public function handle(iterable $directories): void
    {
        (new LazyCollection($directories))
            ->each(function ($directory) {
                $location = transform_realpath_to_relative($directory, $this->workingPath);

                if (! $this->filesystem->isDirectory($directory)) {
                    $this->components?->twoColumnDetail(
                        \sprintf('Directory [%s] doesn\'t exists', $location),
                        '<fg=yellow;options=bold>SKIPPED</>'
                    );

                    return;
                }

                if ($this->confirmation === true && confirm(\sprintf('Delete [%s] directory?', $location)) === false) {
                    return;
                }

                $this->filesystem->deleteDirectory($directory);

                $this->components?->task(
                    \sprintf('Directory [%s] has been deleted', $location)
                );
            });
    }
}
