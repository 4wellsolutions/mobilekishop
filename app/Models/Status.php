<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function Ads(){
    	return $this->hasMany(Ads::class);
    }
}
