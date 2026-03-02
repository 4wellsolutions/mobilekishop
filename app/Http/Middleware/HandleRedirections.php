<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HandleRedirections
{
    /**
     * Handle an incoming request.
     * 
     * Checks if the current URL has a redirect configured.
     * Redirections are cached for 1 hour to avoid hitting the DB on every request.
     * Uses both a full-URL map and a path-only map for O(1) lookups.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply redirections to GET requests (skip POST/PUT/DELETE to avoid CSRF issues)
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

        // Skip dashboard routes — they don't need URL redirections
        if (str_starts_with($request->path(), 'dashboard')) {
            return $next($request);
        }

        $currentUrl = $request->fullUrl();
        $currentPath = '/' . ltrim($request->path(), '/');

        // Cache all redirections as two lookup maps: by full URL and by path
        // Refreshes every hour or when cache is manually cleared
        $maps = Cache::remember('url_redirections_maps', 3600, function () {
            $redirections = DB::table('redirections')->pluck('to_url', 'from_url')->toArray();

            $byUrl = $redirections;
            $byPath = [];

            foreach ($redirections as $fromUrl => $toUrl) {
                $fromPath = parse_url($fromUrl, PHP_URL_PATH);
                if ($fromPath) {
                    $byPath[$fromPath] = $toUrl;
                }
            }

            return ['by_url' => $byUrl, 'by_path' => $byPath];
        });

        // Check exact full URL match (O(1) hash lookup)
        if (isset($maps['by_url'][$currentUrl])) {
            return redirect()->to($maps['by_url'][$currentUrl], 301);
        }

        // Check by path (O(1) hash lookup instead of O(n) loop)
        if (isset($maps['by_path'][$currentPath])) {
            return redirect()->to($maps['by_path'][$currentPath], 301);
        }

        return $next($request);
    }
}
