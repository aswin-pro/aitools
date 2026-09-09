<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Transaction;
use App\Services\PlanActivationService;
use App\Services\TransactionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class RazorPayController extends Controller
{
    protected Collection $config;
    protected Setting $settings;
    protected Api $api;

    public function __construct()
    {
        // config
        $this->config = Config::get();

        // settings
        $this->settings = Setting::first();

        // RazorPay API
        $RAZOR_KEY    = $this->config[6]->config_value;
        $RAZOR_SECRET = $this->config[7]->config_value;

        // initialize RazorPay API
        $this->api = new Api($RAZOR_KEY, $RAZOR_SECRET);
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
            // transaction id
            $transaction_id = uniqid();

            // price
            $price = (float) ($plan_details->price);

            // tax price
            $tax_price = getTaxPrice($this->config, $price);

            // total
            $total = $price + $tax_price;

            // amount
            $amountInPaise = (float) number_format($total, 2) * 100;

            // Create order
            $order = $this->api->order->create(['receipt' => $transaction_id, 'amount' => (int) $amountInPaise, 'currency' => $this->config[1]->config_value]);

            // Create service object
            $transactionService = new TransactionService();

            // Create pending transaction
            $transactionService->createPendingTransaction(
                $this->config,
                Auth::user(),
                $plan_details,
                $order->id,
                $total,
                'Razorpay'
            );

            // view
            return view('user.pages.checkout.pay-with-razorpay', [
                'settings'       => $this->settings,
                'plan_details'   => $plan_details,
                'transaction_id' => $order->id,
                'order'          => $order,
                'config'         => $this->config,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
        }
    }

    // payment status
    public function paymentStatus(string $oid, string $paymentId)
    {
        // check request data
        if ($oid == "" || $paymentId == "") {
            abort(404);
        }

        try {
            // payment data
            $payment = $this->api->payment->fetch($paymentId);

            // Check razorpay status
            if ($payment->status == "authorized" || $payment->status == "captured") {
                // activate plan
                $message = (new PlanActivationService())->activate(
                    $this->config,
                    Auth::user(),
                    $oid,
                    $paymentId
                );

                // return success
                return redirect()->route('dashboard.user.subscriptions.index')->with('success', $message);
            } else {
                // Update transaction details
                Transaction::where('transaction_id', $oid)->update([
                    'transaction_id' => $paymentId,
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
}
