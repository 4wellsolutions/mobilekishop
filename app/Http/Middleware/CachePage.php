<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Full-page cache for guest GET requests.
 *
 * Caches the entire HTML response so subsequent hits skip the full
 * Laravel stack (DB queries, view rendering, etc.).
 *
 * Usage in routes:  ->middleware('cache.page')          // 60 min default
 *                   ->middleware('cache.page:120')      // 120 min
 *
 * Clear all page cache:  php artisan cache:page:clear
 *                    or: Cache::tags('page-cache')->flush()  (if tag-aware driver)
 *                    or: php artisan cache:clear
 */
class CachePage
{
    /** Cache key prefix */
    private const PREFIX = 'page_cache:';

    public function handle(Request $request, Closure $next, int $minutes = 60): Response
    {
        // Only cache GET requests for guests
        if (!$request->isMethod('GET') || Auth::check() || $request->ajax()) {
            return $next($request);
        }

        // Skip caching for search queries to prevent cache explosion
        if ($request->has('query') || $request->routeIs('search') || $request->routeIs('search.*')) {
            return $next($request);
        }

        // Skip if there are flash/session messages (validation errors, notices)
        if ($request->session()->has('errors') || $request->session()->has('status')) {
            return $next($request);
        }

        $key = $this->cacheKey($request);

        // Serve from cache if available
        $cached = Cache::get($key);
        if ($cached) {
            return response($cached['content'], $cached['status'])
                ->withHeaders(array_merge($cached['headers'], [
                    'X-Page-Cache' => 'HIT',
                ]));
        }

        // Process the request
        $response = $next($request);

        // Only cache successful HTML responses
        if (
            $response->getStatusCode() === 200 &&
            str_contains($response->headers->get('Content-Type', ''), 'text/html') &&
            !$response->headers->has('X-No-Page-Cache')
        ) {
            Cache::put($key, [
                'content' => $response->getContent(),
                'status' => $response->getStatusCode(),
                'headers' => [
                    'Content-Type' => $response->headers->get('Content-Type'),
                ],
            ], now()->addMinutes($minutes));

            $response->headers->set('X-Page-Cache', 'MISS');
        }

        return $response;
    }

    /**
     * Build a unique cache key from the URL with sorted query params and tracking params removed.
     */
    private function cacheKey(Request $request): string
    {
        $queryParams = $request->query();

        // 1. Remove common tracking parameters
        $trackingParams = [
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
            'fbclid',
            'gclid',
            'ref',
            '_ga',
            'gclsrc'
        ];

        foreach ($trackingParams as $param) {
            unset($queryParams[$param]);
        }

        // 2. Sort parameters by key to ensure ?a=1&b=2 and ?b=2&a=1 generate the same key
        ksort($queryParams);

        // 3. Rebuild the URL
        $path = $request->path();
        $queryString = http_build_query($queryParams);
        $fullUrl = $request->getSchemeAndHttpHost() . '/' . trim($path, '/') . ($queryString ? '?' . $queryString : '');

        return self::PREFIX . md5($fullUrl);
    }
}
