<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use GuzzleHttp\Client;
use App\Models\Gateway;
use App\Models\Setting;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Classes\AvailableVersion;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;

class DashboardController extends Controller
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

    // Dashboard
    public function index()
    {
        // Dashboard counts
        $settings = Setting::where('status', 1)->first();
        $config = Config::get();
        $currency = Currency::where('iso_code', $config['1']->config_value)->first();
        $this_month_income = Transaction::where('payment_status', 'Success')->whereMonth('created_at', Carbon::now()->month)->sum('transaction_amount');
        $today_income = Transaction::where('payment_status', 'Success')->whereDate('created_at', Carbon::today())->sum('transaction_amount');
        $overall_users = User::where('role_id', 2)->where('status', 1)->count();
        $today_users = User::where('role_id', 2)->where('status', 1)->whereDate('created_at', Carbon::today())->count();

        $monthIncome = [];
        $monthUsers = [];
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create(date('Y'), $month);
            $endDate = $startDate->copy()->endOfMonth();
            $sales = Transaction::where('payment_status', 'Success')->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->sum('transaction_amount');
            $users = User::where('role_id', 2)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->count();
            $monthIncome[$month] = $sales;
            $monthUsers[$month] = $users;
        }
        $monthIncome = implode(',', $monthIncome);
        $monthUsers = implode(',', $monthUsers);

        // Default message
        $available = new AvailableVersion;
        $resp_data = $available->availableVersion();

        if ($resp_data) {
            if ($resp_data['status'] == true && $resp_data['update'] == true) {
                // Store success message in session
                session()->flash('message', trans('<a href="' . route("admin.check") . '" class="text-white">A new version is available! <span style="position: absolute; right: 7.5vh;">' . trans("Install") . '</span></a>'));
            }
        }

        return view('admin.index', compact('this_month_income', 'today_income', 'overall_users', 'today_users', 'currency', 'settings', 'monthIncome', 'monthUsers'));
    }
}
