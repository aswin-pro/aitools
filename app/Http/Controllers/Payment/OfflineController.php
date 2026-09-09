<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Plan;
use App\Models\Setting;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class OfflineController extends Controller
{
    protected Collection $config;
    protected Setting $settings;

    public function __construct()
    {
        // config
        $this->config = Config::get();

        // settings
        $this->settings = Setting::first();
    }

    // Offline checkot
    public function index(string $planId)
    {
        // Check value
        if ($this->config[31]->config_value == null) {
            // return error
            return redirect()->route('checkout.index', $planId)->with('failed', trans('No Bank Transfer details found!'));
        } else {
            // plan details
            $plan_details = Plan::where('id', $planId)->where('status', 1)->first();

            // view
            return view('user.pages.checkout.pay-with-offline', [
                'settings'     => $this->settings,
                'plan_details' => $plan_details,
                'config'       => $this->config,
            ]);
        }
    }

    // Mark offline payment
    public function markOfflinePayment(Request $request)
    {
        // check auth
        if (! Auth::user()) {
            return redirect()->route('login');
        }

        // plan details
        $plan_details = Plan::where('id', $request->plan_id)->where('status', 1)->first();

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

            // Create service object
            $transactionService = new TransactionService();

            // Create pending transaction
            $transactionService->createPendingTransaction(
                $this->config,
                Auth::user(),
                $plan_details,
                $request->transaction_id,
                $total,
                'Offline'
            );

            // redirect success
            return redirect()->route('dashboard.user.subscriptions.index')->with('success', 'Bank transfer transaction pending now. Once, Transaction is done, will be implemented your plan by the admin.');
        } catch (\Exception $e) {
            // return error
            return redirect()->route('checkout.index', ['plan' => $request->plan_id])->with('error', "Payment failed!");
        }
    }
}
