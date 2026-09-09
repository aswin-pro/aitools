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
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TransactionCloudController extends Controller
{
    protected Collection $config;
    protected Setting $settings;

    public function __construct()
    {
        // config
        $this->config   = Config::get();
        $this->settings = Setting::first();
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
            $transaction_id = uniqid();

            // Create service object
            $transactionService = new TransactionService();

            // Create pending transaction
            $transactionService->createPendingTransaction(
                $this->config,
                Auth::user(),
                $plan_details,
                $transaction_id,
                $total,
                'Transaction Cloud'
            );

            // view
            return view('user.pages.checkout.pay-with-transaction-cloud', [
                'settings'       => $this->settings,
                'plan_details'   => $plan_details,
                'config'         => $this->config,
                'transaction_id' => $transaction_id,
            ]);
        } catch (\Exception $e) {
            // return error
            return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
        }
    }

    // Payment Status
    public function paymentStatus(Request $request)
    {
        try {
            // http client
            $client = new Client();

            // transaction details
            $res = $client->request('GET', 'https://api.transaction.cloud/v1/transaction/' . $request->query('id'), [
                'headers' => [
                    'Authorization' => $this->config[44]->config_value . ':' . $this->config[45]->config_value,
                ],
            ]);

            // payment details
            $paymentDetails = json_decode($res->getBody(), true);

            // Check payment id
            if (! $paymentDetails) {
                abort(404);
            }

            // Transaction ID
            $transactionId = $paymentDetails['payload'];

            // Payment ID
            $paymentId = $paymentDetails['id'];

            // Is paid
            if ($paymentDetails['transactionStatus'] == "ONE_TIME_PAYMENT_STATUS_PAID") {
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
                Transaction::where('transaction_id', $transactionId)->update([
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
