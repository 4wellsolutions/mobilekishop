<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\ErrorLog;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            $this->log404($request, $e);
        });
    }

    /**
     * Log a 404 error to the database.
     */
    protected function log404($request, NotFoundHttpException $e): void
    {
        try {
            $url = $request->fullUrl();
            $userAgent = $request->userAgent() ?? '';


            // Skip static asset requests to avoid noise
            $skipExtensions = ['.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.ico', '.svg', '.woff', '.woff2', '.ttf', '.map', '.webp'];
            $path = strtolower($request->path());
            foreach ($skipExtensions as $ext) {
                if (str_ends_with($path, $ext)) {
                    return;
                }
            }

            // Skip common bot/crawler user agents
            $botPatterns = ['bot', 'crawler', 'spider', 'slurp', 'mediapartners', 'feedfetcher'];
            $lowerAgent = strtolower($userAgent);
            foreach ($botPatterns as $pattern) {
                if (str_contains($lowerAgent, $pattern)) {
                    return;
                }
            }

            // Use updateOrCreate to deduplicate by URL
            $existing = ErrorLog::where('url', $url)
                ->where('error_code', 404)
                ->first();

            if ($existing) {
                $existing->increment('hit_count');
                $existing->update([
                    'ip_address' => $request->ip(),
                    'user_agent' => \Illuminate\Support\Str::limit($userAgent, 250),
                    'referer' => \Illuminate\Support\Str::limit($request->header('referer') ?? '', 250),
                ]);
            } else {
                ErrorLog::create([
                    'url' => \Illuminate\Support\Str::limit($url, 250),
                    'error_code' => 404,
                    'message' => $e->getMessage() ?: 'Page not found',
                    'ip_address' => $request->ip(),
                    'user_agent' => \Illuminate\Support\Str::limit($userAgent, 250),
                    'referer' => \Illuminate\Support\Str::limit($request->header('referer') ?? '', 250),
                    'hit_count' => 1,
                ]);
            }
        } catch (\Throwable $ex) {
            // Silently fail — don't break the 404 page if logging fails
            \Illuminate\Support\Facades\Log::error('Failed to log 404: ' . $ex->getMessage());
        }
    }
}
