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

        // Cache all redirections as a lookup map (from_url => to_url)
        // Refreshes every hour or when cache is manually cleared
        $redirections = Cache::remember('url_redirections', 3600, function () {
            return DB::table('redirections')->pluck('to_url', 'from_url')->toArray();
        });

        if (isset($redirections[$currentUrl])) {
            return redirect()->to($redirections[$currentUrl], 301);
        }

        return $next($request);
    }
}
