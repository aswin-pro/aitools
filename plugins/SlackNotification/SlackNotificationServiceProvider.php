<?php

namespace Plugins\SlackNotification;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Plugins\SlackNotification\Observers\SlackObserver;

class SlackNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Cache slack settings
        if (Schema::hasTable('slack_notification_settings')) {
            $settings = cache()->rememberForever(
                'slack_notification_settings',
                fn() => DB::table('slack_notification_settings')->first()
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
                SlackObserver::class,
            ] as $observer
        ) {
            $this->app->singleton($observer, fn() => new $observer($settings));
        }

        // Attach observers
        User::observe($this->app->make(SlackObserver::class));
    }

    public function register() {}
}
