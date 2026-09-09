<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Plan;
use App\Models\Transaction;
use App\Services\PlanActivationService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MercadoPagoController extends Controller
{
    private Collection $config;
    private string $apiUrl = "https://api.mercadopago.com/checkout/preferences";
    private string $accessToken;

    public function __construct()
    {
        // config
        $this->config = Config::get();

        // access token
        $this->accessToken = $this->config[56]->config_value;
    }

    // index
    public function index(string $planId)
    {
        // check auth
        if (! Auth::user()) {
            return redirect()->route('login');
        }

        // plan details
        $plan_details = Plan::where('id', $planId)->where('status', 1)->first();

        // check plan details
        if ($plan_details == null) {
            abort(404);
        }

        try {
            // price
            $price = (float) ($plan_details->price);

            // tax price
            $tax_price = getTaxPrice($this->config, $price);

            // total
            $total = $price + $tax_price;

            // build payload
            $payload = [
                'items'       => [
                    [
                        'title'       => $plan_details->name,
                        'quantity'    => 1,
                        'unit_price'  => $total,
                        'currency_id' => $this->config[1]->config_value,
                    ],
                ],
                'back_urls'   => [
                    'success' => route('payment.mercadopago.status'),
                    'failure' => route('payment.mercadopago.failure'),
                    'pending' => route('payment.mercadopago.pending'),
                ],
                'auto_return' => 'approved',
            ];

            // send request
            $response = Http::withToken($this->accessToken)->post($this->apiUrl, $payload);

            // check response
            if ($response->successful()) {
                // transaction id
                $transactionId = $response['id'];

                // Create service object
                $transactionService = new TransactionService();

                // Create pending transaction
                $transactionService->createPendingTransaction(
                    $this->config,
                    Auth::user(),
                    $plan_details,
                    $transactionId,
                    $total,
                    'Mercado Pago'
                );

                // Redirect to Mercado Pago payment page
                return redirect($response['init_point']);
            } else {
                // return error
                return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
            }
        } catch (\Exception $e) {
            // return error
            return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
        }
    }

    // payment status
    public function paymentStatus(Request $request)
    {
        // transaction id
        $transactionId = $request->query('preference_id');

        try {
            // activate plan
            $message = (new PlanActivationService())->activate(
                $this->config,
                Auth::user(),
                $transactionId,
                $transactionId
            );

            // return success
            return redirect()->route('dashboard.user.subscriptions.index')->with('success', $message);
        } catch (\Exception $e) {
            // return error
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
        }
    }

    // payment failure
    public function paymentFailure(Request $request)
    {
        // transaction id
        $transactionId = $request->query('preference_id');

        // Update the transaction status to FAILED
        Transaction::where('transaction_id', $transactionId)->update([
            'payment_status' => Transaction::PAYMENT_FAILED,
        ]);

        // return error
        return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
    }

    // payment pending
    public function paymentPending(Request $request)
    {
        // transaction id
        $transactionId = $request->query('preference_id');

        // Update the transaction status to PENDING
        Transaction::where('transaction_id', $transactionId)->update([
            'payment_status' => Transaction::PAYMENT_PENDING,
        ]);

        // return error
        return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment is pending!");
    }
}
