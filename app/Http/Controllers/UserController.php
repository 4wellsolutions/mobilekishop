<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Jobs\SendAdPostEmail;
use App\User;
use App\Ad;
use Auth;
use Str;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use File;

class UserController extends Controller
{
    public function index(){
    	return view("user.index");
    }
    public function userUpdate(Request $request){
    	// dd($request->all());
    	$this->validate($request, [
    		"name" => "required",
    		"phone" => "nullable",
    		"password" => 'nullable|confirmed|min:6',
    		"newsletter" => 'nullable',
    	]);

    	$user = User::find(Auth::User()->id);
    	$user->name = $request->name;
    	$user->phone_number = $request->phone;
    	$user->newsletter = $request->newsletter ? 1 : 0;
    	if($request->password){
    		$user->password = Hash::make($request->password);
    	}
    	$user->save();

    	return redirect()->back()->with("success","Profile Updated!");
    }
    public function review(){
    	$reviews = Auth::user()->reviews()->orderBy("id","DESC")->paginate(10);
    	return view("user.review",compact('reviews'));
    }
    public function reviewUpdate(Request $request){
        // dd($request->all());
        $review = Auth::user()->reviews()->find($request->review_id);
        // dd($review);
        if($review){
            $review->stars = $request->stars;
            $review->review = $request->review;
            $review->is_active = 0;
            $review->update();
            return redirect()->route('user.review')->with("success","Review updated. Pending Approval");
        }
        return redirect()->route('user.review')->with("fail","Review not found");
    }
    public function reviewDelete($id){
        $review = Auth::user()->reviews()->find($id);
        if($review){
            Auth::User()->Reviews()->whereId($id)->delete();
            return redirect()->route('user.review')->with("success","Review removed");
        }
        return redirect()->route('user.review')->with("fail","Review not found.");
    }
    public function wishlist(){
        $wishlists = Auth::User()->wishlists()->orderBy("id","DESC")->get();
    	return view("user.wishlist",compact('wishlists'));
    }

    public function wishlistDelete($id=null){
        if(!$id){
            return redirect()->route('user.wishlist')->with("fail","Wishlist not found");
        }
        $wishlist = Auth::User()->Wishlists()->find($id);
        if($wishlist){
            Auth::User()->Wishlists()->whereId($id)->delete();
            return redirect()->route('user.wishlist')->with("success","Wishlist removed");
        }
        return redirect()->route('user.wishlist')->with("fail","Wishlist not found");
    }
}
