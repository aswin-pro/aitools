<?php

namespace App\Http\Controllers\Payment;

use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Classes\UpgradePlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PhonepeController extends Controller
{
    public function preparePhonpe($planId)
    {
        if (Auth::user()) {

            // Queries
            $config = Config::get();
            $userData = User::where('id', Auth::user()->id)->first();
            $plan_details = Plan::where('id', $planId)->where('status', 1)->first();

            if (!$plan_details) {
                return view('errors.404');
            }

            $authToken = $this->getPhonePeAuthToken();
            if (!$authToken) {
                return redirect()->route('user.plans')->with('failed', trans('Failed to fetch PhonePe authentication token.'));
            }

            // Check plan details
            if ($plan_details == null) {
                return view('errors.404');
            } else {

                // Paid amount
                $amountToBePaid = ((float)($plan_details->price) * (float)($config[25]->config_value) / 100) + (float)($plan_details->price);
                $amountToBePaidPaise = (float)number_format($amountToBePaid, 2) * 100;

                // Generate a unique transaction ID
                $transactionId = "TX" . preg_replace('/\D/', '', Str::uuid());

                try {
                    $data = [
                        'merchantOrderId' => $transactionId,  // Unique transaction ID
                        'amount' => $amountToBePaidPaise,  // Amount in paise (1000 = ₹10)
                        'paymentFlow' => [
                            'type' => 'PG_CHECKOUT',  // Correct type for PhonePe checkout
                            'merchantUrls' => [
                                'redirectUrl' => route('phonepe.payment.status') // Redirect after payment
                            ]
                        ]
                    ];

                    $response = Http::withHeaders([
                        'Content-Type'  => 'application/json',
                        'Authorization' => "O-Bearer " . $authToken
                    ])->post('https://api.phonepe.com/apis/pg/checkout/v2/pay', $data);

                    // Get JSON response
                    $rData = $response->json();

                    // Redirect on success
                    if (!empty($rData['state']) && $rData['state'] == "PENDING") {
                        // Generate JSON
                        $invoice_details = [];

                        $invoice_details['from_billing_name'] = $config[16]->config_value;
                        $invoice_details['from_billing_address'] = $config[19]->config_value;
                        $invoice_details['from_billing_city'] = $config[20]->config_value;
                        $invoice_details['from_billing_state'] = $config[21]->config_value;
                        $invoice_details['from_billing_zipcode'] = $config[22]->config_value;
                        $invoice_details['from_billing_country'] = $config[23]->config_value;
                        $invoice_details['from_vat_number'] = $config[26]->config_value;
                        $invoice_details['from_billing_phone'] = $config[18]->config_value;
                        $invoice_details['from_billing_email'] = $config[17]->config_value;
                        $invoice_details['to_billing_name'] = $userData->billing_name;
                        $invoice_details['to_billing_address'] = $userData->billing_address;
                        $invoice_details['to_billing_city'] = $userData->billing_city;
                        $invoice_details['to_billing_state'] = $userData->billing_state;
                        $invoice_details['to_billing_zipcode'] = $userData->billing_zipcode;
                        $invoice_details['to_billing_country'] = $userData->billing_country;
                        $invoice_details['to_billing_phone'] = $userData->billing_phone;
                        $invoice_details['to_billing_email'] = $userData->billing_email;
                        $invoice_details['to_vat_number'] = $userData->vat_number;
                        $invoice_details['tax_name'] = $config[24]->config_value;
                        $invoice_details['tax_type'] = $config[14]->config_value;
                        $invoice_details['tax_value'] = (float)($config[25]->config_value);
                        $invoice_details['invoice_amount'] = $amountToBePaid;
                        $invoice_details['subtotal'] = $plan_details->price;
                        $invoice_details['tax_amount'] = (float)($plan_details->price) * (float)($config[25]->config_value) / 100;

                        // Save transactions
                        $transaction = new Transaction();
                        $transaction->transaction_date = now();
                        $transaction->transaction_id = $transactionId;
                        $transaction->user_id = Auth::user()->id;
                        $transaction->plan_id = $plan_details->id;
                        $transaction->desciption = $plan_details->name . " Plan";
                        $transaction->payment_gateway_name = "Phonepe";
                        $transaction->transaction_amount = $amountToBePaid;
                        $transaction->transaction_currency = $config[1]->config_value;
                        $transaction->invoice_details = json_encode($invoice_details);
                        $transaction->payment_status = "PENDING";
                        $transaction->save();

                        return redirect()->to($rData['redirectUrl']);
                    } else {
                        return redirect()->route('user.plans')->with('failed', trans('Payment failed.'));
                    }
                } catch (\Exception $e) {
                    return redirect()->route('user.plans')->with('failed', trans('Payment failed.'));
                }
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function phonepePaymentStatus(Request $request)
    {
        // Get last transaction id for phonepe and user id
        $transactionDetails = Transaction::where('payment_gateway_name', 'PhonePe')->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();

        if (isset($transactionDetails)) {
            $authToken = $this->getPhonePeAuthToken();
            if (!$authToken) {
                return redirect()->route('user.plans')->with('failed', trans('Failed to fetch PhonePe authentication token.'));
            }

            $statusUrl = "https://api.phonepe.com/apis/pg/checkout/v2/order/" . $transactionDetails->transaction_id . "/status?details=false&errorContext=true";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "O-Bearer " . $authToken
            ])->get($statusUrl);

            $res = json_decode($response->body());

            try {
                // Check status is failed
                if ($res->success == false) {
                    return redirect()->route('user.plans')->with('failed', trans($res->message));
                }
            } catch (\Exception $e) {
            }

            if ($res->state == "COMPLETED") {

                // Get transactionId
                $orderId = $res->orderId;
                $transactionId = $res->paymentDetails[0]->transactionId;

                $upgradePlan = new UpgradePlan;
                $upgradePlan->upgrade($orderId, $res);

                // Update transaction id
                Transaction::where('transaction_id', $transactionDetails->transaction_id)->update(['transaction_id' => $transactionId]);

                return redirect()->route('user.plans')->with('success', trans('Plan activated successfully!'));
            } else {
                Transaction::where('transaction_id', $transactionDetails->transaction_id)->update(['payment_status' => 'FAILED']);

                return redirect()->route('user.plans')->with('failed', trans('Payment failed.'));
            }
        } else {
            return redirect()->route('user.plans')->with('failed', trans('Payment failed.'));
        }
    }

    private function getPhonePeAuthToken()
    {
        // Check if the token is cached
        if (Cache::has('phonepe_auth_token')) {
            return Cache::get('phonepe_auth_token');
        }

        // Query the config table
        $config = Config::get();

        // Check if the config values are empty
        if ($config[53]->config_value == 'YOUR_PHONEPE_CLIENT_ID' || $config[57]->config_value == 'YOUR_PHONEPE_CLIENT_VERSION' || $config[54]->config_value == 'YOUR_PHONEPE_CLIENT_SECRET') {
            return trans("Something went wrong!");
        }

        // Set the auth URL
        $authUrl = "https://api.phonepe.com/apis/identity-manager/v1/oauth/token";

        // Set the payload
        $payload = [
            "client_id" => $config[53]->config_value,
            "client_version" => $config[57]->config_value,
            "client_secret" => $config[54]->config_value,
            "grant_type" => "client_credentials"
        ];

        // Send the request
        $response = Http::asForm()->post($authUrl, $payload); // Ensuring correct encoding

        // Decode the response
        $responseData = $response->json();

        // Check if the response contains an access token
        if (isset($responseData['access_token'])) {
            Cache::put('phonepe_auth_token', $responseData['access_token'], now()->addMinutes(55));

            return $responseData['access_token'];
        } else {
            return trans("Failed to retrieve token");
        }

        return null;
    }
}
