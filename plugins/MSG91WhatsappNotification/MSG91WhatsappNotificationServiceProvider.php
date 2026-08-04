<?php

namespace Plugins\MSG91WhatsappNotification;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Plugins\MSG91WhatsappNotification\Observers\MSG91WhatsappNotificationObserver;

class MSG91WhatsappNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__ . '/Views', 'msg91');

        // Cache msg91 whatsapp settings
        if (Schema::hasTable('msg91_whatsapp_notification_settings')) {
            $settings = cache()->rememberForever(
                'msg91_whatsapp_notification_settings',
                fn() => DB::table('msg91_whatsapp_notification_settings')->first()
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
                MSG91WhatsappNotificationObserver::class,
            ] as $observer
        ) {
            $this->app->singleton($observer, fn() => new $observer($settings));
        }

        // Attach observers
        User::observe($this->app->make(MSG91WhatsappNotificationObserver::class));
    }

    public function register()
    {
        // Register additional services if needed
    }
}
