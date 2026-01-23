<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Validator;
use App\Jobs\sendReviewPostEmail;
use App\Mobile;
use App\Product;
use App\Review;
use App\Brand;
use App\Contact;
use App\Wishlist;
use App\Compare;
use App\Category;
use App\User;
use App\Ad;
use URL;
use Carbon\Carbon;
use Str;
use DB;
use Response;
use Auth;
use App\Page;
use Session;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function index(){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "Phone Reviews, Features, News, Price in {$country->country_name}",
            "description" => "Mobilekishop is the best mobile website that provides the latest mobile phone prices in {$country->country_name} with specifications, features, reviews and comparison.",
            "canonical" => "https://mobilekishop.net",
            "h1" => "Mobile Phones Specifications, Price in {$country->country_name}"
        ];

        $categories = Category::where('is_active', 1)
            ->get()
            ->map(function ($category) use ($country) {
                $category->latest_products = $category->products()
                    ->whereHas('variants', function ($query) use ($country) {
                        $query->where('country_id', $country->id);
                    })
                    ->latest()
                    ->take(4)
                    ->get();
                return $category;
            });

        return view('frontend.index',compact('categories','country','metas'));
    }
    
    public function wishlistPost(Request $request){
        
        $this->validate($request,[
            "product_id" => "required",
            "type" => "required",
        ]);
        if($wishlist = Wishlist::where(["product_id" => $request->product_id,"user_id" => Auth::User()->id])->first()){
            $wishlist->type = $request->type;
            $wishlist->update();
        }else{
            $wishlist = new Wishlist();
            $wishlist->product_id = $request->product_id;
            $wishlist->type = $request->type;
            $wishlist->user_id = Auth::user()->id;
            $wishlist->save();
        }
        $this->saveLog("save Wishlist",$request->all());
        return true;
    }
    public function compare($slug,$slug1=null){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $validator = Validator::make(['slug' => $slug], [
            'slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return abort(404);
        }

        $slugs = explode('-vs-', $slug);

        $products = Product::whereIn('slug', $slugs)
            ->orderByRaw(DB::raw("FIELD(slug, '" . implode("','", $slugs) . "')"))
            ->get();
        
        if ($products->isEmpty()) {
            return abort(404);
        }

        $product = $products->firstWhere('slug', $slugs[0]);
        $product1 = isset($slugs[1]) ? $products->firstWhere('slug', $slugs[1]) : null;
        $product2 = isset($slugs[2]) ? $products->firstWhere('slug', $slugs[2]) : null;

        if (!$product) {
            return abort(404);
        }

        $h1 = $products->pluck('name')->join(' VS ');

        $description = "Compare all the specifications, features, and price of " . $products->pluck('name')->join(', ') . " on the Mobilekishop.";
        
        $compares = Compare::whereIn('product1', $slugs)
                            ->orWhereIn('product2', $slugs)
                            ->inRandomOrder()
                            ->limit(6)
                            ->get();
        if ($product && $product1 && !$product2) {
            $title = "{$product->name} vs {$product1->name} Specs & Prices";
            $description = "Compare {$product->name} vs {$product1->name} by specs, features, and prices. Detailed reviews to find the best choice for you.";
            $h1 = "{$product->name} vs {$product1->name}  in {$country->country_name}";
        } elseif ($product && $product1 && $product2) {
            $title = "{$product->name} vs {$product1->name} vs {$product2->name} Specs & Prices";
            $description = "Compare {$product->name} vs {$product1->name} vs {$product2->name} by specs, features, and prices. Detailed reviews to find the best choice for you.";
            $h1 = "{$product->name} vs {$product1->name} vs {$product2->name} in {$country->country_name}";
        } else {
            $title = null;
            $description = null;
            $h1 = null;
        }


        $metas = (object) [
            "title" => $title,
            "description" => $description,
            "canonical" => URL::to('/compare/'.$slug),
            "h1" => $h1,
            "name" => "Compare Mobiles"
        ];
        $category = $products->first()->category->slug;
        return view("frontend.compare.$category",compact('product','product1','product2','metas','compares','country'));
    }
    public function productUnder($amount, Request $request)
    {
        // Fetch the country details
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $productsPerPage = 32;
        return redirect()->to("/mobile-phones-under-{$amount}", 301);
        // Define price slabs for filtering
        $priceSlabs = [
            15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000, 60000, 70000, 80000, 90000, 100000, 150000,
            200000, 300000, 400000, 500000, 600000, 700000
        ];

        // Initialize price range
        $lowerAmount = 0;
        $upperBound = $amount; // Default upper bound

        // Determine the lower bound price based on the provided amount
        for ($i = 0; $i < count($priceSlabs); $i++) {
            if ($amount == $priceSlabs[$i]) {
                $lowerAmount = $i > 0 ? $priceSlabs[$i - 1] : 0; // Set the lower amount based on slabs
                $upperBound = $priceSlabs[$i]; // Set the upper amount to current slab
                break;
            }
        }

        // Fetch meta data for the current page
        $metas = Page::whereSlug(url()->current())->where("is_active", 1)->first();
        if (!$metas) {
            $metas = (object)[
                "title" => "Latest Products under $amount Reviews, Features, Price in {$country->country_name}",
                "description" => "Mobilekishop is the best website that provides the latest products under $amount price range in {$country->country_name} with specifications, features, reviews, and comparison.",
                "canonical" => \Request::fullUrl(),
                "h1" => "Products Under $amount RS Price in {$country->country_name}",
                "name" => "Products Under $amount"
            ];
        }

        // Fetch the category with ID 1 (you can modify this if needed)
        $category = Category::find(1);
        $categoryId = $category ? $category->id : null;

        // Fetch products based on price range using the ProductController
        $products = app('App\Http\Controllers\ProductController')->getProducts($country->id, $lowerAmount, $upperBound);

        // Apply additional filters if any are passed in the request
        if ($request->has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, $request->all(), $country->id, $lowerAmount, $upperBound);
        }

        // Paginate the results
        $products = $products->paginate($productsPerPage);

        // Handle AJAX requests by returning the partial view for products
        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        // Return the full view with product details
        return view("frontend.product_under_price", compact('products', 'metas', 'category', 'amount', 'lowerAmount', 'country'));
    }
    public function categoryShow($slug){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        
        $category = Category::whereSlug($slug)->first();
        if(!$category){
            return abort(404);
        }
        $countryId = $country->id;

        $products = $category->products()->whereHas('variants', function($query) use ($countryId) {
                        $query->where('country_id', $countryId);
                    });

        if(\Request::has('filter')){
            $products = $this->sortFilter($products, \Request::all(),$country->id);
        }

        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            return view('includes.products-partial', compact('products','country'))->render();
        }
        
        $products = $products->paginate($productsPerPage);
        $metas = (object)[
            "title" => "Latest $category->category_name Reviews, Features, Price in {$country->country_name}",
            "description" => "Mobilekishop is the best $category->category_name website that provides the latest $category->category_name phone prices in {$country->country_name} with specifications, features, reviews and comparison.",
            "canonical" => \Request::fullUrl(),
            "h1" => "$category->category_name Price in {$country->country_name}",
            "name" => "$category->category_name"
        ];
        
        return view("frontend.category",compact('products','metas','category','country'));
    }
    public function brandShow($slug, $category_slug)
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();

        $category = null;
        $products = new Product();
        $country = DB::table("countries")->where("country_code", "pk")->first();
        $brand = Brand::whereSlug($slug)->first();

        if (!$brand) {
            return abort(404);
        }

        $products = $products->where("brand_id", $brand->id);

        if (isset($category_slug)) {
            $category = Category::whereSlug($category_slug)->first();
            if (!$category) {
                abort(404);
            }
            $products = $products->where("products.category_id", $category->id);
        }

        $products = $products->whereHas('variants', function($query) use ($country) {
            $query->where('country_id', $country->id);
        });
        

        if (\Request::has('filter')) {
            $products = $this->sortFilter($products, \Request::all(), $country->id);
        }

        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        
        $products = $products->simplepaginate(24);
        $name = isset($category) ? $category->category_name : 'Products';
        $metas = $this->getMeta(\Request::url(), $brand, $name, $country);

        $currentMonthYear = Carbon::now()->format('F Y');
        $metas->title = "Top {$brand->name} {$category->category_name} in {$country->country_name} - Unbeatable Reviews & Prices";
        $metas->description = "Discover the ultimate {$brand->name} {$category->category_name} in {$country->country_name} with Mobilekishop: Experience cutting-edge specifications, comprehensive reviews, and the best price comparisons. Updated for {$currentMonthYear}!";

        $metas->h1 = "Top {$brand->name} {$category->category_name} in {$country->country_name}";

        return view("frontend.brand", compact('products', 'brand', 'metas', 'category', 'country'));
    }
    public function saveLog($name=null,$log=null){
        DB::table("logs")->insert(["name"=>$name,"log"=>json_encode($log),"user_id"=> isset(Auth::user()->id) ? Auth::user()->id : null]);
    } 
    public function reviewPost(Request $request){
        $validator = Validator::make($request->all(), [
            'stars' => 'required',
            'review' => 'required',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user_id = "";
        $email = "";
        $name = "";
        if (Auth::check()) {
            $user_id = Auth::User()->id;
            $email = Auth::User()->email;
            $name = Auth::User()->name;
        } else {
            $user = User::where('email', $request->email)->select('id', 'email', 'name')->first();
            if ($user) {
                $user_id = $user->id;
                $user_email = $user->email;
                $name = $user->name;
            } else {
                $user_email = $request->email;
                $name = $request->name;
            }
        }

        // Check if the user has posted 10 reviews today
        if ($user_id) {
            $reviewsToday = Review::where('user_id', $user_id)
                ->whereDate('created_at', Carbon::today())
                ->count();

            if ($reviewsToday >= 10) {
                return response()->json(["success" => false, 'error' => 'You have reached the limit of 10 reviews for today.'], 422);
            }
        }

        $review = new Review();
        $review->stars = $request->stars;
        $review->review = $request->review;
        $review->product_id = $request->product_id;
        $review->name = $name ? $name : $request->name;
        $review->email = $email ? $email : $request->email;
        $review->user_id = $user_id ? $user_id : null;
        $review->is_active = 0;
        $review->save();

        $this->saveLog('save Review', $request->all());

        // send registration email
        sendReviewPostEmail::dispatch($review);
        // send registration email

        return response()->json(['success' => true, "message" => 'Review posted successfully and pending approval'], 200);
    }
    public function showBrandsByCategory($category_slug){
        $category = null;
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        if($category_slug == "all"){
                $metas = (object)[
                "title" => Str::title("All Brands Mobile Phones, Tablets, Smart Watches {$country->country_name}"),
                "description" => "Get all the specifications, features, reviews, comparison, and price of All Mobile Phones Brands on the Mobilekishop in {$country->country_name}.",
                "canonical" => URL::to("/brands/all"),
                "h1" => "All Brands Mobile Phones in {$country->country_name}",
                "name" => "All Brands"
            ];  
            $categories = Category::with('brands')->get();
            $brands = [];
        }else{
            $category = Category::where("slug",$category_slug)->first();
            if(!$category){
                return abort(404);
            }
            $categories = [];
            $metas = (object)[
                "title" => Str::title("Latest $category->category_name Brands Spec, Price in {$country->country_name}"),
                "description" => "Get all the specifications, features, reviews, comparison, and price of All $category->category_name Brands on the Mobilekishop in {$country->country_name}.",
                "canonical" => URL::to("/brands/$category->slug"),
                "h1" => "$category->category_name Brands in {$country->country_name}",
                "name" => "All Brands"
            ];  
            
            $brands = Brand::whereHas('products', function ($query) use ($category) {
                $query->where('category_id', $category->id)
                      ->whereHas('variants', function ($variantQuery) {
                          $variantQuery->where('price', '>', 0);
                      });
            })->get();

            
        }
        
        return view("frontend.brands",compact('brands','metas','category','country','categories'));
    }
    public function search(Request $request){
        $country = app('App\Http\Controllers\CountryController')->getCountry();

        if(!$request->has("query")){
            return redirect()->back()->with("fail","Please try again with different query!");
        }
        $query = $request->get("query");
        $metas = (object)[
            "title" => "Search for $query Price in {$country->country_name}",
            "description" => "Search the mobile phones for $query on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => URL::route('search'),
            "h1" => "Search for $query",
            "name" => "Search for $query"
        ];
        $agent = new Agent();
        DB::table("searches")->insert([
            "query" => $query,
            "user_agent" => "User Agent: "." browser:". $agent->browser()." platform:".$agent->platform()." device:".$agent->device(),
        ]);
        $countryId = $country->id;
        $products = Product::whereHas('variants', function($query) use ($countryId) {
                        $query->where('country_id', $countryId) ->where('price', '>', 0);;
                    });
        $products = $products->search($query);
        $products = $products->simplepaginate(32);
        $category = null;
        
        return view("frontend.search",compact('products','metas','category','country'));   
    }  
    public function aboutUs(){
        $country = app('App\Http\Controllers\CountryController')->getCountry();

        $metas = (object)[
            "title" => "About Us - Mobilekishop",
            "description" => "About Us - Find latest Mobiles phones prices in {$country->country_name}.",
            "canonical" => URL::to('/about-us'),
            "h1" => "About Us",
            "name" => "About Us"
        ];
        return view("frontend.about",compact('metas','country'));
    }
    public function privacyPolicy(){
        $country = app('App\Http\Controllers\CountryController')->getCountry();

        $metas = (object)[
            "title" => "Privacy Policy - Mobilekishop",
            "description" => "Privacy Policy - Find latest Mobiles phones prices in {$country->country_name}.",
            "canonical" => URL::to('/privacy-policy'),
            "h1" => "Privacy Policy",
            "name" => "Privacy Policy"
        ];
        return view("frontend.privacy-policy",compact('metas','country'));
    }
    public function termsConditions(){
        $country = app('App\Http\Controllers\CountryController')->getCountry();

        $metas = (object)[
            "title" => "Terms and Conditions - Mobilekishop",
            "description" => "Terms and Conditions - Find latest Mobiles phones prices in {$country->country_name}.",
            "canonical" => URL::to('/terms-and-conditions'),
            "h1" => "Terms and Conditions",
            "name" => "Terms and Conditions"
        ];
        return view("frontend.terms",compact('metas','country'));
    }
    public function contact(){
        $country = app('App\Http\Controllers\CountryController')->getCountry();

        $metas = (object)[
            "title" => "Contact Us - Mobilekishop",
            "description" => "Contact Us - Find latest Mobiles phones prices in {$country->country_name}.",
            "canonical" => URL::to('/contact'),
            "h1" => "Contact Us",
            "name" => "contact us"
        ];
        return view("frontend.contact",compact('metas','country'));
    }
    public function contactPost(Request $request){
        // Validate the request inputs
        $this->validate($request, [
            "name" => ["required", "regex:/^[a-zA-Z\s]+$/"],
            "email" => "required|email",
            "phone" => "required|regex:/^\+?[0-9]{7,15}$/",
            "message" => "required",
        ], [
            "name.required" => "Please provide your name.",
            "name.regex" => "The name may only contain letters and spaces.",
            "phone.required" => "Please provide your phone number.",
            "phone.regex" => "Please provide a valid phone number."
        ]);

        // Sanitize the phone number
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);

        // Create a new contact instance and save the sanitized phone number
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $sanitizedPhone;
        $contact->message = $request->message;
        $contact->save();

        // Send email notification
        Mail::send("emails.contact", ['contact' => $contact], function($m) use ($contact) {
            $m->from('info@mobilekishop.net', 'MobileKiShop');
            $m->cc("info@mobilekishop.net");
            $m->to($contact->email);
            $m->subject("Your Message Has Been Received");
        });

        // Redirect back with a success message
        return redirect()->back()->with("success", "We have received your query. You will get reply back soon.");
    }
    public function sortFilter($products,$filter,$countryId,$lowerAmount=null,$upperBound=null){
        
        $attributeFilters = \Request::only([
            'ram_in_gb', 'rom_in_gb', 'pixels', 'year', 'network_band'
        ]);
        if (isset($filter['filter']) && $filter['filter'] == "true") {
            if (isset($filter['orderby']) && $filter['orderby'] == "price_asc") {
                $sortField = 'product_variants.price';
                $sortOrder = 'ASC';
            } elseif (isset($filter['orderby']) && $filter['orderby'] == "price_desc") {
                $sortField = 'product_variants.price';
                $sortOrder = 'DESC';
            }
        }else{
            $sortField = 'id';
            $sortOrder = 'DESC';
        }

        foreach ($attributeFilters as $attribute => $value) {
            if (!is_null($value)) {
                $products = $products->whereHas('productAttributes', function ($query) use ($attribute, $value) {
                    $query->whereHas('attribute', function ($subQuery) use ($attribute, $value) {
                        if ($attribute === 'year') {
                            // Special case for 'year' attribute
                            $subQuery->where('name', "release_date")->whereYear('value', '=', $value);
                        } else {    
                            $subQuery->where('name', $attribute)->where('value', $value);
                        }
                    });
                });
            }
        }

        // Check for sorting options
        if (isset($filter["orderby"]) && $filter["orderby"] == "new") {
            $products = $products->orderBy('release_date', 'DESC');
        }

        if($lowerAmount && $upperBound && $filter["orderby"] != "new"){
            $products = $products->orderBy($sortField,$sortOrder)->with(['variants' => function ($query) use ($countryId, $lowerAmount, $upperBound) {
                $query->where('country_id', $countryId)
                      ->where('price', '>=', $lowerAmount)
                      ->where('price', '<=', $upperBound)
                      ->orderBy('price', 'ASC')
                      ->limit(1);
            }]);
        }

        return $products;
    }
    public function comparison(){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => Str::title("Mobiles Phone Comparison, Spec, Price in {$country->country_name}"),
            "description" => "Get all Mobiles Phone Comparison, specifications, features, reviews, prices on the Mobilekishop in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "Mobile Phones Comparison",
            "name" => "Comparison"
        ]; 
        $compares = Compare::orderBy("id","DESC");
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $compares = $compares->paginate($productsPerPage);
            return view('includes.compare-partial', compact('compares'))->render();
        }
        $compares = $compares->simplepaginate(32);

        return view("frontend.comparison",compact('compares','metas','country'));
    }
    public function FilterPhoneCoverProducts($slug){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        $title = ucwords(str_replace('-', ' ', $slug));
        if(!$metas){
            $metas = (object)[
                "title" => "Best $title Phone Cases in {$country->country_name}",
                "description" => "Protect your $title with premium phone cases designed for durability, style, and functionality. Buy now in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "$title Phone Cases {$country->country_name}",
                "name" => "$title Phone Cases"
            ];
        }
        $products = Product::whereHas('attributes', function ($query) use ($slug) {
            $query->where('attribute_id', 312)
                  ->where('value', $slug);
        });

        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(8);
        return view("frontend.brand",compact('products','metas','category','country')); 
    }
    public function FilterPhoneCoverByBrandProducts($brand, $slug){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        $title = ucwords(str_replace('-', ' ', $slug));
        if(!$metas){
            $metas = (object)[
                "title" => "Best ".Str::title($brand)." $title Phone Cases in {$country->country_name}",
                "description" => "Protect your ".Str::title($brand)." $title with premium phone cases designed for durability, style, and functionality. Buy now in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => Str::title($brand)." $title Phone Cases {$country->country_name}",
                "name" => Str::title($brand)." $title Phone Cases"
            ];
        }
        $products = Product::whereHas('attributes', function ($query) use ($slug) {
            $query->where('attribute_id', 312)
                  ->where('value', $slug);
        });

        // Filter by brand slug
        $products = $products->whereHas('brand', function ($query) use ($brand) {
            $query->where('slug', $brand);
        });

        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(8);
        return view("frontend.brand",compact('products','metas','category','country')); 
    }
    public function getMeta($slug, $brand,$name,$country)
    {
        $metas = DB::table("pages")->whereSlug($slug)->first();

        return $metas ?: (object)[
            "title" => "{$brand->name} Latest {$name} Reviews, Features, Price in {$country->country_name}",
            "description" => "Mobilekishop is the best website that provides the latest {$brand->name} {$name} prices in {$country->country_name} with specifications, features, reviews and comparison.",
            "canonical" => $slug,
            "h1" => "{$brand->name} {$name} Price in {$country->country_name}",
            "name"      => $name
        ];
    }
}
