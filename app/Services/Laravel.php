<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Services;

use App\Providers\ConsoleServiceProvider;
use Illuminate\Bus\BusServiceProvider;
use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Encryption\EncryptionServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Pagination\PaginationServiceProvider;
use Illuminate\Queue\QueueServiceProvider;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\ViewServiceProvider;

class Laravel extends Container
{
    /**
     * Indicates if the class aliases have been registered.
     *
     * @var bool
     */
    protected static $aliasesRegistered = false;

    /**
     * The base path of the application installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * All of the loaded configuration files.
     *
     * @var array
     */
    protected $loadedConfigurations = [];

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * The loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * The service binding methods that have been executed.
     *
     * @var array
     */
    protected $ranServiceBinders = [];

    /**
     * The custom storage path defined by the developer.
     *
     * @var string
     */
    protected $storagePath;

    /**
     * The array of terminating callbacks.
     *
     * @var callable[]
     */
    protected $terminatingCallbacks = [];

    /**
     * The available container bindings and their respective load methods.
     *
     * @var array
     */
    public $availableBindings = [
        Dispatcher::class                             => 'registerBusBindings',
        'cache'                                       => 'registerCacheBindings',
        'cache.store'                                 => 'registerCacheBindings',
        \Illuminate\Contracts\Cache\Factory::class    => 'registerCacheBindings',
        \Illuminate\Contracts\Cache\Repository::class => 'registerCacheBindings',
        'config'                                      => 'registerConfigBindings',
        'composer'                                    => 'registerComposerBindings',
        'db'                                          => 'registerDatabaseBindings',
        // \Illuminate\Database\Eloquent\Factory::class => 'registerDatabaseBindings',
        'filesystem'                                       => 'registerFilesystemBindings',
        'filesystem.cloud'                                 => 'registerFilesystemBindings',
        'filesystem.disk'                                  => 'registerFilesystemBindings',
        \Illuminate\Contracts\Filesystem\Cloud::class      => 'registerFilesystemBindings',
        \Illuminate\Contracts\Filesystem\Filesystem::class => 'registerFilesystemBindings',
        \Illuminate\Contracts\Filesystem\Factory::class    => 'registerFilesystemBindings',
        'encrypter'                                        => 'registerEncrypterBindings',
        \Illuminate\Contracts\Encryption\Encrypter::class  => 'registerEncrypterBindings',
        'events'                                           => 'registerEventBindings',
        'files'                                            => 'registerFilesBindings',
        'hash'                                             => 'registerHashBindings',
        \Illuminate\Contracts\Hashing\Hasher::class        => 'registerHashBindings',
        'queue'                                            => 'registerQueueBindings',
        'queue.connection'                                 => 'registerQueueBindings',
        \Illuminate\Contracts\Queue\Factory::class         => 'registerQueueBindings',
        \Illuminate\Contracts\Queue\Queue::class           => 'registerQueueBindings',
        \Illuminate\Contracts\Events\Dispatcher::class     => 'registerEventBindings',
        'view'                                             => 'registerViewBindings',
        \Illuminate\Contracts\View\Factory::class          => 'registerViewBindings',
    ];

    /**
     * Create a new Mini application instance.
     *
     * @param string|null $basePath
     *
     * @return void
     */
    public function __construct($basePath = null)
    {
        $this->basePath = $basePath;

        $this->bootstrapContainer();
    }

    /**
     * Bootstrap the application container.
     *
     * @return void
     */
    protected function bootstrapContainer()
    {
        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance(self::class, $this);

        $this->instance('path', $this->path());

        $this->instance('env', $this->environment());

        $this->registerContainerAliases();
    }

    /**
     * Get or check the current application environment.
     *
     * @param  mixed
     *
     * @return string
     */
    public function environment()
    {
        $env = ENVIRONMENT;

        if (func_num_args() > 0) {
            $patterns = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();

            foreach ($patterns as $pattern) {
                if (Str::is($pattern, $env)) {
                    return true;
                }
            }

            return false;
        }

        return $env;
    }

    /**
     * Determine if the given service provider is loaded.
     */
    public function providerIsLoaded(string $provider): bool
    {
        return isset($this->loadedProviders[$provider]);
    }

    /**
     * Register a service provider with the application.
     *
     * @param ServiceProvider|string $provider
     */
    public function register($provider): void
    {
        if (! $provider instanceof ServiceProvider) {
            $provider = new $provider($this);
        }

        if (array_key_exists($providerName = get_class($provider), $this->loadedProviders)) {
            return;
        }

        $this->loadedProviders[$providerName] = $provider;

        if (method_exists($provider, 'register')) {
            $provider->register();
        }

        if ($this->booted) {
            $this->bootProvider($provider);
        }
    }

    /**
     * Register a deferred provider and service.
     *
     * @param string $provider
     */
    public function registerDeferredProvider($provider): void
    {
        $this->register($provider);
    }

