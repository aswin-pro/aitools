<?php

use Illuminate\Support\Facades\Route;
use Plugins\SMTP\Controllers\SMTPController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard/admin/plugin/smtp/settings', [SMTPController::class, 'smtpSettings'])->name('admin.plugin.smtp.settings');
    Route::post('dashboard/admin/plugin/smtp/settings/update', [SMTPController::class, 'smtpSettingsUpdate'])->name('admin.smtp_settings.update')->middleware(['demo.mode']);
    Route::get('dashboard/admin/plugin/test/email', [SMTPController::class, 'testEmail'])->name('admin.plugin.test.email');
});
