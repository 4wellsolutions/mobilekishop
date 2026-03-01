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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $currentUrl = $request->fullUrl();
        $currentPath = '/' . ltrim($request->path(), '/');

        // Cache all redirections as a lookup map
        // Refreshes every hour or when cache is manually cleared
        $redirections = Cache::remember('url_redirections', 3600, function () {
            return DB::table('redirections')->pluck('to_url', 'from_url')->toArray();
        });

        // Check exact full URL match first
        if (isset($redirections[$currentUrl])) {
            return redirect()->to($redirections[$currentUrl], 301);
        }

        // Also check by path — extract path from stored from_url and compare
        foreach ($redirections as $fromUrl => $toUrl) {
            $fromPath = parse_url($fromUrl, PHP_URL_PATH);
            if ($fromPath && $fromPath === $currentPath) {
                return redirect()->to($toUrl, 301);
            }
        }

        return $next($request);
    }
}