    /**
     * Boots the registered providers.
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        $this->instance(Request::class, Request::capture());

        foreach ($this->loadedProviders as $provider) {
            $this->bootProvider($provider);
        }

        $this->booted = true;
    }

    /**
     * Boot the given service provider.
     *
     * @return mixed
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            return $this->call([$provider, 'boot']);
        }
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     *
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        $abstract = $this->getAlias($abstract);

        if (! $this->bound($abstract)
            && array_key_exists($abstract, $this->availableBindings)
            && ! array_key_exists($this->availableBindings[$abstract], $this->ranServiceBinders)) {
            $this->{$method = $this->availableBindings[$abstract]}();

            $this->ranServiceBinders[$method] = true;
        }

        return parent::make($abstract, $parameters);
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerBusBindings()
    {
        $this->singleton(Dispatcher::class, function () {
            $this->register(BusServiceProvider::class);

            return $this->make(Dispatcher::class);
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerCacheBindings()
    {
        $this->singleton('cache', fn () => $this->loadComponent('cache', CacheServiceProvider::class));
        $this->singleton('cache.store', fn () => $this->loadComponent('cache', CacheServiceProvider::class, 'cache.store'));
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerComposerBindings()
    {
        $this->singleton('composer', fn ($app): \Illuminate\Support\Composer => new Composer($app->make('files'), $this->basePath()));
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerConfigBindings()
    {
        $this->singleton('config', static fn (): \Illuminate\Config\Repository => new Repository());
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerDatabaseBindings()
    {
        $this->singleton('db', function () {
            $this->configure('app');

            if (file_exists($this->basePath('desa'))) {
                $this->make('config')->set('database', require $this->configPath('eloquent.php'));
            }

            $this->register(DatabaseServiceProvider::class);
            $this->register(PaginationServiceProvider::class);

            return $this->make('db');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerEncrypterBindings()
    {
        $this->singleton('encrypter', fn () => $this->loadComponent('app', EncryptionServiceProvider::class, 'encrypter'));
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerEventBindings()
    {
        $this->singleton('events', function () {
            $this->register(EventServiceProvider::class);

            return $this->make('events');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerFilesBindings()
    {
        $this->singleton('files', static fn (): \Illuminate\Filesystem\Filesystem => new Filesystem());
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerFilesystemBindings()
    {
        $this->singleton('filesystem', fn () => $this->loadComponent('filesystems', FilesystemServiceProvider::class, 'filesystem'));
        $this->singleton('filesystem.disk', fn () => $this->loadComponent('filesystems', FilesystemServiceProvider::class, 'filesystem.disk'));
        $this->singleton('filesystem.cloud', fn () => $this->loadComponent('filesystems', FilesystemServiceProvider::class, 'filesystem.cloud'));
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerHashBindings()
    {
        $this->singleton('hash', fn () => $this->loadComponent('hashing', HashServiceProvider::class, 'hash'));
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerQueueBindings()
    {
        $this->singleton('queue', fn () => $this->loadComponent('queue', QueueServiceProvider::class, 'queue'));
        $this->singleton('queue.connection', fn () => $this->loadComponent('queue', QueueServiceProvider::class, 'queue.connection'));
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerViewBindings()
    {
        $this->singleton('view', fn () => $this->loadComponent('view', ViewServiceProvider::class));
    }

    /**
     * Configure and load the given component and provider.
     *
     * @param string       $config
     * @param array|string $providers
     * @param string|null  $return
     *
     * @return mixed
     */
    public function loadComponent($config, $providers, $return = null)
    {
        $this->configure($config);

        foreach ((array) $providers as $provider) {
            $this->register($provider);
        }

        return $this->make($return ?: $config);
    }

    /**
     * Load a configuration file into the application.
     *
     * @param string $name
     */
    public function configure($name): void
    {
        if (isset($this->loadedConfigurations[$name])) {
            return;
        }

        $this->loadedConfigurations[$name] = true;

        $path = $this->getConfigurationPath($name);

        if ($path) {
            $this->make('config')->set($name, require $path);
        }
    }

    /**
     * Get the path to the given configuration file.
     *
     * If no name is provided, then we'll return the path to the config folder.
     *
     * @param string|null $name
     *
     * @return string
     */
    public function getConfigurationPath($name = null)
    {
        if (! $name) {
            $appConfigDir = $this->basePath('donjo-app/config') . '/';
            if (file_exists($appConfigDir)) {
                return $appConfigDir;
            }

            if (file_exists($path = __DIR__ . '/../config/')) {
                return $path;
            }
        } else {
            $appConfigPath = $this->basePath('donjo-app/config') . '/' . $name . '.php';
            if (file_exists($appConfigPath)) {
                return $appConfigPath;
            }

            if (file_exists($path = __DIR__ . '/../config/' . $name . '.php')) {
                return $path;
            }
        }
    }

    /**
     * Register the facades for the application.
     *
     * @param bool  $aliases
     * @param array $userAliases
     */
    public function withFacades($aliases = true, $userAliases = []): void
    {
        Facade::setFacadeApplication($this);

        if ($aliases) {
            $this->withAliases($userAliases);
        }
    }

