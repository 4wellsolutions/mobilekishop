<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubdomainToPathRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $domain = 'mobilekishop.net';
        $wwwDomain = 'www.mobilekishop.net';

        // Check if host is a subdomain and not www
        if ($host !== $domain && $host !== $wwwDomain && str_ends_with($host, $domain)) {
            // Extract subdomain
            $subdomain = str_replace('.' . $domain, '', $host);

            // Build new URL: mobilekishop.net/{subdomain}/{original_path}
            $newUrl = $request->getScheme() . '://' . $domain . '/' . $subdomain . $request->getRequestUri();

            return redirect($newUrl, 301);
        }

        return $next($request);
    }
}
