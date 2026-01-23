<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = ["price"];
    public function Products()
    {
        return $this->belongsToMany(Product::class, 'product_variants')
                    ->withPivot('price', 'country_id')
                    ->withTimestamps();
    }
}
