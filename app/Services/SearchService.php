<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class SearchService
{
    /**
     * Search products by query
     */
    public function searchProducts(string $query, Country $country, int $perPage = 32)
    {
        $products = Product::whereHas('variants', function ($q) use ($country) {
            $q->where('country_id', $country->id)
                ->where('price', '>', 0);
        })->with([
                    'variants' => function ($q) use ($country) {
                        $q->where('country_id', $country->id)
                            ->where('price', '>', 0);
                    },
                    'brand',
                    'category',
                    'attributes' => function ($q) {
                        $q->whereIn('attributes.name', ['size', 'chipset', 'main', 'capacity', 'battery']);
                    },
                ]);

        $products = $products->search($query);

        return $products->simplePaginate($perPage);
    }

    /**
     * Log search query
     */
    public function logSearch(string $query): void
    {
        try {
            $agent = new Agent();

            DB::table('searches')->insert([
                'query' => $query,
                'user_agent' => "User Agent: browser:" . $agent->browser() .
                    " platform:" . $agent->platform() .
                    " device:" . $agent->device(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Don't let search logging failures break the search page
            \Log::warning('Failed to log search query: ' . $e->getMessage());
        }
    }
}
