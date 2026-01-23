<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News;

class NewsController extends Controller
{
    public function index(){
    	$country = app('App\Http\Controllers\CountryController')->getCountry();

    	$news = News::latest()->where("country_id",$country->id)->paginate(10);
    	return view("frontend.news.index",compact("news","country"));
    }
    public function show($countryCode,$slug){
    	$country = app('App\Http\Controllers\CountryController')->getCountry();
    	
    	$news = News::whereSlug($slug)->where("country_id",$country->id)->first();
    	
    	if(!$news){
    		abort(404);
    	}

    	return view("frontend.news.show",compact("news",'country'));
    }
}
