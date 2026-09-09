<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PlanActivationService;
use App\Services\TransactionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeController extends Controller
{
    protected Collection $config;
    protected Setting $settings;

    public function __construct()
    {
        // config
        $this->config = Config::get();

        // settings
        $this->settings = Setting::first();

        // initialize Stripe
        Stripe::setApiKey(trim($this->config[10]->config_value));
    }

    // index
    public function index(string $planId)
    {
        // check auth
        if (! Auth::user()) {
            return redirect()->route('login');
        }

        // plan details
        $plan_details = Plan::where('id', $planId)->where('status', 1)->first();

        // check plan details
        if ($plan_details == null) {
            abort(404);
        }

        try {

            // price
            $price = (float) ($plan_details->price);

            // tax price
            $tax_price = getTaxPrice($this->config, $price);

            // total
            $total = $price + $tax_price;

            // amount
            $amountInPaise = (float) number_format($total, 2) * 100;

            // Stripe payment intent
            $payment_intent = PaymentIntent::create([
                'description'          => $plan_details->name . " Plan",
                'shipping'             => [
                    'name'    => Auth::user()->name,
                    'address' => [
                        'line1'       => Auth::user()->billing_address,
                        'postal_code' => Auth::user()->billing_zipcode,
                        'city'        => Auth::user()->billing_city,
                        'state'       => Auth::user()->billing_state,
                        'country'     => Auth::user()->billing_country,
                    ],
                ],
                'amount'               => $amountInPaise,
                'currency'             => $this->config[1]->config_value,
                'payment_method_types' => ['card'],
            ]);

            $intent    = $payment_intent->client_secret;
            $paymentId = $payment_intent->id;

            // Create service object
            $transactionService = new TransactionService();

            // Create pending transaction
            $transactionService->createPendingTransaction(
                $this->config,
                Auth::user(),
                $plan_details,
                $paymentId,
                $total,
                'Stripe'
            );

            // view
            return view('user.pages.checkout.pay-with-stripe', [
                'settings'     => $this->settings,
                'intent'       => $intent,
                'plan_details' => $plan_details,
                'config'       => $this->config,
                'paymentId'    => $paymentId,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
        }
    }

    // payment status
    public function paymentStatus(string $paymentId)
    {
        // Check payment id
        if (! $paymentId) {
            return view('errors.404');
        }

        try {
            // stripe client
            $stripe = new StripeClient($this->config[10]->config_value);

            // payment details
            $payment = $stripe->paymentIntents->retrieve($paymentId, []);

            // Check payment status
            if ($payment->status == "succeeded") {
                // activate plan
                $message = (new PlanActivationService())->activate(
                    $this->config,
                    Auth::user(),
                    $paymentId,
                    $paymentId
                );

                // return success
                return redirect()->route('dashboard.user.subscriptions.index')->with('success', $message);
            } else {
                // Update tranaction details
                Transaction::where('transaction_id', $paymentId)->update([
                    'payment_status' => Transaction::PAYMENT_FAILED,
                ]);

                // return error
                return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
            }
        } catch (\Exception $e) {
            // return error
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
        }
    }

    public function paymentCancel(string $paymentId)
    {
        // check request data
        if (! $paymentId) {
            abort(404);
        }

        // stripe client
        $stripe = new StripeClient($this->config[10]->config_value);

        try {
            // cancel payment
            $stripe->paymentIntents->cancel($paymentId, []);

            // Update transaction details
            Transaction::where('transaction_id', $paymentId)->update([
                'payment_status' => Transaction::PAYMENT_FAILED,
            ]);

            // return success
            return redirect()->route('dashboard.user.subscriptions.index')->with('success', "Payment cancelled!");
        } catch (\Exception $e) {
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Request failed!");
        }
    }
}
