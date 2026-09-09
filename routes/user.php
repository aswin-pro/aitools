<?php

use App\Http\Controllers\User\VerificationController;
use App\Http\Controllers\User\CodeGeneratorController;
use App\Http\Controllers\User\ContentGeneratorController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\DocumentAnalyzerController;
use App\Http\Controllers\User\ImageGeneratorController;
use App\Http\Controllers\User\PersonalizedChatController;
use App\Http\Controllers\User\SiteAnalyzerController;
use App\Http\Controllers\User\SpeechToTextConverterController;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\User\TextToSpeechConverterController;
use App\Http\Controllers\User\UserUploadController;
use Illuminate\Support\Facades\Route;

// User routes
Route::group(['as' => 'dashboard.user.', 'prefix' => 'dashboard/user', 'middleware' => ['auth', 'user'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {
    // Resend Email Verfication
    Route::get('verify-email-verification', [VerificationController::class, "verifyEmailVerification"])->name('verify.email.verification');
    Route::get('resend-email-verification', [VerificationController::class, "resendEmailVerification"])->name('resend.email.verification');

    // Dashboard
    Route::get('overview', [DashboardController::class, "index"])->name('overview');

    // Content Generator
    Route::prefix('content-generator')->as('content-generator.')->controller(ContentGeneratorController::class)->group(function () {
        // index
        Route::get('/', 'index')->name('index');

        // templates
        Route::get('/templates', 'templates')->name('templates');

        // generate
        Route::get('/generate/{template}', 'generate')->name('generate');
        Route::post('/generate/{template}', 'generateContent')->name('generate.store');

        // update
        Route::put('/update/{id}', 'update')->name('update');

        // destroy
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    // Image Generator
    Route::prefix('image-generator')->as('image-generator.')->controller(ImageGeneratorController::class)->group(function () {
        // index
        Route::get('/', 'index')->name('index');

        // generate
        Route::get('/generate', 'generate')->name('generate');

        // store
        Route::post('/generate', 'generateImage')->name('generate.store');

        // destroy
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    // Code Generator
    Route::prefix('code-generator')->as('code-generator.')->controller(CodeGeneratorController::class)->group(function () {
        // index
        Route::get('/', 'index')->name('index');

        // generate
        Route::get('/generate', 'generate')->name('generate');

        // store
        Route::post('/generate', 'generateCode')->name('generate.store');

        // destroy
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    // Speech to Text
    Route::prefix('speech-to-text')->as('speech-to-text.')->controller(SpeechToTextConverterController::class)->group(function () {
        // index
        Route::get('/', 'index')->name('index');

        // convert
        Route::get('/convert', 'convert')->name('convert');
        Route::post('/convert', 'convertToText')->name('convert.store');

        // update
        Route::put('/update/{id}', 'update')->name('update');

        // destroy
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    // Text to Speech
    Route::prefix('text-to-speech')->as('text-to-speech.')->controller(TextToSpeechConverterController::class)->group(function () {
        // index
        Route::get('/', 'index')->name('index');

        // convert
        Route::get('convert', 'convert')->name('convert');
        Route::post('convert', 'convertToSpeech')->name('convert.store');

        // destroy
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    // Personalized Chat
    Route::prefix('personalized-chat')->as('personalized-chat.')->controller(PersonalizedChatController::class)->group(function () {
        // index
        Route::get('/', 'index')->name('index');

        // chat index
        Route::get('/assistant/{assistant}/chat/{chat?}', 'chat')->name('chat');

        // Send message (new or existing chat)
        Route::post('/assistant/{assistant}/message', 'message')->name('message');
        Route::post('/assistant/{assistant}/chat/{chat}/message', 'message')->name('message.chat');

        // update
        Route::put('/chat/{chat}', 'updateChatTitle')->name('update');

        // delete
        Route::delete('/assistant/{assistant}/chat/{id}', 'destroy')->name('destroy');
    });

    // Document Analyzer
    Route::prefix('document-analyzer')->as('document-analyzer.')->controller(DocumentAnalyzerController::class)->group(function () {
        // index
        Route::get('/chat/{chat?}', 'index')->name('index');

        // chat
        Route::post('/message', 'message')->name('message');
        Route::post('chat/{chat}/message', 'message')->name('message.chat');

        // update
        Route::put('/update/{chat}', 'updateChatTitle')->name('update');

        // delete
        Route::delete('/delete/{chat}', 'destroy')->name('destroy');
    });

    // User Uploads
    Route::prefix('user-uploads')->as('user-uploads.')->controller(UserUploadController::class)->group(function () {
        // index
        Route::get('/', 'index')->name('index');

        // upload
        Route::post('/upload', 'upload')->name('upload');

        // delete
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    // Site Analyzer
    Route::prefix('site-analyzer')->as('site-analyzer.')->controller(SiteAnalyzerController::class)->group(function () {
        // index
        Route::get('/{chat?}', 'index')->name('index');

        // analyze
        Route::post('/analyze', 'analyze')->name('analyze');
        Route::post('/{chat}/message', 'message')->name('message.chat');

        // update
        Route::put('/update/{chat}', 'updateChatTitle')->name('update');

        // delete
        Route::delete('/delete/{chat}', 'destroy')->name('destroy');
    });

    // Subscriptions
    Route::prefix('subscriptions')->as('subscriptions.')->controller(SubscriptionController::class)->group(function () {
        // index
        Route::get('/', 'index')->name('index');

        // plans
        Route::get('/plans', 'plans')->name('plans');

        // invoice
        Route::get('/invoice/{id}', 'invoice')->name('invoice');
    });

    // Settings
    Route::redirect('settings', 'settings/profile')->name('settings');
    Route::prefix('settings')->as('settings.')->group(function () {
        // Profile
        Route::get('profile', [App\Http\Controllers\User\ProfileController::class, 'index'])->name('profile');
        Route::post('profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');

        // Password
        Route::get('password', [App\Http\Controllers\User\PasswordController::class, 'index'])->name('password');
        Route::post('password', [App\Http\Controllers\User\PasswordController::class, 'update'])->name('password.update');

        // Preferences
        Route::get('preferences', [App\Http\Controllers\User\PreferenceController::class, 'index'])->name('preferences');
        Route::put('preferences', [App\Http\Controllers\User\PreferenceController::class, 'update'])->name('preferences.update');
    });
});
