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

        $middleware->web(replace: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class => \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class => \App\Http\Middleware\VerifyCsrfToken::class,
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
        $exceptions->report(function (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info("Caught exception: " . get_class($e));
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, \Illuminate\Http\Request $request) {
            try {
                $url = $request->fullUrl();
                $userAgent = $request->userAgent() ?? '';

                // Filtering bots and static assets
                $skipExtensions = ['.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.ico', '.svg', '.woff', '.woff2', '.ttf', '.map', '.webp'];
                $path = strtolower($request->path());
                foreach ($skipExtensions as $ext) {
                    if (str_ends_with($path, $ext))
                        return null;
                }

                $botPatterns = ['bot', 'crawler', 'spider', 'slurp', 'mediapartners', 'feedfetcher'];
                $lowerAgent = strtolower($userAgent);
                foreach ($botPatterns as $pattern) {
                    if (str_contains($lowerAgent, $pattern))
                        return null;
                }

                // Deduplication
                $existing = \App\Models\ErrorLog::where('url', $url)->where('error_code', 404)->first();
                if ($existing) {
                    $existing->increment('hit_count');
                    $existing->update([
                        'ip_address' => $request->ip(),
                        'user_agent' => \Illuminate\Support\Str::limit($userAgent, 250),
                        'referer' => \Illuminate\Support\Str::limit($request->header('referer') ?? '', 250),
                    ]);
                    return null;
                }

                \App\Models\ErrorLog::create([
                    'url' => \Illuminate\Support\Str::limit($url, 250),
                    'error_code' => 404,
                    'message' => $e->getMessage() ?: 'Page not found',
                    'ip_address' => $request->ip(),
                    'user_agent' => \Illuminate\Support\Str::limit($userAgent, 250),
                    'referer' => \Illuminate\Support\Str::limit($request->header('referer') ?? '', 250),
                    'hit_count' => 1,
                ]);
            } catch (\Throwable $loggingError) {
                \Illuminate\Support\Facades\Log::error('Failed to log 404 in render: ' . $loggingError->getMessage());
            }

            return null; // Continue to default 404 page
        });
    })
    ->create();
