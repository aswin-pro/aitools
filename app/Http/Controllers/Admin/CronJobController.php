<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Config;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class CronJobController extends Controller
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


    // Cron Jobs
    public function index()
    {
        $settings = Setting::first();

        $config = Config::get()->keyBy('config_key');

        $dates = $config->get('cronjob_dates_in_array')?->config_value ?? '';
        $cronHour = $config->get('cron_hour')?->config_value ?? '0';

        // Remove brackets if stored as [10,5,3,1]
        $dates = str_replace(['[', ']'], '', $dates);

        // Generate CRON command
        $projectPath = str_replace('\\', '/', base_path());

        $cronCommand = "0 {$cronHour} * * * php {$projectPath}/artisan schedule:run >> /dev/null 2>&1";

        return Inertia::render(
            'admin/settings/cron-job/index',
            compact(
                'settings',
                'cronCommand',
                'dates',
                'cronHour'
            )
        );
    }

    // Update cron jobs
    public function update(Request $request)
    {
        $request->validate([
            'dates_in_array' => 'required|string',
            'cron_hour' => 'required|integer|between:0,23',
        ]);

        // Convert dates string to array
        $dates = explode(',', $request->dates_in_array);

        $dates = array_map(function ($date) {
            return (int) trim($date);
        }, $dates);

        $dates = array_unique($dates);

        // Validate date range
        foreach ($dates as $date) {
            if ($date < -30 || $date > 366) {
                return back()->withErrors([
                    'dates_in_array' =>
                    'Please enter a valid number of dates between -30 and 366.'

                ]);
            }
        }

        Config::where('config_key', 'cronjob_dates_in_array')->update([
            'config_value' => implode(',', $dates),
        ]);

        Config::where('config_key', 'cron_hour')->update([
            'config_value' => $request->cron_hour,
        ]);
    }

    public function testReminder()
    {
        $details = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ];

        try {
            Mail::to(Auth::user()->email)
                ->send(new \App\Mail\TestMail($details));

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Test reminder email sent successfully.'
                );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(
                    'failed',
                    'Failed to send test reminder email.'
                );
        }
    }
}
