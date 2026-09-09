<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Plan;
use App\Models\Transaction;
use App\Services\PlanActivationService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaystackController extends Controller
{
    private Collection $config;

    // Paystack
    public function __construct()
    {
        // config
        $this->config = Config::get();
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

            // amount in paise
            $amountInPaise = (float) number_format($total, 2) * 100;

            // Transaction ID
            $transactionId = uniqid();

            // send request
            $response = Http::withToken($this->config[38]->config_value)
                ->post('https://api.paystack.co/transaction/initialize', [
                    'email'        => Auth::user()->email,
                    'amount'       => (int) $amountInPaise,
                    'currency'     => $this->config[1]->config_value,
                    'reference'    => $transactionId,
                    'callback_url' => route('payment.paystack.callback'),
                    'metadata'     => [
                        'transactionId' => $transactionId,
                    ],
                ]);

            if (! $response->successful()) {
                return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
            }

            // Create service object
            $transactionService = new TransactionService();

            // Create pending transaction
            $transactionService->createPendingTransaction(
                $this->config,
                Auth::user(),
                $plan_details,
                $transactionId,
                $total,
                'Paystack'
            );

            // redirect to paystack
            return redirect($response->json('data.authorization_url'));
        } catch (\Exception $e) {
            return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
        }
    }

    // callback
    public function callback(Request $request)
    {
        // reference
        $reference = $request->reference;

        // check reference
        if (! $reference) {
            // return error
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
        }

        // Verify payment with Paystack
        $response = Http::withToken($this->config[38]->config_value)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        // check response
        if (! $response->successful()) {
            // return error
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Unable to verify payment.");
        }

        // parse response
        $payment = $response->json();

        // Get our transaction id from metadata
        $transactionId = $payment['data']['metadata']['transactionId'];

        // payment id
        $paymentId = $payment['data']['reference'];

        // Check payment status
        if ($payment['data']['status'] == "success") {
            try {
                // activate plan
                $message = (new PlanActivationService())->activate(
                    $this->config,
                    Auth::user(),
                    $transactionId,
                    $paymentId
                );

                // return success
                return redirect()->route('dashboard.user.subscriptions.index')->with('success', $message);
            } catch (\Exception $e) {
                // return error
                return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
            }
        } else {
            // Update transaction details
            Transaction::where('transaction_id', $transactionId)->update([
                'transaction_id' => $paymentId,
                'payment_status' => Transaction::PAYMENT_FAILED,
            ]);

            // return error
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
        }
    }
}
