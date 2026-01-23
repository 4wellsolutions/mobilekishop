<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function Category(){
    	return $this->belongsTo(Category::class);
    }
    public function Products(){
        return $this->belongsToMany(Product::class, 'product_attributes')->withPivot('value');
    }
}
