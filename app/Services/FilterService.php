<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
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
     * Get products by type (folding, flip, 4g, 5g)
     */
    public function getProductsByType(string $type): Builder
    {
        $type = strtolower($type);

        // Handle 4G/5G network technology
        if (in_array($type, ['4g', '5g'])) {
            $tech = strtoupper($type);
            return Product::where('category_id', 1)
                ->whereHas('attributes', function ($query) use ($tech) {
                    $query->where('attribute_id', 36)
                        ->where('value', 'like', "%{$tech}%");
                })->with(['brand', 'category']);
        }

        $typeMap = [
            'folding' => 265,
            'flip' => 264,
        ];

        $attrId = $typeMap[$type] ?? null;

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

    /**
     * Get tablets by screen size
     */
    public function getTabletsByScreenSize(float $inch): Builder
    {
        return Product::where('category_id', 3)
            ->whereHas('attributes', function ($query) use ($inch) {
                $query->where('attribute_id', 1) // Using same attribute ID as mobile for screen size
                    ->where('value', 'like', $inch . '%');
            })->with(['brand', 'category']);
    }

    /**
     * Get tablets by camera MP
     */
    public function getTabletsByCameraMp(int $mp): Builder
    {
        return Product::where('category_id', 3)
            ->whereHas('attributes', function ($query) use ($mp) {
                $query->where('attribute_id', 5) // Using same attribute ID as mobile for camera
                    ->where('value', 'like', $mp . 'MP');
            })->with(['brand', 'category']);
    }

    /**
     * Get tablets by type (4g, 5g)
     */
    public function getTabletsByType(string $type): Builder
    {
        $type = strtolower($type);
        $attrId = $type === '4g' ? 199 : 200;

        return Product::where('category_id', 3)
            ->whereHas('attributes', function ($query) use ($attrId) {
                $query->where('attribute_id', $attrId);
            })->with(['brand', 'category']);
    }

    /**
     * Get chargers by port type (Attribute 315)
     */
    public function getChargersByPortType(string $portType): Builder
    {
        // Category 10 for Chargers
        $tech = str_replace('-', ' ', $portType);
        return Product::where('category_id', 10)
            ->whereHas('attributes', function ($query) use ($tech) {
                $query->where('attribute_id', 315)
                    ->where('value', 'like', "%{$tech}%");
            })->with(['brand', 'category']);
    }

    /**
     * Get chargers by wattage (Attribute 323)
     */
    public function getChargersByWatt(int $watt): Builder
    {
        $watt_values = [0, 15, 20, 25, 35, 45, 65, 75, 100, 120, 150, 180, 200, 240];
        $lowerWatt = $this->getLowerValue($watt, $watt_values);

        return Product::where('category_id', 10)
            ->whereHas('attributes', function ($query) use ($lowerWatt, $watt) {
                $query->where('attribute_id', 323)
                    ->whereBetween('value', [$lowerWatt, $watt]);
            })->with(['brand', 'category']);
    }

    /**
     * Get chargers by wattage and port type (USB Type C specific likely)
     */
    public function getChargersByWattAndPortType(int $watt, string $portType): Builder
    {
        $watt_values = [0, 30, 45, 60, 65, 67, 140];
        $lowerWatt = $this->getLowerValue($watt, $watt_values);
        $tech = str_replace('-', ' ', $portType);

        return Product::where('category_id', 10)
            ->whereHas('attributes', function ($query) use ($lowerWatt, $watt) {
                $query->where('attribute_id', 323)
                    ->whereBetween('value', [$lowerWatt, $watt]);
            })
            ->whereHas('attributes', function ($query) use ($tech) {
                $query->where('attribute_id', 315)
                    ->where('value', 'like', "%{$tech}%");
            })->with(['brand', 'category']);
    }

    /**
     * Helper to get lower value for wattage range
     */
    protected function getLowerValue($value, $watt_values)
    {
        sort($watt_values);
        foreach ($watt_values as $index => $watt) {
            if ($watt >= $value) {
                if ($index === 0 && $watt == $value) {
                    return null;
                }
                return $watt_values[$index - 1] + 1;
            }
        }
        return null;
    }
}
