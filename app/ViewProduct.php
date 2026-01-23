<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewProduct extends Model
{
    use HasFactory;
    protected $table = 'view_product_attributes';
}
