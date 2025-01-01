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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Services;

use App\Providers\ConsoleServiceProvider;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Broadcasting\BroadcastServiceProvider;
use Illuminate\Bus\BusServiceProvider;
use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Contracts\Broadcasting\Factory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Cookie\CookieServiceProvider;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Encryption\EncryptionServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\NotificationServiceProvider;
use Illuminate\Pagination\PaginationServiceProvider;
use Illuminate\Queue\QueueServiceProvider;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Translation\TranslationServiceProvider;
use Illuminate\Validation\ValidationServiceProvider;
use Illuminate\View\ViewServiceProvider;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Throwable;

class Laravel extends Container
{
    /**
     * Indicates if the class aliases have been registered.
     *
     * @var bool
     */
    protected static $aliasesRegistered = false;

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
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

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
        'auth'                                        => 'registerAuthBindings',
        'auth.driver'                                 => 'registerAuthBindings',
        AuthManager::class                            => 'registerAuthBindings',
        \Illuminate\Contracts\Auth\Guard::class       => 'registerAuthBindings',
        Gate::class                                   => 'registerAuthBindings',
        Broadcaster::class                            => 'registerBroadcastingBindings',
        Factory::class                                => 'registerBroadcastingBindings',
        Dispatcher::class                             => 'registerBusBindings',
        'cache'                                       => 'registerCacheBindings',
        'cache.store'                                 => 'registerCacheBindings',
        \Illuminate\Contracts\Cache\Factory::class    => 'registerCacheBindings',
        \Illuminate\Contracts\Cache\Repository::class => 'registerCacheBindings',
        'config'                                      => 'registerConfigBindings',
        'composer'                                    => 'registerComposerBindings',
        'db'                                          => 'registerDatabaseBindings',
        Dispatcher::class                             => 'registerBusBindings',
        'cache'                                       => 'registerCacheBindings',
        'cache.store'                                 => 'registerCacheBindings',
        \Illuminate\Contracts\Cache\Factory::class    => 'registerCacheBindings',
        \Illuminate\Contracts\Cache\Repository::class => 'registerCacheBindings',
        'config'                                      => 'registerConfigBindings',
        'composer'                                    => 'registerComposerBindings',
        'cookie'                                      => 'registerCookieBindings',
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
        'log'                                              => 'registerLogBindings',
        LoggerInterface::class                             => 'registerLogBindings',
        ChannelManager::class                              => 'registerNotificationBindings',
        'queue'                                            => 'registerQueueBindings',
        'queue.connection'                                 => 'registerQueueBindings',
        \Illuminate\Contracts\Queue\Factory::class         => 'registerQueueBindings',
        \Illuminate\Contracts\Queue\Queue::class           => 'registerQueueBindings',
        \Illuminate\Contracts\Events\Dispatcher::class     => 'registerEventBindings',
        'session'                                          => 'registerSessionBindings',
        'session.store'                                    => 'registerSessionBindings',
        'translator'                                       => 'registerTranslationBindings',
        'validator'                                        => 'registerValidatorBindings',
        \Illuminate\Contracts\Validation\Factory::class    => 'registerValidatorBindings',
        'view'                                             => 'registerViewBindings',
        'view.engine.resolver'                             => 'registerViewBindings',
        \Illuminate\Contracts\View\Factory::class          => 'registerViewBindings',
    ];

    /**
     * Create a new Mini application instance.
     *
     * @param string|null $basePath
     *
     * @return void
     */
    public function __construct(
        /**
         * The base path of the application installation.
         */
        protected $basePath = null
    ) {
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
     * Get the version number of the application.
     */
    public function version(): string
    {
        return sprintf('OpenSID (%s) (Illuminate Components ^10.0)', VERSION);
    }

    /**
     * Determine if the application is currently down for maintenance.
     */
    public function isDownForMaintenance(): bool
    {
        return false;
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
     * Determine if the application is in the local environment.
     */
    public function isLocal(): bool
    {
        return $this->environment() === 'local';
    }

    /**
     * Determine if the application is in the production environment.
     */
    public function isProduction(): bool
    {
        return $this->environment() === 'production';
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

        if (array_key_exists($providerName = $provider::class, $this->loadedProviders)) {
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
     * Run the application and send the response.
     */
    public function run(): void
    {
        $this->dispatch();
        $this->terminate();
    }

    /**
     * Dispatch the incoming request.
     */
    public function dispatch(): void
    {
        $this->instance(Request::class, $this->prepareRequest(Request::capture()));

        try {
            $this->boot();
        } catch (Throwable $th) {
            $this->make(ExceptionHandler::class)->report($th);
        }
    }

    /**
     * Boots the registered providers.
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        array_walk($this->loadedProviders, fn ($provider) => $this->bootProvider($provider));

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

        return null;
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

        if (
            ! $this->bound($abstract)
            && array_key_exists($abstract, $this->availableBindings)
            && ! array_key_exists($this->availableBindings[$abstract], $this->ranServiceBinders)
        ) {
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
    protected function registerAuthBindings()
    {
        $this->singleton('auth', fn () => $this->loadComponent('auth', AuthServiceProvider::class, 'auth'));
        $this->singleton('auth.driver', fn () => $this->loadComponent('auth', AuthServiceProvider::class, 'auth.driver'));
        $this->singleton(AuthManager::class, fn () => $this->loadComponent('auth', AuthServiceProvider::class, 'auth'));
        $this->singleton(Gate::class, fn () => $this->loadComponent('auth', AuthServiceProvider::class, Gate::class));
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerBroadcastingBindings()
    {
        $this->singleton(Factory::class, fn () => $this->loadComponent('broadcasting', BroadcastServiceProvider::class, Factory::class));
        $this->singleton(Broadcaster::class, fn () => $this->loadComponent('broadcasting', BroadcastServiceProvider::class, Broadcaster::class));
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
    protected function registerCookieBindings()
    {
        $this->singleton('cookie', fn () => $this->loadComponent('session', CookieServiceProvider::class, 'cookie'));
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
                $this->configure('database');
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
    protected function registerLogBindings()
    {
        $this->singleton(LoggerInterface::class, function (): LogManager {
            $this->configure('logging');

            return new LogManager($this);
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerNotificationBindings()
    {
        $this->singleton(ChannelManager::class, function () {
            $this->register(NotificationServiceProvider::class);

            return $this->make(ChannelManager::class);
        });
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
    protected function registerSessionBindings()
    {
        $this->singleton('session', fn () => $this->loadComponent('session', SessionServiceProvider::class, 'session'));
        $this->singleton('session.store', fn () => $this->loadComponent('session', SessionServiceProvider::class, 'session.store'));
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerTranslationBindings()
    {
        $this->singleton('translator', function () {
            $this->configure('app');

            $this->instance('path.lang', $this->getLanguagePath());

            $this->register(TranslationServiceProvider::class);

            return $this->make('translator');
        });
    }

    /**
     * Prepare the given request instance for use with the application.
     *
     * @return Request
     */
    protected function prepareRequest(SymfonyRequest $request)
    {
        if (! $request instanceof Request) {
            $request = Request::createFromBase($request);
        }

        $request->setUserResolver(fn ($guard = null) => $this->make('auth')->guard($guard)->user());

        return $request;
    }

    /**
     * Get the path to the application's language files.
     */
    protected function getLanguagePath(): string
    {
        if (is_dir($langPath = $this->basePath() . '/resources/lang')) {
            return $langPath;
        }

        return __DIR__ . '/../resources/lang';
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerValidatorBindings()
    {
        $this->singleton('validator', function () {
            $this->register(ValidationServiceProvider::class);

            return $this->make('validator');
        });
    }

    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerViewBindings()
    {
        $this->singleton('view', fn () => $this->loadComponent('view', ViewServiceProvider::class, 'view'));
        $this->singleton('view.engine.resolver', fn () => $this->loadComponent('view', ViewServiceProvider::class, 'view.engine.resolver'));
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
            $appConfigDir = $this->basePath('config') . '/';

            if (file_exists($appConfigDir)) {
                return $appConfigDir;
            }
            if (file_exists($path = __DIR__ . '/../config/')) {
                return $path;
            }
        } else {
            $appConfigPath = $this->basePath('config') . '/' . $name . '.php';

            if (file_exists($appConfigPath)) {
                return $appConfigPath;
            }
            if (file_exists($path = __DIR__ . '/../config/' . $name . '.php')) {
                return $path;
            }
        }

        return null;
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
            \Illuminate\Support\Facades\Cache::class     => 'Cache',
            \Illuminate\Support\Facades\DB::class        => 'DB',
            \Illuminate\Support\Facades\Event::class     => 'Event',
            \Illuminate\Support\Facades\Log::class       => 'Log',
            \Illuminate\Support\Facades\Queue::class     => 'Queue',
            \Illuminate\Support\Facades\Schema::class    => 'Schema',
            \Illuminate\Support\Facades\Storage::class   => 'Storage',
            \Illuminate\Support\Facades\Validator::class => 'Validator',
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
        return $this->basePath . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the database directory.
     */
    public function databasePath(?string $path = ''): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'database' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the language files.
     */
    public function langPath(string $path = ''): string
    {
        return $this->getLanguagePath() . ($path !== '' ? DIRECTORY_SEPARATOR . $path : '');
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
     * Determine if the application events are cached.
     */
    public function eventsAreCached(): bool
    {
        return false;
    }

    /**
     * Determine if the application is running in the console.
     */
    public function runningInConsole(): bool
    {
        return \PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg';
    }

    /**
     * Determine if we are running unit tests.
     */
    public function runningUnitTests(): bool
    {
        return $this->environment() == 'testing';
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
     * Get the application namespace.
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public function getNamespace()
    {
        if (null !== $this->namespace) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents($this->basePath('composer.json')), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath($this->path()) == realpath($this->basePath() . '/' . $pathChoice)) {
                    return $this->namespace = $namespace;
                }
            }
        }

        throw new RuntimeException('Unable to detect application namespace.');
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

        static::$instance          = null;
        static::$aliasesRegistered = false;
    }

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this['config']->get('app.locale');
    }

    /**
     * Get the current application fallback locale.
     *
     * @return string
     */
    public function getFallbackLocale()
    {
        return $this['config']->get('app.fallback_locale');
    }

    /**
     * Set the current application locale.
     *
     * @param string $locale
     */
    public function setLocale($locale): void
    {
        $this['config']->set('app.locale', $locale);
        $this['translator']->setLocale($locale);
    }

    /**
     * Set the current application fallback locale.
     *
     * @param string $fallbackLocale
     */
    public function setFallbackLocale($fallbackLocale): void
    {
        $this['config']->set('app.fallback_locale', $fallbackLocale);
        $this['translator']->setFallback($fallbackLocale);
    }

    /**
     * Determine if application locale is the given locale.
     *
     * @param string $locale
     */
    public function isLocale($locale): bool
    {
        return $this->getLocale() == $locale;
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
            \Illuminate\Contracts\Auth\Factory::class               => 'auth',
            \Illuminate\Contracts\Auth\Guard::class                 => 'auth.driver',
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
            'log'                                                   => LoggerInterface::class,
            \Illuminate\Contracts\Notifications\Dispatcher::class   => ChannelManager::class,
            \Illuminate\Contracts\Notifications\Factory::class      => ChannelManager::class,
            \Illuminate\Contracts\Queue\Factory::class              => 'queue',
            \Illuminate\Contracts\Queue\Queue::class                => 'queue.connection',
            'request'                                               => Request::class,
            \Illuminate\Contracts\Translation\Translator::class     => 'translator',
            \Illuminate\Contracts\Validation\Factory::class         => 'validator',
            \Illuminate\Contracts\View\Factory::class               => 'view',
            \Illuminate\View\ViewFinderInterface::class             => 'view.finder',
        ];
    }
}
