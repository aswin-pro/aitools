<?php

use Illuminate\Support\Facades\Route;
use Plugins\MSG91SmsNotification\Controllers\MSG91SmsNotificationController;

// Settings Update
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Settings
    Route::get('admin/plugin/msg91_sms_notification/settings', [MSG91SmsNotificationController::class, 'msg91SmsNotificationSettings'])->name('admin.plugin.msg91_sms_notification.settings');
    Route::post('admin/plugin/msg91_sms_notification/settings/update', [MSG91SmsNotificationController::class, 'msg91SmsNotificationSettingsUpdate'])->name('admin.msg91_sms_notification_settings.update')->middleware('demo.mode');

    // New Register Template Update
    Route::post('admin/plugin/msg91_sms/template/user_register/update', [MSG91SmsNotificationController::class, 'msg91SmsTemplateUserRegisterUpdate'])->name('admin.msg91_sms_template_user_register.update')->middleware('demo.mode');

    // Plan Purchase Template Update
    Route::post('admin/plugin/msg91_sms/template/plan_purchase/update', [MSG91SmsNotificationController::class, 'msg91SmsTemplatePlanPurchaseUpdate'])->name('admin.msg91_sms_template_plan_purchase.update')->middleware('demo.mode');

    // Plan Renewal Template Update
    Route::post('admin/plugin/msg91_sms/template/plan_renewal/update', [MSG91SmsNotificationController::class, 'msg91SmsTemplatePlanRenewalUpdate'])->name('admin.msg91_sms_template_plan_renewal.update')->middleware('demo.mode');

    // User Plan Expiry Remainder Template Update
    Route::post('admin/plugin/msg91_sms/template/plan_expiry_remainder/update', [MSG91SmsNotificationController::class, 'msg91SmsTemplateUserPlanExpiryRemainderUpdate'])->name('admin.msg91_sms_template_plan_expiry_remainder.update')->middleware('demo.mode');

    // User Plan Expired Template Update
    Route::post('admin/plugin/msg91_sms/template/plan_expired_notification/update', [MSG91SmsNotificationController::class, 'msg91SmsTemplateUserExpiredUpdate'])->name('admin.msg91_sms_template_plan_expired_notification.update')->middleware('demo.mode');
});
