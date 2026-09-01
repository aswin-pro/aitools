<?php

use Illuminate\Support\Facades\Route;
use Plugins\TawkChat\Controllers\TawkChatController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard/admin/plugin/tawkchat/settings', [TawkChatController::class, 'tawkChatSettings'])->name('admin.plugin.tawkchat.settings');
    Route::post('dashboard/admin/plugin/tawkchat/settings/update', [TawkChatController::class, 'tawkChatSettingsUpdate'])->name('admin.tawkchat_settings.update')->middleware(['demo.mode']);
});
