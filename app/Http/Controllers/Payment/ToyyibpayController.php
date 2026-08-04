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

class ToyyibpayController extends Controller
{
    protected $apiKey;
    protected $categoryCode;
    protected $baseUrl;

    public function __construct()
    {
        // Get API key and category code from config table
        $config = Config::get();

        // Set API key and category code
        $this->apiKey = $config[65]->config_value;
        $this->categoryCode = $config[66]->config_value;
        $this->baseUrl = "https://toyyibpay.com/"; // Production URL
        if ($config[64]->config_value == 'sandbox') {
            $this->baseUrl = "https://dev.toyyibpay.com/"; // Development URL
        }
    }

    public function prepareToyyibpay(Request $request, $planId)
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

                // Applied tax in total
                $appliedTaxInTotal = ((float)($plan_details->price) * (float)($config[25]->config_value) / 100);

                // Total
                $amountToBePaid = ($plan_details->price + $appliedTaxInTotal);

                $amountToBePaidPaise = $amountToBePaid * 100;

                // Transaction ID
                $transactionId = uniqid();

                $client = new Client(['base_uri' => $this->baseUrl]);

                // Prepare bill details
                $billDetails = [
                    'billName' => 'Payment for #' . $transactionId,
                    'billDescription' => 'Plan Payment',
                    'billAmount' => $amountToBePaidPaise, // Amount in cents
                    'billReturnUrl' => route('toyyibpay.payment.success'),
                    'billCallbackUrl' => route('toyyibpay.payment.status'),
                    'billExternalReferenceNo' => $transactionId,
                    'userSecretKey' => $this->apiKey,
                    'categoryCode' => $this->categoryCode,
                    'billPriceSetting' => 1,
                    'billPayorInfo' => 1,
                    'billTo' => Auth::user()->name,
                    'billEmail' => Auth::user()->email,
                    'billPhone' => Auth::user()->billing_phone == null ? '9876543210' : Auth::user()->billing_phone,
                ];

                // Create a bill with ToyyibPay
                $response = $client->post('index.php/api/createBill', [
                    'form_params' => $billDetails,
                ]);

                $responseBody = json_decode($response->getBody(), true);

                if (isset($responseBody[0]['BillCode'])) {
                    $billCode = $responseBody[0]['BillCode'];

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
                    $transaction->transaction_id = $billCode;
                    $transaction->user_id = Auth::user()->id;
                    $transaction->plan_id = $plan_details->id;
                    $transaction->desciption = $plan_details->name . " Plan";
                    $transaction->payment_gateway_name = "Toyyibpay";
                    $transaction->transaction_amount = $amountToBePaid;
                    $transaction->transaction_currency = $config[1]->config_value;
                    $transaction->invoice_details = json_encode($invoice_details);
                    $transaction->payment_status = "PENDING";
                    $transaction->save();

                    return redirect()->to($this->baseUrl . "{$billCode}");
                }

                return back()->with('failed', trans('Failed to initiate payment.'));
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function toyyibpayPaymentStatus(Request $request)
    {
        // Get the bill code from the request
        $statusId = $request['status_id'];
        $billCode = $request['billcode'];
        $transactionId = $request['transaction_id'];

        // Call the static function
        $updatedData = $this->toyyibpayPaymentSuccessStatic($statusId, $billCode, $transactionId);

        if (isset($updatedData['success']) && $updatedData['success']) {
            return redirect()->route('user.plans')->with($updatedData)->with('success', trans('Plan activation success!'));
        } else {
            return redirect()->route('user.plans')->with('failed', trans('Plan activation failed!'));
        }
    }

    public function toyyibpayPaymentSuccess(Request $request)
    {
        // Get the bill code from the request
        $statusId = $request['status_id'];
        $billCode = $request['billcode'];
        $transactionId = $request['transaction_id'];

        // Call the static function
        $updatedData = $this->toyyibpayPaymentSuccessStatic($statusId, $billCode, $transactionId);

        if (isset($updatedData['success']) && $updatedData['success']) {
            return redirect()->route('user.plans')->with($updatedData)->with('success', trans('Plan activation success!'));
        } else {
            return redirect()->route('user.plans')->with('failed', trans('Plan activation failed!'));
        }
    }

