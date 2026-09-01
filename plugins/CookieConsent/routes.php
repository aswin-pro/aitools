<?php

use Illuminate\Support\Facades\Route;
use Plugins\CookieConsent\Controllers\CookieConsentController;

Route::middleware(['web', 'auth', 'admin'])->group(function () {
    // Admin routes
    Route::get('dashboard/admin/plugin/cookie-consent/settings', [CookieConsentController::class, 'index'])->name('admin.plugin.cookie-consent');
    Route::post('dashboard/admin/plugin/cookie-consent/update/settings', [CookieConsentController::class, 'update'])->name('admin.plugin.cookie-consent.update')->middleware(['demo.mode']);
});