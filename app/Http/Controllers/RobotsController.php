<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class RobotsController extends Controller
{
    public function index($country_code = null) {
	    // Get the country details
	    $country = app('App\Http\Controllers\CountryController')->getCountry();
	    $countryCode = $country->country_code;
	    
	    // Get the host and extract the subdomain
	    $host = request()->getHost(); // e.g., us.example.com
	    $subdomain = explode('.', $host)[0]; // Extracts 'us' from 'us.example.com'

	    $robotsFile = public_path('robots/' . $countryCode . '.txt');
	    
	    // Check if the robots file exists and serve it
	    if (file_exists($robotsFile)) {
	        return Response::file($robotsFile, [
	            'Content-Type' => 'text/plain',
	        ]);
	    }

	    // Fallback to the default robots.txt if the specific file doesn't exist
	    return Response::file(public_path('robots/robots.txt'), [
	        'Content-Type' => 'text/plain',
	    ]);
	}

}
