<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PlanActivationService;
use App\Services\TransactionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Mollie\Laravel\Facades\Mollie;

class MollieController extends Controller
{
    private Collection $config;

    public function __construct()
    {
        // config
        $this->config = Config::get();

        // initialize Mollie
        Mollie::api()->setApiKey(trim($this->config[41]->config_value));
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

            // Transaction ID
            $transactionId = uniqid();

            // payment
            $payment = Mollie::api()->payments->create([
                'amount'      => [
                    "currency" => $this->config[1]->config_value,
                    "value"    => number_format($total, 2),
                ],
                'description' => "Initial Plan Payment",
                'redirectUrl' => route('payment.mollie.status'),
                "metadata"    => [
                    "transactionId" => $transactionId,
                    "userId"        => Auth::user()->id,
                ],
            ]);

            // Create service object
            $transactionService = new TransactionService();

            // Create pending transaction
            $transactionService->createPendingTransaction(
                $this->config,
                Auth::user(),
                $plan_details,
                $payment->id,
                $total,
                'Mollie'
            );

            // redirect to mollie payment
            return redirect($payment->getCheckoutUrl(), 303);
        } catch (\Exception $e) {
            return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
        }
    }

    // payment status
    public function paymentStatus()
    {
        // Get transaction details
        $transaction_details = Transaction::where('user_id', Auth::user()->id)->where('status', 1)->latest()->first();

        // Check payment
        $paymentDetails = Mollie::api()->payments->get($transaction_details->transaction_id);

        // Check payment id
        if (! $paymentDetails) {
            abort(404);
        }

        try {
            // Is paid
            if ($paymentDetails->isPaid()) {
                // Queries
                $transactionId = $transaction_details->transaction_id;
                $paymentId     = $paymentDetails->id;

                // activate plan
                $message = (new PlanActivationService())->activate(
                    $this->config,
                    Auth::user(),
                    $transactionId,
                    $paymentId
                );

                // return success
                return redirect()->route('dashboard.user.subscriptions.index')->with('success', $message);
            } else {
                // Update tranaction details
                Transaction::where('transaction_id', $transaction_details->transaction_id)->update([
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
