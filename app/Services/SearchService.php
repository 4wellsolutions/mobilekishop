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
        });

        $products = $products->search($query);

        return $products->simplePaginate($perPage);
    }

    /**
     * Log search query
     */
    public function logSearch(string $query): void
    {
        $agent = new Agent();

        DB::table('searches')->insert([
            'query' => $query,
            'user_agent' => "User Agent: browser:" . $agent->browser() .
                " platform:" . $agent->platform() .
                " device:" . $agent->device(),
        ]);
    }
}
