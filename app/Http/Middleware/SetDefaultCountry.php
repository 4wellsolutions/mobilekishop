<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Route;
use DB;

class SetDefaultCountry
{    
    public function handle($request, Closure $next)
    {
        $allowedCountries = DB::table('countries')->pluck('country_code')->toArray();

        // Extract subdomain from the request host
        $host = $request->getHost();
        $hostParts = explode('.', $host);

        Route::currentRouteName();

        // Determine the subdomain
        $subdomain = count($hostParts) > 2 ? $hostParts[0] : 'pk';

        // Determine the country code to use
        $routeCountryCode = in_array($subdomain, $allowedCountries) ? $subdomain : 'pk';        

        // Set the country code as a parameter for use in controllers
        $request->route()->setParameter('country_code', $routeCountryCode);

        // Store the country code in the session for subsequent requests
        session(['country_code' => $routeCountryCode]);

        // **NEW CODE:** Fetch the country object and share it with all views.
        $country = \App\Country::where('country_code', $routeCountryCode)->first();
        \View::share('country', $country);

        return $next($request);
    }
}
