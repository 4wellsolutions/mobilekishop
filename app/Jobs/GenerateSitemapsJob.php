<?php

namespace App\Jobs;

use App\Models\Country;
use App\Services\SitemapService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateSitemapsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600; // 10 minutes max

    protected ?int $countryId;

    /**
     * @param int|null $countryId  Pass null to regenerate ALL countries + master index
     */
    public function __construct(?int $countryId = null)
    {
        $this->countryId = $countryId;
    }

    public function handle(SitemapService $sitemapService): void
    {
        if ($this->countryId) {
            // Single country
            $country = Country::findOrFail($this->countryId);
            Log::info("Sitemap job: generating for {$country->country_name}");
            $sitemapService->generateSitemapsForCountry($country);
            Log::info("Sitemap job: done for {$country->country_name}");
        } else {
            // All countries
            $countries = Country::where('is_active', 1)->get();
            $total = $countries->count();
            $done = 0;

            foreach ($countries as $country) {
                try {
                    $sitemapService->generateSitemapsForCountry($country);
                    $done++;
                    Log::info("Sitemap job: {$done}/{$total} — {$country->country_name} done");
                } catch (\Throwable $e) {
                    Log::error("Sitemap job: failed for {$country->country_name}: {$e->getMessage()}");
                }
            }

            // Build master index
            $sitemapService->buildMasterIndex();
            Log::info("Sitemap job: master index built. {$done}/{$total} countries completed.");
        }
    }
}
