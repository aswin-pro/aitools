    <?php

use Illuminate\Support\Facades\Route;

    Route::group(['middleware' => 'checkType'], function () {
        // Choose Payment Gateway
        Route::post('/prepare-payment/{planId}', [App\Http\Controllers\Payment\PaymentController::class, "preparePaymentGateway"])->name('prepare.payment.gateway')->middleware(['demo.mode']);

        // PayPal Payment Gateway
        Route::get('/payment-paypal/{planId}', [App\Http\Controllers\Payment\PaypalController::class, "paywithpaypal"])->name('paywithpaypal');
        Route::get('/payment/status', [App\Http\Controllers\Payment\PaypalController::class, "paypalPaymentStatus"])->name('paypalPaymentStatus');

        // RazorPay
        Route::get('payment-razorpay/{planId}', [App\Http\Controllers\Payment\RazorPayController::class, "prepareRazorpay"])->name('paywithrazorpay');
        Route::get('razorpay-payment-status/{oid}/{paymentId}', [App\Http\Controllers\Payment\RazorPayController::class, "razorpayPaymentStatus"])->name('razorpay.payment.status');

        // Phonepe
        Route::get('payment-phonepe/{planId}', [App\Http\Controllers\Payment\PhonepeController::class, 'preparePhonpe'])->name('paywithphonepe');
        Route::any('phonepe-payment-status', [App\Http\Controllers\Payment\PhonepeController::class, 'phonepePaymentStatus'])->name('phonepe.payment.status');

        // Stripe
        Route::get('/payment-stripe/{planId}', [App\Http\Controllers\Payment\StripeController::class, "stripeCheckout"])->name('paywithstripe');
        Route::post('/stripe-payment-status/{paymentId}', [App\Http\Controllers\Payment\StripeController::class, "stripePaymentStatus"])->name('stripe.payment.status');
        Route::get('/stripe-payment-cancel/{paymentId}', [App\Http\Controllers\Payment\StripeController::class, "stripePaymentCancel"])->name('stripe.payment.cancel');

        // Paystack
        Route::get('/payment-paystack/{planId}', [App\Http\Controllers\Payment\PaystackController::class, "paystackCheckout"])->name('paywithpaystack');
        Route::get('/paystack-payment/callback', [App\Http\Controllers\Payment\PaystackController::class, 'paystackHandleGatewayCallback'])->name('paystack.handle.gateway.callback');

        // Mollie
        Route::get('/payment-mollie/{planId}', [App\Http\Controllers\Payment\MollieController::class, "prepareMollie"])->name('paywithmollie');
        Route::get('/mollie-payment-status', [App\Http\Controllers\Payment\MollieController::class, "molliePaymentStatus"])->name('mollie.payment.status');

        // Offline
        Route::get('/payment-offline/{planId}', [App\Http\Controllers\Payment\OfflineController::class, "offlineCheckout"])->name('paywithoffline');
        Route::post('/mark-offline-payment', [App\Http\Controllers\Payment\OfflineController::class, "markOfflinePayment"])->name('mark.payment.payment');

        // Transaction Cloud
        Route::get('/payment-transactioncloud/{planId}', [App\Http\Controllers\Payment\TransactionCloudController::class, "prepareTransactionCloud"])->name('paywithtransactioncloud');
        Route::get('/transactioncloud-payment-status', [App\Http\Controllers\Payment\TransactionCloudController::class, "transactionCloudPaymentStatus"])->name('transactioncloud.payment.status');

        // Mercado Pago
        Route::get('/payment-mercadopago/{planId}', [App\Http\Controllers\Payment\MercadoPagoController::class, "prepareMercadoPago"])->name('paywithmercadopago');
        Route::get('/mercadopago-payment-status', [App\Http\Controllers\Payment\MercadoPagoController::class, "mercadoPagoPaymentStatus"])->name('mercadopago.payment.status');
        Route::get('/mercadopago-payment-failure', [App\Http\Controllers\Payment\MercadoPagoController::class, "mercadoPagoPaymentFailure"])->name('mercadopago.payment.failure');
        Route::get('/mercadopago-payment-pending', [App\Http\Controllers\Payment\MercadoPagoController::class, "mercadoPagoPaymentPending"])->name('mercadopago.payment.pending');
        Route::get('/mercadopago-callback', [App\Http\Controllers\Payment\MercadoPagoController::class, "mercadoPagoCallback"])->name('mercadopago.callback');

        // Toyyibpay
        Route::get('/payment-toyyibpay/{planId}', [App\Http\Controllers\Payment\ToyyibpayController::class, "prepareToyyibpay"])->name('prepare.toyyibpay');
        Route::get('/toyyibpay-payment-status', [App\Http\Controllers\Payment\ToyyibpayController::class, "toyyibpayPaymentStatus"])->name('toyyibpay.payment.status');
        Route::get('/toyyibpay-payment-success', [App\Http\Controllers\Payment\ToyyibpayController::class, 'toyyibpayPaymentSuccess'])->name('toyyibpay.payment.success');

        // Flutterwave
        Route::get('/payment-flutterwave/{planId}', [App\Http\Controllers\Payment\FlutterwaveController::class, "prepareFlutterwave"])->name('prepare.flutterwave');
        Route::get('/flutterwave-payment-status', [App\Http\Controllers\Payment\FlutterwaveController::class, "flutterwavePaymentStatus"])->name('flutterwave.payment.status');
    });