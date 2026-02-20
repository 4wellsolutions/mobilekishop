<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Services\MetaService;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Review;
use App\Models\Contact;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Jobs\sendReviewPostEmail;

class HomeController extends Controller
{
    public function __construct(
        private CountryService $countryService,
        private MetaService $metaService
    ) {
    }

    /**
     * Home page
     */
    public function index(Request $request)
    {
        $country = $request->attributes->get('country');

        // Fetch data for home page (customized for country if needed)
        // For now, let's keep it simple as per legacy but using services later
        $categories = Category::where('is_active', 1)
            ->get()
            ->map(function ($category) use ($country) {
                /** @var \App\Models\Category $category */
                $category->latest_products = $category->products()
                    ->whereHas('variants', function ($query) use ($country) {
                        $query->where('country_id', $country->id)->where('price', '>', 0);
                    })
                    ->with([
                        'variants' => function ($query) use ($country) {
                            $query->where('country_id', $country->id)->where('price', '>', 0);
                        },
                        'brand',
                        'category',
                        'attributes' => function ($query) {
                            $query->whereIn('attributes.name', ['size', 'chipset', 'main', 'capacity', 'battery']);
                        },
                    ])
                    ->latest()
                    ->take(4)
                    ->get();
                return $category;
            });

        // Generate meta
        $metas = $this->metaService->generateHomeMeta($country);

        return view('frontend.index', compact('categories', 'country', 'metas'));
    }

    /**
     * Static Pages
     */
    public function aboutUs(Request $request)
    {
        $country = $request->attributes->get('country');
        $metas = (object) [
            "title" => "About Us - Mobilekishop",
            "description" => "About Us - Find latest Mobiles phones prices in {$country->country_name}.",
            "canonical" => url('/about-us'),
            "h1" => "About Us",
            "name" => "About Us"
        ];
        return view("frontend.about", compact('metas', 'country'));
    }

    public function privacyPolicy(Request $request)
    {
        $country = $request->attributes->get('country');
        $metas = (object) [
            "title" => "Privacy Policy - Mobilekishop",
            "description" => "Privacy Policy - Find latest Mobiles phones prices in {$country->country_name}.",
            "canonical" => url('/privacy-policy'),
            "h1" => "Privacy Policy",
            "name" => "Privacy Policy"
        ];
        return view("frontend.privacy-policy", compact('metas', 'country'));
    }

    public function termsConditions(Request $request)
    {
        $country = $request->attributes->get('country');
        $metas = (object) [
            "title" => "Terms and Conditions - Mobilekishop",
            "description" => "Terms and Conditions - Find latest Mobiles phones prices in {$country->country_name}.",
            "canonical" => url('/terms-and-conditions'),
            "h1" => "Terms and Conditions",
            "name" => "Terms and Conditions"
        ];
        return view("frontend.terms", compact('metas', 'country'));
    }

    public function contact(Request $request)
    {
        $country = $request->attributes->get('country');
        $metas = (object) [
            "title" => "Contact Us - Mobilekishop",
            "description" => "Contact Us - Find latest Mobiles phones prices in {$country->country_name}.",
            "canonical" => url('/contact'),
            "h1" => "Contact Us",
            "name" => "contact us"
        ];
        return view("frontend.contact", compact('metas', 'country'));
    }


    public function reviewPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stars' => 'required',
            'review' => 'nullable',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user_id = null;
        $email = $request->email;
        $name = $request->name;

        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $email = Auth::user()->email;
            $name = Auth::user()->name;
        } else {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user_id = $user->id;
                $email = $user->email;
                $name = $user->name;
            }
        }

        // Limit reviews
        if ($user_id) {
            $reviewsToday = Review::where('user_id', $user_id)
                ->whereDate('created_at', Carbon::today())
                ->count();

            if ($reviewsToday >= 10) {
                return response()->json(["success" => false, 'error' => 'Daily limit reached.'], 422);
            }
        }

        $review = new Review();
        $review->stars = $request->stars;
        $review->review = $request->review;
        $review->product_id = $request->product_id;
        $review->name = $name;
        $review->email = $email;
        $review->user_id = $user_id;
        $review->is_active = 0;
        $review->save();

        $this->saveLog('save Review', $request->all());
        sendReviewPostEmail::dispatch($review);

        return response()->json(['success' => true, "message" => 'Review pending approval'], 200);
    }

    public function contactPost(Request $request)
    {
        $this->validate($request, [
            "name" => ["required", "regex:/^[a-zA-Z\s]+$/"],
            "email" => "required|email",
            "phone" => "required|regex:/^\+?[0-9]{7,15}$/",
            "message" => "required",
        ]);

        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $sanitizedPhone;
        $contact->message = $request->message;
        $contact->save();

        Mail::send("emails.contact", ['contact' => $contact], function ($m) use ($contact) {
            $m->from('info@mobilekishop.net', 'MobileKiShop');
            $m->to($contact->email);
            $m->subject("Your Message Has Been Received");
        });

        return redirect()->back()->with("success", "Message received.");
    }

    private function saveLog($name = null, $log = null)
    {
        // Log implementation here if needed
    }
}
