<?php

namespace App\Console\Commands;

use App\Models\Config;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class PlanExpiryCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiry:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send plan expiry reminders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    // cron job
    public function handle()
    {
        // cron days
        $days = explode(',', Config::where('config_key', 'cronjob_dates_in_array')->value('config_value'));
        $days = array_map('intval', $days);

        // today
        $today = Carbon::now();

        // loop days
        foreach ($days as $day) {
            // calculate target expiry range
            $target = $today->copy()->addDays($day);

            // get users whose plans expire on the target date
            $users = User::where('status', 1)
                ->whereDate('plan_validity', $target)
                ->get();

            // loop users
            foreach ($users as $user) {
                // skip if user has no phone
                if (!$user->billing_phone) continue;

                // build variables
                $vars = $this->buildVariables($user);

                // get templates
                $template = $day <= 0
                    ? 'User Plan Expired Notification'
                    : 'User Plan Expiry Remainder';

                // twilio notifications
                $this->sendTwilioWhatsapp($template, $user, $vars);
                $this->sendTwilioSms($template, $user, $vars);

                // msg91 notifications
                $this->sendMsg91Whatsapp($template, $user, $vars);
                $this->sendMsg91Sms($template, $user, $vars);
            }
        }
    }

    /* ------------------------- HELPERS ------------------------- */

    // variables
    private function buildVariables($user)
    {
        return [
            'app_name'    => config('app.name'),
            'name'        => $user->name,
            'email'       => $user->email,
            'plan_name'   => json_decode($user->plan_details)->name ?? '',
            'expiry_date' => Carbon::parse($user->plan_validity)->format('Y-m-d'),
        ];
    }

    // get notification template
    private function template($table, $name)
    {
        return DB::table($table)->where('template_name', $name)->where('is_enabled', 1)->first();
    }

    // msg91 components
    private function msg91Components($tpl, $vars)
    {
        // get selected variables
        $list = json_decode($tpl->variables, true) ?? [];

        // build components
        $out = [];
        foreach ($list as $i => $v) {
            $out['body_' . ($i + 1)] = ['type' => 'text', 'value' => $vars[$v] ?? ''];
        }

        // return components
        return $out;
    }

    /* ------------------------- TWILIO ------------------------- */

    // send twilio whatsapp notification
    private function sendTwilioWhatsapp($template, $user, $vars)
    {
        // check if twilio plugin is installed
        if (!Schema::hasTable('twilio_whatsapp_notification_settings') || !is_dir('plugins/TwilioWhatsappNotification')) return;

        // get template
        $template = $this->template('twilio_whatsapp_notification_templates', $template);

        // check if template exists
        if (!$template) return;

        // get twilio settings
        $settings = cache()->rememberForever(
            'twilio_whatsapp_notification_settings',
            fn() => DB::table('twilio_whatsapp_notification_settings')->first()
        );

        // send notification
        Http::withBasicAuth($settings->account_sid, $settings->auth_token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$settings->account_sid}/Messages.json", [
                'From' => 'whatsapp:+' . $settings->from_number,
                'To'   => 'whatsapp:+' . $user->billing_phone,
                'ContentSid'       => $template->template_sid,
                'ContentVariables' => json_encode($vars),
            ]);
    }

    // send twilio sms notification
    private function sendTwilioSms($template, $user, $vars)
    {
        // check if twilio plugin is installed
        if (!Schema::hasTable('twilio_sms_notification_settings') || !is_dir('plugins/TwilioSmsNotification')) return;

        // get template
        $template = $this->template('twilio_sms_notification_templates', $template);

        // check if template exists
        if (!$template) return;

        // get twilio settings
        $settings = cache()->rememberForever(
            'twilio_sms_notification_settings',
            fn() => DB::table('twilio_sms_notification_settings')->first()
        );

        // send notification
        Http::withBasicAuth($settings->account_sid, $settings->auth_token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$settings->account_sid}/Messages.json", [
                'From' => '+' . $settings->from_number,
                'To'   => '+' . $user->billing_phone,
                'ContentSid'       => $template->template_sid,
                'ContentVariables' => json_encode($vars),
            ]);
    }

    /* ------------------------- MSG91 ------------------------- */

    // send msg91 whatsapp notification
    private function sendMsg91Whatsapp($template, $user, $vars)
    {
        if (!Schema::hasTable('msg91_whatsapp_notification_settings') || !is_dir('plugins/MSG91WhatsappNotification')) return;

        // get template
        $template = $this->template('msg91_whatsapp_notification_templates', $template);

        // check if template exists
        if (!$template) return;

        // get msg91 settings
        $settings = cache()->rememberForever(
            'msg91_whatsapp_notification_settings',
            fn() => DB::table('msg91_whatsapp_notification_settings')->first()
        );

        // get components
        $components = $this->msg91Components($template, $vars);

        // send notification
        Http::withHeaders([
            'authkey' => $settings->auth_key,
            'Content-Type' => 'application/json'
        ])->post("https://control.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/", [
            'integrated_number' => $settings->sender_id,
            'content_type' => 'template',
            'payload' => [
                'messaging_product' => 'whatsapp',
                'type' => 'template',
                'template' => [
                    'name' => $template->template_id,
                    'language' => ['code' => 'en', 'policy' => 'deterministic'],
                    'namespace' => $template->namespace,
                    'to_and_components' => [[
                        'to' => [$user->billing_phone],
                        'components' => $components
                    ]]
                ]
            ]
        ]);
    }

    // send msg91 sms notification
    private function sendMsg91Sms($template, $user, $vars)
    {
        // check if msg91 plugin is installed
        if (!Schema::hasTable('msg91_sms_notification_settings') || !is_dir('plugins/MSG91SmsNotification')) return;

        // get template
        $template = $this->template('msg91_sms_notification_templates', $template);

        // check if template exists
        if (!$template) return;

        // get msg91 settings
        $settings = cache()->rememberForever(
            'msg91_sms_notification_settings',
            fn() => DB::table('msg91_sms_notification_settings')->first()
        );

        // send notification
        Http::withHeaders([
            'authkey' => $settings->auth_key,
            'Content-Type' => 'application/json'
        ])->post("https://control.msg91.com/api/v5/flow/", array_merge([
            'flow_id' => $template->template_id,
            'sender' => $settings->sender_id,
            'mobiles' => $user->billing_phone,
        ], $vars));
    }
}
