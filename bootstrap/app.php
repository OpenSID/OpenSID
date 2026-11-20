<?php

// bootstrap/app.php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        then: function () {
            // Load internal API routes from a separate file
            Route::middleware('api')
                ->prefix('internal_api')
                ->group(base_path('routes/internal-api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->throttleApi();

        $middleware->remove(\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class);

        $middleware->web(
            append: [
                \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            ],
            replace: [
                \Illuminate\Cookie\Middleware\EncryptCookies::class => \App\Http\Middleware\EncryptCookies::class,
                \Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class => \App\Http\Middleware\VerifyCsrfToken::class,
            ],
        );
        $middleware->api(
            append: [
                \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            ],
        );

        $middleware->alias([
            'auth'   => \App\Http\Middleware\Authenticate::class,
            'guest'  => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'can'    => \App\Http\Middleware\Authorize::class,
        ]);

        $middleware->group('admin', [
            'auth:admin',
            \App\Http\Middleware\EnsureDesaInstalled::class,
            \App\Http\Middleware\CheckDesaSetupCompletion::class,
            \App\Http\Middleware\ShareAnjungan::class,
            \App\Http\Middleware\ShareAdminModul::class,
            \App\Http\Middleware\ShareDesaData::class,
            \App\Http\Middleware\CheckDatabaseMigrations::class,
            \App\Http\Middleware\TrackDesaActivity::class,
        ]);

        $middleware->group('codeigniter', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AuthenticateSession::class,
            \App\Http\Middleware\CodeIgniterResponseMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
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
