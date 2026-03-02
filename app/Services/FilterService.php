<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class FilterService
{
    /**
     * Standard eager-loads for filter result product cards.
     * Includes variants scoped to country for price display.
     */
    private function withStandardRelations(?string $countryCode = null): array
    {
        $relations = ['brand', 'category'];

        if ($countryCode) {
            $relations['variants'] = function ($query) use ($countryCode) {
                $query->where('country_id', function ($sub) use ($countryCode) {
                    $sub->select('id')->from('countries')->where('country_code', $countryCode)->limit(1);
                })->where('price', '>', 0);
            };
        }

        return $relations;
    }

    /**
     * Base query for a given category.
     */
    private function categoryQuery(string $categoryConfigKey)
    {
        return Product::where('category_id', config("categories.{$categoryConfigKey}"));
    }

    /**
     * Get products under a specific price (Mobile Phones)
     */
    public function getProductsUnderPrice(int $amount, string $countryCode): Builder
    {
        return $this->categoryQuery('mobile_phones')
            ->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_id', function ($sub) use ($countryCode) {
                    $sub->select('id')->from('countries')->where('country_code', $countryCode)->limit(1);
                })
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })
            ->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get products by brand and price
     */
    public function getProductsByBrandAndPrice(string $brandSlug, int $amount, string $countryCode): Builder
    {
        return $this->categoryQuery('mobile_phones')
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
            ->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get products by RAM size
     */
    public function getProductsByRam(int $ram, ?string $countryCode = null): Builder
    {
        return $this->categoryQuery('mobile_phones')
            ->whereHas('attributes', function ($query) use ($ram) {
                $query->where('attribute_id', config('attributes.mobile.ram'))
                    ->where('value', 'like', $ram . 'GB');
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get products by ROM/Storage size
     */
    public function getProductsByRom(int $rom, string $unit = 'GB', ?string $countryCode = null): Builder
    {
        return $this->categoryQuery('mobile_phones')
            ->whereHas('attributes', function ($query) use ($rom, $unit) {
                $query->where('attribute_id', config('attributes.mobile.rom'))
                    ->where('value', 'like', $rom . strtoupper($unit));
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get products by RAM and ROM combination
     */
    public function getProductsByRamRom(int $ram, int $rom, ?string $countryCode = null): Builder
    {
        return $this->categoryQuery('mobile_phones')
            ->whereHas('attributes', function ($query) use ($ram) {
                $query->where('attribute_id', config('attributes.mobile.ram'))
                    ->where('value', $ram . 'GB');
            })->whereHas('attributes', function ($query) use ($rom) {
                $query->where('attribute_id', config('attributes.mobile.rom'))
                    ->where('value', $rom . 'GB');
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get products by screen size range
     */
    public function getProductsByScreenSize(float $maxSize, ?string $countryCode = null): Builder
    {
        $ranges = [
            4 => [0, 4],
            5 => [4.1, 5],
            6 => [5.1, 6],
            7 => [6.1, 7],
            8 => [7.1, 24],
        ];

        if (!array_key_exists((int) $maxSize, $ranges)) {
            abort(404, 'Invalid screen size');
        }

        $range = $ranges[(int) $maxSize];

        return $this->categoryQuery('mobile_phones')
            ->whereHas('attributes', function ($query) use ($range) {
                $query->where('attribute_id', config('attributes.mobile.screen_size'))
                    ->whereBetween('value', $range);
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get products by number of cameras
     */
    public function getProductsByCameraCount(string $parameter, ?string $countryCode = null): Builder
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

        return $this->categoryQuery('mobile_phones')
            ->whereHas('attributes', function ($query) use ($count) {
                $query->where('attribute_id', config('attributes.mobile.camera_count'))
                    ->where('value', $count);
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get products by camera megapixels
     */
    public function getProductsByCameraMp(int $mp, ?string $countryCode = null): Builder
    {
        return $this->categoryQuery('mobile_phones')
            ->whereHas('attributes', function ($query) use ($mp) {
                $query->where('attribute_id', config('attributes.mobile.camera_mp'))
                    ->where('value', $mp);
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get products by processor types
     */
    public function getProductsByProcessor(string $processor, ?string $countryCode = null): Builder
    {
        $safeProcessor = str_replace(['%', '_'], ['\%', '\_'], $processor);

        return $this->categoryQuery('mobile_phones')
            ->whereHas('attributes', function ($query) use ($safeProcessor) {
                $query->where('attribute_id', config('attributes.mobile.processor'))
                    ->where('value', 'like', "%" . $safeProcessor . "%");
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get curved screen phones by brand
     */
    public function getCurvedScreensByBrand(string $brandSlug, ?string $countryCode = null): Builder
    {
        $query = $this->categoryQuery('mobile_phones')
            ->whereHas('attributes', function ($query) {
                $query->where('attribute_id', config('attributes.mobile.curved_display'))->where('value', 1);
            });

        if ($brandSlug !== 'all') {
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        return $query->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get products by type (folding, flip, 4g, 5g)
     */
    public function getProductsByType(string $type, ?string $countryCode = null): Builder
    {
        $type = strtolower($type);

        // Handle 4G/5G network technology
        if (in_array($type, ['4g', '5g'])) {
            $tech = strtoupper($type);
            return $this->categoryQuery('mobile_phones')
                ->whereHas('attributes', function ($query) use ($tech) {
                    $query->where('attribute_id', config('attributes.mobile.network'))
                        ->where('value', 'like', "%{$tech}%");
                })->with($this->withStandardRelations($countryCode));
        }

        $typeMap = [
            'folding' => config('attributes.mobile.folding'),
            'flip' => config('attributes.mobile.flip'),
        ];

        $attrId = $typeMap[$type] ?? null;

        if (!$attrId) {
            abort(404, 'Invalid type parameter');
        }

        return $this->categoryQuery('mobile_phones')
            ->whereHas('attributes', function ($query) use ($attrId) {
                $query->where('attribute_id', $attrId)
                    ->where('value', 1);
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get upcoming products
     */
    public function getUpcomingProducts(?string $countryCode = null): Builder
    {
        return $this->categoryQuery('mobile_phones')
            ->whereHas('attributes', function ($query) {
                $query->where('attribute_id', config('attributes.mobile.release_date'))
                    ->where('value', '>', now());
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get power banks by capacity (mAh)
     */
    public function getPowerBanksByCapacity(int $mah, ?string $countryCode = null): Builder
    {
        $safeMah = str_replace(['%', '_'], ['\%', '\_'], (string) $mah);

        return $this->categoryQuery('power_banks')
            ->whereHas('attributes', function ($query) use ($safeMah) {
                $query->where('attribute_id', config('attributes.powerbank.capacity'))
                    ->where('value', 'like', "%" . $safeMah . "%");
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get phone covers by model slug
     */
    public function getPhoneCoversByModel(string $slug, ?string $countryCode = null): Builder
    {
        return $this->categoryQuery('phone_covers')
            ->whereHas('attributes', function ($query) use ($slug) {
                $query->where('attribute_id', config('attributes.phonecover.model'))
                    ->where('value', $slug);
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get tablets by RAM
     */
    public function getTabletsByRam(int $ram, ?string $countryCode = null): Builder
    {
        return $this->categoryQuery('tablets')
            ->whereHas('attributes', function ($query) use ($ram) {
                $query->where('attribute_id', config('attributes.tablet.ram'))
                    ->where('value', 'like', $ram . 'GB');
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get tablets by ROM
     */
    public function getTabletsByRom(int $rom, ?string $countryCode = null): Builder
    {
        return $this->categoryQuery('tablets')
            ->whereHas('attributes', function ($query) use ($rom) {
                $query->where('attribute_id', config('attributes.tablet.rom'))
                    ->where('value', 'like', $rom . 'GB');
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get tablets under price
     */
    public function getTabletsUnderPrice(int $amount, string $countryCode): Builder
    {
        return $this->categoryQuery('tablets')
            ->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_id', function ($sub) use ($countryCode) {
                    $sub->select('id')->from('countries')->where('country_code', $countryCode)->limit(1);
                })
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get smartwatches under price
     */
    public function getSmartWatchesUnderPrice(int $amount, string $countryCode): Builder
    {
        return $this->categoryQuery('smart_watches')
            ->whereHas('variants', function ($query) use ($amount, $countryCode) {
                $query->where('country_id', function ($sub) use ($countryCode) {
                    $sub->select('id')->from('countries')->where('country_code', $countryCode)->limit(1);
                })
                    ->where('price', '<=', $amount)
                    ->where('price', '>', 0);
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get phone covers by brand and model slug
     */
    public function getPhoneCoversByBrand(string $brandSlug, string $modelSlug, ?string $countryCode = null): Builder
    {
        return $this->categoryQuery('phone_covers')
            ->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            })
            ->whereHas('attributes', function ($q) use ($modelSlug) {
                $q->where('attribute_id', config('attributes.phonecover.model'))
                    ->where('value', $modelSlug);
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get tablets by screen size
     */
    public function getTabletsByScreenSize(float $inch, ?string $countryCode = null): Builder
    {
        return $this->categoryQuery('tablets')
            ->whereHas('attributes', function ($query) use ($inch) {
                $query->where('attribute_id', config('attributes.tablet.screen'))
                    ->where('value', 'like', $inch . '%');
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get tablets by camera MP
     */
    public function getTabletsByCameraMp(int $mp, ?string $countryCode = null): Builder
    {
        return $this->categoryQuery('tablets')
            ->whereHas('attributes', function ($query) use ($mp) {
                $query->where('attribute_id', config('attributes.tablet.camera_mp'))
                    ->where('value', 'like', $mp . 'MP');
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get tablets by type (4g, 5g)
     */
    public function getTabletsByType(string $type, ?string $countryCode = null): Builder
    {
        $type = strtolower($type);
        $attrId = config("attributes.tablet.{$type}");

        return $this->categoryQuery('tablets')
            ->whereHas('attributes', function ($query) use ($attrId) {
                $query->where('attribute_id', $attrId);
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get chargers by port type (Attribute 315)
     */
    public function getChargersByPortType(string $portType, ?string $countryCode = null): Builder
    {
        $tech = str_replace('-', ' ', $portType);
        return $this->categoryQuery('chargers')
            ->whereHas('attributes', function ($query) use ($tech) {
                $query->where('attribute_id', config('attributes.charger.port_type'))
                    ->where('value', 'like', "%{$tech}%");
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get chargers by wattage (Attribute 323)
     */
    public function getChargersByWatt(int $watt, ?string $countryCode = null): Builder
    {
        $watt_values = [0, 15, 20, 25, 35, 45, 65, 75, 100, 120, 150, 180, 200, 240];
        $lowerWatt = $this->getLowerValue($watt, $watt_values);

        return $this->categoryQuery('chargers')
            ->whereHas('attributes', function ($query) use ($lowerWatt, $watt) {
                $query->where('attribute_id', config('attributes.charger.wattage'))
                    ->whereBetween('value', [$lowerWatt, $watt]);
            })->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get chargers by wattage and port type (USB Type C specific likely)
     */
    public function getChargersByWattAndPortType(int $watt, string $portType, ?string $countryCode = null): Builder
    {
        $watt_values = [0, 30, 45, 60, 65, 67, 140];
        $lowerWatt = $this->getLowerValue($watt, $watt_values);
        $tech = str_replace('-', ' ', $portType);

        return $this->categoryQuery('chargers')
            ->whereHas('attributes', function ($query) use ($lowerWatt, $watt) {
                $query->where('attribute_id', config('attributes.charger.wattage'))
                    ->whereBetween('value', [$lowerWatt, $watt]);
            })
            ->whereHas('attributes', function ($query) use ($tech) {
                $query->where('attribute_id', config('attributes.charger.port_type'))
                    ->where('value', 'like', "%{$tech}%");
            })->with($this->withStandardRelations($countryCode));
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

    /**
     * Get cables by type slug (e.g., usb-c-to-usb-c, usb-a-to-usb-c)
     */
    public function getCablesByType(string $typeSlug, ?string $countryCode = null): Builder
    {
        $search = str_replace('-', ' ', $typeSlug);
        return $this->categoryQuery('cables')
            ->where('name', 'like', "%{$search}%")
            ->with($this->withStandardRelations($countryCode));
    }

    /**
     * Get cables by brand and wattage
     */
    public function getCablesByBrandAndWatt(string $brandSlug, int $watt, ?string $countryCode = null): Builder
    {
        $watt_values = [0, 15, 30, 60, 100, 140, 240];
        $lowerWatt = $this->getLowerValue($watt, $watt_values);

        $query = $this->categoryQuery('cables')
            ->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });

        if ($lowerWatt !== null) {
            $query->where('name', 'like', "%{$watt}%");
        }

        return $query->with($this->withStandardRelations($countryCode));
    }
}
