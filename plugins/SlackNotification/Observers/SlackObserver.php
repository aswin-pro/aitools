<?php

namespace Plugins\SlackNotification\Observers;

use App\Models\Config;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SlackObserver
{
    protected $settings;

    // slack notification settings
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function created($model)
    {
        // check credentials
        if (!$this->hasValidCredentials()) {
            return;
        }

        // check if user registration is enabled
        try {
            if ($model instanceof User && $this->settings->user_registration == 1) {
                $message = "🎉 *New sign-up alert!*\n";
                $message .= "👤 *" . $model->name . "* just joined!\n";
                $message .= "📧  *Email:* " . $model->email . "\n";
                $message .= "🔥 More users, more growth!\n";
                // send message to slack
                Http::post($this->settings->slack_webhook_url, [
                    'text' => $message,
                ]);
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

        $user = $model;

        // check if plan details is changed
        try {
            if ($model instanceof User && $model->isDirty('plan_details')) {
                // get old plan details
                $oldPlan = json_decode($model->getOriginal('plan_details'), true);
                // get new plan details
                $newPlan = json_decode($model->plan_details, true);
                // currency
                $currency = Config::where('config_key', 'currency')->first()->config_value;

                // total
                $total_price = Transaction::orderBy('id', 'desc')->where('user_id', $model->id)->where('plan_id', $newPlan['id'])->first()->transaction_amount;

                // check if plan purchase is enabled
                if ($this->settings->plan_purchase == 1 && $oldPlan == null) {
                    $message = "💰 *Cha-ching! New Sale!* 💳\n";
                    $message .= "👤 " . $user->name . " | " . $user->email . "\n";
                    $message .= "📦 *Plan:* " . $newPlan['name'] . "\n";
                    $message .= "💵 *Price:* " . $currency . " " . $total_price . "\n";
                    $message .= "🚀 Keep the momentum going!\n";

                    // send message to slack
                    Http::post($this->settings->slack_webhook_url, [
                        'text' => $message,
                    ]);
                } elseif ($this->settings->plan_renewal == 1 && $oldPlan != null) { // check if plan renewal is enabled
                    $message = "🔄 Renewal Success! ✅\n";
                    $message .= "👤 " . $user->name . " just renewed their plan!\n";
                    $message .= "📦 *Plan:* " . $newPlan['name'] . "\n";
                    $message .= "💵 *Charged:* " . $currency . " " . $total_price . "\n";
                    $message .= "🎯 Loyal customers = 🔥 business!\n";

                    // send message to slack
                    Http::post($this->settings->slack_webhook_url, [
                        'text' => $message,
                    ]);
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
            $this->settings->slack_webhook_url;
    }
}
