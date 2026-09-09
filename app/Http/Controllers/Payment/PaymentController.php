<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PaymentRequest;
use App\Models\Gateway;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private ?Gateway $payment_gateway = null;

    public function __construct(Request $request)
    {
        // payment mode
        $this->payment_gateway = Gateway::where('id', $request->payment_gateway_id)->first();
    }

    public function preparePaymentGateway(PaymentRequest $request, string $planId)
    {
        // check auth
        if (! Auth::user()) {
            return redirect()->route('login');
        }

        // check payment gateway
        if ($this->payment_gateway == null) {
            return redirect()->back()->with('failed', trans('Please choose valid payment method!'));
        }

        // update billing
        $this->updateBilling($request);

        // redirect to payment gateway
        if ($this->payment_gateway->name == 'Paypal') {
            // redirect to paypal
            return redirect()->route('payment.paypal', $planId);
        } else if ($this->payment_gateway->name == "Razorpay") {
            // redirect to razorpay
            return redirect()->route('payment.razorpay', $planId);
        } else if ($this->payment_gateway->name == "Stripe") {
            // redirect to stripe
            return redirect()->route('payment.stripe', $planId);
        } else if ($this->payment_gateway->name == "PhonePe") {
            // redirect to phonepe
            return redirect()->route('payment.phonepe', $planId);
        } else if ($this->payment_gateway->name == "Paystack") {
            // redirect to paystack
            return redirect()->route('payment.paystack', $planId);
        } else if ($this->payment_gateway->name == "Mollie") {
            // redirect to mollie
            return redirect()->route('payment.mollie', $planId);
        } else if ($this->payment_gateway->name == "Bank Transfer") {
            // redirect to offline
            return redirect()->route('payment.offline', $planId);
        } else if ($this->payment_gateway->name == "Transaction Cloud") {
            // redirect to transaction cloud
            return redirect()->route('payment.transactioncloud', $planId);
        } else if ($this->payment_gateway->name == "Mercado Pago") {
            // redirect to mercado pago
            return redirect()->route('payment.mercadopago', $planId);
        } else if ($this->payment_gateway->name == "Toyyibpay") {
            // redirect to toyyibpay
            return redirect()->route('payment.toyyibpay', compact('planId'));
        } else if ($this->payment_gateway->name == "Flutterwave") {
            // redirect to flutterwave
            return redirect()->route('payment.flutterwave', compact('planId'));
        } else {
            return redirect()->back()->with('failed', trans('Something went wrong!'));
        }
    }

    // update billing
    private function updateBilling(PaymentRequest $request)
    {
        User::where('id', Auth::user()->id)->update([
            'billing_name'    => $request->billing_name,
            'billing_email'   => $request->billing_email,
            'billing_phone'   => $request->billing_phone,
            'billing_address' => $request->billing_address,
            'billing_city'    => $request->billing_city,
            'billing_state'   => $request->billing_state,
            'billing_zipcode' => $request->billing_zipcode,
            'billing_country' => $request->billing_country,
            'type'            => $request->type,
            'vat_number'      => $request->vat_number,
        ]);
    }
}
