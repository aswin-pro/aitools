<?php

namespace App\Http\Controllers\Payment;

use App\Models\User;
use App\Models\Config;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function preparePaymentGateway(Request $request, $planId)
    {
        // Queries
        $config = Config::get();
        $payment_mode = Gateway::where('id', $request->payment_gateway_id)->first();

        if ($payment_mode == null) {
            return redirect()->back()->with('failed', trans('Please choose valid payment method!'));
        } else {
            $validated = $request->validate([
                'billing_name' => 'required',
                'billing_email' => 'required',
                'billing_phone' => 'required',
                'billing_address' => 'required',
                'billing_city' => 'required',
                'billing_state' => 'required',
                'billing_zipcode' => 'required',
                'billing_country' => 'required',
                'type' => 'required'
            ]);

            User::where('id', Auth::user()->id)->update([
                'billing_name' => $request->billing_name,
                'billing_email' => $request->billing_email,
                'billing_phone' => $request->billing_phone,
                'billing_address' => $request->billing_address,
                'billing_city' => $request->billing_city,
                'billing_state' => $request->billing_state,
                'billing_zipcode' => $request->billing_zipcode,
                'billing_country' => $request->billing_country,
                'type' => $request->type,
                'vat_number' => $request->vat_number
            ]);

            if ($payment_mode->name == "Paypal") {
                // Check key and secret
                if ($config[4]->config_value != "YOUR_PAYPAL_CLIENT_ID" || $config[5]->config_value != "YOUR_PAYPAL_SECRET") {
                    return redirect()->route('paywithpaypal', $planId);
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->name == "Razorpay") {
                // Check key and secret
                if ($config[6]->config_value != "YOUR_RAZORPAY_KEY" || $config[7]->config_value != "YOUR_RAZORPAY_SECRET") {
                    return redirect()->route('paywithrazorpay', $planId);
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->name == "PhonePe") {
                // Check key and secret
                if ($config[53]->config_value != "") {
                    return redirect()->route('paywithphonepe', $planId);
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->name == "Stripe") {
                // Check key and secret
                if ($config[9]->config_value != "YOUR_STRIPE_PUB_KEY" || $config[10]->config_value != "YOUR_STRIPE_SECRET") {
                    return redirect()->route('paywithstripe', $planId);
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->name == "Paystack") {
                // Check key and secret
                if ($config[37]->config_value != "PAYSTACK_PUBLIC_KEY" || $config[38]->config_value != "PAYSTACK_SECRET_KEY") {
                    return redirect()->route('paywithpaystack', $planId);
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->name == "Mollie") {
                // Check key and secret
                if ($config[41]->config_value != "mollie_key") {
                    return redirect()->route('paywithmollie', $planId);
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->name == "Bank Transfer") {
                // Check key and secret
                if ($config[31]->config_value != "") {
                    return redirect()->route('paywithoffline', $planId);
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->name == "Transaction Cloud") {
                // Check key and secret
                if ($config[44]->config_value != "YOUR_TRANSACTION_CLOUD_API_KEY" || $config[45]->config_value != "YOUR_TRANSACTION_CLOUD_API_PASSWORD") {
                    return redirect()->route('paywithtransactioncloud', $planId);
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->name == "Mercado Pago") {
                // Check key and secret
                if ($config[55]->config_value != "YOUR_MERCADO_PAGO_PUBLIC_KEY" || $config[56]->config_value != "YOUR_MERCADO_PAGO_ACCESS_TOKEN") {
                    return redirect()->route('paywithmercadopago', $planId);
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->id == 10) {
                // Check key and secret
                if ($config[65]->config_value != "YOUR_TOYYIBPAY_API_KEY" || $config[66]->config_value != "YOUR_TOYYIBPAY_CATEGORY_CODE") {
                    return redirect()->route('prepare.toyyibpay', compact('planId'));
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('You can not use Toyyibpay payment gateway!. For more information, please contact us.'));
                }
            } else if ($payment_mode->id == 11) {
                // Check key, secret and encryption key
                if ($config[67]->config_value != "YOUR_FLW_PUBLIC_KEY" || $config[68]->config_value != "YOUR_FLW_SECRET_KEY" || $config[69]->config_value != "YOUR_FLW_ENCRYPTION_KEY") {
                    return redirect()->route('prepare.flutterwave', compact('planId'));
                } else {
                    return redirect()->route('user.plans')->with('failed', trans('You can not use Flutterwave payment gateway!. For more information, please contact us.'));
                }
            } else {
                return redirect()->back()->with('failed', trans('Something went wrong!'));
            }
        }
    }
}
