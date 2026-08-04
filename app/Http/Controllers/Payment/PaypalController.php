<?php

namespace App\Http\Controllers\Payment;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;
use PayPalHttp\HttpException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaypalController extends Controller
{
    protected $apiContext;

    public function __construct()
    {
        // Fetch PayPal configuration from database
        $paypalConfiguration = Config::get();

        // Set up PayPal environment
        $clientId = $paypalConfiguration[4]->config_value;
        $clientSecret = $paypalConfiguration[5]->config_value;
        $mode = $paypalConfiguration[3]->config_value;

        if ($mode == "sandbox") {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        $this->apiContext = new PayPalHttpClient($environment);
    }

    public function paywithpaypal(Request $request, $planId)
    {
        if (Auth::check()) {
            $planDetails = Plan::where('id', $planId)->where('status', 1)->first();
            $config = Config::get();
            $userData = Auth::user();

            if ($planDetails == null) {
                return view('errors.404');
            } else {
                // Paid amount
                $amountToBePaid = ((float)($planDetails->price) * (float)($config[25]->config_value) / 100) + (float)($planDetails->price);
                $amountToBePaidPaise = (float)number_format($amountToBePaid, 2);

                // Construct PayPal order request
                $request = new OrdersCreateRequest();
                $request->prefer('return=representation');
                $request->body = [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => $config[1]->config_value,
                            'value' => $amountToBePaidPaise,
                        ]
                    ]],
                    'application_context' => [
                        'cancel_url' => route('paypalPaymentStatus'),
                        'return_url' => route('paypalPaymentStatus'),
                    ]
                ];

                try {
                    // Create PayPal order
                    $response = $this->apiContext->execute($request);
                    foreach ($response->result->links as $link) {
                        if ($link->rel == 'approve') {
                            $redirectUrl = $link->href;
                            break;
                        }
                    }

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
                    $invoice_details['subtotal'] = $planDetails->price;
                    $invoice_details['tax_amount'] = (float)($planDetails->price) * (float)($config[25]->config_value) / 100;

                    // Store transaction details in database before redirecting to PayPal
                    $transaction = new Transaction();
                    $transaction->transaction_date = now();
                    $transaction->transaction_id = $response->result->id;
                    $transaction->user_id = Auth::user()->id;
                    $transaction->plan_id = $planDetails->id;
                    $transaction->desciption = $planDetails->name . " Plan";
                    $transaction->payment_gateway_name = "Paypal";
                    $transaction->transaction_amount = $amountToBePaid;
                    $transaction->transaction_currency = $config[1]->config_value;
                    $transaction->invoice_details = json_encode($invoice_details);
                    $transaction->payment_status = "PENDING";
                    $transaction->save();

                    // Redirect to PayPal for payment
                    return Redirect::away($redirectUrl);
                } catch (\Exception $ex) {
                    if (config('app.debug')) {
                        Session::put('error', 'Connection timeout');
                        return redirect()->route('user.plans')->with('failed', trans('Payment failed, Something went wrong!'));
                    } else {
                        Session::put('error', 'Some error occur, sorry for the inconvenience');
                        return redirect()->route('user.plans')->with('failed', trans('Payment failed, Something went wrong!'));
                    }
                    return redirect()->route('user.plans')->with('failed', trans('Payment failed, Something went wrong!'));
                }
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function paypalPaymentStatus(Request $request)
    {
        if (empty($request->PayerID) || empty($request->token)) {
            Session::put('error', 'Payment cancelled!');
            return redirect()->route('user.plans');
        }

        try {
            // Get the payment ID from the request
            $paymentId = $request->token;
            $orderId = $paymentId;

            $transactionDetails = Transaction::where('transaction_id', $paymentId)->where('status', 1)->first();
            $user_details = User::find(Auth::user()->id);
            $config = Config::get();

            $request = new OrdersCaptureRequest($paymentId);
            $response = $this->apiContext->execute($request);

            if ($response->statusCode == 201) {
                $plan_data = Plan::where('id', $transactionDetails->plan_id)->first();
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
                    Transaction::where('transaction_id', $orderId)->update([
                        'transaction_id' => $paymentId,
                        'invoice_prefix' => $config[15]->config_value,
                        'invoice_number' => $invoice_number,
                        'payment_status' => 'SUCCESS',
                    ]);

                    // Update customer details
                    $user_details->plan_id = $transactionDetails->plan_id;
                    $user_details->term = $term_days;
                    $user_details->plan_validity = $plan_validity;
                    $user_details->plan_activation_date = now();
                    $user_details->plan_details = $plan_data;
                    $user_details->save();

                    // Generate JSON
                    $encode = json_decode($transactionDetails['invoice_details'], true);
                    $details = [
                        'from_billing_name' => $encode['from_billing_name'],
                        'from_billing_email' => $encode['from_billing_email'],
                        'from_billing_address' => $encode['from_billing_address'],
                        'from_billing_city' => $encode['from_billing_city'],
                        'from_billing_state' => $encode['from_billing_state'],
                        'from_billing_country' => $encode['from_billing_country'],
                        'from_billing_zipcode' => $encode['from_billing_zipcode'],
                        'transaction_id' => $paymentId,
                        'to_billing_name' => $encode['to_billing_name'],
                        'to_vat_number' => $encode['to_vat_number'],
                        'invoice_currency' => $transactionDetails->transaction_currency,
                        'subtotal' => $encode['subtotal'],
                        'tax_amount' => (float)($plan_data->price) * (float)($config[25]->config_value) / 100,
                        'invoice_amount' => $encode['invoice_amount'],
                        'invoice_id' => $config[15]->config_value . $invoice_number,
                        'invoice_date' => $transactionDetails->created_at,
                        'description' => $transactionDetails->desciption,
                        'email_heading' => $config[27]->config_value,
                        'email_footer' => $config[28]->config_value,
                    ];

                    // Send email to user email
                    try {
                        Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                    } catch (\Exception $e) {
                    }

                    // Page redirect
                    return redirect()->route('user.plans')->with('success', trans('Plan activation success!'));
                } else {

                    $message = "";

                    // Check plan id
                    if ($user_details->plan_id == $transactionDetails->plan_id) {

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
                    Transaction::where('transaction_id', $orderId)->update([
                        'transaction_id' => $paymentId,
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
                    $user_details->plan_id = $transactionDetails->plan_id;
                    $user_details->term = $term_days;
                    $user_details->plan_validity = $plan_validity;
                    $user_details->plan_activation_date = now();
                    $user_details->plan_details = $plan_data;
                    $user_details->save();

                    // Generate JSON
                    $encode = json_decode($transactionDetails['invoice_details'], true);
                    $details = [
                        'from_billing_name' => $encode['from_billing_name'],
                        'from_billing_email' => $encode['from_billing_email'],
                        'from_billing_address' => $encode['from_billing_address'],
                        'from_billing_city' => $encode['from_billing_city'],
                        'from_billing_state' => $encode['from_billing_state'],
                        'from_billing_country' => $encode['from_billing_country'],
                        'from_billing_zipcode' => $encode['from_billing_zipcode'],
                        'transaction_id' => $paymentId,
                        'to_billing_name' => $encode['to_billing_name'],
                        'to_vat_number' => $encode['to_vat_number'],
                        'invoice_currency' => $transactionDetails->transaction_currency,
                        'subtotal' => $encode['subtotal'],
                        'tax_amount' => (float)($plan_data->price) * (float)($config[25]->config_value) / 100,
                        'invoice_amount' => $encode['invoice_amount'],
                        'invoice_id' => $config[15]->config_value . $invoice_number,
                        'invoice_date' => $transactionDetails->created_at,
                        'description' => $transactionDetails->desciption,
                        'email_heading' => $config[27]->config_value,
                        'email_footer' => $config[28]->config_value,
                    ];

                    // Send email to user email
                    try {
                        Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                    } catch (\Exception $e) {
                    }

                    // Page redirect
                    return redirect()->route('user.plans')->with('success', $message);
                }
            } else {
                Transaction::where('transaction_id', $orderId)->update([
                    'transaction_id' => $orderId,
                    'payment_status' => 'FAILED',
                ]);

                return redirect()->route('user.plans')->with('failed', trans("Payment cancelled!"));
            }
        } catch (HttpException $e) { // Corrected class name
            // Handle the HTTP exception
            // Log the error or display an error message
            // Example: Log::error('PayPal HTTP Exception: ' . $e->getMessage());

            // Set an error message for the user
            // Session::flash('error', 'An error occurred while communicating with PayPal. Please try again later.');

            // Redirect back to the user plans page or any other appropriate page
            return redirect()->route('user.plans')->with('failed', trans("An error occurred while communicating with PayPal. Please try again later."));
        }
    }
}