    // Static function call
    public function toyyibpayPaymentSuccessStatic($statusId, $billCode, $transactionId)
    {
        // Get the bill code from the request
        $statusId = $statusId;
        $billCode = $billCode;
        $transactionId = $transactionId;

        if ($billCode == null) {
            // Update the transaction status to PENDING
            Transaction::where('transaction_id', $billCode)->update(['payment_status' => 'FAILED']);

            return [
                'failed' => trans('Transaction not found or already processed.'),
            ];
        } else {
            // Payment success
            if ($statusId == 1) {
                // Config
                $config = Config::get();

                // Get transaction details based on the preference_id
                $transaction_details = Transaction::where('transaction_id', $billCode)->first();

                if (!$transaction_details) {
                    return [
                        'failed' => trans('Transaction not found or already processed.'),
                    ];
                }

                // Get user details
                $user_details = User::find(Auth::user()->id);

                // Get plan details
                $plan_data = Plan::where('id', $transaction_details->plan_id)->where('status', 1)->first();
                $term_days = (int) $plan_data->validity;

                // Determine the plan validity and update accordingly
                if ($user_details->plan_validity == "") {
                    // Add days for new plan
                    $plan_validity = Carbon::now()->addDays($term_days);

                    // Generate invoice number
                    $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                    $invoice_number = $invoice_count + 1;

                    // Update transaction details
                    $transaction_details->update([
                        'transaction_id' => $transactionId,
                        'invoice_prefix' => $config[15]->config_value,
                        'invoice_number' => $invoice_number,
                        'payment_status' => 'SUCCESS',
                    ]);

                    // Update user details
                    $user_details->plan_id = $transaction_details->plan_id;
                    $user_details->term = $term_days;
                    $user_details->plan_validity = $plan_validity;
                    $user_details->plan_activation_date = now();
                    $user_details->plan_details = $plan_data;
                    $user_details->save();

                    $message = 'Plan activation success!';
                } else {
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

                    // Generate invoice number
                    $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                    $invoice_number = $invoice_count + 1;

                    // Update transaction details
                    Transaction::where('transaction_id', $billCode)->update([
                        'transaction_id' => $transactionId,
                        'invoice_prefix' => $config[15]->config_value,
                        'invoice_number' => $invoice_number,
                        'payment_status' => 'SUCCESS',
                    ]);

                    // Check if plan data exists and update
                    if ($plan_data) {
                        $plan_data->max_words += json_decode($user_details->plan_details)->max_words;
                        $plan_data->max_images += json_decode($user_details->plan_details)->max_images;
                    }

                    // Update user details
                    $user_details->plan_id = $transaction_details->plan_id;
                    $user_details->term = $term_days;
                    $user_details->plan_validity = $plan_validity;
                    $user_details->plan_activation_date = now();
                    $user_details->plan_details = $plan_data;
                    $user_details->save();
                }

                // Generate and send invoice details
                $encode = json_decode($transaction_details->invoice_details, true);
                $details = [
                    'from_billing_name' => $encode['from_billing_name'],
                    'from_billing_email' => $encode['from_billing_email'],
                    'from_billing_address' => $encode['from_billing_address'],
                    'from_billing_city' => $encode['from_billing_city'],
                    'from_billing_state' => $encode['from_billing_state'],
                    'from_billing_country' => $encode['from_billing_country'],
                    'from_billing_zipcode' => $encode['from_billing_zipcode'],
                    'transaction_id' => $transactionId,
                    'to_billing_name' => $encode['to_billing_name'],
                    'to_vat_number' => $encode['to_vat_number'],
                    'invoice_currency' => $transaction_details->transaction_currency,
                    'subtotal' => $encode['subtotal'],
                    'tax_amount' => (float)($plan_data->price) * (float)($config[25]->config_value) / 100,
                    'invoice_amount' => $encode['invoice_amount'],
                    'invoice_id' => $config[15]->config_value . $invoice_number,
                    'invoice_date' => $transaction_details->created_at,
                    'description' => $transaction_details->description,
                    'email_heading' => $config[27]->config_value,
                    'email_footer' => $config[28]->config_value,
                ];

                // Send invoice via email
                try {
                    Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                } catch (\Exception $e) {
                    // Handle email sending failure if needed
                }

                // Redirect to the user's plans page with success message
                return [
                    'success' => $message,
                ];
            }

            return [
                'failed' => trans('Payment failed'),
            ];
        }

        // Payment pending
        if ($statusId == 2 || $statusId == 4) {
            // Update the transaction status to FAILED
            Transaction::where('transaction_id', $billCode)->update(['payment_status' => 'PENDING']);

            return [
                'failed' => trans('Payment pending'),
            ];
        }

        // Payment failed
        if ($statusId == 3) {
            // Update the transaction status to FAILED
            Transaction::where('transaction_id', $billCode)->update(['payment_status' => 'FAILED']);

            return [
                'failed' => trans('Payment failed'),
            ];
        }
    }
}
