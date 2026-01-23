<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SitemapService;
use Illuminate\Support\Facades\File;
use App\Country;
use URL;
use Session;

class SitemapController extends Controller
{
    /**
     * The SitemapService instance.
     *
     * @var SitemapService
     */
    protected $sitemapService;

    /**
     * Create a new controller instance.
     *
     * @param SitemapService $sitemapService
     * @return void
     */
    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * Trigger sitemap generation.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate()
    {
        $country = Country::where("country_code",Session::get("country_code"))->first();
        try {
            
            $this->sitemapService->generateSitemapsForCountry($country);

            return response()->json([
                'message' => 'Sitemap generation started successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sitemap generation failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Serve the requested sitemap file based on the domain.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $sitemapName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function serveSitemap(Request $request, $sitemapName = 'sitemap.xml')
	{
	    // Get the current scheme and host
	    $scheme = $request->getScheme(); // 'http' or 'https'
	    $host = $request->getHost();     // e.g., 'mobilekishop.net' or 'us.mobilekishop.net'

	    // Build the full domain URL
	    $domain = "{$scheme}://{$host}/";
	    
	    // Find the country by full domain
	    $country = Country::where('domain', $domain)->first();
        
	    // Debugging line - remove or comment out in production
	    // dd($country);

	    if (!$country) {
	        abort(404, 'Country not found.');
	    }

	    $countryCode = $country->country_code;

	    // Check if the sitemap name is not the default 'sitemap.xml', and append '.xml'
	    if ($sitemapName !== 'sitemap.xml' && !str_ends_with($sitemapName, '.xml')) {
	        $sitemapName .= '.xml';
	        $sitemapName = 'sitemap-'.$sitemapName;
	    }
	    
	    // Define the path to the requested sitemap file
	    $sitemapPath = public_path("sitemaps/{$countryCode}/{$sitemapName}");
	    
	    if (!File::exists($sitemapPath)) {
	        abort(404, 'Sitemap not found.');
	    }

	    // Determine the content type based on file extension
	    $mimeType = File::mimeType($sitemapPath);

	    // Serve the sitemap file
	    return response()->file($sitemapPath, [
	        'Content-Type' => $mimeType,
	    ]);
	}

    public function htmlSitemap() {
        $country = app('App\Http\Controllers\CountryController')->getCountry();

        // Define $metas as an instance of stdClass
        $metas = new \stdClass();
        $metas->title = "HTML Sitemap - Mobilekishop";
        $metas->description = "HTML Sitemap - Mobilekishop";
        $metas->canonical = URL::full();

        // Pass variables to the view
        return view("frontend.pages.html-sitemap", compact('country', 'metas'));
    }
}
