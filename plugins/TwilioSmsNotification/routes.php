<?php

use Illuminate\Support\Facades\Route;
use Plugins\TwilioSmsNotification\Controllers\TwilioSmsNotificationController;

// Settings Update
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Settings
    Route::get('admin/plugin/twilio_sms_notification/settings', [TwilioSmsNotificationController::class, 'twilioSmsNotificationSettings'])->name('admin.plugin.twilio_sms_notification.settings');
    Route::post('admin/plugin/twilio_sms_notification/settings/update', [TwilioSmsNotificationController::class, 'twilioSmsNotificationSettingsUpdate'])->name('admin.twilio_sms_notification_settings.update')->middleware('demo.mode');

    // New Register Template Update
    Route::post('admin/plugin/twilio_sms/template/user_register/update', [TwilioSmsNotificationController::class, 'twilioSmsTemplateUserRegisterUpdate'])->name('admin.twilio_sms_template_user_register.update')->middleware('demo.mode');

    // Plan Purchase Template Update
    Route::post('admin/plugin/twilio_sms/template/plan_purchase/update', [TwilioSmsNotificationController::class, 'twilioSmsTemplatePlanPurchaseUpdate'])->name('admin.twilio_sms_template_plan_purchase.update')->middleware('demo.mode');

    // Plan Renewal Template Update
    Route::post('admin/plugin/twilio_sms/template/plan_renewal/update', [TwilioSmsNotificationController::class, 'twilioSmsTemplatePlanRenewalUpdate'])->name('admin.twilio_sms_template_plan_renewal.update')->middleware('demo.mode');

    // Plan Expiry Remainder Template Update
    Route::post('admin/plugin/twilio_sms/template/plan_expiry_remainder/update', [TwilioSmsNotificationController::class, 'twilioSmsTemplateUserPlanExpiryRemainderUpdate'])->name('admin.twilio_sms_template_plan_expiry_remainder.update')->middleware('demo.mode');

    // Plan Expired Template Update
    Route::post('admin/plugin/twilio_sms/template/plan_expired_notification/update', [TwilioSmsNotificationController::class, 'twilioSmsTemplateUserExpiredUpdate'])->name('admin.twilio_sms_template_plan_expired_notification.update')->middleware('demo.mode');
});
