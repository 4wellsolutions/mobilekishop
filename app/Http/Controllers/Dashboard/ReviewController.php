<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Review;
use Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Review::orderBy("id","DESC")->paginate(100);
        return view("dashboard.reviews.index",compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = Review::find($id);
        return view("dashboard.reviews.edit",compact('review'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $this->validate($request, [
            "review" => "required",
            "product_id" => "nullable",
            "name" => "nullable",
            "email" => "nullable",
        ]);
        
        $review             = Review::find($id);
        $review->review     = $request->review;
        $review->product_id = $request->product_id;
        $review->name       = $request->name;
        $review->email      = $request->email;
        $review->user_id    = $request->user_id ? $request->user_id : null;
        $review->is_active  = $request->is_active ? 1 : 0;
        $review->approved_by = Auth::User()->id;
        $review->save();
         // dd($review);
        Mail::send("emails.review_approved",['review' => $review], function($m) use ($review) {
            $m->from('info@mobilekishop.net','MKS');
            $m->to($review->email);
            $m->subject("Your Review is Live");
        });
        // dd($request->all());
        return redirect()->route('dashboard.reviews.index')->with("success","Review Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
