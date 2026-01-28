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
        // Category 1 for Mobile Phones
        return Product::where('category_id', 1)
            ->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_id', function ($sub) use ($countryCode) {
                    $sub->select('id')->from('countries')->where('country_code', $countryCode)->limit(1);
                })
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })
            ->with([
                'brand',
                'category'
            ]);
    }

    /**
     * Get products by brand and price
     */
    public function getProductsByBrandAndPrice(string $brandSlug, int $amount, string $countryCode): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('brand', function ($query) use ($brandSlug) {
                $query->where('slug', $brandSlug);
            })
            ->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_id', function ($sub) use ($countryCode) {
                    $sub->select('id')->from('countries')->where('country_code', $countryCode)->limit(1);
                })
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })
            ->with(['brand', 'category']);
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
            })->with(['brand', 'category']);
    }

    /**
     * Get products by ROM/Storage size
     */
    public function getProductsByRom(int $rom, string $unit = 'GB'): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($rom, $unit) {
                $query->where('attribute_id', 77)
                    ->where('value', 'like', $rom . strtoupper($unit));
            })->with(['brand', 'category']);
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
            })->with(['brand', 'category']);
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
            8 => [7.1, 24], // 8+ means up to very large
        ];

        if (!array_key_exists((int) $maxSize, $ranges)) {
            abort(404, 'Invalid screen size');
        }

        $range = $ranges[(int) $maxSize];

        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($range) {
                $query->where('attribute_id', 75)
                    ->whereBetween('value', $range);
            })->with(['brand', 'category']);
    }

    /**
     * Get products by number of cameras
     */
    public function getProductsByCameraCount(string $parameter): Builder
    {
        $cameraMap = [
            'dual' => 2,
            'triple' => 3,
            'quad' => 4,
            'penta' => 5,
        ];

        $count = $cameraMap[strtolower($parameter)] ?? null;

        if (!$count) {
            abort(404, 'Invalid camera parameter');
        }

        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($count) {
                $query->where('attribute_id', 74)
                    ->where('value', $count);
            })->with(['brand', 'category']);
    }

    /**
     * Get products by camera megapixels
     */
    public function getProductsByCameraMp(int $mp): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($mp) {
                $query->where('attribute_id', 73)
                    ->where('value', $mp);
            })->with(['brand', 'category']);
    }

    /**
     * Get products by processor types
     */
    public function getProductsByProcessor(string $processor): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($processor) {
                $query->where('attribute_id', 34)
                    ->where('value', 'like', "%" . $processor . "%");
            })->with(['brand', 'category']);
    }

    /**
     * Get curved screen phones by brand
     */
    public function getCurvedScreensByBrand(string $brandSlug): Builder
    {
        $query = Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) {
                $query->where('attribute_id', 263)->where('value', 1);
            });

        if ($brandSlug !== 'all') {
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        return $query->with(['brand', 'category']);
    }

    /**
     * Get products by type (folding, flip)
     */
    public function getProductsByType(string $type): Builder
    {
        $typeMap = [
            'folding' => 265,
            'flip' => 264,
        ];

        $attrId = $typeMap[strtolower($type)] ?? null;

        if (!$attrId) {
            abort(404, 'Invalid type parameter');
        }

        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) use ($attrId) {
                $query->where('attribute_id', $attrId)
                    ->where('value', 1);
            })->with(['brand', 'category']);
    }

    /**
     * Get upcoming products
     */
    public function getUpcomingProducts(): Builder
    {
        return Product::where('category_id', 1)
            ->whereHas('attributes', function ($query) {
                $query->where('attribute_id', 80)
                    ->where('value', '>', now());
            })->with(['brand', 'category']);
    }

    /**
     * Get power banks by capacity (mAh)
     */
    public function getPowerBanksByCapacity(int $mah): Builder
    {
        return Product::where('category_id', 9)
            ->whereHas('attributes', function ($query) use ($mah) {
                $query->where('attribute_id', 302)
                    ->where('value', 'like', "%" . $mah . "%");
            })->with(['brand', 'category']);
    }

    /**
     * Get phone covers by model slug
     */
    public function getPhoneCoversByModel(string $slug): Builder
    {
        return Product::where('category_id', 8)
            ->whereHas('attributes', function ($query) use ($slug) {
                $query->where('attribute_id', 312)
                    ->where('value', $slug);
            })->with(['brand', 'category']);
    }

    /**
     * Get tablets by RAM
     */
    public function getTabletsByRam(int $ram): Builder
    {
        return Product::where('category_id', 3)
            ->whereHas('attributes', function ($query) use ($ram) {
                $query->where('attribute_id', 239)
                    ->where('value', 'like', $ram . 'GB');
            })->with(['brand', 'category']);
    }

    /**
     * Get tablets by ROM
     */
    public function getTabletsByRom(int $rom): Builder
    {
        return Product::where('category_id', 3)
            ->whereHas('attributes', function ($query) use ($rom) {
                $query->where('attribute_id', 77)
                    ->where('value', 'like', $rom . 'GB');
            })->with(['brand', 'category']);
    }

    /**
     * Get tablets under price
     */
    public function getTabletsUnderPrice(int $amount, string $countryCode): Builder
    {
        return Product::where('category_id', 3)
            ->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_id', function ($sub) use ($countryCode) {
                    $sub->select('id')->from('countries')->where('country_code', $countryCode)->limit(1);
                })
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })->with(['brand', 'category']);
    }

    /**
     * Get smartwatches under price
     */
    public function getSmartWatchesUnderPrice(int $amount, string $countryCode): Builder
    {
        return Product::where('category_id', 2)
            ->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_id', function ($sub) use ($countryCode) {
                    $sub->select('id')->from('countries')->where('country_code', $countryCode)->limit(1);
                })
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })->with(['brand', 'category']);
    }

    /**
     * Get phone covers by brand and model slug
     */
    public function getPhoneCoversByBrand(string $brandSlug, string $modelSlug): Builder
    {
        return Product::where('category_id', 8)
            ->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            })
            ->whereHas('attributes', function ($q) use ($modelSlug) {
                $q->where('attribute_id', 312)
                    ->where('value', $modelSlug);
            })->with(['brand', 'category']);
    }
}
