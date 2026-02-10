<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Services\SitemapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SitemapController extends Controller
{
    protected SitemapService $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * Display sitemap management page with per-country file listings.
     */
    public function index()
    {
        $countries = Country::where('is_active', 1)->orderBy('country_name')->get();
        $basePath = public_path('sitemaps');

        $totalFiles = 0;
        $totalSize = 0;
        $countryData = [];

        foreach ($countries as $country) {
            $dir = "{$basePath}/{$country->country_code}";
            $files = [];

            if (File::isDirectory($dir)) {
                foreach (File::files($dir) as $file) {
                    if (strtolower($file->getExtension()) !== 'xml') {
                        continue;
                    }

                    $size = $file->getSize();
                    $urlCount = 0;

                    try {
                        $content = File::get($file->getPathname());
                        $urlCount += substr_count($content, '<url>');
                        $urlCount += substr_count($content, '<sitemap>');
                    } catch (\Throwable $e) {
                        // Ignore parse errors
                    }

                    $files[] = [
                        'name' => $file->getFilename(),
                        'size' => $size,
                        'url_count' => $urlCount,
                        'modified' => $file->getMTime(),
                    ];

                    $totalFiles++;
                    $totalSize += $size;
                }

                usort($files, function ($a, $b) {
                    if ($a['name'] === 'sitemap.xml')
                        return -1;
                    if ($b['name'] === 'sitemap.xml')
                        return 1;
                    return strcmp($a['name'], $b['name']);
                });
            }

            $countryData[] = [
                'country' => $country,
                'files' => $files,
            ];
        }

        return view('dashboard.sitemap.index', compact('countryData', 'totalFiles', 'totalSize'));
    }

    /**
     * Regenerate sitemaps for a specific country (AJAX).
     */
    public function generate(Request $request)
    {
        $request->validate(['country_id' => 'required|exists:countries,id']);

        $country = Country::findOrFail($request->country_id);

        try {
            $this->sitemapService->generateSitemapsForCountry($country);

            return response()->json([
                'status' => 'success',
                'message' => "Sitemaps regenerated for {$country->country_name}.",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Failed for {$country->country_name}: {$e->getMessage()}",
            ], 500);
        }
    }

    /**
     * Regenerate sitemaps for all active countries (AJAX).
     */
    public function generateAll()
    {
        $countries = Country::where('is_active', 1)->get();
        $success = [];
        $failed = [];

        foreach ($countries as $country) {
            try {
                $this->sitemapService->generateSitemapsForCountry($country);
                $success[] = $country->country_name;
            } catch (\Throwable $e) {
                $failed[] = "{$country->country_name}: {$e->getMessage()}";
            }
        }

        if (!empty($failed)) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Some failed: ' . implode(', ', $failed),
                'success' => $success,
                'failed' => $failed,
            ], 207);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'All sitemaps regenerated: ' . implode(', ', $success) . '.',
            'success' => $success,
        ]);
    }

    /**
     * Build a single master sitemap index at public/sitemap.xml (AJAX).
     */
    public function createIndex()
    {
        try {
            $included = $this->sitemapService->buildMasterIndex();

            return response()->json([
                'status' => 'success',
                'message' => 'Master sitemap index created with ' . count($included) . ' countries: ' . implode(', ', $included) . '.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Failed to create master index: {$e->getMessage()}",
            ], 500);
        }
    }

    /**
     * Delete a specific sitemap file.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'country_code' => 'required|string|max:5',
            'file' => 'required|string|max:255',
        ]);

        $countryCode = $request->country_code;
        $filename = basename($request->file);
        $filePath = public_path("sitemaps/{$countryCode}/{$filename}");

        if (!File::exists($filePath)) {
            return back()->with('warning', "File not found: {$filename}");
        }

        File::delete($filePath);

        return back()->with('success', "Deleted {$filename} from {$countryCode} sitemaps.");
    }
}
