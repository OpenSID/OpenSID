<?php

// bootstrap/app.php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$builder = new ApplicationBuilder(new Application(dirname(__DIR__)));
$app = $builder->create();

$app->singleton(
    \Illuminate\Contracts\Http\Kernel::class,
    \App\Http\Kernel::class,
);

$app->singleton(
    \Illuminate\Contracts\Console\Kernel::class,
    \App\Console\Kernel::class,
);

return $builder
    ->withEvents(discover: false)
    ->withCommands()
    ->withProviders()
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->remove(\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class);

        $middleware->web(
            append: [\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class],
        );
        $middleware->api(
            append: [\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class],
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->renderable(function (\PDOException $e, Request $request) {
            $code = (int) $e->getCode();
            if (in_array($code, [1049, 2002, 2006]) || $e->getCode() === 'HY000') {
                logger()->error('Database connection error: ' . $e->getMessage());
                session(['db_error' => ['code' => 1049, 'message' => $e->getMessage()]]);

                if (str_contains($request->getRequestUri(), 'koneksi_database')) {
                    return null;
                }

                return redirect(url('koneksi_database'));
            }
        });
    })
    ->create();
