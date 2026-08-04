<?php

namespace Plugins\TwilioWhatsappNotification;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Plugins\TwilioWhatsappNotification\Observers\TwilioWhatsappNotificationObserver;

class TwilioWhatsappNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__ . '/Views', 'Twilio');

        // Cache twilio whatsapp settings
        if (Schema::hasTable('twilio_whatsapp_notification_settings')) {
            $settings = cache()->rememberForever(
                'twilio_whatsapp_notification_settings',
                fn() => DB::table('twilio_whatsapp_notification_settings')->first()
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
                TwilioWhatsappNotificationObserver::class,
            ] as $observer
        ) {
            $this->app->singleton($observer, fn() => new $observer($settings));
        }

        // Attach observers
        User::observe($this->app->make(TwilioWhatsappNotificationObserver::class));
    }

    public function register()
    {
        // Register additional services if needed
    }
}
