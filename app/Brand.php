<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
	protected $fillable = [
        'name','slug','thumbnail','body','title','description'
    ];
    public function Filters(){
        return $this->hasMany(Filter::class);
    }
    public function Products(){
    	return $this->hasMany(Product::class);
    }
     public function categories()
    {
        return $this->belongsToMany(Category::class, 'brand_category');
    }
}
