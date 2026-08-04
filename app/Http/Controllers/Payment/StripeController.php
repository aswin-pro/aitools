<?php

namespace App\Http\Controllers\Payment;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{
    // Stripe checkout
    public function stripeCheckout(Request $request, $planId)
    {
        if (Auth::user()) {

            // Queries
            $config = Config::get();
            $userData = User::where('id', Auth::user()->id)->first();
            $settings = Setting::where('status', 1)->first();
            $plan_details = Plan::where('id', $planId)->where('status', 1)->first();

            // Check plan details
            if ($plan_details == null) {
                return view('errors.404');
            } else {

                // Paid amount
                $amountToBePaid = ((float)($plan_details->price) * (float)($config[25]->config_value) / 100) + (float)($plan_details->price);
                $amountToBePaidPaise = (float)number_format($amountToBePaid, 2) * 100;

                \Stripe\Stripe::setApiKey($config[10]->config_value);
                $gobiz_transaction_id = uniqid();

                // Stripe payment intent
                $payment_intent = \Stripe\PaymentIntent::create([
                    'description' => $plan_details->name . " Plan",
                    'shipping' => [
                        'name' => Auth::user()->name,
                        'address' => [
                            'line1' => Auth::user()->billing_address,
                            'postal_code' => Auth::user()->billing_zipcode,
                            'city' => Auth::user()->billing_city,
                            'state' => Auth::user()->billing_state,
                            'country' => Auth::user()->billing_country,
                        ],
                    ],
                    'amount' => (float) $amountToBePaidPaise,
                    'currency' => $config[1]->config_value,
                    'payment_method_types' => ['card'],
                ]);

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

                $intent = $payment_intent->client_secret;
                $paymentId = $payment_intent->id;
                // If order is created from stripe
                if (isset($intent)) {
                    $transaction = new Transaction();
                    $transaction->transaction_date = now();
                    $transaction->transaction_id = $paymentId;
                    $transaction->user_id = Auth::user()->id;
                    $transaction->plan_id = $plan_details->id;
                    $transaction->desciption = $plan_details->name . " Plan";
                    $transaction->payment_gateway_name = "Stripe";
                    $transaction->transaction_amount = $amountToBePaid;
                    $transaction->transaction_currency = $config[1]->config_value;
                    $transaction->invoice_details = json_encode($invoice_details);
                    $transaction->payment_status = "PENDING";
                    $transaction->save();
                    return view('user.pages.checkout.pay-with-stripe', compact('settings', 'intent', 'plan_details', 'gobiz_transaction_id', 'config', 'paymentId'));
                }
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function stripePaymentStatus(Request $request, $paymentId)
    {
        // Check payment id
        if (!$paymentId) {
            return view('errors.404');
        } else {
            // Queries
            $orderId = $paymentId;
            $config = Config::get();
            $stripe = new \Stripe\StripeClient($config[10]->config_value);

            try {
                $payment = $stripe->paymentIntents->retrieve($paymentId, []);
            } catch (\Exception $e) {
                $payment = new \stdClass();
                $payment->status = "error";
            }

            // Check payment status
            if ($payment->status == "succeeded") {

                // Get transaction details
                $transaction_details = Transaction::where('transaction_id', $orderId)->where('status', 1)->first();

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
                    Transaction::where('transaction_id', $orderId)->update([
                        'transaction_id' => $paymentId,
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
                        'transaction_id' => $transaction_details->transaction_id,
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

                    // Send mail to customer invoice
                    try {
                        Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                    } catch (\Exception $e) {
                    }

                    // Page redirect
                    return redirect()->route('user.plans')->with('success', trans('Plan activation success!'));
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
                        'gobiz_transaction_id' => $transaction_details->gobiz_transaction_id,
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

                    // Send mail to customer invoice
                    try {
                        Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                    } catch (\Exception $e) {
                    }

                    // Page redirect
                    return redirect()->route('user.plans')->with('success', $message);
                }
            } else {

                // Update tranaction details
                Transaction::where('transaction_id', $orderId)->update([
                    'transaction_id' => $paymentId,
                    'payment_status' => 'FAILED',
                ]);

                // Page redirect
                return redirect()->route('user.plans')->with('failed', trans("Something went wrong!"));
            }
        }
    }

    public function stripePaymentCancel(Request $request, $paymentId)
    {
        if (!$paymentId) {
            return view('errors.404');
        } else {
            $config = Config::get();
            $stripe = new \Stripe\StripeClient($config[10]->config_value);

            try {
                $payment = $stripe->paymentIntents->cancel($paymentId, []);
            } catch (\Exception $e) {
                $payment = new \stdClass();
                $payment->status = "error";
            }

            Transaction::where('transaction_id', $paymentId)->update([
                'transaction_id' => $paymentId,
                'payment_status' => 'FAILED',
            ]);

            return redirect()->route('user.plans')->with('failed', trans("Payment cancelled!"));
        }
    }
}
