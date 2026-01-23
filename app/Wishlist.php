<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    public function Mobile(){
    	return $this->belongsTo(Mobile::class);
    }
    public function User(){
    	return $this->belongsTo(User::class);
    }
}
