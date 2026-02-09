<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SubscribeController extends Controller
{
    public function index($token = null){
    	$user = User::where('unsubscribe_token', $token)->first();

        if (!$user) {
            return view('frontend.unsubscribe')->with('fail','Invalid token');
        }
        if($token && $user){
        	$user->newsletter = false;
        	$user->unsubscribe_token = null; // clear the token
        	$user->save();
        	return view('frontend.unsubscribe',compact('user'))->with("success","Email unsubscribed.");
        }
    	return view("frontend.unsubscribe");
    }
    public function update(Request $request){
    	$this->validate($request,[
    		"email" =>"required|email"
    	]);
    	$user = User::where("email",$request->email)->first();
    	if(!$user){
    		return redirect()->back()->with("fail","Email not found.");
    	}
    	$user->newsletter = false;
        $user->unsubscribe_token = null; // clear the token
		$user->save();
		return redirect()->back()->with("success","Email unsubscribed.");
    }
}
