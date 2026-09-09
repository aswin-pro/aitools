<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PlanActivationService;
use App\Services\TransactionService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ToyyibpayController extends Controller
{
    protected Collection $config;
    protected string $apiKey;
    protected string $categoryCode;
    protected string $baseUrl;

    public function __construct()
    {
        // config
        $this->config = Config::get();

        // api key
        $this->apiKey = $this->config[65]->config_value;

        // category code
        $this->categoryCode = $this->config[66]->config_value;

        // base url
        if ($this->config[64]->config_value == 'sandbox') {
            $this->baseUrl = "https://dev.toyyibpay.com/"; // Development URL
        } else {
            $this->baseUrl = "https://toyyibpay.com/"; // Production URL
        }
    }

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

            // amount in paise
            $amountInPaise = (float) number_format($total, 2) * 100;

            // Transaction ID
            $transactionId = uniqid();

            // create client
            $client = new Client(['base_uri' => $this->baseUrl]);

            // Prepare bill details
            $billDetails = [
                'billName'                => 'Payment for #' . $transactionId,
                'billDescription'         => 'Plan Payment',
                'billAmount'              => $amountInPaise,
                'billReturnUrl'           => route('payment.toyyibpay.success'),
                'billCallbackUrl'         => route('payment.toyyibpay.status'),
                'billExternalReferenceNo' => $transactionId,
                'userSecretKey'           => $this->apiKey,
                'categoryCode'            => $this->categoryCode,
                'billPriceSetting'        => 1,
                'billPayorInfo'           => 1,
                'billTo'                  => Auth::user()->name,
                'billEmail'               => Auth::user()->email,
                'billPhone'               => Auth::user()->billing_phone,
            ];

            // send request
            $response = $client->post('index.php/api/createBill', [
                'form_params' => $billDetails,
            ]);

            // response body
            $responseBody = json_decode($response->getBody(), true);

            // check response body
            if (isset($responseBody[0]['BillCode'])) {
                // bill code
                $billCode = $responseBody[0]['BillCode'];

                // Create service object
                $transactionService = new TransactionService();

                // Create pending transaction
                $transactionService->createPendingTransaction(
                    $this->config,
                    Auth::user(),
                    $plan_details,
                    $billCode,
                    $total,
                    'Toyyibpay'
                );

                // redirect to toyyibpay
                return redirect()->to($this->baseUrl . "{$billCode}");
            } else {
                // return error
                return back()->with('failed', trans('Failed to initiate payment.'));
            }
        } catch (\Exception $e) {
            // return error
            return back()->with('failed', trans('Failed to initiate payment.'));
        }
    }

    // payment status
    public function paymentStatus(Request $request)
    {
        // status id
        $statusId = $request['status_id'];

        // bill code
        $billCode = $request['billcode'];

        // transaction id
        $transactionId = $request['transaction_id'];

        // activate plan
        $activate_plan = $this->activateUserPlan($statusId, $billCode, $transactionId);

        if (isset($activate_plan['success']) && $activate_plan['success']) {
            // return success
            return redirect()->route('dashboard.user.subscriptions.index')->with('success', "Plan activation success!");
        } else {
            // return error
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
        }
    }

    // payment success
    public function paymentSuccess(Request $request)
    {
        // status id
        $statusId = $request['status_id'];

        // bill code
        $billCode = $request['billcode'];

        // transaction id
        $transactionId = $request['transaction_id'];

        // activate plan
        $activate_plan = $this->activateUserPlan($statusId, $billCode, $transactionId);

        if (isset($activate_plan['success']) && $activate_plan['success']) {
            // return success
            return redirect()->route('dashboard.user.subscriptions.index')->with('success', "Plan activation success!");
        } else {
            // return error
            return redirect()->route('dashboard.user.subscriptions.index')->with('error', "Payment failed!");
        }
    }

    // activate user plan
    public function activateUserPlan(?string $statusId, ?string $billCode, ?string $transactionId)
    {
        // check bill code
        if (empty($billCode)) {
            // return error
            return [
                'success' => false,
            ];
        }

        // Payment success
        if ($statusId == 1) {
            // user details
            $user_details = User::find(Auth::user()->id);

            try {
                // activate plan
                $activate_plan = new PlanActivationService();

                // activate
                $activate_plan->activate(
                    $this->config,
                    $user_details,
                    $billCode,
                    $transactionId
                );
            } catch (\Exception $e) {
                // return error
                return [
                    'success' => false,
                ];
            }

            // return success
            return [
                'success' => true,
            ];
        }

        // Payment pending
        if ($statusId == 2 || $statusId == 4) {
            // Update the transaction status to FAILED
            Transaction::where('transaction_id', $billCode)->update(['payment_status' => 'PENDING']);

            return [
                'success' => false,
            ];
        }

        // Payment failed
        if ($statusId == 3) {
            // Update the transaction status to FAILED
            Transaction::where('transaction_id', $billCode)->update(['payment_status' => 'FAILED']);

            return [
                'success' => false,
            ];
        }
    }
}
