<?php

use Illuminate\Support\Facades\Route;
use Plugins\SlackNotification\Controllers\SlackNotificationController;

Route::middleware(['web', 'auth', 'admin'])->group(function () {
    // Admin Settings
    Route::get('admin/plugin/slack/settings', [SlackNotificationController::class, 'slackSettings'])->name('admin.plugin.slack.settings');
    Route::post('admin/plugin/slack/settings/update', [SlackNotificationController::class, 'slackSettingsUpdate'])->name('admin.slack_settings.update')->middleware('demo.mode');
});
