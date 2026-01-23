<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HandleRedirections
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $currentUrl = $request->fullUrl();
        $redirection = DB::table('redirections')->where('from_url', $currentUrl)->first();

        if ($redirection) {
            return redirect()->to($redirection->to_url, 301);
        }

        return $next($request);
    }
}
