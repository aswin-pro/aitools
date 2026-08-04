<?php

namespace Plugins\TwilioSmsNotification;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Plugins\TwilioSmsNotification\Observers\TwilioSmsNotificationObserver;

class TwilioSmsNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__ . '/Views', 'TwilioSms');

        // Cache twilio sms settings
        if (Schema::hasTable('twilio_sms_notification_settings')) {
            $settings = cache()->rememberForever(
                'twilio_sms_notification_settings',
                fn() => DB::table('twilio_sms_notification_settings')->first()
            );  
        } else {
            $settings = null;
        }

        // If settings not found, stop observer registration
        if (!$settings) {
            return;
        }

        // Register observers with dependency injection
        foreach (
            [
                TwilioSmsNotificationObserver::class,
            ] as $observer
        ) {
            $this->app->singleton($observer, fn() => new $observer($settings));
        }

        // Attach observers
        User::observe($this->app->make(TwilioSmsNotificationObserver::class));
    }

    public function register()
    {
        // Register additional services if needed
    }
}
