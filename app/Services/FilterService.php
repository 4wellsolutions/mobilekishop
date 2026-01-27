<?php

namespace App\Services;

use App\Product;
use App\Category;
use Illuminate\Database\Eloquent\Builder;

class FilterService
{
    /**
     * Get products under a specific price (Mobile Phones)
     */
    public function getProductsUnderPrice(int $amount, string $countryCode): Builder
    {
        // ID 1 for Mobile Phones
        return Product::where('category_id', 1)
            ->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_code', $countryCode)
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })
            ->with([
                'variants' => function ($query) use ($countryCode) {
                    $query->where('country_code', $countryCode);
                }
            ]);
    }

    /**
     * Get products by RAM size
     */
    public function getProductsByRam(int $ram): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($ram) {
                $query->where('attribute_id', 76)
                    ->where('value', 'like', $ram . 'GB');
            })->with(['variants']);
    }

    /**
     * Get products by ROM/Storage size
     */
    public function getProductsByRom(int $rom): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($rom) {
                $query->where('attribute_id', 77)
                    ->where('value', 'like', $rom . 'GB');
            })->with(['variants']);
    }

    /**
     * Get products by RAM and ROM combination
     */
    public function getProductsByRamRom(int $ram, int $rom): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($ram) {
                $query->where('attribute_id', 76)
                    ->where('value', $ram . 'GB');
            })->whereHas('attributes', function ($query) use ($rom) {
                $query->where('attribute_id', 77)
                    ->where('value', $rom . 'GB');
            })->with(['variants']);
    }

    /**
     * Get products by screen size range
     */
    public function getProductsByScreenSize(float $maxSize): Builder
    {
        $ranges = [
            4 => [0, 4],
            5 => [4.1, 5],
            6 => [5.1, 6],
            7 => [6.1, 7],
            8 => [7.1, 8],
        ];

        if (!array_key_exists($maxSize, $ranges)) {
            abort(404, 'Invalid screen size');
        }

        $range = $ranges[$maxSize];

        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($range) {
                $query->where('attribute_id', 75)
                    ->whereBetween('value', $range);
            })->with(['variants']);
    }

    /**
     * Get products by number of cameras
     */
    public function getProductsByCameraCount(string $parameter): Builder
    {
        // Parameter can be like "dual-camera", "triple-camera", "quad-camera"
        $cameraMap = [
            'dual-camera' => 2,
            'triple-camera' => 3,
            'quad-camera' => 4,
            'penta-camera' => 5,
        ];

        $count = $cameraMap[$parameter] ?? null;

        if (!$count) {
            abort(404, 'Invalid camera parameter');
        }

        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($count) {
                $query->where('attribute_id', 74) // Legacy ID 74
                    ->where('value', $count);
            })->with(['variants']);
    }

    /**
     * Get products by camera megapixels
     */
    public function getProductsByCameraMp(int $mp): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($mp) {
                $query->where('attribute_id', 73) // Legacy ID 73
                    ->where('value', $mp); // Exact match as per legacy
            })->with(['variants']);
    }

    /**
     * Get curved screen phones by brand
     */
    public function getCurvedScreensByBrand(string $brandSlug): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('brand', function ($query) use ($brandSlug) {
                $query->where('slug', $brandSlug);
            })->whereHas('attributes', function ($query) {
                $query->where('attribute_id', 263) // Legacy ID 263
                    ->where('value', 1); // Legacy value 1
            })->with(['variants', 'brand']);
    }

    /**
     * Get all curved screen phones
     */
    public function getCurvedScreens(): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) {
                $query->where('attribute_id', 263)
                    ->where('value', 1);
            })->with(['variants', 'brand']);
    }

    /**
     * Get products by brand and price
     */
    public function getProductsByBrandAndPrice(string $brandSlug, int $amount, string $countryCode): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('brand', function ($query) use ($brandSlug) {
                $query->where('slug', $brandSlug);
            })->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_code', $countryCode)
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })->with([
                    'variants' => function ($query) use ($countryCode) {
                        $query->where('country_code', $countryCode);
                    },
                    'brand'
                ]);
    }

    /**
     * Get tablets by RAM
     */
    public function getTabletsByRam(int $ram): Builder
    {
        // Legacy ID 3 for Tablets (Corrected from 2)
        return Product::where('category_id', 3)
            ->whereHas('attributes', function ($query) use ($ram) {
                $query->where('attribute_id', 239) // ID 239 for tablet RAM from legacy code
                    ->where('value', 'like', $ram . 'GB');
            })->with(['variants']);
    }

    /**
     * Get tablets by ROM
     */
    public function getTabletsByRom(int $rom): Builder
    {
        return Product::where('category_id', 3)
            ->whereHas('attributes', function ($query) use ($rom) {
                $query->where('attribute_id', 77) // Verify if 77 is correct for tablets? Legacy code didn't show getTabletsByRom but likely same.
                    ->where('value', 'like', $rom . 'GB');
            })->with(['variants']);
    }

    /**
     * Get tablets by price
     */
    public function getTabletsUnderPrice(int $amount, string $countryCode): Builder
    {
        return Product::where('category_id', 3)
            ->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_code', $countryCode)
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })->with([
                    'variants' => function ($query) use ($countryCode) {
                        $query->where('country_code', $countryCode);
                    }
                ]);
    }

    /**
     * Get tablets by screen size
     */
    public function getTabletsByScreenSize(float $inch): Builder
    {
        return Product::where('category_id', 3)
            ->whereHas('attributes', function ($query) use ($inch) {
                $query->where('attribute_id', 75)
                    ->where('value', '>=', $inch);
            })->with(['variants']);
    }

    /**
     * Get tablets by camera megapixels
     */
    public function getTabletsByCameraMp(int $mp): Builder
    {
        return Product::where('category_id', 3)
            ->whereHas('attributes', function ($query) use ($mp) {
                $query->where('attribute_id', 79)
                    ->where('value', '>=', $mp);
            })->with(['variants']);
    }

    /**
     * Get smartwatches under price
     */
    public function getSmartWatchesUnderPrice(int $amount, string $countryCode): Builder
    {
        // Legacy ID 2 for Smart Watches (Corrected from 3)
        return Product::where('category_id', 2)
            ->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_code', $countryCode)
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })->with([
                    'variants' => function ($query) use ($countryCode) {
                        $query->where('country_code', $countryCode);
                    }
                ]);
    }

    /**
     * Get upcoming/unreleased products
     */
    public function getUpcomingProducts(): Builder
    {
        return Product::where('status', 'upcoming')
            ->orWhere('release_date', '>', now())
            ->with(['variants', 'brand']);
    }

    /**
     * Get power banks by capacity (mAh)
     */
    public function getPowerBanksByCapacity(int $mah): Builder
    {
        // Legacy ID 9 for Power Banks
        return Product::where('category_id', 9)
            ->whereHas('attributes', function ($query) use ($mah) {
                $query->where('attribute_id', 302)
                    ->where('value', 'like', "%" . $mah . "%");
            })->with(['variants']);
    }

    /**
     * Get phone covers by model slug
     */
    public function getPhoneCoversByModel(string $slug): Builder
    {
        // Legacy ID 8 for Phone Covers
        return Product::where('category_id', 8)
            ->whereHas('attributes', function ($query) use ($slug) {
                $query->where('attribute_id', 312)
                    ->where('value', $slug);
            })->with(['variants']);
    }
}
