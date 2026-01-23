<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
	protected $fillable = [
        'stars', 'review'
    ];
    public function User(){
    	return $this->belongsTo(User::class);
    }
    public function Product(){
    	return $this->belongsTo(Product::class);
    }
}
