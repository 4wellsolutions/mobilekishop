<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = ["price"];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variants')
            ->withPivot('price', 'country_id')
            ->withTimestamps();
    }
}
