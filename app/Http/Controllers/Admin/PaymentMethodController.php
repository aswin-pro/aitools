<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use App\Models\Gateway;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // All Payment Methods
    public function index()
    {
        // Get payment methods
        $payment_methods = Gateway::orderBy('created_at', 'desc')->get();
        $settings = Setting::where('status', 1)->first();

        return view('admin.pages.payment-methods.index', compact('payment_methods', 'settings'));
    }

    // Add Payment Method
    public function addPaymentMethod()
    {
        // Queries
        $settings = Setting::where('status', 1)->first();
        return view('admin.pages.payment-methods.add', compact('settings'));
    }

    // Save Payment Method
    public function savePaymentMethod(Request $request)
    {
        // Validation
        $validator = $request->validate([
            'logo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:' . env('SIZE_LIMIT'),
            'name' => 'required',
            'display_name' => 'required',
            'client_id' => 'required',
            'secret_key' => 'required'
        ]);

        // Upload images
        $logo = 'img/payments/' . 'payment-' . time() . '.' . $request->logo->extension();

        // Upload
        $request->logo->move(public_path('img/payments'), $logo);

        // Save payment method
        $paymentMethod = new Gateway;
        $paymentMethod->logo = $logo;
        $paymentMethod->name = $request->name;
        $paymentMethod->display_name = $request->display_name;
        $paymentMethod->client_id = $request->client_id;
        $paymentMethod->secret_key = $request->secret_key;
        $paymentMethod->save();

        // Page redirect
        return redirect()->route('admin.payment.methods')->with('success', trans('New Payment Method Created Successfully!'));
    }

    // Edit Payment Method
    public function editPaymentMethod(Request $request, $id)
    {
        // Payment gateway id
        $gateway_id = $request->id;

        // Check payment gateway id
        if ($gateway_id == null) {
            return view('errors.404');
        } else {
            // Check payment gateway details
            $gateway_details = Gateway::where('id', $gateway_id)->first();
            $settings = Setting::where('status', 1)->first();

            return view('admin.pages.payment-methods.edit', compact('gateway_details', 'settings'));
        }
    }

    // Update Payment Method
    public function updatePaymentMethod(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'payment_gateway_name' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Check payment method image
        if (isset($request->payment_gateway_image)) {
            // Image validatation
            $validator = Validator::make($request->all(), [
                'payment_gateway_image' => 'required|mimes:jpeg,png,jpg,gif,svg,webp|max:' . env("SIZE_LIMIT") . '',
            ]);

            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            // get profile image
            $payment_gateway_image = $request->payment_gateway_image->getClientOriginalName();
            $UploadPaymentGatewayImage = pathinfo($payment_gateway_image, PATHINFO_FILENAME);
            $UploadExtension = pathinfo($payment_gateway_image, PATHINFO_EXTENSION);

            // Upload image
            if ($UploadExtension == "jpeg" || $UploadExtension == "png" || $UploadExtension == "jpg" || $UploadExtension == "gif" || $UploadExtension == "svg") {
                // Upload image
                $payment_gateway_image = 'img/payments/' . 'IMG-' . $request->payment_gateway_image->getClientOriginalName() . '-' . time() . '.' . $request->payment_gateway_image->extension();
                $request->payment_gateway_image->move(public_path('img/payments'), $payment_gateway_image);

                // Update user profile image
                Gateway::where('id', $request->payment_gateway_id)->update([
                    'logo' => $payment_gateway_image,
                    'display_name' => $request->payment_gateway_name
                ]);
            }
        }

        // Update payment method
        Gateway::where('id', $request->payment_gateway_id)->update([
            'name' => $request->payment_gateway_name,
            'display_name' => $request->payment_gateway_name
        ]);
        
        // Page redirect
        return redirect()->route('admin.payment.methods')->with('success', trans('Payment Gateway Details Updated Successfully!'));
    }

    // Delete Payment Method
    public function deletePaymentMethod(Request $request)
    {
        // Payment gateway details
        $payment_gateway_details = Gateway::where('id', $request->query('id'))->first();

        // Check payment gateway
        if ($payment_gateway_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        // Update payment gateway
        Gateway::where('id', $request->query('id'))->update(['status' => $status]);
        // Page redirect
        return redirect()->route('admin.payment.methods')->with('success', trans('Payment Method Status Updated Successfully!'));
    }

    // Payment Configuration
    public function configurePaymentMethod(Request $request, $id)
    {
        // Queries
        $config = Config::get();
        $gateway_details = Gateway::where('id', $id)->first();

        // Check gateway details
        if (empty($gateway_details)) {
            return redirect()->route('admin.payment.methods')->with('failed', trans('Not Found!'));
        }

        return view('admin.pages.payment-methods.configuration', compact('config', 'gateway_details'));
    }

    // Update Payment Configuration
    public function updatePaymentConfiguration(Request $request, $id)
    {
        // Paypal mode
        if ($id == 1) {
            Config::where('config_key', 'paypal_mode')->update([
                'config_value' => $request->paypal_mode,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'paypal_client_id')->update([
                'config_value' => $request->paypal_client_key,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'paypal_secret')->update([
                'config_value' => $request->paypal_secret,
                'updated_at' => now(),
            ]);
        }

        // Razorpay
        if ($id == 2) {
            Config::where('config_key', 'razorpay_key')->update([
                'config_value' => $request->razorpay_client_key,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'razorpay_secret')->update([
                'config_value' => $request->razorpay_secret,
                'updated_at' => now(),
            ]);
        }

        // Phonepe
        if ($id == 8) {
            Config::where('config_key', 'phonepe_client_id')->update([
                'config_value' => $request->clientId,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'phonepe_client_version')->update([
                'config_value' => $request->clientVersion,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'phonepe_client_secret')->update([
                'config_value' => $request->clientSecret,
                'updated_at' => now(),
            ]);
        }

        // Stripe
        if ($id == 3) {
            Config::where('config_key', 'stripe_publishable_key')->update([
                'config_value' => $request->stripe_publishable_key,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'stripe_secret')->update([
                'config_value' => $request->stripe_secret,
                'updated_at' => now(),
            ]);
        }

        // Paystack
        if ($id == 4) {
            Config::where('config_key', 'paystack_public_key')->update([
                'config_value' => $request->paystack_public_key,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'paystack_secret_key')->update([
                'config_value' => $request->paystack_secret,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'merchant_email')->update([
                'config_value' => $request->merchant_email,
                'updated_at' => now(),
            ]);
        }

        // Mollie
        if ($id == 5) {
            Config::where('config_key', 'mollie_key')->update([
                'config_value' => $request->mollie_key,
                'updated_at' => now(),
            ]);
        }

        // Transaction Cloud
        if ($id == 7) {
            Config::where('config_key', 'transaction_cloud_api_key')->update([
                'config_value' => $request->transaction_cloud_login
            ]);
    
            Config::where('config_key', 'transaction_cloud_api_password')->update([
                'config_value' => $request->transaction_cloud_password
            ]);
        }

        // Bank transfer
        if ($id == 6) {
            Config::where('config_key', 'bank_transfer')->update([
                'config_value' => $request->bank_transfer,
                'updated_at' => now(),
            ]);
        }

        // Mercado Pago
        if ($id == 9) {
            Config::where('config_key', 'mercado_pago_public_key')->update([
                'config_value' => $request->mercado_pago_public_key,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'mercado_pago_access_token')->update([
                'config_value' => $request->mercado_pago_access_token,
                'updated_at' => now(),
            ]);
        }

        // Toyyibpay
        if ($id == 10) {
            Config::where('config_key', 'toyyibpay_mode')->update([
                'config_value' => $request->toyyibpay_mode,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'toyyibpay_api_key')->update([
                'config_value' => $request->toyyibpay_api_key,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'toyyibpay_category_code')->update([
                'config_value' => $request->toyyibpay_category_code,
                'updated_at' => now(),
            ]);
        }

        // Flutterwave
        if ($id == 11) {
            Config::where('config_key', 'flw_public_key')->update([
                'config_value' => $request->flw_public_key,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'flw_secret_key')->update([
                'config_value' => $request->flw_secret_key,
                'updated_at' => now(),
            ]);

            Config::where('config_key', 'flw_encryption_key')->update([
                'config_value' => $request->flw_encryption_key,
                'updated_at' => now(),
            ]);
        }

        // Page redirect
        return redirect()->route('admin.payment.methods')->with('success', trans('Updated!'));
    }
}
