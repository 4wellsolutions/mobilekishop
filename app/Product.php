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
    public function Attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')
            ->withPivot('value')
            ->withTimestamps();
    }
    public function Variants()
    {
        return $this->belongsToMany(Variant::class, 'product_variants')
            ->withPivot('price', 'country_id');
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
    public function Brand()
    {
        return $this->belongsTo(Brand::class, "brand_id");
    }
    public function Category()
    {
        return $this->belongsTo(Category::class, "category_id");
    }
    public function Images()
    {
        return $this->hasMany(Image::class);
    }
    public function Reviews()
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

    public static function getFirstVariantPriceForCountry($productId, $countryId)
    {
        // Retrieve the product along with its variants and pivot data
        $product = self::find($productId);

        // Get the first variant that matches the country_id
        $variant = $product->variants()->wherePivot('country_id', $countryId)->first();

        // Return the price if variant is found, otherwise return 0
        return $variant ? $variant->pivot->price : 0;
    }


}
