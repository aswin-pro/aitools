<?php

namespace Plugins\MSG91SmsNotification\Observers;

use App\Models\Config;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MSG91SmsNotificationObserver
{
    protected $settings;
    protected $apiUrl = "https://control.msg91.com/api/v5/flow/";

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

        // check if user registration is enabled
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
        } catch (\Exception $e) {
        }
    }

    public function updated($model)
    {
        // check credentials
        if (!$this->hasValidCredentials()) {
            return;
        }

        // send notification
        try {
            if ($model instanceof User && $model->isDirty('plan_details')) {
                // get old plan details
                $oldPlan = json_decode($model->getOriginal('plan_details'), true);

                // get new plan details
                $newPlan = json_decode($model->plan_details, true);

                if (!$newPlan || !isset($newPlan['id'])) {
                    return;
                }

                // get plan price
                $price = $this->getPlanPrice($model->id, $newPlan['id']);

                // prepare plan variables
                $vars  = $this->preparePlanVariables($model, $newPlan, $price);

                // Purchase Notification
                if (is_null($oldPlan)) {
                    $this->sendNotification('Plan Purchase Admin', $this->settings->admin_number, $vars);
                    $this->sendNotification('Plan Purchase User', $model->billing_phone, $vars);
                } else {
                    // Renewal Notification
                    $this->sendNotification('Plan Renewal Admin', $this->settings->admin_number, $vars);
                    $this->sendNotification('Plan Renewal User', $model->billing_phone, $vars);
                }
            }
        } catch (\Exception $e) {
        }
    }

    /* -------------------------------------------------------------------------- */
    /* HELPERS */
    /* -------------------------------------------------------------------------- */

    // check credentials
    private function hasValidCredentials()
    {
        return $this->settings &&
            $this->settings->auth_key &&
            $this->settings->sender_id &&
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

    // send notification
    private function sendNotification($templateName, $toNumber, array $variables)
    {
        // get template
        $template = DB::table('msg91_sms_notification_templates')
            ->where('template_name', $templateName)
            ->where('is_enabled', 1)
            ->first();

        if (!$template) return;

        // send
        $this->sendMsg91($template->template_id, $toNumber, $variables);
    }

    // send
    private function sendMsg91($templateId, $toNumber, array $variables)
    {
        // prepare payload
        $payload = array_merge([
            "flow_id" => $templateId,
            "sender"  => $this->settings->sender_id,
            "mobiles" => $toNumber,
        ], $variables);

        // send request
        Http::withHeaders([
            'authkey'      => $this->settings->auth_key,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, $payload);
    }
}
