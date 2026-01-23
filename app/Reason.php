<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    public function Ads(){
    	return $this->hasMany(Ads::class);
    }
}
