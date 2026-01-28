<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Log;

class Product extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'name',
        'slug',
        'title',
        'description',
        'canonical',
        'brand_id',
        'category_id',
        'thumbnail',
        'body',
        'release_date',
        'views',
    ];
    use HasFactory;
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'products.name' => 10,
        ],
    ];
    // Or, preferably using $casts
    protected $casts = [
        'release_date' => 'datetime',
    ];
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')
            ->withPivot('value')
            ->withTimestamps();
    }
    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'product_variants')
            ->withPivot('price', 'country_id');
    }
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'product_variants', 'product_id', 'country_id')
            ->withPivot('price', 'variant_id');
    }
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors')
            ->withPivot('image')
            ->withTimestamps();
    }
    // public function getAttributeValue($attributeName)
    // {
    //     $attribute = $this->attributes()->where('name', $attributeName)->first();
    //     return $attribute ? $attribute->pivot->value : null;
    // }
    public function brand()
    {
        return $this->belongsTo(Brand::class, "brand_id");
    }
    public function category()
    {
        return $this->belongsTo(Category::class, "category_id");
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function reviews()
    {
        $Reviews = $this->hasMany(Review::class);
        $Reviews->getQuery()->where('is_active', 1);
        return $Reviews;
    }

    public function ProductAttributeViews()
    {
        return $this->hasMany(ProductAttributeView::class);
    }
    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
    public function getRamInGbAttribute()
    {
        $ramAttribute = $this->ProductAttributeViews()->where('attribute_label', 'ram_in_gb')->first();
        return $ramAttribute ? $ramAttribute->attribute_value : null;
    }

    /**
     * Get the first variant price for a given country.
     * Optimized to use eager-loaded variants when available.
     *
     * @param int|Product $productOrId Product object or ID
     * @param int $countryId
     * @return int|float
     */
    public static function getFirstVariantPriceForCountry($productOrId, $countryId)
    {
        if ($productOrId instanceof self) {
            $product = $productOrId;
        } else {
            $product = self::find($productOrId);
        }

        if (!$product) {
            return 0;
        }

        // Check if countries (preferred) or Variants are already eager-loaded
        if ($product->relationLoaded('countries')) {
            $variant = $product->countries->first(function ($c) use ($countryId) {
                return $c->id == $countryId || $c->pivot->country_id == $countryId;
            });
            return $variant ? $variant->pivot->price : 0;
        }

        if ($product->relationLoaded('variants')) {
            $variant = $product->variants->first(function ($variant) use ($countryId) {
                return $variant->pivot->country_id == $countryId;
            });
            return $variant ? $variant->pivot->price : 0;
        }

        // Fallback: query the database
        $variant = $product->countries()->wherePivot('country_id', $countryId)->first();
        return $variant ? $variant->pivot->price : 0;
    }


}
