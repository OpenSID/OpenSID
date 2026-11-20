<?php

namespace App\Providers;

use App\Events\CallingController;
use App\Events\ControllerCalled;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Factory;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            $this->app->rebinding('request', function ($app, $request) {
                if ($this->isMatchedRouteLaravel($request)) {
                    return;
                }

                $this->extendControllerDispatcher();
                $this->extendRedirectInstance();
                $this->extendViewInstance();
                $this->configureConfig();

                global $EXT, $URI, $RTR, $OUT, $SEC, $IN;

                // We need to ignore certain classes when assigning its because
                // when running on unit tests, these classes is crashed to
                // load properly and causes fatal errors
                is_loaded(unsetClass: ['cache', 'form_validation', 'session']);

                // Call the pre_system hook to replicate CI booting process
                $EXT->call_hook('pre_system');

                // Sync the request instance to CodeIgniter core classes
                $URI->setRequest($request);
                $RTR->setRequest($request);
                $OUT->setRequest($request);
                $SEC->setRequest($request);
                $IN->setRequest($request);

                Route::middleware('codeigniter')
                    ->group(base_path('bootstrap/routeCodeIgniter.php'));
            });
        });
    }

    /**
     * Checks if the current HTTP request matches any registered Laravel route.
     */
    protected function isMatchedRouteLaravel(Request $request): bool
    {
        return rescue(fn () => (bool) Route::getRoutes()->match($request), false);
    }

    /**
     * Extend the ControllerDispatcher to handle legacy code without return.
     */
    protected function extendControllerDispatcher()
    {
        $this->app->singleton(\Illuminate\Routing\Contracts\ControllerDispatcher::class, function ($app) {
            return new class($app) extends \Illuminate\Routing\ControllerDispatcher {
                /**
                 * Dispatch a request to a given controller and method.
                 */
                public function dispatch(\Illuminate\Routing\Route $route, $controller, $method)
                {
                    $parameters = $this->resolveParameters($route, $controller, $method);

                    $this->container['events']->dispatch(
                        new CallingController($route, $controller, $method, $parameters, request())
                    );

                    $obLevelBefore = ob_get_level();

                    ob_start();

                    try {
                        $response = (function () use ($controller, $method, $parameters) {
                            if (method_exists($controller, 'callAction')) {
                                return $controller->callAction($method, $parameters);
                            }

                            return $controller->{$method}(...array_values($parameters));
                        })();
                    } finally {
                        $buffered = ob_get_level() > $obLevelBefore
                            ? ob_get_clean()
                            : null;
                    }

                    if ($response === null && $buffered !== '' && $buffered !== null && $buffered !== false) {
                        $response = $this->buildResponseFromBuffer($buffered);
                    }

                    if ($response === null) {
                        $response = $this->resolvePendingResponse();
                    }

                    $this->container['events']->dispatch(
                        new ControllerCalled($route, $controller, $method, $parameters, request(), $response)
                    );

                    return $response;
                }

                /**
                 * Build response dari output buffer
                 */
                protected function buildResponseFromBuffer(string $buffered): \Illuminate\Http\Response
                {
                    $headers = [];
                    foreach (headers_list() as $header) {
                        [$key, $value] = explode(':', $header, 2);
                        $headers[trim($key)] = trim($value);
                    }

                    return response($buffered)->withHeaders($headers);
                }

                /**
                 * Resolve pending response dari view() atau redirect() tanpa return.
                 */
                protected function resolvePendingResponse()
                {
                    // Check pending view
                    $viewFactory = $this->container['view'];
                    if (method_exists($viewFactory, 'getPendingView')) {
                        $view = $viewFactory->getPendingView();
                        if ($view !== null) {
                            $viewFactory->clearPendingView();

                            return $view;
                        }
                    }
                }
            };
        });
    }

    /**
     * Extend the Redirector instance to handle CodeIgniter responses.
     */
    protected function extendRedirectInstance()
    {
        $this->app->extend('redirect', function () {
            $redirector = new class($this->app['url']) extends Redirector {
                /**
                 * {@inheritDoc}
                 */
                protected function createRedirect($path, $status, $headers)
                {
                    if ($status === 'refresh') {
                        $headers['Refresh'] = "0; url='{$path}'";
                        $status = 302;
                    }

                    $redirect = new RedirectResponse($path, $status, $headers);

                    if (isset($this->session)) {
                        $redirect->setSession($this->session);
                    }

                    $redirect->setRequest($this->generator->getRequest());

                    throw new class ($redirect) extends \Error implements Responsable {
                        public function __construct(private RedirectResponse $response)
                        {
                            parent::__construct('Redirect');
                        }

                        public function toResponse($request): RedirectResponse
                        {
                            return $this->response;
                        }

                        public function report(): bool
                        {
                            return true;
                        }
                    };
                }
            };

            // If the session is set on the application instance, we'll inject it into
            // the redirector instance. This allows the redirect responses to allow
            // for the quite convenient "with" methods that flash to the session.
            if (isset($this->app['session.store'])) {
                $redirector->setSession($this->app['session.store']);
            }

            return $redirector;
        });
    }

    /**
     * Extend the View instance to handle pending view responses.
     */
    protected function extendViewInstance()
    {
        $this->app->extend('view', function ($factory, $app) {
            return new class($factory->getEngineResolver(), $factory->getFinder(), $app['events']) extends Factory {
                protected null|ViewContract|ViewFactoryContract $pendingView = null;

                public function getPendingView()
                {
                    return $this->pendingView;
                }

                public function clearPendingView()
                {
                    $this->pendingView = null;

                    return $this;
                }

                public function make($view, $data = [], $mergeData = [])
                {
                    $viewInstance = parent::make($view, $data, $mergeData);
                    $this->pendingView = $viewInstance;

                    return $viewInstance;
                }
            };
        });
    }

    /**
     * Configure application settings from CodeIgniter controller settings.
     */
    protected function configureConfig()
    {
        Event::listen(CallingController::class, function (CallingController $event) {
            $setting = $event->controller->setting ?? null;

            if (! $setting) {
                return;
            }

            // Configure email
            if (! empty($setting->email_smtp_host)) {
                $protocol = $setting->email_protocol ?: 'smtp';
                config([
                    'mail.default' => $protocol,
                    "mail.mailers.{$protocol}" => [
                        'transport'    => $protocol,
                        'host'         => $setting->email_smtp_host,
                        'port'         => $setting->email_smtp_port,
                        'username'     => $setting->email_smtp_user,
                        'password'     => $setting->email_smtp_pass,
                    ],
                ]);
            }

            // Configure Telegram
            if (! empty($setting->telegram_token)) {
                config(['services.telegram-bot-api.token' => $setting->telegram_token]);
            }

            // Configure reCAPTCHA
            if (! empty($setting->google_recaptcha_secret_key)) {
                config([
                    'captcha' => [
                        'secret'          => $setting->google_recaptcha_secret_key,
                        'sitekey'         => $setting->google_recaptcha_site_key,
                        'options'         => [],
                        'version'         => $setting->google_recaptcha_version ?? 'v2',
                        'score_threshold' => $setting->google_recaptcha_score_threshold ?? 0.5,
                    ],
                ]);
            }
        });
    }
}
