<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Services\MetaService;
use App\Services\SitemapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SitemapController extends Controller
{
    public function __construct(
        private SitemapService $sitemapService,
        private MetaService $metaService
    ) {
    }

    /**
     * Show HTML Sitemap
     */
    public function htmlSitemap(Request $request)
    {
        $country = $request->attributes->get('country');

        $metas = (object) [
            "title" => "HTML Sitemap - Mobilekishop",
            "description" => "HTML Sitemap - Buy latest Mobile phones in {$country->country_name}.",
            "canonical" => url()->current(),
            "h1" => "Sitemap",
            "name" => "Sitemap"
        ];

        return view("frontend.pages.html-sitemap", compact('country', 'metas'));
    }

    /**
     * Serve XML Sitemaps
     */
    public function serveSitemap(Request $request)
    {
        $country = $request->attributes->get('country');
        $sitemapName = $request->route('sitemap') ?: 'sitemap.xml';

        if (!str_ends_with($sitemapName, '.xml')) {
            $sitemapName .= '.xml';
        }

        $countryCode = $country->country_code;
        $sitemapPath = public_path("sitemaps/{$countryCode}/{$sitemapName}");

        if (!File::exists($sitemapPath)) {
            // Try fallback to sitemap-{name}.xml structure if name doesn't match
            $fallbackPath = public_path("sitemaps/{$countryCode}/sitemap-{$sitemapName}");
            if (File::exists($fallbackPath)) {
                $sitemapPath = $fallbackPath;
            } else {
                abort(404, 'Sitemap not found.');
            }
        }

        return response()->file($sitemapPath, [
            'Content-Type' => 'text/xml',
        ]);
    }
}
