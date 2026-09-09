<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'checkType'], function () {
    // Checkout
    Route::get('/checkout/{plan}', [App\Http\Controllers\User\CheckoutController::class, "checkout"])->name('checkout.index');

    // Choose Payment Gateway
    Route::post('/prepare-payment/{planId}', [App\Http\Controllers\Payment\PaymentController::class, "preparePaymentGateway"])->name('prepare.payment.gateway')->middleware(['demo.mode']);

    // PayPal
    Route::get('/payment/paypal/{planId}', [App\Http\Controllers\Payment\PaypalController::class, "index"])->name('payment.paypal');
    Route::get('/paypal-payment/status', [App\Http\Controllers\Payment\PaypalController::class, "paymentStatus"])->name('payment.paypal.status');

    // RazorPay
    Route::get('payment/razorpay/{planId}', [App\Http\Controllers\Payment\RazorPayController::class, "index"])->name('payment.razorpay');
    Route::get('/razorpay-payment/status/{oid}/{paymentId}', [App\Http\Controllers\Payment\RazorPayController::class, "paymentStatus"])->name('payment.razorpay.status');

    // Stripe
    Route::get('/payment/stripe/{planId}', [App\Http\Controllers\Payment\StripeController::class, "index"])->name('payment.stripe');
    Route::post('/stripe-payment/status/{paymentId}', [App\Http\Controllers\Payment\StripeController::class, "paymentStatus"])->name('payment.stripe.status');
    Route::get('/stripe-payment/cancel/{paymentId}', [App\Http\Controllers\Payment\StripeController::class, "paymentCancel"])->name('payment.stripe.cancel');

    // Mollie
    Route::get('/payment/mollie/{planId}', [App\Http\Controllers\Payment\MollieController::class, "index"])->name('payment.mollie');
    Route::get('/mollie-payment/status', [App\Http\Controllers\Payment\MollieController::class, "paymentStatus"])->name('payment.mollie.status');

    // Offline
    Route::get('/payment/offline/{planId}', [App\Http\Controllers\Payment\OfflineController::class, "index"])->name('payment.offline');
    Route::post('/mollie-payment/mark', [App\Http\Controllers\Payment\OfflineController::class, "markOfflinePayment"])->name('payment.offline.mark');

    // Transaction Cloud
    Route::get('/payment/transactioncloud/{planId}', [App\Http\Controllers\Payment\TransactionCloudController::class, "index"])->name('payment.transactioncloud');
    Route::get('/transactioncloud-payment/status', [App\Http\Controllers\Payment\TransactionCloudController::class, "paymentStatus"])->name('payment.transactioncloud.status');

    // Phonepe
    Route::get('payment-phonepe/{planId}', [App\Http\Controllers\Payment\PhonepeController::class, 'index'])->name('payment.phonepe');
    Route::any('phonepe-payment/status', [App\Http\Controllers\Payment\PhonepeController::class, 'paymentStatus'])->name('payment.phonepe.status');

    // Mercado Pago
    Route::get('/payment-mercadopago/{planId}', [App\Http\Controllers\Payment\MercadoPagoController::class, "index"])->name('payment.mercadopago');
    Route::get('/mercadopago-payment/status', [App\Http\Controllers\Payment\MercadoPagoController::class, "paymentStatus"])->name('payment.mercadopago.status');
    Route::get('/mercadopago-payment/failure', [App\Http\Controllers\Payment\MercadoPagoController::class, "paymentFailure"])->name('payment.mercadopago.failure');
    Route::get('/mercadopago-payment/pending', [App\Http\Controllers\Payment\MercadoPagoController::class, "paymentPending"])->name('payment.mercadopago.pending');

    // Toyyibpay
    Route::get('/payment-toyyibpay/{planId}', [App\Http\Controllers\Payment\ToyyibpayController::class, "index"])->name('payment.toyyibpay');
    Route::get('/toyyibpay-payment/status', [App\Http\Controllers\Payment\ToyyibpayController::class, "paymentStatus"])->name('payment.toyyibpay.status');
    Route::get('/toyyibpay-payment/success', [App\Http\Controllers\Payment\ToyyibpayController::class, 'paymentSuccess'])->name('payment.toyyibpay.success');

    // Flutterwave
    Route::get('/payment-flutterwave/{planId}', [App\Http\Controllers\Payment\FlutterwaveController::class, "index"])->name('payment.flutterwave');
    Route::get('/flutterwave-payment/status', [App\Http\Controllers\Payment\FlutterwaveController::class, "paymentStatus"])->name('payment.flutterwave.status');

    // Paystack
    Route::get('/payment-paystack/{planId}', [App\Http\Controllers\Payment\PaystackController::class, "index"])->name('payment.paystack');
    Route::get('/paystack-payment/callback', [App\Http\Controllers\Payment\PaystackController::class, 'callback'])->name('payment.paystack.callback');
});
