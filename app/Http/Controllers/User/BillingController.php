<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BillingController extends Controller
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

    //  Billing
    public function billing($id)
    {
        // Selected plan
        $selected_plan = Plan::where('id', $id)->where('status', 1)->first();
        $config = Config::get();

        // Check price
        if ($selected_plan->price != 0.0) {
            // Queries
            $user = User::where('id', Auth::user()->id)->first();
            $settings = Setting::first();

            return view('user.pages.billing.index', compact('user', 'settings'));
        } else {
            // Free plan
            $transaction = new Transaction();
            $transaction->transaction_date = now();
            $transaction->transaction_id = uniqid();
            $transaction->user_id = Auth::user()->id;
            $transaction->plan_id = $selected_plan->id;
            $transaction->desciption = $selected_plan->name . " Plan";
            $transaction->payment_gateway_name = "FREE";
            $transaction->transaction_amount = $selected_plan->price;
            $transaction->transaction_currency = $config[1]->config_value;
            $transaction->invoice_details = "";
            $transaction->payment_status = "SUCCESS";
            $transaction->save();

            $plan_validity = Carbon::now();
            $plan_validity->addDays($selected_plan->validity);
            
            User::where('id', Auth::user()->id)->update([
                'plan_id' => $id,
                'term' => $selected_plan->validity,
                'plan_validity' => $plan_validity,
                'plan_activation_date' => now(),
                'plan_details' => $selected_plan,
            ]);
            return redirect()->route('user.plans')->with('success', trans("FREE Plan activated!"));
        }
    }
}
