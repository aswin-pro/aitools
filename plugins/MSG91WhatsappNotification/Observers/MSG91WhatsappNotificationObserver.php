<?php

namespace Plugins\MSG91WhatsappNotification\Observers;

use App\Models\Config;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MSG91WhatsappNotificationObserver
{
    protected $settings;
    protected $apiUrl = "https://control.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/";

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /* -------------------------------------------------------------------------- */
    /* CREATED */
    /* -------------------------------------------------------------------------- */

    public function created($model)
    {
        // check credentials
        if (!$this->hasValidCredentials()) return;

        // send notification
        try {
            if ($model instanceof User) {
                $this->sendNotification(
                    'New User Registration Admin',
                    $this->settings->admin_number,
                    [
                        "app_name" => config('app.name'),
                        "name"     => $model->name,
                        "email"    => $model->email,
                    ]
                );
            }
        } catch (\Throwable $e) {
            return;
        }
    }

    /* -------------------------------------------------------------------------- */
    /* UPDATED */
    /* -------------------------------------------------------------------------- */

    public function updated($model)
    {
        // check credentials
        if (!$this->hasValidCredentials()) return;

        // send notification
        try {
            if ($model instanceof User && $model->isDirty('plan_details')) {

                // get old plan details
                $oldPlan = json_decode($model->getOriginal('plan_details'), true);

                // get new plan details
                $newPlan = json_decode($model->plan_details, true);

                // check plan details
                if (!$newPlan || !isset($newPlan['id'])) return;

                // get plan price
                $price = $this->getPlanPrice($model->id, $newPlan['id']);

                // prepare plan variables
                $vars  = $this->preparePlanVariables($model, $newPlan, $price);

                // purchase notification
                if (is_null($oldPlan)) {
                    $this->sendNotification('Plan Purchase Admin', $this->settings->admin_number, $vars);
                    $this->sendNotification('Plan Purchase User', $model->billing_phone, $vars);
                } else {
                    // renewal notification
                    $this->sendNotification('Plan Renewal Admin', $this->settings->admin_number, $vars);
                    $this->sendNotification('Plan Renewal User', $model->billing_phone, $vars);
                }
            }
        } catch (\Throwable $e) {
            return;
        }
    }

    /* -------------------------------------------------------------------------- */
    /* HELPERS */
    /* -------------------------------------------------------------------------- */

    // check credentials
    private function hasValidCredentials()
    {
        return $this->settings &&
            $this->settings->sender_id &&
            $this->settings->auth_key &&
            $this->settings->admin_number;
    }

    // get plan price
    private function getPlanPrice($userId, $planId)
    {
        return Transaction::where('user_id', $userId)
            ->where('plan_id', $planId)
            ->latest('id')
            ->value('transaction_amount');
    }

    // prepare plan variables
    private function preparePlanVariables(User $user, array $plan, $price)
    {
        return [
            "app_name"         => config('app.name'),
            "name"             => $user->name,
            "email"            => $user->email,
            "plan_name"        => $plan['name'] ?? '',
            "currency"         => Config::where('config_key', 'currency')->value('config_value'),
            "plan_price"       => (string) $price,
            "plan_validity"    => (string) ($plan['validity'] ?? ''),
            "plan_expiry_date" => Carbon::parse($user->plan_validity)->format('d/m/Y'),
        ];
    }

    /* -------------------------------------------------------------------------- */
    /* SEND FUNCTIONS */
    /* -------------------------------------------------------------------------- */

    // send notification
    private function sendNotification($templateName, $toNumber, array $variables)
    {
        // get template
        $template = DB::table('msg91_whatsapp_notification_templates')
            ->where('template_name', $templateName)
            ->where('is_enabled', 1)
            ->first();

        // check template and phone
        if (!$template || empty($toNumber)) return;

        // send
        $this->sendMSG91($template, $toNumber, $variables);
    }

    // send msg91 whatsapp
    private function sendMSG91($template, $to, $data)
    {
        // build components
        $components = $this->buildComponents($template, $data);

        // build payload
        $payload = [
            "integrated_number" => $this->settings->sender_id,
            "content_type" => "template",
            "payload" => [
                "messaging_product" => "whatsapp",
                "type" => "template",
                "template" => [
                    "name" => $template->template_id,
                    "language" => ["code" => "en", "policy" => "deterministic"],
                    "namespace" => $template->namespace,
                    "to_and_components" => [[
                        "to" => [$to],
                        "components" => $components,
                    ]]
                ]
            ]
        ];

        // send
        Http::withHeaders([
            'authkey'      => $this->settings->auth_key,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, $payload);
    }

    // build components
    private function buildComponents($template, $data)
    {
        // get variables
        $vars = json_decode($template->variables, true) ?? [];
        
        // components
        $components = [];

        // loop variables
        foreach ($vars as $i => $var) {
            $components["body_" . ($i + 1)] = [
                "type"  => "text",
                "value" => $data[$var] ?? '',
            ];
        }

        // return components
        return $components;
    }
}
