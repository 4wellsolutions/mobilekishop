<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendRegistrationEmail;
use Auth;
use URL;
use App\Models\User;
use Validator;
use Response;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo()
    {
        $type = Auth::user()->type_id;
        if ($type == "1" || $type == "2" || $type == "3") {
            return route('dashboard.index');
        }

        // Redirect back to the page they came from (supports country routes)
        return session('login.intended_url', route('user.index'));
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form — store the referrer so we can redirect back after login.
     */
    public function showLoginForm()
    {
        // Store the previous page URL (only if it's from our own site)
        $referer = request()->headers->get('referer');
        if ($referer && str_contains($referer, request()->getHost()) && !str_contains($referer, '/login')) {
            session(['login.intended_url' => $referer]);
        }

        return view('auth.login');
    }
    public function postLogin(Request $request)
    {
        // dd($request->all());
        $auth = false;
        $credentials = [
            "email" => $request->login_email,
            "password" => $request->login_password,
        ];


        if (Auth::attempt($credentials, $request->has('remember'))) {
            $auth = true; // Success
            $type = Auth::user()->type_id;
            if ($type == "1" || $type == "2" || $type == "3") {
                return response()->json([
                    'auth' => $auth,
                    'intended' => URL::route('dashboard.index')
                ]);
            } else {
                // Redirect back to the page they came from (supports country routes)
                $intended = session()->pull('login.intended_url', URL::route('user.index'));
                return response()->json([
                    'auth' => $auth,
                    'intended' => $intended
                ]);
            }
        }

        // dd($auth);
        return response()->json([
            'auth' => $auth,
            'errors' => ["Wrong login details"]
        ]);

    }
    public function socialRedirect($social, Request $request)
    {
        // Store the initial URL in session to redirect back to it after authentication
        session(['redirect_url' => $request->headers->get('referer')]);

        return Socialite::driver($social)->redirect();
    }
    public function socialCallback($social)
    {

        try {
            $socialUser = Socialite::driver($social)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['msg' => 'Unable to login using ' . $social . '. Please try again.']);
        }

        // Find or create the user based on Google information
        $user = User::where('google_id', $socialUser->id)->orWhere("email", $socialUser->email)->first();
        if ($user) {
            $user->update([
                'google_token' => $socialUser->token,
                'google_refresh_token' => $socialUser->refreshToken,
            ]);
        } else {
            $user = User::create([
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'password' => bcrypt($socialUser->name),
                'type_id' => "4",
                'google_id' => $socialUser->id,
                'google_token' => $socialUser->token,
                'google_refresh_token' => $socialUser->refreshToken,
            ]);
        }

        Auth::login($user);

        // Redirect admins to dashboard, regular users to previous page
        $type = $user->type_id;
        if ($type == "1" || $type == "2" || $type == "3") {
            return redirect()->route('dashboard.index');
        }

        // Get the initially stored URL and redirect back to it
        $redirectUrl = session('redirect_url', url('/'));

        return redirect($redirectUrl);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'newsletter' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return Response::make(['success' => false, "errors" => $validator->errors()]);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'newsletter' => isset($request->newsletter) ? 1 : 0,
            'type_id' => 4,
            'password' => Hash::make($request->password),
        ]);
        $credentials = $request->only('email', 'password');
        // send registration email
        SendRegistrationEmail::dispatch($user);
        // send registration email
        if (Auth::attempt($credentials)) {
            return Response::make(['success' => true, "message" => "Register Successfully."]);
        }


    }
}