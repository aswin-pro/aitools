<?php

use Illuminate\Support\Facades\Route;
use Plugins\GoogleAnalytics\Controllers\GoogleAnalyticsController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard/admin/plugin/google_analytics/settings', [GoogleAnalyticsController::class, 'googleAnalyticsSettings'])->name('admin.plugin.google_analytics.settings');
    Route::post('dashboard/admin/plugin/google_analytics/settings/update', [GoogleAnalyticsController::class, 'googleAnalyticsSettingsUpdate'])->name('admin.google_analytics_settings.update')->middleware(['demo.mode']);
});
