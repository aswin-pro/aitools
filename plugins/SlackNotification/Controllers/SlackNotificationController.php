<?php

namespace Plugins\SlackNotification\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class SlackNotificationController extends Controller
{
    public function slackSettings(Request $request)
    {
        // check database
        if (! Schema::hasTable('slack_notification_settings')) {
            Schema::create('slack_notification_settings', function (Blueprint $table) {
                $table->id();
                $table->string('slack_webhook_url')->nullable();
                $table->boolean('user_registration')->default(0);
                $table->boolean('plan_purchase')->default(0);
                $table->boolean('plan_renewal')->default(0);
                $table->boolean('error_logging')->default(0);
                $table->timestamps();
            });
        }

        // slack settings
        $slack_settings = DB::table('slack_notification_settings')->first();

        // return view
        return view()->file(base_path('plugins/SlackNotification/Views/index.blade.php'), compact('slack_settings'));
    }

    public function slackSettingsUpdate(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'slack_webhook_url' => 'required|url',
        ]);

        // if validation fails, redirect to settings page
        if ($validator->fails()) {
            return redirect()->route('admin.plugin.slack.settings')->with('failed', __('Invalid Slack Webhook URL!'));
        }

        // update or insert                                                                                                     
        DB::table('slack_notification_settings')->updateOrInsert(
            ['id' => 1],
            [
                'slack_webhook_url' => $request->slack_webhook_url,
                'user_registration' => $request->has('user_registration') ? 1 : 0,
                'plan_purchase'     => $request->has('plan_purchase') ? 1 : 0,
                'plan_renewal'      => $request->has('plan_renewal') ? 1 : 0,
                'error_logging'     => $request->has('error_logging') ? 1 : 0,
                'updated_at'        => now(),
            ]
        );

        // forget cache
        cache()->forget('slack_notification_settings');

        // update env
        $this->updateEnv('LOG_CHANNEL', $request->has('error_logging') ? 'slack' : 'stack');
        $this->updateEnv('LOG_SLACK_WEBHOOK_URL', $request->slack_webhook_url);

        // return view
        return redirect()->route('admin.plugin.slack.settings')->with('success', __('Slack Settings Updated Successfully!'));
    }

    public function updateEnv($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            // Read the file contents
            $envContent = file_get_contents($path);

            // Create a new key-value pair
            $pattern     = "/^" . preg_quote($key) . "=.*/m";
            $replacement = $key . '=' . $value;

            // Check if the key exists in .env
            if (preg_match($pattern, $envContent)) {
                // Replace existing key
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                // Append new key-value pair
                $envContent .= "\n" . $replacement;
            }

            // Write back to .env file
            file_put_contents($path, $envContent);
        }
    }
}
