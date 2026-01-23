<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    public function Brand(){
    	return $this->belongsTo(Brand::class,"brand_id");
    }
    public function Category(){
    	return $this->belongsTo(Category::class,"category_id");
    }
}
