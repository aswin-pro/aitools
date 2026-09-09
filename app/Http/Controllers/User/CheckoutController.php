<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Gateway;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private ?Plan $plan = null;
    private Collection $config;
    private Setting $settings;

    public function __construct()
    {
        // plan
        $this->plan = Plan::where('id', request()->route('plan'))->where('status', 1)->first();

        // config
        $this->config = Config::get();

        // settings
        $this->settings = Setting::where('status', 1)->first();
    }

    // Checkout
    public function checkout(): RedirectResponse | View
    {
        // check auth
        if (! Auth::user()) {
            return redirect()->route('login');
        }

        // check plan
        if (empty($this->plan)) {
            abort(404);
        }

        // check is free plan
        if ($this->plan->price == 0) {
            // check already used free plan
            if (Transaction::where('plan_id', $this->plan->id)->where('payment_status', Transaction::PAYMENT_SUCCESS)->exists()) {
                return redirect()->route('dashboard.user.subscriptions.plans')->with('error', "You already used free plan.");
            }

            // assign free plan
            $this->assignFreePlan();

            // return success
            return redirect()->route('dashboard.user.subscriptions.index')->with('success', "FREE Plan activated!");
        } else {
            // payment gateways
            $payment_gateways = Gateway::where('is_status', Gateway::STATUS_ENABLED)->get();

            // plan price and tax
            $price = (int) $this->plan->price;

            // tax price
            $tax_price = getTaxPrice($this->config, $price);

            // total
            $total = $price + $tax_price;

            // return view
            return view('user.pages.checkout.index', [
                'settings'         => $this->settings,
                'config'           => $this->config,
                'plan'             => $this->plan,
                'payment_gateways' => $payment_gateways,
                'price'            => $price,
                'tax_price'        => $tax_price,
                'total'            => $total,
            ]);
        }
    }

    // assign free plan
    private function assignFreePlan(): void
    {
        // Save transaction
        $transaction                       = new Transaction();
        $transaction->transaction_id       = uniqid();
        $transaction->transaction_date     = now();
        $transaction->user_id              = Auth::user()->id;
        $transaction->plan_id              = $this->plan->id;
        $transaction->description          = $this->plan->name . " Plan";
        $transaction->payment_gateway_name = "FREE";
        $transaction->transaction_amount   = $this->plan->price;
        $transaction->transaction_currency = $this->config[1]->config_value;
        $transaction->invoice_details      = json_encode([]);
        $transaction->payment_status       = "SUCCESS";
        $transaction->save();

        // update plan details
        $user                       = Auth::user();
        $user->plan_id              = $this->plan->id;
        $user->term                 = $this->plan->validity;
        $user->plan_validity        = Carbon::now()->addDays($this->plan->validity);
        $user->plan_activation_date = now();
        $user->plan_details         = $this->plan;
        $user->save();

        // assign ai credits
        addCredits('plan', $this->plan);
    }
}
