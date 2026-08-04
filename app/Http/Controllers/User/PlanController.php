<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Setting;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\CustomTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
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

    // plans
    public function index()
    {
        // Plans & templates
        $plans = Plan::where('status', 1)->where('is_private', '0')->get();
        $templates = CustomTemplate::where('status', 1)->get();

        // Get access templates
        $access_templates = "";
        for ($i = 0; $i < count($plans); $i++) {
            $planTemplate = json_decode($plans[$i]->templates, true);
            for ($j = 0; $j < count($templates); $j++) {
                $currentTemplate = $templates[$j]->unique_slug;

                if (isset($planTemplate[$currentTemplate]) == 1) {
                    $plans[$i]->access_templates .= $templates[$j]->name . ', ';
                }
            }
        }

        // Queries
        $config = Config::get();
        $settings = Setting::where('status', 1)->first();
        $currency = Currency::where('iso_code', $config[1]->config_value)->first();

        // Current user plan details
        $free_plan = Transaction::where('user_id', Auth::user()->id)->where('transaction_amount', '0')->count();

        $plan = User::where('id', Auth::user()->id)->first();
        $active_plan = json_decode($plan->plan_details);

        // Initial remaining days
        $remaining_days = 0;

        // Check plan
        if (isset($active_plan)) {
            $plan_validity = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', Auth::user()->plan_validity);
            $current_date = Carbon::now();

            // Remaining days
            $remaining_days = $current_date->diffInDays($plan_validity, false);
        }

        return view('user.pages.plans.index', compact('plans', 'settings', 'currency', 'active_plan', 'remaining_days', 'config', 'free_plan'));
    }
}
