<?php

namespace App\Providers;

use App\Libraries\NoCaptcha;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class NoCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $app = $this->app;

        $app['validator']->extend('captcha', function ($attribute, $value) use ($app) {
            return $app['captcha']->verifyResponse($value, $app['request']->getClientIp());
        });
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('captcha', function ($app) {
            return new NoCaptcha(
                $app['config']['captcha.secret'],
                $app['config']['captcha.sitekey'],
                $app['config']['captcha.options']
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['captcha'];
    }
}