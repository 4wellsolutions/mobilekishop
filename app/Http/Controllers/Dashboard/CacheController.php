<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class CacheController extends Controller
{
    /**
     * Show cache management page with stats.
     */
    public function index()
    {
        $stats = $this->getCacheStats();
        return view('dashboard.cache.index', compact('stats'));
    }

    /**
     * Clear selected cache types.
     */
    public function clear(Request $request)
    {
        $types = $request->input('types', []);
        $cleared = [];

        if (in_array('page', $types)) {
            $this->clearPageCache();
            $cleared[] = 'Page Cache';
        }

        if (in_array('application', $types)) {
            Artisan::call('cache:clear');
            $cleared[] = 'Application Cache';
        }

        if (in_array('views', $types)) {
            Artisan::call('view:clear');
            $cleared[] = 'Compiled Views';
        }

        if (in_array('routes', $types)) {
            Artisan::call('route:clear');
            $cleared[] = 'Route Cache';
        }

        if (in_array('config', $types)) {
            Artisan::call('config:clear');
            $cleared[] = 'Config Cache';
        }

        if (empty($cleared)) {
            return back()->with('warning', 'No cache type selected.');
        }

        return back()->with('success', 'Cleared: ' . implode(', ', $cleared));
    }

    /**
     * Clear ALL caches at once.
     */
    public function clearAll()
    {
        $this->clearPageCache();
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');

        return back()->with('success', 'All caches cleared successfully!');
    }

    /**
     * Remove only page-cache entries from file cache.
     */
    private function clearPageCache(): void
    {
        $cachePath = storage_path('framework/cache/data');
        if (File::isDirectory($cachePath)) {
            // Page cache keys start with 'page_cache:' — the file cache stores
            // them as serialized data. Safest approach: flush the entire file cache.
            // For a more surgical approach, we'd need a tagged cache driver (Redis).
            Cache::flush();
        }
    }

    /**
     * Gather cache statistics for the dashboard.
     */
    private function getCacheStats(): array
    {
        $stats = [];

        // Page cache files (file driver)
        $cachePath = storage_path('framework/cache/data');
        if (File::isDirectory($cachePath)) {
            $files = File::allFiles($cachePath);
            $stats['cache_files'] = count($files);
            $stats['cache_size'] = collect($files)->sum(fn($f) => $f->getSize());
        } else {
            $stats['cache_files'] = 0;
            $stats['cache_size'] = 0;
        }

        // Compiled views
        $viewsPath = storage_path('framework/views');
        if (File::isDirectory($viewsPath)) {
            $viewFiles = File::files($viewsPath);
            $stats['view_files'] = count($viewFiles);
            $stats['view_size'] = collect($viewFiles)->sum(fn($f) => $f->getSize());
        } else {
            $stats['view_files'] = 0;
            $stats['view_size'] = 0;
        }

        // Route cache
        $stats['route_cached'] = File::exists(base_path('bootstrap/cache/routes-v7.php'));

        // Config cache
        $stats['config_cached'] = File::exists(base_path('bootstrap/cache/config.php'));

        // Cache driver
        $stats['cache_driver'] = config('cache.default');

        return $stats;
    }

    /**
     * Build the route cache.
     */
    public function buildRoute()
    {
        Artisan::call('route:cache');
        return back()->with('success', 'Route cache built successfully!');
    }

    /**
     * Build the config cache.
     */
    public function buildConfig()
    {
        Artisan::call('config:cache');
        return back()->with('success', 'Config cache built successfully!');
    }
}
