<?php

namespace App\Http\Controllers\Payment;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use GuzzleHttp\Client;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FlutterwaveController extends Controller
{
    protected $secretKey;
    protected $baseUrl;
    protected $client;

    public function __construct()
    {
        // Get API key and category code from config table
        $config = Config::get();

        $this->secretKey = $config[68]->config_value;
        $this->baseUrl = "https://api.flutterwave.com/v3";
    }

    // Prepare Flutterwave payment
    public function prepareFlutterwave(Request $request, $planId)
    {
        if (Auth::user()) {

            // Queries
            $config = Config::get();
            $userData = User::where('id', Auth::user()->id)->first();
            $plan_details = Plan::where('id', $planId)->where('status', 1)->first();

            // Check plan details
            if ($plan_details == null) {
                return view('errors.404');
            } else {

                // Paid amount
                $amountToBePaid = ((float)($plan_details->price) * (float)($config[25]->config_value) / 100) + (float)($plan_details->price);
                $amountToBePaidPaise = number_format($amountToBePaid, 2);

                // Transaction ID
                $transactionId = uniqid();

                $client = new Client();

                $data = [
                    'tx_ref' => $transactionId,
                    'amount' => $amountToBePaidPaise,
                    'currency' => $config[1]->config_value, // Set the currency code
                    'redirect_url' => route('flutterwave.payment.status'),
                    'customer' => [
                        'email' => Auth::user()->email,
                        'name' => Auth::user()->name,
                        'phone_number' => Auth::user()->billing_phone == null ? '9876543210' : Auth::user()->billing_phone,
                    ],
                    'customizations' => [
                        'title' => config('app.name'),
                        'logo' => asset('img/favicon.png'),
                    ]
                ];

                try {
                    $response = $client->post("{$this->baseUrl}/payments", [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $this->secretKey,
                            'Content-Type' => 'application/json',
                        ],
                        'json' => $data
                    ]);

                    $responseBody = json_decode($response->getBody(), true);

                    if ($responseBody['status'] === 'success') {

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
                        $transaction->payment_gateway_name = "Flutterwave";
                        $transaction->transaction_amount = $amountToBePaid;
                        $transaction->transaction_currency = $config[1]->config_value;
                        $transaction->invoice_details = json_encode($invoice_details);
                        $transaction->payment_status = "PENDING";
                        $transaction->save();

                        return redirect($responseBody['data']['link']);
                    }

                    return redirect()->route('user.plans')->with('failed', trans('Payment initiation failed'));
                } catch (\Exception $e) {
                    return redirect()->route('user.plans')->with('failed', trans('Failed to initiate payment.'));
                }
            }
        } else {
            return redirect()->route('login');
        }
    }

    // Flutterwave Payment Status
    public function flutterwavePaymentStatus(Request $request)
    {
        // Get transaction id from the request
        $txRef = $request->query('tx_ref');
        $status = $request->query('status');

        // Transaction success
        if ($status == "successful") {
            // Check if the transaction is already verified
            $transactionId = $request->query('transaction_id');

            if ($transactionId) {
                $client = new Client();

                try {
                    $response = $client->get("{$this->baseUrl}/transactions/{$transactionId}/verify", [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $this->secretKey,
                            'Content-Type' => 'application/json',
                        ]
                    ]);

                    $verificationResponse = json_decode($response->getBody(), true);

                    // Get tx_ref and flw_ref
                    $tx_ref = $verificationResponse['data']['tx_ref'];
                    $flw_ref = $verificationResponse['data']['flw_ref'];

                    if ($verificationResponse['status'] === 'success') {
                        // Call the static function
                        $updatedData = $this->paymentSuccessStatic($tx_ref, $flw_ref);

                        return redirect()->route('user.plans')->with($updatedData);
                    }

                    // Handle failed payment
                    return redirect()->route('user.plans')->with('failed', trans('Payment failed.'));
                } catch (\Exception $e) {
                    return redirect()->route('user.plans')->with('failed', trans('Payment verification failed.'));
                }
            } else {
                return redirect()->route('user.plans')->with('failed', trans('Transaction not found.'));
            }
        } elseif ($status === 'failed') {
            // Update transaction details
            Transaction::where('transaction_id', $txRef)->update([
                'payment_status' => 'FAILED',
            ]);

            return redirect()->route('user.plans')->with('failed', trans('Transaction failed.'));
        } elseif ($status === 'cancelled') {
            // Update transaction details
            Transaction::where('transaction_id', $txRef)->update([
                'payment_status' => 'CANCELLED',
            ]);

            return redirect()->route('user.plans')->with('failed', trans('Transaction cancelled.'));
        }

        return redirect()->route('user.plans')->with('failed', trans('Invalid transaction status.'));
    }

    // Static function call
    public function paymentSuccessStatic($txRef, $flwRef)
    {
        // Get the bill code from the request
        $txRef = $txRef;
        $flwRef = $flwRef;

        if ($txRef == null && $flwRef == null) {
            // Update the transaction status to PENDING
            Transaction::where('transaction_id', $txRef)->update(['payment_status' => 'FAILED']);

            return [
                'failed' => trans('Transaction not found.'),
            ];
        } else {
            // Config
            $config = Config::get();

            // Get transaction details based on the preference_id
            $transaction_details = Transaction::where('transaction_id', $txRef)->first();

            if (!$transaction_details) {
                return [
                    'failed' => trans('Transaction not found.'),
                ];
            }

            // Get user details
            $user_details = User::find(Auth::user()->id);

            // Get plan details
            $plan_data = Plan::where('id', $transaction_details->plan_id)->first();
            $term_days = (int) $plan_data->validity;

            // Check plan validity
            if ($user_details->plan_validity == "") {

                // Add days
                $plan_validity = Carbon::now();
                $plan_validity->addDays($term_days);

                // Transactions count
                $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                $invoice_number = $invoice_count + 1;

                // Update transaction details
                Transaction::where('transaction_id', $txRef)->update([
                    'transaction_id' => $flwRef,
                    'invoice_prefix' => $config[15]->config_value,
                    'invoice_number' => $invoice_number,
                    'payment_status' => 'SUCCESS',
                ]);

                // Update customer details
                $user_details->plan_id = $transaction_details->plan_id;
                $user_details->term = $term_days;
                $user_details->plan_validity = $plan_validity;
                $user_details->plan_activation_date = now();
                $user_details->plan_details = $plan_data;
                $user_details->save();

                // Generate JSON
                $encode = json_decode($transaction_details['invoice_details'], true);
                $details = [
                    'from_billing_name' => $encode['from_billing_name'],
                    'from_billing_email' => $encode['from_billing_email'],
                    'from_billing_address' => $encode['from_billing_address'],
                    'from_billing_city' => $encode['from_billing_city'],
                    'from_billing_state' => $encode['from_billing_state'],
                    'from_billing_country' => $encode['from_billing_country'],
                    'from_billing_zipcode' => $encode['from_billing_zipcode'],
                    'transaction_id' => $flwRef,
                    'to_billing_name' => $encode['to_billing_name'],
                    'to_vat_number' => $encode['to_vat_number'],
                    'invoice_currency' => $transaction_details->transaction_currency,
                    'subtotal' => $encode['subtotal'],
                    'tax_amount' => (float)($plan_data->price) * (float)($config[25]->config_value) / 100,
                    'invoice_amount' => $encode['invoice_amount'],
                    'invoice_id' => $config[15]->config_value . $invoice_number,
                    'invoice_date' => $transaction_details->created_at,
                    'description' => $transaction_details->desciption,
                    'email_heading' => $config[27]->config_value,
                    'email_footer' => $config[28]->config_value,
                ];

                // Send email to user email
                try {
                    Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                } catch (\Exception $e) {
                }

                return [
                    'success' => trans('Plan activation success!'),
                ];
            } else {
                $message = "";

                // Check plan id
                if ($user_details->plan_id == $transaction_details->plan_id) {

                    // Check if plan validity is expired or not.
                    $plan_validity = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $user_details->plan_validity);
                    $current_date = Carbon::now();
                    $remaining_days = $current_date->diffInDays($plan_validity, false);

                    // Check plan remaining days
                    if ($remaining_days > 0) {
                        // Add days
                        $plan_validity = Carbon::parse($user_details->plan_validity);
                        $plan_validity->addDays($term_days);
                        $message = "Plan renewed successfully!";
                    } else {
                        // Add days
                        $plan_validity = Carbon::now();
                        $plan_validity->addDays($term_days);
                        $message = "Plan renewed successfully!";
                    }
                } else {
                    // Add days
                    $plan_validity = Carbon::now();
                    $plan_validity->addDays($term_days);
                    $message = "Plan activated successfully!";
                }

                // Transactions count
                $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                $invoice_number = $invoice_count + 1;

                // Update transaction details
                Transaction::where('transaction_id', $txRef)->update([
                    'transaction_id' => $flwRef,
                    'invoice_prefix' => $config[15]->config_value,
                    'invoice_number' => $invoice_number,
                    'payment_status' => 'SUCCESS',
                ]);

                // Check if the $plan_data object exists
                if ($plan_data) {
                    // Add PLAN DATAS to the max_words attribute
                    $plan_data->max_words = json_decode($user_details->plan_details)->max_words + (int)$plan_data->max_words;
                    $plan_data->max_images = json_decode($user_details->plan_details)->max_images + (int)$plan_data->max_images;
                }

                // Update customer plan details
                $user_details->plan_id = $transaction_details->plan_id;
                $user_details->term = $term_days;
                $user_details->plan_validity = $plan_validity;
                $user_details->plan_activation_date = now();
                $user_details->plan_details = $plan_data;
                $user_details->save();

                // Generate JSON
                $encode = json_decode($transaction_details['invoice_details'], true);
                $details = [
                    'from_billing_name' => $encode['from_billing_name'],
                    'from_billing_email' => $encode['from_billing_email'],
                    'from_billing_address' => $encode['from_billing_address'],
                    'from_billing_city' => $encode['from_billing_city'],
                    'from_billing_state' => $encode['from_billing_state'],
                    'from_billing_country' => $encode['from_billing_country'],
                    'from_billing_zipcode' => $encode['from_billing_zipcode'],
                    'transaction_id' => $flwRef,
                    'to_billing_name' => $encode['to_billing_name'],
                    'to_vat_number' => $encode['to_vat_number'],
                    'invoice_currency' => $transaction_details->transaction_currency,
                    'subtotal' => $encode['subtotal'],
                    'tax_amount' => (float)($plan_data->price) * (float)($config[25]->config_value) / 100,
                    'invoice_amount' => $encode['invoice_amount'],
                    'invoice_id' => $config[15]->config_value . $invoice_number,
                    'invoice_date' => $transaction_details->created_at,
                    'description' => $transaction_details->desciption,
                    'email_heading' => $config[27]->config_value,
                    'email_footer' => $config[28]->config_value,
                ];

                // Send email to user email
                try {
                    Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                } catch (\Exception $e) {
                }

                return [
                    'success' => trans($message),
                ];
            }

            return [
                'success' => trans($message),
            ];
        }
    }
}
