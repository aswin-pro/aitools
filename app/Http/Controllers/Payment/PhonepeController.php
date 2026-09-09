<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Plan;
use App\Models\Transaction;
use App\Services\PlanActivationService;
use App\Services\TransactionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PhonepeController extends Controller
{
    private Collection $config;
    private string $authUrl = "https://api.phonepe.com/apis/identity-manager/v1/oauth/token";
    private mixed $authToken;

    public function __construct()
    {
        // config
        $this->config = Config::get();

        // auth token
        $this->authToken = $this->getPhonePeAuthToken();
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

        // check auth token
        if (empty($this->authToken)) {
            return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Failed to fetch PhonePe authentication token.");
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

            // transaction id
            $transactionId = "TX-" . uniqid();

            // build data
            $data = [
                'merchantOrderId' => $transactionId,
                'amount'          => $amountInPaise,
                'paymentFlow'     => [
                    'type'         => 'PG_CHECKOUT',
                    'merchantUrls' => [
                        'redirectUrl' => route('payment.phonepe.status'),
                    ],
                ],
            ];

            // send request
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => "O-Bearer " . $this->authToken,
            ])->post('https://api.phonepe.com/apis/pg/checkout/v2/pay', $data);

            // response data
            $responseData = $response->json();

            // Redirect on success
            if (! empty($responseData['state']) && $responseData['state'] == "PENDING") {
                // Create service object
                $transactionService = new TransactionService();

                // Create pending transaction
                $transactionService->createPendingTransaction(
                    $this->config,
                    Auth::user(),
                    $plan_details,
                    $transactionId,
                    $total,
                    'Phonepe'
                );

                // redirect to phonepe
                return redirect()->to($responseData['redirectUrl']);
            } else {
                // return error
                return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
            }
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
        }
    }

    // payment status
    public function paymentStatus()
    {
        // check auth token
        if (empty($this->authToken)) {
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
        }

        try {
            // get last transaction
            $transactionDetails = Transaction::where('payment_gateway_name', 'PhonePe')->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();

            // status url
            $statusUrl = "https://api.phonepe.com/apis/pg/checkout/v2/order/" . $transactionDetails->transaction_id . "/status?details=false&errorContext=true";

            // send request
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => "O-Bearer " . $this->authToken,
            ])->get($statusUrl);

            // response data
            $res = json_decode($response->body());

            // Check status is failed
            if ($res->success == false) {
                return redirect()->route('dashboard.user.subscriptions.index')->with('error', trans($res->message));
            }

            // Check status is completed
            if ($res->state == "COMPLETED") {
                // Get transactionId
                $paymentId = $res->paymentDetails[0]->transactionId;

                // activate plan
                $message = (new PlanActivationService())->activate(
                    $this->config,
                    Auth::user(),
                    $transactionDetails->transaction_id,
                    $paymentId
                );

                // return success
                return redirect()->route('dashboard.user.subscriptions.index')->with('success', $message);
            } else {
                // Update transaction details
                Transaction::where('transaction_id', $transactionDetails->transaction_id)->update([
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

    private function getPhonePeAuthToken()
    {
        // Check if the config values are empty
        if ($this->config[53]->config_value == 'YOUR_PHONEPE_CLIENT_ID' || $this->config[57]->config_value == 'YOUR_PHONEPE_CLIENT_VERSION' || $this->config[54]->config_value == 'YOUR_PHONEPE_CLIENT_SECRET') {
            return null;
        }

        // Set the payload
        $payload = [
            "client_id"      => $this->config[53]->config_value,
            "client_version" => $this->config[57]->config_value,
            "client_secret"  => $this->config[54]->config_value,
            "grant_type"     => "client_credentials",
        ];

        // Send the request
        $response = Http::asForm()->post($this->authUrl, $payload);

        // Decode the response
        $responseData = $response->json();

        // Check if the response contains an access token
        if (isset($responseData['access_token'])) {
            // return access token
            return $responseData['access_token'];
        } else {
            return null;
        }
    }
}
