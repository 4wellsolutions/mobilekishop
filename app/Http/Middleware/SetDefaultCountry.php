<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\CountryService;
use Route;

class SetDefaultCountry
{
    public function __construct(private CountryService $countryService)
    {
    }

    public function handle($request, Closure $next)
    {
        // Get country using the service
        $country = $this->countryService->getCurrentCountry($request);

        // Set the country code as a route parameter for use in controllers
        $request->route()->setParameter('country_code', $country->country_code);

        // Store in session for backward compatibility (will be removed later)
        session(['country_code' => $country->country_code]);

        // Share country with all views
        \View::share('country', $country);

        // Store in request attributes for easy access in controllers
        $request->attributes->set('country', $country);

        return $next($request);
    }
}
