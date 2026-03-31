<?php

namespace Orchestra\Testbench\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Orchestra\Testbench\Workbench\Workbench;
use Symfony\Component\Finder\Finder;

use function Orchestra\Testbench\workbench_path;

/**
 * @internal
 */
class LoadConfigurationWithWorkbench extends LoadConfiguration
{
    /**
     * Determine if workbench config file should be loaded.
     *
     * @var bool
     */
    protected bool $usesWorkbenchConfigFile = false;

    /**
     * Construct a new bootstrap class.
     */
    public function __construct()
    {
        $this->usesWorkbenchConfigFile = (Workbench::configuration()->getWorkbenchDiscoversAttributes()['config'] ?? false)
            && is_dir(workbench_path('config'));
    }

    /** {@inheritDoc} */
    #[\Override]
    public function bootstrap(Application $app): void
    {
        parent::bootstrap($app);

        $userModel = Workbench::applicationUserModel();

        if (! \is_null($userModel) && is_a($userModel, Authenticatable::class, true)) {
            $app->make('config')->set('auth.providers.users.model', $userModel);
        }
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function resolveConfigurationFile(string $path, string $key): string
    {
        $config = workbench_path('config', "{$key}.php");

        return $this->usesWorkbenchConfigFile === true && is_file($config) ? $config : $path;
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function extendsLoadedConfiguration(Collection $configurations): Collection
    {
        if ($this->usesWorkbenchConfigFile === false) {
            return $configurations;
        }

        (new LazyCollection(function () {
            $path = workbench_path('config');

            foreach (Finder::create()->files()->name('*.php')->in($path) as $file) {
                $directory = $this->getNestedDirectory($file, $path);

                yield $directory.basename($file->getRealPath(), '.php') => $file->getRealPath();
            }
        }))->reject(static fn ($path, $key) => $configurations->has($key))
            ->each(static function ($path, $key) use ($configurations) {
                $configurations->put($key, $path);
            });

        return $configurations;
    }
}
