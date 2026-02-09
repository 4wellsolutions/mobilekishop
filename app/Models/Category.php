<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['code', 'name', 'is_active'];

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_category');
    }
}
