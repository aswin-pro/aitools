<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Page;
use App\Models\User;
use App\Models\Config;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Providers\RouteServiceProvider;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\OpenGraph;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if (env('RECAPTCHA_ENABLE') == 'on') {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:5', 'confirmed'],
                'g-recaptcha-response' => ['recaptcha', 'required']
            ]);
        } else {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:5', 'confirmed']
            ]);
        }
    }

    // Redirect to dashboard
    protected function redirectTo()
    {
        return '/user/dashboard';
    }


    // Show register page
    public function showRegistrationForm()
    {
        // Queries
        $config = Config::get();
        $settings = Setting::first();
        $page = Page::where('status', 1)->first();

        // Recaptcha Configuration
        $recaptcha_configuration = [
            'RECAPTCHA_ENABLE' => env('RECAPTCHA_ENABLE', 'off'),
            'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY', ''),
            'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY', ''),
            'RECAPTCHA_SKIP_IP' => env('RECAPTCHA_SKIP_IP', '[]'),
        ];

        $settings['recaptcha_configuration'] = $recaptcha_configuration;

        // Seo Tools
        SEOTools::setTitle(trans('Sign up') . ' - ' . config('app.name'));
        SEOTools::setDescription($page->description);

        SEOMeta::setTitle(trans('Sign up') . ' - ' . config('app.name'));
        SEOMeta::setDescription($page->description);
        SEOMeta::addMeta('article:section', $page->seo_site, 'property');
        SEOMeta::addKeyword([$page->seo_keywords]);

        OpenGraph::setTitle(trans('Sign up') . ' - ' . config('app.name'));
        OpenGraph::setDescription($page->description);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addImage([asset($settings->site_logo), 'size' => 300]);

        JsonLd::setTitle(trans('Sign up') . ' - ' . config('app.name'));
        JsonLd::setDescription($page->description);
        JsonLd::addImage(asset($settings->site_logo));

        return view('auth.register', compact('config', 'settings'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Queries
        $config = Config::get();

        // User saved
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->email_verified_at = $config[52]->config_value == '1' ? null : now();
        $user->auth_type = 'Email';
        $user->password = Hash::make($data['password']);
        $user->save();

        // Email Message
        $message = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        try {
            // Send Welcome email
            Mail::to($data['email'])->send(new \App\Mail\WelcomeMail($message));
            // Send email verification
            // $user->newEmail($data['email']);
        } catch (Exception $e) {
            // Return send user details
            return $user;
        }

        // Return user details
        return $user;
    }
}
