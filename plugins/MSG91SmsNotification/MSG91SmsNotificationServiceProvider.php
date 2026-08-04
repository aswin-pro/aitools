<?php

namespace Plugins\MSG91SmsNotification;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Plugins\MSG91SmsNotification\Observers\MSG91SmsNotificationObserver;

class MSG91SmsNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__ . '/Views', 'msg91Sms');

        // Cache msg91 settings
        if (Schema::hasTable('msg91_sms_notification_settings')) {
            $settings = cache()->rememberForever(
                'msg91_sms_notification_settings',
                fn() => DB::table('msg91_sms_notification_settings')->first()
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
                MSG91SmsNotificationObserver::class,
            ] as $observer
        ) {
            $this->app->singleton($observer, fn() => new $observer($settings));
        }

        // Attach observers
        User::observe($this->app->make(MSG91SmsNotificationObserver::class));
    }

    public function register()
    {
        // Register additional services if needed
    }
}
