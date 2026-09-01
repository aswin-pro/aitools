<?php

use Illuminate\Support\Facades\Route;
use Plugins\TwilioWhatsappNotification\Controllers\TwilioWhatsappNotificationController;

// Settings Update
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Settings
    Route::get('dashboard/admin/plugin/twilio_whatsapp_notification/settings', [TwilioWhatsappNotificationController::class, 'twilioWhatsappNotificationSettings'])->name('admin.plugin.twilio_whatsapp_notification.settings');
    Route::post('dashboard/admin/plugin/twilio_whatsapp_notification/settings/update', [TwilioWhatsappNotificationController::class, 'twilioWhatsappNotificationSettingsUpdate'])->name('admin.twilio_whatsapp_notification_settings.update')->middleware('demo.mode');

    // New Register Template Update
    Route::post('dashboard/admin/plugin/twilio_whatsapp/template/user_register/update', [TwilioWhatsappNotificationController::class, 'twilioWhatsappTemplateUserRegisterUpdate'])->name('admin.twilio_whatsapp_template_user_register.update')->middleware('demo.mode');

    // Plan Purchase Template Update
    Route::post('dashboard/admin/plugin/twilio_whatsapp/template/plan_purchase/update', [TwilioWhatsappNotificationController::class, 'twilioWhatsappTemplatePlanPurchaseUpdate'])->name('admin.twilio_whatsapp_template_plan_purchase.update')->middleware('demo.mode');

    // Plan Renewal Template Update
    Route::post('dashboard/admin/plugin/twilio_whatsapp/template/plan_renewal/update', [TwilioWhatsappNotificationController::class, 'twilioWhatsappTemplatePlanRenewalUpdate'])->name('admin.twilio_whatsapp_template_plan_renewal.update')->middleware('demo.mode');

    // Plan Expiry Remainder Template Update
    Route::post('dashboard/admin/plugin/twilio_whatsapp/template/plan_expiry_remainder/update', [TwilioWhatsappNotificationController::class, 'twilioWhatsappTemplateUserPlanExpiryRemainderUpdate'])->name('admin.twilio_whatsapp_template_plan_expiry_remainder.update')->middleware('demo.mode');

    // Plan Expired Template Update
    Route::post('dashboard/admin/plugin/twilio_whatsapp/template/plan_expired_notification/update', [TwilioWhatsappNotificationController::class, 'twilioWhatsappTemplateUserExpiredUpdate'])->name('admin.twilio_whatsapp_template_plan_expired_notification.update')->middleware('demo.mode');
});
