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
use Illuminate\Support\Facades\Redirect;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\OrderApplicationContextBuilder;
use PaypalServerSdkLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSdkLib\Models\Builders\PurchaseUnitRequestBuilder;
use PaypalServerSdkLib\Models\CheckoutPaymentIntent;
use PaypalServerSdkLib\PaypalServerSdkClient;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;

class PaypalController extends Controller
{
    protected PaypalServerSdkClient $paypal;
    protected Collection $config;

    public function __construct()
    {
        // config
        $this->config = Config::get();

        // initialize paypal
        $this->paypal = PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                ClientCredentialsAuthCredentialsBuilder::init(
                    trim($this->config[4]->config_value),
                    trim($this->config[5]->config_value)
                )
            )
            ->environment(
                $this->config[3]->config_value === 'sandbox'
                    ? Environment::SANDBOX
                    : Environment::PRODUCTION
            )
            ->build();
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

            // Construct PayPal order request
            $order = OrderRequestBuilder::init(
                CheckoutPaymentIntent::CAPTURE,
                [
                    PurchaseUnitRequestBuilder::init(
                        AmountWithBreakdownBuilder::init(
                            $this->config[1]->config_value,
                            number_format($total, 2, '.', '')
                        )->build()
                    )->build(),
                ]
            )
                ->applicationContext(
                    OrderApplicationContextBuilder::init()
                        ->returnUrl(route('payment.paypal.status'))
                        ->cancelUrl(route('payment.paypal.status'))
                        ->build()
                )
                ->build();

            // response
            $response = $this->paypal
                ->getOrdersController()
                ->createOrder([
                    'body'   => $order,
                    'prefer' => 'return=representation',
                ]);

            // Check PayPal response
            if ($response->getStatusCode() !== 201) {
                return redirect()
                    ->route('checkout.index', ['plan' => $planId])
                    ->with(
                        'error',
                        "Payment failed. Please try again."
                    );
            }

            // result
            $result = $response->getResult();

            // order id
            $orderId = $result->getId();

            // initialize null redirect url
            $redirectUrl = null;

            // loop and get approve post request url
            foreach ($result->getLinks() as $link) {
                if ($link->getRel() === 'approve') {
                    $redirectUrl = $link->getHref();
                    break;
                }
            }

            // Create service object
            $transactionService = new TransactionService();

            // Create pending transaction
            $transactionService->createPendingTransaction(
                $this->config,
                Auth::user(),
                $plan_details,
                $orderId,
                $total,
                'Paypal'
            );

            // Redirect to PayPal for payment
            return Redirect::away($redirectUrl);
        } catch (\Exception $ex) {
            return redirect()->route('checkout.index', ['plan' => $planId])->with('error', "Payment failed!");
        }
    }

    // payment status
    public function paymentStatus(Request $request)
    {
        // check request data
        if (empty($request->PayerID) || empty($request->token)) {
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
        }

        try {
            // Payment ID
            $paymentId = $request->token;

            // capture order
            $response = $this->paypal
                ->getOrdersController()
                ->captureOrder([
                    'id'     => $paymentId,
                    'prefer' => 'return=representation',
                ]);

            // check response code
            if ($response->getStatusCode() === 201) {
                // activate plan
                $message = (new PlanActivationService())->activate(
                    $this->config,
                    Auth::user(),
                    $paymentId,
                    $paymentId
                );

                // return success
                return redirect()->route('dashboard.user.subscriptions.index')->with('success', $message);
            } else {
                Transaction::where('transaction_id', $paymentId)->update([
                    'payment_status' => Transaction::PAYMENT_FAILED,
                ]);

                // return error
                return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
            }
        } catch (\Exception $e) {
            // return error
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
        }
    }
}
