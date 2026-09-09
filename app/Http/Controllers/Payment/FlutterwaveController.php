<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Plan;
use App\Models\Transaction;
use App\Services\PlanActivationService;
use App\Services\TransactionService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class FlutterwaveController extends Controller
{
    protected string $secretKey;
    protected string $baseUrl;
    protected Collection $config;

    public function __construct()
    {
        // config
        $this->config = Config::get();

        // secret key
        $this->secretKey = $this->config[68]->config_value;

        // base url
        $this->baseUrl = "https://api.flutterwave.com/v3";
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

            // transaction id
            $transactionId = uniqid();

            // create client
            $client = new Client();

            // build payload
            $data = [
                'tx_ref'         => $transactionId,
                'amount'         => $total,
                'currency'       => $this->config[1]->config_value,
                'redirect_url'   => route('payment.flutterwave.status'),
                'customer'       => [
                    'email'        => Auth::user()->email,
                    'name'         => Auth::user()->name,
                    'phone_number' => Auth::user()->billing_phone,
                ],
                'customizations' => [
                    'title' => config('app.name'),
                    'logo'  => asset('img/favicon.png'),
                ],
            ];

            // send request
            $response = $client->post("{$this->baseUrl}/payments", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type'  => 'application/json',
                ],
                'json'    => $data,
            ]);

            // parse response
            $responseBody = json_decode($response->getBody(), true);

            // check response body
            if ($responseBody['status'] === 'success') {

                // Create service object
                $transactionService = new TransactionService();

                // Create pending transaction
                $transactionService->createPendingTransaction(
                    $this->config,
                    Auth::user(),
                    $plan_details,
                    $transactionId,
                    $total,
                    'Flutterwave'
                );

                // redirect to flutterwave
                return redirect($responseBody['data']['link']);
            } else {
                return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
            }
        } catch (\Exception $e) {
            return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
        }
    }

    // Payment Status
    public function paymentStatus(Request $request)
    {
        // transaction ref
        $transactionRef = $request->query('tx_ref');

        // status
        $transactionStatus = $request->query('status');

        // Transaction success
        if ($transactionStatus == "successful") {
            try {
                // Check if the transaction is already verified
                $transactionId = $request->query('transaction_id');

                // create client
                $client = new Client();

                // check if the transaction is already verified
                $response = $client->get("{$this->baseUrl}/transactions/{$transactionId}/verify", [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->secretKey,
                        'Content-Type'  => 'application/json',
                    ],
                ]);

                // parse response
                $verificationResponse = json_decode($response->getBody(), true);

                // Get tx_ref and flw_ref
                $tx_ref  = $verificationResponse['data']['tx_ref'];
                $flw_ref = $verificationResponse['data']['flw_ref'];

                if (empty($tx_ref) && empty($flw_ref)) {
                    // update transaction details
                    Transaction::where('transaction_id', $transactionRef)->update(['payment_status' => Transaction::PAYMENT_FAILED]);
                } else {
                    if ($verificationResponse['status'] === 'success') {
                        // activate plan
                        $message = (new PlanActivationService())->activate(
                            $this->config,
                            Auth::user(),
                            $tx_ref,
                            $flw_ref
                        );

                        // return success
                        return redirect()->route('dashboard.user.subscriptions.index')->with('success', $message);
                    }
                }

                // return error
                return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
            } catch (\Exception $e) {
                // return error
                return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
            }
        } elseif ($transactionStatus === 'failed') {
            // Update transaction details
            Transaction::where('transaction_id', $transactionRef)->update([
                'payment_status' => Transaction::PAYMENT_FAILED,
            ]);

            // return error
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
        } elseif ($transactionStatus === 'cancelled') {
            // Update transaction details
            Transaction::where('transaction_id', $transactionRef)->update([
                'payment_status' => Transaction::PAYMENT_CANCELLED,
            ]);

            // return error
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment cancelled!");
        }

        // return error
        return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Invalid transaction status!");
    }
}
