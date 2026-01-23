<?php

namespace App\Services;

use App\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CountryService
{
    /**
     * Get the current country based on the request URL
     */
    public function getCurrentCountry(Request $request): Country
    {
        $countryCode = $this->extractCountryCodeFromUrl($request);

        $country = Country::where('country_code', $countryCode)->first();

        // Fallback to Pakistan if country not found
        if (!$country) {
            $country = Country::where('country_code', 'pk')->first();
        }

        return $country;
    }

    /**
     * Extract country code from URL path
     */
    private function extractCountryCodeFromUrl(Request $request): string
    {
        $pathSegments = explode('/', trim($request->path(), '/'));
        $firstSegment = $pathSegments[0] ?? null;

        $allowedCountries = $this->getAllowedCountryCodes();

        return in_array($firstSegment, $allowedCountries) ? $firstSegment : 'pk';
    }

    /**
     * Get all allowed country codes (cached for performance)
     */
    private function getAllowedCountryCodes(): array
    {
        return Cache::remember('allowed_country_codes', 3600, function () {
            return Country::pluck('country_code')->toArray();
        });
    }

    /**
     * Get country by code
     */
    public function getCountryByCode(string $countryCode): ?Country
    {
        return Country::where('country_code', $countryCode)->first();
    }

    /**
     * Get all active countries for menu
     */
    public function getMenuCountries()
    {
        return Country::where('is_menu', 1)->get();
    }
}