    /**
     * Register the aliases for the application.
     *
     * @param array $userAliases
     */
    public function withAliases($userAliases = []): void
    {
        $defaults = [
            \Illuminate\Support\Facades\Cache::class   => 'Cache',
            \Illuminate\Support\Facades\DB::class      => 'DB',
            \Illuminate\Support\Facades\Event::class   => 'Event',
            \Illuminate\Support\Facades\Queue::class   => 'Queue',
            \Illuminate\Support\Facades\Schema::class  => 'Schema',
            \Illuminate\Support\Facades\Storage::class => 'Storage',
        ];

        if (! static::$aliasesRegistered) {
            static::$aliasesRegistered = true;

            $merged = array_merge($defaults, $userAliases);

            foreach ($merged as $original => $alias) {
                class_alias($original, $alias);
            }
        }
    }

    /**
     * Load the Eloquent library for the application.
     */
    public function withEloquent(): void
    {
        $this->make('db');
    }

    /**
     * Get the path to the application "app" directory.
     */
    public function path(): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'app';
    }

    /**
     * Get the base path for the application.
     *
     * @return string
     */
    public function basePath(?string $path = '')
    {
        if ($this->basePath !== null) {
            return $this->basePath . ($path ? '/' . $path : $path);
        }

        $this->basePath = $this->runningInConsole() ? getcwd() : realpath(getcwd() . '/../');

        return $this->basePath($path);
    }

    /**
     * Get the path to the application configuration files.
     */
    public function configPath(?string $path = ''): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'donjo-app' . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the database directory.
     */
    public function databasePath(?string $path = ''): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'database' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the storage path for the application.
     *
     * @param string|null $path
     */
    public function storagePath($path = ''): string
    {
        return ($this->storagePath ?: $this->basePath . DIRECTORY_SEPARATOR . 'storage') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Set the storage directory.
     *
     * @param string $path
     *
     * @return $this
     */
    public function useStoragePath($path): self
    {
        $this->storagePath = $path;

        $this->instance('path.storage', $path);

        return $this;
    }

    /**
     * Get the path to the resources directory.
     *
     * @param string|null $path
     */
    public function resourcePath($path = ''): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'resources' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Determine if the application is running in the console.
     */
    public function runningInConsole(): bool
    {
        return \PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg';
    }

    /**
     * Prepare the application to execute a console command.
     *
     * @param bool $aliases
     */
    public function prepareForConsoleCommand($aliases = true): void
    {
        $this->withFacades($aliases);

        $this->make('cache');
        $this->make('queue');

        $this->register(MigrationServiceProvider::class);
        $this->register(ConsoleServiceProvider::class);
    }

    /**
     * Flush the container of all bindings and resolved instances.
     */
    public function flush(): void
    {
        parent::flush();

        $this->loadedProviders         = [];
        $this->reboundCallbacks        = [];
        $this->resolvingCallbacks      = [];
        $this->availableBindings       = [];
        $this->ranServiceBinders       = [];
        $this->loadedConfigurations    = [];
        $this->afterResolvingCallbacks = [];

        static::$instance = null;
    }

    /**
     * Register a terminating callback with the application.
     *
     * @param callable|string $callback
     *
     * @return $this
     */
    public function terminating($callback): self
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

    /**
     * Terminate the application.
     */
    public function terminate(): void
    {
        $index = 0;

        while ($index < count($this->terminatingCallbacks)) {
            $this->call($this->terminatingCallbacks[$index]);

            $index++;
        }
    }

    /**
     * Register the core container aliases.
     *
     * @return void
     */
    protected function registerContainerAliases()
    {
        $this->aliases = [
            \Illuminate\Contracts\Foundation\Application::class     => 'app',
            \Illuminate\Contracts\Cache\Factory::class              => 'cache',
            \Illuminate\Contracts\Cache\Repository::class           => 'cache.store',
            \Illuminate\Contracts\Config\Repository::class          => 'config',
            Repository::class                                       => 'config',
            Container::class                                        => 'app',
            \Illuminate\Contracts\Container\Container::class        => 'app',
            \Illuminate\Database\ConnectionResolverInterface::class => 'db',
            \Illuminate\Database\DatabaseManager::class             => 'db',
            \Illuminate\Contracts\Encryption\Encrypter::class       => 'encrypter',
            \Illuminate\Contracts\Events\Dispatcher::class          => 'events',
            \Illuminate\Contracts\Filesystem\Factory::class         => 'filesystem',
            \Illuminate\Contracts\Filesystem\Filesystem::class      => 'filesystem.disk',
            \Illuminate\Contracts\Filesystem\Cloud::class           => 'filesystem.cloud',
            \Illuminate\Contracts\Hashing\Hasher::class             => 'hash',
            \Illuminate\Contracts\Queue\Factory::class              => 'queue',
            \Illuminate\Contracts\Queue\Queue::class                => 'queue.connection',
            'request'                                               => Request::class,
            \Illuminate\Contracts\View\Factory::class               => 'view',
        ];
    }
}
