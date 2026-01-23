<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Ad;

class JobController extends Controller
{
    public function markAsDeleted() {
    	$ads = Ad::where('expiry_date', '<=', Carbon::now()->subDays(14))->get();

	    foreach ($ads as $ad) {
	        $ad->status_id = 5;
	        $ad->save();
	    }
	}
	public function markAsExpired(){
		$now = Carbon::now();

		$ads = Ad::where('expiry_date', '<', $now)
                ->where('status_id', '=', 1)
                ->get();
		foreach($ads as $ad){
			$expiryDate = Carbon::parse($ad->expiry_date);
	        if ($expiryDate->isPast()) {
	            $ad->status_id = 4;
	            $ad->update();
	        }
        }
	}
}
