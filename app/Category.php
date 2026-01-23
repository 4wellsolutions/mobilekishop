<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['code', 'name', 'is_active'];
    
    public function Attributes(){
    	return $this->hasMany(Attribute::class);
    }
    public function Products(){
    	return $this->hasMany(Product::class);
    }
    public function Brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_category'); 
    }
}
