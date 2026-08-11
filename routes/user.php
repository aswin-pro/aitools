<?php

use Illuminate\Support\Facades\Route;

    Route::group(['as' => 'user.', 'prefix' => 'user', 'namespace' => 'User', 'middleware' => ['auth', 'user'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {
        // Dashboard
        Route::get('dashboard', [App\Http\Controllers\User\DashboardController::class, "index"])->name('dashboard');

        // Plans
        Route::get('plans', [App\Http\Controllers\User\PlanController::class, "index"])->name('plans');

        // Create AI Content 
        Route::get('ai/gc', [App\Http\Controllers\User\AIContentCreatorController::class, "indexAllAiContent"])->name('all.ai.content');
        Route::get('ai/gc/templates', [App\Http\Controllers\User\AIContentCreatorController::class, "indexAiTemplates"])->name('new.ai.templates');
        Route::get('ai/gc/new/{slug}', [App\Http\Controllers\User\AIContentCreatorController::class, "indexNewAiContent"])->name('new.ai.content');
        Route::post('ai/gc/generate', [App\Http\Controllers\User\AIContentCreatorController::class, "generateAiContent"])->name('generate.ai.content');
        Route::get('ai/gc/view/{id}', [App\Http\Controllers\User\AIContentCreatorController::class, "viewAiContent"])->name('view.ai.content');
        Route::get('ai/gc/edit/{id}', [App\Http\Controllers\User\AIContentCreatorController::class, "editAiContent"])->name('edit.ai.content');
        Route::post('ai/gc/update', [App\Http\Controllers\User\AIContentCreatorController::class, "updateAiContent"])->name('update.ai.content');
        Route::get('ai/gc/export-docs/{id}', [App\Http\Controllers\User\AIContentCreatorController::class, "exportDocsAiContent"])->name('export.docs.content');
        Route::get('ai/gc/delete', [App\Http\Controllers\User\AIContentCreatorController::class, "deleteAiContent"])->name('delete.ai.content');

        // Create AI Images 
        Route::get('ai/gi', [App\Http\Controllers\User\AiImageCreatorController::class, "indexAllAiImage"])->name('all.ai.images');
        Route::get('ai/gi/new', [App\Http\Controllers\User\AiImageCreatorController::class, "indexNewAiImage"])->name('new.ai.image');
        Route::post('ai/gi/generate', [App\Http\Controllers\User\AiImageCreatorController::class, "generateAiImage"])->name('generate.ai.image');
        Route::get('ai/gi/view/{id}', [App\Http\Controllers\User\AiImageCreatorController::class, "viewAiImage"])->name('view.ai.image');
        Route::get('ai/gi/delete-image', [App\Http\Controllers\User\AiImageCreatorController::class, "deleteAiImage"])->name('delete.ai.image');

        // Create AI Speech to text 
        Route::get('ai/gst', [App\Http\Controllers\User\AiSpeechToTextController::class, "indexAllAiSpeechToText"])->name('all.ai.speech.to.text');
        Route::get('ai/gst/new', [App\Http\Controllers\User\AiSpeechToTextController::class, "indexNewAiSpeechToText"])->name('new.ai.speech.to.text');
        Route::post('ai/gst/generate', [App\Http\Controllers\User\AiSpeechToTextController::class, "generateAiSpeechToText"])->name('generate.ai.speech.to.text');
        Route::get('ai/gst/view/{id}', [App\Http\Controllers\User\AiSpeechToTextController::class, "viewAiSpeechToText"])->name('view.ai.speech.to.text');
        Route::get('ai/gst/edit/{id}', [App\Http\Controllers\User\AiSpeechToTextController::class, "editAiSpeechToText"])->name('edit.ai.speech.to.text');
        Route::post('ai/gst/update', [App\Http\Controllers\User\AiSpeechToTextController::class, "updateAiSpeechToText"])->name('update.ai.speech.to.text');
        Route::get('ai/gst/export-docs/{id}', [App\Http\Controllers\User\AiSpeechToTextController::class, "exportDocsAiSpeechToText"])->name('export.docs.speech.to.text');

        // Create AI Text to speech
        Route::get('ai/gts', [App\Http\Controllers\User\AiTextToSpeechController::class, "indexAllAiTextToSpeech"])->name('all.ai.text.to.speech');
        Route::get('ai/gts/new', [App\Http\Controllers\User\AiTextToSpeechController::class, "indexNewAiTextToSpeech"])->name('new.ai.text.to.speech');
        Route::post('ai/gts/generate', [App\Http\Controllers\User\AiTextToSpeechController::class, "generateAiTextToSpeech"])->name('generate.ai.text.to.speech');
        Route::get('ai/gts/delete', [App\Http\Controllers\User\AiTextToSpeechController::class, "deleteAiTextToSpeech"])->name('delete.ai.text.to.speech');

        // Create AI Code
        Route::get('ai/gcode', [App\Http\Controllers\User\AiCodeController::class, "indexAllAiCode"])->name('all.ai.code');
        Route::get('ai/gcode/new', [App\Http\Controllers\User\AiCodeController::class, "indexNewAiCode"])->name('new.ai.code');
        Route::post('ai/gcode/generate', [App\Http\Controllers\User\AiCodeController::class, "generateAiCode"])->name('generate.ai.code');
        Route::get('ai/gcode/view/{id}', [App\Http\Controllers\User\AiCodeController::class, "viewAiCode"])->name('view.ai.code');
        Route::get('ai/gcode/edit/{id}', [App\Http\Controllers\User\AiCodeController::class, "editAiCode"])->name('edit.ai.code');
        Route::post('ai/gcode/update', [App\Http\Controllers\User\AiCodeController::class, "updateAiCode"])->name('update.ai.code');
        Route::get('ai/gcode/export-docs/{id}', [App\Http\Controllers\User\AiCodeController::class, "exportDocsAiCode"])->name('export.docs.code');

        // Chat Genius
        Route::get('ai/chatgenius', [App\Http\Controllers\User\ChatGeniusController::class, "indexAllAiChatGenius"])->name('all.ai.chatgenius');
        Route::get('ai/chatgenius/new/{slug}', [App\Http\Controllers\User\ChatGeniusController::class, "indexNewAiChatGenius"])->name('new.ai.chatgenius');
        Route::post('ai/chatgenius/generate', [App\Http\Controllers\User\ChatGeniusController::class, "generateAiChatGenius"])->name('generate.ai.chatgenius');
        Route::any('ai/chatgenius/new-conversation/{slug}', [App\Http\Controllers\User\ChatGeniusController::class, "newConversationAiChatGenius"])->name('new.ai.chatgenius.conversation');
        Route::post('ai/chatgenius/update-details', [App\Http\Controllers\User\ChatGeniusController::class, 'updateAiChatGeniusDetails'])->name('update.ai.chatgenius.details');
        Route::post('ai/chatgenius/delete', [App\Http\Controllers\User\ChatGeniusController::class, "deleteAiChatGenius"])->name('delete.ai.chatgenius');
        Route::get('ai/chatgenius/export-docs/{id}', [App\Http\Controllers\User\ChatGeniusController::class, "exportAiChatGenius"])->name('export.ai.chatgenius');

        // DocuAssistant
        Route::get('ai/docu-assistant', [App\Http\Controllers\User\DocuAssistController::class, "indexAllAiDocuAssistant"])->name('all.ai.docuassistant');
        Route::post('ai/docu-assistant/generate', [App\Http\Controllers\User\DocuAssistController::class, "generateAiDocuAssistant"])->name('generate.ai.docuassistant');
        Route::any('ai/docu-assistant/new-conversation/{slug}', [App\Http\Controllers\User\DocuAssistController::class, "newConversationAiDocuAssistant"])->name('new.ai.docuassistant');
        Route::post('ai/docu-assistant/update-details', [App\Http\Controllers\User\DocuAssistController::class, 'updateAiDocuAssistantDetails'])->name('update.ai.docuassistant.details');
        Route::post('ai/docu-assistant/delete', [App\Http\Controllers\User\DocuAssistController::class, "deleteAiDocuAssistant"])->name('delete.ai.docuassistant');
        Route::get('ai/docu-assistant/export-docs/{id}', [App\Http\Controllers\User\DocuAssistController::class, "exportAiDocuAssistant"])->name('export.ai.docuassistant');

        // WebChat
        Route::get('ai/webchat', [App\Http\Controllers\User\WebChatController::class, "indexAllAiWebChat"])->name('all.ai.webchat');
        Route::post('ai/webchat/generate', [App\Http\Controllers\User\WebChatController::class, "generateAiWebChat"])->name('generate.ai.webchat');
        Route::any('ai/webchat/new-conversation/{slug}', [App\Http\Controllers\User\WebChatController::class, "newConversationAiWebChat"])->name('new.ai.webchat');
        Route::post('ai/webchat/update-details', [App\Http\Controllers\User\WebChatController::class, 'updateAiWebChatDetails'])->name('update.ai.webchat.details');
        Route::post('ai/webchat/delete', [App\Http\Controllers\User\WebChatController::class, "deleteAiWebChat"])->name('delete.ai.webchat');
        Route::get('ai/webchat/export-docs/{id}', [App\Http\Controllers\User\WebChatController::class, "exportAiWebChat"])->name('export.ai.webchat');

        //Addtional Tootls -> QR Maker
        Route::get('tools/whois-lookup', [App\Http\Controllers\User\AdditionalController::class, "whoisLookup"])->name('whois-lookup');
        Route::post('tools/whois-lookup', [App\Http\Controllers\User\AdditionalController::class, "resultWhoisLookup"])->name('result.whois-lookup');
        Route::get('tools/dns-lookup', [App\Http\Controllers\User\AdditionalController::class, "dnsLookup"])->name('dns-lookup');
        Route::post('tools/dns-lookup', [App\Http\Controllers\User\AdditionalController::class, "resultDnsLookup"])->name('result.dns-lookup');
        Route::get('tools/ip-lookup', [App\Http\Controllers\User\AdditionalController::class, "ipLookup"])->name('ip-lookup');
        Route::post('tools/ip-lookup', [App\Http\Controllers\User\AdditionalController::class, "resultIpLookup"])->name('result.ip-lookup');

        // Transactions
        Route::get('transactions', [App\Http\Controllers\User\TransactionsController::class, "indexTransactions"])->name('transactions');
        Route::get('view-invoice/{id}', [App\Http\Controllers\User\TransactionsController::class, "viewInvoice"])->name('view.invoice');

        // Billing
        Route::get('billing/{id}', [App\Http\Controllers\User\BillingController::class, "billing"])->name('billing');
        Route::post('update-billing', [App\Http\Controllers\User\BillingController::class, "updateBilling"])->name('update.billing')->middleware(['demo.mode']);

        // Checkout
        Route::get('checkout/{id}', [App\Http\Controllers\User\CheckOutController::class, "checkout"])->name('checkout');

        // Account Setting
        Route::get('account', [App\Http\Controllers\User\AccountController::class, "index"])->name('index.account');
        Route::get('edit-account', [App\Http\Controllers\User\AccountController::class, "editAccount"])->name('edit.account');
        Route::post('update-account', [App\Http\Controllers\User\AccountController::class, "updateAccount"])->name('update.account')->middleware(['demo.mode']);
        Route::get('change-password', [App\Http\Controllers\User\AccountController::class, "changePassword"])->name('change.password');
        Route::post('update-password', [App\Http\Controllers\User\AccountController::class, "updatePassword"])->name('update.password')->middleware(['demo.mode']);

        // Change theme
        Route::get('theme/{id}', [App\Http\Controllers\User\AccountController::class, "changeTheme"])->name('change.theme');

        // Resend Email Verfication
        Route::get('verify-email-verification', [App\Http\Controllers\User\VerificationController::class, "verifyEmailVerification"])->name('verify.email.verification');
        Route::get('resend-email-verification', [App\Http\Controllers\User\VerificationController::class, "resendEmailVerification"])->name('resend.email.verification');
    });