<?php

return [
    /**
     * Package Service Providers...
     */

    /**
     * Application Service Providers...
     */
    \App\Providers\AppServiceProvider::class,
    \App\Providers\AuthServiceProvider::class,
    // \App\Providers\BroadcastServiceProvider::class,
    \App\Providers\EventServiceProvider::class,
    \App\Providers\RouteServiceProvider::class,

    \App\Providers\JsonApiPaginateServiceProvider::class,
    \App\Providers\NoCaptchaServiceProvider::class,
    \App\Providers\ViewServiceProvider::class,
];
