<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Orchestra\Workbench\Http\Controllers\WorkbenchController;

Route::group([
    'prefix' => '_workbench',
    'middleware' => 'web',
], static function (Router $router) {
    $router->get(
        '/', [WorkbenchController::class, 'start']
    )->name('workbench.start');

    $router->get(
        '/login/{userId}/{guard?}', [WorkbenchController::class, 'login']
    )->name('workbench.login');

    $router->get(
        '/logout/{guard?}', [WorkbenchController::class, 'logout']
    )->name('workbench.logout');

    $router->get(
        '/user/{guard?}', [WorkbenchController::class, 'user']
    )->name('workbench.user');
});
