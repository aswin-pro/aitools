<?php

use Illuminate\Support\Facades\Route;
use Plugins\WhatsAppChatButton\Controllers\WhatsAppChatButtonController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard/admin/plugin/whatsapp_chat_button/settings', [WhatsAppChatButtonController::class, 'whatsAppChatButtonSettings'])->name('admin.plugin.whatsapp_chat_button.settings');
    Route::post('dashboard/admin/plugin/whatsapp_chat_button/settings/update', [WhatsAppChatButtonController::class, 'whatsAppChatButtonSettingsUpdate'])->name('admin.whatsapp_chat_button_settings.update')->middleware(['demo.mode']);
});
