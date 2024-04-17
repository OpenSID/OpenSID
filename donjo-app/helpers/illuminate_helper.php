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

use Illuminate\Container\Container;

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param string|null $abstract
     *
     * @return Illuminate\Contracts\Foundation\Application|mixed
     */
    function app($abstract = null, array $parameters = [])
    {
        $ci = &get_instance();

        $container = Container::getInstance();

        $container->singleton('ci', static fn () => $ci);

        if (null === $abstract) {
            return $container;
        }

        return $container->make($abstract, $parameters);
    }
}

if (! function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     */
    function base_path(?string $path = ''): string
    {
        return app()->basePath() . ($path ? '/' . $path : $path);
    }
}

if (! function_exists('bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param string $value
     * @param array  $options
     *
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->driver('bcrypt')->make($value, $options);
    }
}

if (! function_exists('cache')) {
    /**
     * Get / set the specified cache value.
     *
     * If an array is passed, we'll assume you want to put to the cache.
     *
     * @param dynamic  key|key,default|data,expiration|null
     *
     * @throws InvalidArgumentException
     *
     * @return Illuminate\Cache\CacheManager|mixed
     */
    function cache(...$arguments)
    {
        if ($arguments === []) {
            return app('cache');
        }

        if (is_string($arguments[0])) {
            return app('cache')->get(...$arguments);
        }

        if (! is_array($arguments[0])) {
            throw new InvalidArgumentException(
                'When setting a value in the cache, you must pass an array of key / value pairs.'
            );
        }

        return app('cache')->put(key($arguments[0]), reset($arguments[0]), $arguments[1] ?? null);
    }
}

if (! function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param array|string|null $key
     * @param mixed             $default
     *
     * @return mixed
     */
    function config($key = null, $default = null)
    {
        if (null === $key) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (! function_exists('database_path')) {
    /**
     * Get the path to the database directory of the install.
     *
     * @param string $path
     *
     * @return string
     */
    function database_path($path = '')
    {
        return app()->databasePath($path);
    }
}

if (! function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param string $value
     *
     * @return string
     */
    function decrypt($value)
    {
        return app('encrypter')->decrypt($value);
    }
}

if (! function_exists('dispatch')) {
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param mixed $job
     */
    function dispatch($job): object
    {
        return new class ($job) {
            /**
             * The job.
             *
             * @var mixed
             */
            protected $job;

            /**
             * Create a new pending job dispatch.
             *
             * @param mixed $job
             *
             * @return void
             */
            public function __construct($job)
            {
                $this->job = $job;
            }

            /**
             * Set the desired connection for the job.
             *
             * @param string|null $connection
             *
             * @return $this
             */
            public function onConnection($connection): self
            {
                $this->job->onConnection($connection);

                return $this;
            }

            /**
             * Set the desired queue for the job.
             *
             * @param string|null $queue
             *
             * @return $this
             */
            public function onQueue($queue): self
            {
                $this->job->onQueue($queue);

                return $this;
            }

            /**
             * Determine if the job should be dispatched.
             */
            protected function shouldDispatch(): bool
            {
                if (! $this->job instanceof Illuminate\Contracts\Queue\ShouldBeUnique) {
                    return true;
                }

                $uniqueId = method_exists($this->job, 'uniqueId')
                    ? $this->job->uniqueId()
                    : ($this->job->uniqueId ?? '');

                $cache = method_exists($this->job, 'uniqueVia')
                    ? $this->job->uniqueVia()
                    : Container::getInstance()->make('cache');

                return (bool) $cache->lock(
                    $key = 'laravel_unique_job:' . get_class($this->job) . $uniqueId,
                    $this->job->uniqueFor ?? 0
                )->get();
            }

            /**
             * Handle the object's destruction.
             *
             * @return void
             */
            public function __destruct()
            {
                if (! $this->shouldDispatch()) {
                    return;
                }

                app(Illuminate\Contracts\Bus\Dispatcher::class)->dispatch($this->job);
            }
        };
    }
}

if (! function_exists('dispatch_now')) {
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param mixed $job
     * @param mixed $handler
     *
     * @return mixed
     */
    function dispatch_now($job, $handler = null)
    {
        return app(Illuminate\Contracts\Bus\Dispatcher::class)->dispatchNow($job, $handler);
    }
}

if (! function_exists('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param string $value
     *
     * @return string
     */
    function encrypt($value)
    {
        return app('encrypter')->encrypt($value);
    }
}

if (! function_exists('event')) {
    /**
     * Dispatch an event and call the listeners.
     *
     * @param object|string $event
     * @param mixed         $payload
     * @param bool          $halt
     *
     * @return array|null
     */
    function event($event, $payload = [], $halt = false)
    {
        return app('events')->dispatch($event, $payload, $halt);
    }
}

if (! function_exists('resource_path')) {
    /**
     * Get the path to the resources folder.
     *
     * @param string $path
     *
     * @return string
     */
    function resource_path($path = '')
    {
        return app()->resourcePath($path);
    }
}

if ( ! function_exists('response')) {
    /**
     * Response construction helper
     *
     * @param string $content
     * @param int    $statusCode
     * @param array  $headers
     *
     * @return Illuminate\Http\Response|Response
     */
    function response($content = '', $statusCode = 200, $headers = [])
    {
        $responseClass = class_exists(Illuminate\Http\Response::class) ? Illuminate\Http\Response::class : 'Response';

        return new $responseClass($content, $statusCode, $headers);
    }
}

if (! function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param string $path
     *
     * @return string
     */
    function storage_path($path = '')
    {
        return app()->storagePath($path);
    }
}

if (! function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     * @param mixed  $returnView
     *
     * @return Illuminate\View\View
     */
    function view($view = null, $data = [], $mergeData = [], $returnView = false)
    {
        app()->register(App\Providers\ViewServiceProvider::class);

        $factory = app('view');

        if (func_num_args() === 0) {
            return $factory;
        }

        if ($returnView) {
            return $factory->make($view, $data, $mergeData);
        }

        echo $factory->make($view, $data, $mergeData);
    }
}
