<?php

namespace Plugins\SlackNotification\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SlackNotificationController extends Controller
{

    public function slackSettings(Request $request)
    {
        // Check database
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

        // Slack settings
        $slack_settings = DB::table('slack_notification_settings')->first();

        return Inertia::render('admin/plugins/slack-notification', [
            'slack_settings' => $slack_settings,
        ]);
    }


public function slackSettingsUpdate(Request $request)
{
    $request->validate([
        'slack_webhook_url' => 'required|url',
    ]);

    DB::table('slack_notification_settings')->updateOrInsert(
        ['id' => 1],
        [
            'slack_webhook_url' => $request->slack_webhook_url,
            'user_registration' => $request->boolean('user_registration') ? 1 : 0,
            'plan_purchase'     => $request->boolean('plan_purchase') ? 1 : 0,
            'plan_renewal'      => $request->boolean('plan_renewal') ? 1 : 0,
            'error_logging'     => $request->boolean('error_logging') ? 1 : 0,
            'updated_at'        => now(),
        ]
    );

    cache()->forget('slack_notification_settings');

    $this->updateEnv(
        'LOG_CHANNEL',
        $request->boolean('error_logging') ? 'slack' : 'stack'
    );

    $this->updateEnv(
        'LOG_SLACK_WEBHOOK_URL',
        $request->slack_webhook_url
    );

    return redirect()
        ->route('admin.plugin.slack.settings')
        ->with('success', __('Slack Settings Updated Successfully!'));
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
