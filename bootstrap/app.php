<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(


        using: function () {

            // Dashboard routes first — must load before catch-all web routes
            Route::middleware('web')
                ->group(base_path('routes/dashboard.php'));

            Route::middleware('web')
                ->group(base_path('routes/web_v2.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/console.php')); // console routes often web or command
        },
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            \App\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\SubdomainToPathRedirect::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->api(prepend: [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'default.country' => \App\Http\Middleware\SetDefaultCountry::class,
            'handle.redirections' => \App\Http\Middleware\HandleRedirections::class,
            'cache.page' => \App\Http\Middleware\CachePage::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $e) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $statusCode = $e->getStatusCode();
                if (in_array($statusCode, [404, 500])) {
                    try {
                        \App\ErrorLog::create([
                            'url' => Request::fullUrl(),
                            'error_code' => $statusCode,
                            'message' => $e->getMessage() ?: ($statusCode == 404 ? 'Page not found' : 'Internal server error')
                        ]);
                    } catch (\Throwable $loggingError) {
                        // Prevent infinite loop if DB logging fails
                    }
                }
            }
        });
    })
    ->create();
