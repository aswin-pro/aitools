<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\PluginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Payment\ToyyibpayController;
use App\Http\Controllers\Payment\FlutterwaveController;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [HomeController::class, 'index']);

// Installer Middleware
Route::group(['middleware' => 'Installer'], function () {
    // Path to plugins directory
    $pluginsPath = base_path('plugins');
    if (File::exists($pluginsPath)) {
        foreach (File::directories($pluginsPath) as $plugin) {
            $routeFile = $plugin . '/routes.php';
            if (File::exists($routeFile)) {
                require_once $routeFile;
            }
        }
    }

    // Website routes
    Route::get('/', [App\Http\Controllers\Website\WebController::class, "webIndex"])->name("web.index")->middleware('scriptsanitizer');
    Route::get('/tools', [App\Http\Controllers\Website\WebController::class, "webTools"])->name("web.tools")->middleware('scriptsanitizer');
    Route::get('/features', [App\Http\Controllers\Website\WebController::class, "webFeatures"])->name("web.features")->middleware('scriptsanitizer');
    Route::get('/about', [App\Http\Controllers\Website\WebController::class, "webAbout"])->name("web.about")->middleware('scriptsanitizer');
    Route::get('/pricing', [App\Http\Controllers\Website\WebController::class, "webPricing"])->name("web.pricing")->middleware('scriptsanitizer');
    Route::get('/contact', [App\Http\Controllers\Website\WebController::class, "webContact"])->name("web.contact")->middleware('scriptsanitizer');
    Route::post("/send-email", [App\Http\Controllers\Website\MailerController::class, "composeEmail"])->name("send-email")->middleware('scriptsanitizer');
    Route::get('/faq', [App\Http\Controllers\Website\WebController::class, "webFAQ"])->name("web.faq")->middleware('scriptsanitizer');
    Route::get('/privacy-policy', [App\Http\Controllers\Website\WebController::class, "webPrivacy"])->name("web.privacy")->middleware('scriptsanitizer');
    Route::get('/refund-policy', [App\Http\Controllers\Website\WebController::class, "webRefund"])->name("web.refund")->middleware('scriptsanitizer');
    Route::get('/terms-and-conditions', [App\Http\Controllers\Website\WebController::class, "webTerms"])->name("web.terms")->middleware('scriptsanitizer');
    Route::get('/blogs', [App\Http\Controllers\Website\WebController::class, "blogs"])->name("web.blogs")->middleware('scriptsanitizer');
    Route::get('/blog/{slug}', [App\Http\Controllers\Website\WebController::class, "viewBlog"])->name("web.view.blog")->middleware('scriptsanitizer');

    // Blog post share
    Route::get('/blog/{slug}/share/facebook', [App\Http\Controllers\Website\ShareController::class, "shareToFacebook"])->name("sharetofacebook");
    Route::get('/blog/{slug}/share/twitter', [App\Http\Controllers\Website\ShareController::class, "shareToTwitter"])->name("sharetotwitter");
    Route::get('/blog/{slug}/share/linkedin', [App\Http\Controllers\Website\ShareController::class, "shareToLinkedIn"])->name("sharetolinkedin");
    Route::get('/blog/{slug}/share/instagram', [App\Http\Controllers\Website\ShareController::class, "shareToInstagram"])->name("sharetoinstagram");
    Route::get('/blog/{slug}/share/whatsapp', [App\Http\Controllers\Website\ShareController::class, "shareToWhatsApp"])->name("sharetowhatsapp");

    // Custom pages
    Route::get('/p/{id}', [App\Http\Controllers\Website\WebController::class, "customPage"])->name("web.custom.page");

    // Auth routes
    Auth::routes();

    // Admin routes
    Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {
        // Dashboard
        // Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, "index"])->name('dashboard');

        Route::get('dashboard', function () {
            return Inertia::render('admin/Dashboard');
        })->name('dashboard');
        
        // Users
        Route::get('users', [App\Http\Controllers\Admin\UserController::class, "index"])->name('users');
        Route::get('edit-user/{id}', [App\Http\Controllers\Admin\UserController::class, "editUser"])->name('edit.user');
        Route::post('update-user', [App\Http\Controllers\Admin\UserController::class, "updateUser"])->name('update.user')->middleware(['demo.mode']);
        Route::get('view-user/{id}', [App\Http\Controllers\Admin\UserController::class, "viewUser"])->name('view.user');
        Route::get('change-user-plan/{id}', [App\Http\Controllers\Admin\UserController::class, "ChangeUserPlan"])->name('change.user.plan')->middleware(['demo.mode']);
        Route::post('update-user-plan', [App\Http\Controllers\Admin\UserController::class, "UpdateUserPlan"])->name('update.user.plan')->middleware(['demo.mode']);
        Route::get('update-status', [App\Http\Controllers\Admin\UserController::class, "updateStatus"])->name('update.status')->middleware(['demo.mode']);
        Route::get('delete-user', [App\Http\Controllers\Admin\UserController::class, "deleteUser"])->name('delete.user')->middleware(['demo.mode']);
        Route::get('login-as/{id}', [App\Http\Controllers\Admin\UserController::class, "authAs"])->name('login-as.user');

        // Categories
        Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, "index"])->name('categories');
        Route::get('add-category', [App\Http\Controllers\Admin\CategoryController::class, "addCategory"])->name('add.category');
        Route::post('save-category', [App\Http\Controllers\Admin\CategoryController::class, "saveCategory"])->name('save.category')->middleware(['demo.mode']);
        Route::get('edit-category/{id}', [App\Http\Controllers\Admin\CategoryController::class, "editCategory"])->name('edit.category');
        Route::post('update-category', [App\Http\Controllers\Admin\CategoryController::class, "updateCategory"])->name('update.category')->middleware(['demo.mode']);
        Route::get('delete-category', [App\Http\Controllers\Admin\CategoryController::class, "deleteCategory"])->name('delete.category')->middleware(['demo.mode']);

        // Templates
        Route::get('templates', [App\Http\Controllers\Admin\TemplateController::class, "index"])->name('templates');
        Route::get('add-template', [App\Http\Controllers\Admin\TemplateController::class, "addTemplate"])->name('add.template');
        Route::post('save-template', [App\Http\Controllers\Admin\TemplateController::class, "saveTemplate"])->name('save.template')->middleware(['demo.mode']);
        Route::get('edit-template/{id}', [App\Http\Controllers\Admin\TemplateController::class, "editTemplate"])->name('edit.template');
        Route::post('update-template', [App\Http\Controllers\Admin\TemplateController::class, "updateTemplate"])->name('update.template')->middleware(['demo.mode']);
        Route::get('delete-template', [App\Http\Controllers\Admin\TemplateController::class, "deleteTemplate"])->name('delete.template')->middleware(['demo.mode']);

        // Chat Genius
        Route::get('chatgenius', [App\Http\Controllers\Admin\ChatGeniusController::class, "index"])->name('chatgenius');
        Route::get('create-chatgenius', [App\Http\Controllers\Admin\ChatGeniusController::class, "createChatgenius"])->name('create.chatgenius');
        Route::post('save-chatgenius', [App\Http\Controllers\Admin\ChatGeniusController::class, "saveChatgenius"])->name('save.chatgenius')->middleware(['demo.mode']);
        Route::get('edit-chatgenius/{id}', [App\Http\Controllers\Admin\ChatGeniusController::class, "editChatgenius"])->name('edit.chatgenius');
        Route::post('update-chatgenius', [App\Http\Controllers\Admin\ChatGeniusController::class, "updateChatgenius"])->name('update.chatgenius')->middleware(['demo.mode']);
        Route::get('action-chatgenius', [App\Http\Controllers\Admin\ChatGeniusController::class, "actionChatgenius"])->name('action.chatgenius')->middleware(['demo.mode']);
        Route::get('delete-chatgenius', [App\Http\Controllers\Admin\ChatGeniusController::class, "deleteChatgenius"])->name('delete.chatgenius')->middleware(['demo.mode']);

        // Plans
        Route::get('plans', [App\Http\Controllers\Admin\PlanController::class, "index"])->name('index.plans');
        Route::get('add-plan', [App\Http\Controllers\Admin\PlanController::class, "addPlan"])->name('add.plan');
        Route::post('save-plan', [App\Http\Controllers\Admin\PlanController::class, "savePlan"])->name('save.plan')->middleware(['demo.mode']);
        Route::get('edit-plan/{id}', [App\Http\Controllers\Admin\PlanController::class, "editPlan"])->name('edit.plan');
        Route::post('update-plan', [App\Http\Controllers\Admin\PlanController::class, "updatePlan"])->name('update.plan')->middleware(['demo.mode']);
        Route::get('delete-plan', [App\Http\Controllers\Admin\PlanController::class, "deletePlan"])->name('delete.plan')->middleware(['demo.mode']);

        // Payment Gateways
        Route::get('payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, "index"])->name('payment.methods');
        Route::get('add-payment-method', [App\Http\Controllers\Admin\PaymentMethodController::class, "addPaymentMethod"])->name('add.payment.method');
        Route::post('save-payment-method', [App\Http\Controllers\Admin\PaymentMethodController::class, "savePaymentMethod"])->name('save.payment.method')->middleware(['demo.mode']);
        Route::get('edit-payment-method/{id}', [App\Http\Controllers\Admin\PaymentMethodController::class, "editPaymentMethod"])->name('edit.payment.method');
        Route::post('update-payment-method', [App\Http\Controllers\Admin\PaymentMethodController::class, "updatePaymentMethod"])->name('update.payment.method')->middleware(['demo.mode']);
        Route::get('delete-payment-method', [App\Http\Controllers\Admin\PaymentMethodController::class, "deletePaymentMethod"])->name('delete.payment.method')->middleware(['demo.mode']);

        // Payment Configuration
        Route::get('configure-payment-method/{id}', [App\Http\Controllers\Admin\PaymentMethodController::class, 'configurePaymentMethod'])->name('configure.payment');
        Route::post('update-payment-configuration/{id}', [App\Http\Controllers\Admin\PaymentMethodController::class, 'updatePaymentConfiguration'])->name('update.payment.configuration')->middleware(['demo.mode']);

        // Transactions
        Route::get('transactions', [App\Http\Controllers\Admin\TransactionController::class, "index"])->name('transactions');
        Route::get('transaction-status/{id}/{status}', [App\Http\Controllers\Admin\TransactionController::class, "transactionStatus"])->name('trans.status')->middleware(['demo.mode']);
        Route::get('offline-transactions', [App\Http\Controllers\Admin\TransactionController::class, "offlineTransactions"])->name('offline.transactions');
        Route::get('offline-transaction-status/{id}/{status}', [App\Http\Controllers\Admin\TransactionController::class, "offlineTransactionStatus"])->name('offline.trans.status')->middleware(['demo.mode']);
        Route::get('view-invoice/{id}', [App\Http\Controllers\Admin\TransactionController::class, "viewInvoice"])->name('view.invoice');

        // Account Setting
        Route::get('account', [App\Http\Controllers\Admin\AccountController::class, "index"])->name('index.account');
        Route::get('edit-account', [App\Http\Controllers\Admin\AccountController::class, "editAccount"])->name('edit.account');
        Route::post('update-account', [App\Http\Controllers\Admin\AccountController::class, "updateAccount"])->name('update.account')->middleware(['demo.mode']);
        Route::get('change-password', [App\Http\Controllers\Admin\AccountController::class, "changePassword"])->name('change.password');
        Route::post('update-password', [App\Http\Controllers\Admin\AccountController::class, "UpdatePassword"])->name('update.password')->middleware(['demo.mode']);

        // Plugins
        Route::get('plugins', [PluginController::class, 'index'])->name('plugins.index');
        Route::delete('/plugins/{pluginName}', [PluginController::class, 'deletePlugin'])->name('plugins.delete');
        Route::post('plugin/upload', [PluginController::class, 'upload'])->name('plugin.upload');

        // Change theme
        Route::get('theme/{id}', [App\Http\Controllers\Admin\AccountController::class, "changeTheme"])->name('change.theme');

        // Pages
        Route::get('pages', [App\Http\Controllers\Admin\PageController::class, "index"])->name('pages');
        Route::get('add-page', [App\Http\Controllers\Admin\PageController::class, "addPage"])->name('add.page');
        Route::post('save-page', [App\Http\Controllers\Admin\PageController::class, "savePage"])->name('save.page')->middleware(['demo.mode']);
        Route::get('custom-page/{id}', [App\Http\Controllers\Admin\PageController::class, "editCustomPage"])->name('edit.custom.page');
        Route::post('custom-update-page', [App\Http\Controllers\Admin\PageController::class, "updateCustomPage"])->name('update.custom.page')->middleware(['demo.mode']);
        Route::get('status-page', [App\Http\Controllers\Admin\PageController::class, "statusPage"])->name('status.page')->middleware(['demo.mode']);
        Route::get('page/{id}', [App\Http\Controllers\Admin\PageController::class, "editPage"])->name('edit.page');
        Route::post('update-page/{id}', [App\Http\Controllers\Admin\PageController::class, "updatePage"])->name('update.page')->middleware(['demo.mode']);
        Route::get('disable-page', [App\Http\Controllers\Admin\PageController::class, "disablePage"])->name('disable.page')->middleware(['demo.mode']);
        Route::get('delete-page', [App\Http\Controllers\Admin\PageController::class, "deletePage"])->name('delete.page')->middleware(['demo.mode']);

        // Blogs Categories
        Route::get('blog-categories', [App\Http\Controllers\Admin\BlogCategoryController::class, "index"])->name('blog.categories');
        Route::get('create-blog-category', [App\Http\Controllers\Admin\BlogCategoryController::class, "createBlogCategory"])->name('create.blog.category');
        Route::post('publish-blog-category', [App\Http\Controllers\Admin\BlogCategoryController::class, "publishBlogCategory"])->name('publish.blog.category')->middleware(['demo.mode']);
        Route::get('edit-blog-category/{id}', [App\Http\Controllers\Admin\BlogCategoryController::class, "editBlogCategory"])->name('edit.blog.category');
        Route::post('update-blog-category/{id}', [App\Http\Controllers\Admin\BlogCategoryController::class, "updateBlogCategory"])->name('update.blog.category')->middleware(['demo.mode']);
        Route::get('action-blog-category', [App\Http\Controllers\Admin\BlogCategoryController::class, "actionBlogCategory"])->name('action.blog.category')->middleware(['demo.mode']);

        // Blogs
        Route::get('blogs', [App\Http\Controllers\Admin\BlogController::class, "index"])->name('blogs');
        Route::get('create-blog', [App\Http\Controllers\Admin\BlogController::class, "createBlog"])->name('create.blog');
        Route::post('publish-blog', [App\Http\Controllers\Admin\BlogController::class, "publishBlog"])->name('publish.blog')->middleware(['demo.mode']);
        Route::get('edit-blog/{id}', [App\Http\Controllers\Admin\BlogController::class, "editBlog"])->name('edit.blog');
        Route::post('update-blog/{id}', [App\Http\Controllers\Admin\BlogController::class, "updateBlog"])->name('update.blog')->middleware(['demo.mode']);
        Route::get('action-blog', [App\Http\Controllers\Admin\BlogController::class, "actionBlog"])->name('action.blog')->middleware(['demo.mode']);

        // Setting
        Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, "index"])->name('settings');
        Route::post('change-general-settings', [App\Http\Controllers\Admin\SettingController::class, "changeGeneralSettings"])->name('change.general.settings')->middleware(['demo.mode']);
        Route::post('change-aws-s3-settings', [App\Http\Controllers\Admin\SettingController::class, "changeS3Settings"])->name('change.aws.s3.settings')->middleware(['demo.mode']);
        Route::post('change-ai-settings', [App\Http\Controllers\Admin\SettingController::class, "changeAISettings"])->name('change.ai.settings')->middleware(['demo.mode']);
        Route::post('change-website-settings', [App\Http\Controllers\Admin\SettingController::class, "changeWebsiteSettings"])->name('change.website.settings')->middleware(['demo.mode']);
        Route::post('change-payments-settings', [App\Http\Controllers\Admin\SettingController::class, "changePaymentsSettings"])->name('change.payments.settings')->middleware(['demo.mode']);

        Route::get('tax-setting', [App\Http\Controllers\Admin\SettingController::class, "taxSetting"])->name('tax.setting');
        Route::post('update-tex-setting', [App\Http\Controllers\Admin\SettingController::class, "updateTaxSetting"])->name('update.tax.setting')->middleware(['demo.mode']);
        Route::post('update-email-setting', [App\Http\Controllers\Admin\SettingController::class, "updateEmailSetting"])->name('update.email.setting')->middleware(['demo.mode']);

        // Currencies
        Route::get('currencies', [CurrencyController::class, 'currencies'])->name('currencies');
        Route::get('edit-currency/{id}', [CurrencyController::class, 'editCurrency'])->name('edit.currency');
        Route::post('update-currency', [CurrencyController::class, 'updateCurrency'])->name('update.currency')->middleware(['demo.mode']);
        Route::get('delete-currency', [CurrencyController::class, 'deleteCurrency'])->name('delete.currency');

        // License
        Route::get('license', [App\Http\Controllers\Admin\LicenseController::class, "license"])->name('license');
        Route::post('verify-license', [App\Http\Controllers\Admin\LicenseController::class, "verifyLicense"])->name('verify.license')->middleware(['demo.mode']);

        // Log Authentication
        Route::get('logs', [App\Http\Controllers\Admin\AuthenticationLogController::class, "index"])->name('logs');

        // Settingup cron jobs
        Route::get('cron/cron-jobs', [App\Http\Controllers\Admin\CronJobController::class, 'index'])->name('cron.jobs');
        Route::post('cron/cron-jobs/update', [App\Http\Controllers\Admin\CronJobController::class, 'update'])->name('update.cron.jobs')->middleware(['demo.mode']);

        // Test Reminder
        Route::get('cron/test-reminder', [App\Http\Controllers\Admin\CronJobController::class, 'testReminder'])->name('test.reminder');

        // Set cronjob time
        Route::post('cron/set-cronjob-time', [App\Http\Controllers\Admin\CronJobController::class, 'setCronjobTime'])->name('set.cronjob.time')->middleware(['demo.mode']);

        // Clear cache
        Route::get('clear/cache', [App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('clear.cache')->middleware(['demo.mode']);

        // Generating a sitemap
        Route::get('sitemap', [App\Http\Controllers\Admin\SitemapController::class, 'index'])->name('sitemap');
        Route::post('generate-sitemap', [App\Http\Controllers\Admin\SitemapController::class, 'generate'])->name('generate.sitemap')->middleware(['demo.mode']);

        // Backup
        Route::get('backups', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups');
        Route::get('backups/get-database-backup', [App\Http\Controllers\Admin\BackupController::class, 'getDatabaseBackup'])->name('get.database.backup');
        Route::get('backups/create-file-backup', [App\Http\Controllers\Admin\BackupController::class, 'createFileBackup'])->name('create.file.backup')->middleware(['demo.mode']);
        Route::get('backups/create-database-backup', [App\Http\Controllers\Admin\BackupController::class, 'createDatabaseBackup'])->name('create.database.backup')->middleware(['demo.mode']);
        Route::get('backups/restore-backup', [App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backup.restore')->middleware(['demo.mode']);
        Route::get('backups/download-backup', [App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backup.download')->middleware(['demo.mode']);
        Route::get('backups/delete-backup', [App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('backup.delete')->middleware(['demo.mode']);

        // Check update
        Route::get('check', [App\Http\Controllers\Admin\UpdateController::class, 'check'])->name('check');
        Route::post('check-update', [App\Http\Controllers\Admin\UpdateController::class, 'checkUpdate'])->name('check.update');
        Route::post('update-code', [App\Http\Controllers\Admin\UpdateController::class, 'updateCode'])->name('update.code')->middleware(['demo.mode']);
    });

    // User routes
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

    // Google auth routes
    Route::get('/google-login', [App\Http\Controllers\Auth\LoginController::class, "redirectToProvider"])->name('login.google');
    Route::get('/sign-in-with-google', [App\Http\Controllers\Auth\LoginController::class, "handleProviderCallback"]);


    Route::group(['middleware' => 'checkType'], function () {
        // Choose Payment Gateway
        Route::post('/prepare-payment/{planId}', [App\Http\Controllers\Payment\PaymentController::class, "preparePaymentGateway"])->name('prepare.payment.gateway')->middleware(['demo.mode']);

        // PayPal Payment Gateway
        Route::get('/payment-paypal/{planId}', [App\Http\Controllers\Payment\PaypalController::class, "paywithpaypal"])->name('paywithpaypal');
        Route::get('/payment/status', [App\Http\Controllers\Payment\PaypalController::class, "paypalPaymentStatus"])->name('paypalPaymentStatus');

        // RazorPay
        Route::get('payment-razorpay/{planId}', [App\Http\Controllers\Payment\RazorPayController::class, "prepareRazorpay"])->name('paywithrazorpay');
        Route::get('razorpay-payment-status/{oid}/{paymentId}', [App\Http\Controllers\Payment\RazorPayController::class, "razorpayPaymentStatus"])->name('razorpay.payment.status');

        // Phonepe
        Route::get('payment-phonepe/{planId}', [App\Http\Controllers\Payment\PhonepeController::class, 'preparePhonpe'])->name('paywithphonepe');
        Route::any('phonepe-payment-status', [App\Http\Controllers\Payment\PhonepeController::class, 'phonepePaymentStatus'])->name('phonepe.payment.status');

        // Stripe
        Route::get('/payment-stripe/{planId}', [App\Http\Controllers\Payment\StripeController::class, "stripeCheckout"])->name('paywithstripe');
        Route::post('/stripe-payment-status/{paymentId}', [App\Http\Controllers\Payment\StripeController::class, "stripePaymentStatus"])->name('stripe.payment.status');
        Route::get('/stripe-payment-cancel/{paymentId}', [App\Http\Controllers\Payment\StripeController::class, "stripePaymentCancel"])->name('stripe.payment.cancel');

        // Paystack
        Route::get('/payment-paystack/{planId}', [App\Http\Controllers\Payment\PaystackController::class, "paystackCheckout"])->name('paywithpaystack');
        Route::get('/paystack-payment/callback', [App\Http\Controllers\Payment\PaystackController::class, 'paystackHandleGatewayCallback'])->name('paystack.handle.gateway.callback');

        // Mollie
        Route::get('/payment-mollie/{planId}', [App\Http\Controllers\Payment\MollieController::class, "prepareMollie"])->name('paywithmollie');
        Route::get('/mollie-payment-status', [App\Http\Controllers\Payment\MollieController::class, "molliePaymentStatus"])->name('mollie.payment.status');

        // Offline
        Route::get('/payment-offline/{planId}', [App\Http\Controllers\Payment\OfflineController::class, "offlineCheckout"])->name('paywithoffline');
        Route::post('/mark-offline-payment', [App\Http\Controllers\Payment\OfflineController::class, "markOfflinePayment"])->name('mark.payment.payment');

        // Transaction Cloud
        Route::get('/payment-transactioncloud/{planId}', [App\Http\Controllers\Payment\TransactionCloudController::class, "prepareTransactionCloud"])->name('paywithtransactioncloud');
        Route::get('/transactioncloud-payment-status', [App\Http\Controllers\Payment\TransactionCloudController::class, "transactionCloudPaymentStatus"])->name('transactioncloud.payment.status');

        // Mercado Pago
        Route::get('/payment-mercadopago/{planId}', [App\Http\Controllers\Payment\MercadoPagoController::class, "prepareMercadoPago"])->name('paywithmercadopago');
        Route::get('/mercadopago-payment-status', [App\Http\Controllers\Payment\MercadoPagoController::class, "mercadoPagoPaymentStatus"])->name('mercadopago.payment.status');
        Route::get('/mercadopago-payment-failure', [App\Http\Controllers\Payment\MercadoPagoController::class, "mercadoPagoPaymentFailure"])->name('mercadopago.payment.failure');
        Route::get('/mercadopago-payment-pending', [App\Http\Controllers\Payment\MercadoPagoController::class, "mercadoPagoPaymentPending"])->name('mercadopago.payment.pending');
        Route::get('/mercadopago-callback', [App\Http\Controllers\Payment\MercadoPagoController::class, "mercadoPagoCallback"])->name('mercadopago.callback');

        // Toyyibpay
        Route::get('/payment-toyyibpay/{planId}', [ToyyibpayController::class, "prepareToyyibpay"])->name('prepare.toyyibpay');
        Route::get('/toyyibpay-payment-status', [ToyyibpayController::class, "toyyibpayPaymentStatus"])->name('toyyibpay.payment.status');
        Route::get('/toyyibpay-payment-success', [ToyyibpayController::class, 'toyyibpayPaymentSuccess'])->name('toyyibpay.payment.success');

        // Flutterwave
        Route::get('/payment-flutterwave/{planId}', [FlutterwaveController::class, "prepareFlutterwave"])->name('prepare.flutterwave');
        Route::get('/flutterwave-payment-status', [FlutterwaveController::class, "flutterwavePaymentStatus"])->name('flutterwave.payment.status');
    });
});