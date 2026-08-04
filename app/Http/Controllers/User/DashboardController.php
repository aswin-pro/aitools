<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\User;
use App\Models\Config;
use App\Models\Setting;
use App\Models\AiImages;
use App\Models\Generate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // Dashboard
    public function index()
    {
        // User current plan details
        $plan = User::where('id', Auth::user()->id)->first();
        $active_plan = json_decode($plan->plan_details);

        // Queries
        $settings = Setting::where('status', 1)->first();
        $config = Config::get();

        // Initial Remaining days
        $remaining_days = 0;

        $current_words = 0;
        $current_images = 0;

        // Subscription words
        if (Auth::user()->plan_details != null) {
            $current_words = json_decode(Auth::user()->plan_details)->max_words;
            $current_images = json_decode(Auth::user()->plan_details)->max_images;
        }

        // Get used words
        $generateWordCount = Generate::where('generate_by', Auth::user()->id)->sum('word_count');

        // Get chat word count
        $chatWordCount = Chat::where('generate_by', Auth::user()->id)->sum('word_count');

        // Sum of Generates and chats
        $usedWords = $generateWordCount + $chatWordCount;

        // Get used images
        $usedImages = AiImages::where('generate_by', Auth::user()->id)->sum('n'); 

        // Get User generate content
        $generates = Generate::where('generate_by', Auth::user()->id)->where('type', '!=', 'chatgenius')->where('type', '!=', 'Speech to Text')->where('type', '!=', 'Text To Speech')->where('type', '!=', 'Code')->where('status', 1)->orderBy('id', 'desc')->limit(10)->get();

        // Get User generate images
        $images = AiImages::where('generate_by', Auth::user()->id)->orderBy('id', 'desc')->limit(8)->get();

        // Get today generate content count
        $todayGenerateWordCount = Generate::where('generate_by', Auth::user()->id)->whereDate('created_at', Carbon::today())->sum('word_count');
        $todatGenearteChatWordCount = Chat::where('generate_by', Auth::user()->id)->whereDate('created_at', Carbon::today())->sum('word_count');

        $today_generates = $todayGenerateWordCount + $todatGenearteChatWordCount;

        // Get today generate images count
        $today_images = AiImages::where('generate_by', Auth::user()->id)->whereDate('created_at', Carbon::today())->sum('n');

        // Month wise data
        $imagesData = [];
        $contentsData = [];
        for ($i = 1; $i <= 12; $i++) {
            $startDate = Carbon::create(date('Y'), $i);
            $endDate = $startDate->copy()->endOfMonth();
            // Get month wise generate content count
            $contentsGenerateArray = Generate::where('generate_by', Auth::user()->id)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->count();
            $contentsChatArray = Chat::where('generate_by', Auth::user()->id)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->count();
            $contentsArray = $contentsGenerateArray + $contentsChatArray;
            
            // Get month wise generate images count
            $imagesArray = AiImages::where('generate_by', Auth::user()->id)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->sum('n');
            $contentsData[$i] = $contentsArray;
            $imagesData[$i] = $imagesArray;
        }
        $contentsData = implode(',', $contentsData);
        $imagesData = implode(',', $imagesData);

        // Check active plan
        if ($active_plan != null) {
            if (isset($active_plan)) {
                $plan_validity = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', Auth::user()->plan_validity);
                $current_date = Carbon::now();

                // Remaining dates
                $remaining_days = $current_date->diffInDays($plan_validity, false);
            }

            return view('user.index', compact('active_plan', 'remaining_days', 'generates', 'today_generates', 'contentsData', 'imagesData', 'images', 'today_images', 'current_words', 'current_images', 'usedWords', 'usedImages', 'settings', 'config'));
        } else {
            // Redirect plan
            return redirect()->route('user.plans');
        }
    }
}
