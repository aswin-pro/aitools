    <?php

use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\PluginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

    Route::group(['as' => 'dashboard.admin.', 'prefix' => 'dashboard/admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {
        // Dashboard
        Route::get('overview', function () {
            return Inertia::render('admin/dashboard');
        })->name('overview');
        
        // Users
        Route::get('users', [App\Http\Controllers\Admin\UserController::class, "index"])->name('users');
        // Route::get('edit-user/{id}', [App\Http\Controllers\Admin\UserController::class, "editUser"])->name('edit.user');
        Route::post('update-user', [App\Http\Controllers\Admin\UserController::class, "updateUser"])->name('update.user')->middleware(['demo.mode']);
        Route::get('view-user/{id}', [App\Http\Controllers\Admin\UserController::class, "viewUser"])->name('view.user');
        // Route::get('change-user-plan/{id}', [App\Http\Controllers\Admin\UserController::class, "ChangeUserPlan"])->name('change.user.plan')->middleware(['demo.mode']);
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
        Route::get('settings', [App\Http\Controllers\Admin\AccountController::class, "index"])->name('index.account');
        Route::get('settings/profile', [App\Http\Controllers\Admin\AccountController::class, "editAccount"])->name('edit.account');
        Route::post('update-account', [App\Http\Controllers\Admin\AccountController::class, "updateAccount"])->name('update.account')->middleware(['demo.mode']);
        Route::get('settings/password', [App\Http\Controllers\Admin\AccountController::class, "changePassword"])->name('change.password');
        Route::post('update-password', [App\Http\Controllers\Admin\AccountController::class, "UpdatePassword"])->name('update.password')->middleware(['demo.mode']);
        Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
        
        //general settings
        Route::get('settings/system-configuration', [App\Http\Controllers\Admin\SettingController::class, "index"])->name('settings');
        Route::post('change-general-settings', [App\Http\Controllers\Admin\SettingController::class, "changeGeneralSettings"])->name('change.general.settings')->middleware(['demo.mode']);
        
        Route::get('settings/website-configuration', [App\Http\Controllers\Admin\SettingController::class, "websiteSettings"])->name('website.settings')->middleware(['demo.mode']);
        Route::post('settings/update-website-settings', [App\Http\Controllers\Admin\SettingController::class, "changeWebsiteSettings"])->name('change.website.settings')->middleware(['demo.mode']);

        Route::get('settings/aitools-configuration', [App\Http\Controllers\Admin\SettingController::class, "getAISettings"])->name('ai.settings');
        Route::post('settings/update-ai-settings', [App\Http\Controllers\Admin\SettingController::class, "changeAISettings"])->name('update.ai.settings')->middleware(['demo.mode']);

        Route::get('settings/aws-s3-configuration', [App\Http\Controllers\Admin\SettingController::class, "getS3Settings"])->name('awss3.settings');
        Route::post('settings/update-aws-s3-settings', [App\Http\Controllers\Admin\SettingController::class, "changeS3Settings"])->name('update.awss3.settings')->middleware(['demo.mode']);

        // Settingup cron jobs
        Route::get('settings/cron-jobs', [App\Http\Controllers\Admin\CronJobController::class, 'index'])->name('cron.jobs');
        Route::post('settings/cron-jobs/update', [App\Http\Controllers\Admin\CronJobController::class, 'update'])->name('update.cron.jobs')->middleware(['demo.mode']);
        // Test Reminder
        Route::get('settings/test-reminder', [App\Http\Controllers\Admin\CronJobController::class, 'testReminder'])->name('test.reminder');
        Route::get('settings/tax-setting', [App\Http\Controllers\Admin\SettingController::class, "taxSetting"])->name('tax.setting');
        Route::post('settings/update-tex-setting', [App\Http\Controllers\Admin\SettingController::class, "updateTaxSetting"])->name('update.tax.setting')->middleware(['demo.mode']);
        Route::post('settings/update-email-setting', [App\Http\Controllers\Admin\SettingController::class, "updateEmailSetting"])->name('update.email.setting')->middleware(['demo.mode']);
        // Generating a sitemap
        
        //system - login activity | clear cache | generate sitemap
        Route::get('system/login-activity', [App\Http\Controllers\Admin\AuthenticationLogController::class, "index"])->name('system.login-activity');
        Route::get('system/clear-cache', [App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('system.clear-cache')->middleware(['demo.mode']);
        Route::get('system/sitemap', [App\Http\Controllers\Admin\SitemapController::class, 'index'])->name('system.sitemap');
        Route::post('system/generate-sitemap', [App\Http\Controllers\Admin\SitemapController::class, 'generate'])->name('system.generate.sitemap')->middleware(['demo.mode']);

        // Currencies
        Route::get('currencies', [CurrencyController::class, 'currencies'])->name('currencies');
        Route::post('create-currency', [CurrencyController::class, 'createCurrency'])->name('create.currency');
        Route::post('update-currency', [CurrencyController::class, 'updateCurrency'])->name('update.currency')->middleware(['demo.mode']);
        Route::get('delete-currency', [CurrencyController::class, 'deleteCurrency'])->name('delete.currency');


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
        Route::get('blog/blog-categories', [App\Http\Controllers\Admin\BlogCategoryController::class, "index"])->name('blog.categories');
        Route::post('blog/publish-blog-category', [App\Http\Controllers\Admin\BlogCategoryController::class, "publishBlogCategory"])->name('publish.blog.category')->middleware(['demo.mode']);
        // Route::get('edit-blog-category/{id}', [App\Http\Controllers\Admin\BlogCategoryController::class, "editBlogCategory"])->name('edit.blog.category');
        Route::post('blog/update-blog-category/{id}', [App\Http\Controllers\Admin\BlogCategoryController::class, "updateBlogCategory"])->name('update.blog.category')->middleware(['demo.mode']); 
        Route::get('blog/action-blog-category', [App\Http\Controllers\Admin\BlogCategoryController::class, "actionBlog"])->name('action.blog.category')->middleware(['demo.mode']); //actions for unplish/publish
        // Route::get('create-blog-category', [App\Http\Controllers\Admin\BlogCategoryController::class, "createBlogCategory"])->name('create.blog.category');

        // Blogs
        Route::get('blog/blog-posts', [App\Http\Controllers\Admin\BlogController::class, "index"])->name('blogs.post');
        Route::get('blog/create-blog', [App\Http\Controllers\Admin\BlogController::class, "createBlog"])->name('create.blog');
        Route::post('blog/publish-blog', [App\Http\Controllers\Admin\BlogController::class, "publishBlog"])->name('publish.blog')->middleware(['demo.mode']);
        Route::get('blog/edit-blog/{id}', [App\Http\Controllers\Admin\BlogController::class, "editBlog"])->name('edit.blog');
        Route::post('blog/update-blog/{id}', [App\Http\Controllers\Admin\BlogController::class, "updateBlog"])->name('update.blog')->middleware(['demo.mode']);
        Route::get('blog/action-blog', [App\Http\Controllers\Admin\BlogController::class, "actionBlog"])->name('action.blog')->middleware(['demo.mode']);




        Route::post('change-payments-settings', [App\Http\Controllers\Admin\SettingController::class, "changePaymentsSettings"])->name('change.payments.settings')->middleware(['demo.mode']);
        

        // License
        Route::get('license', [App\Http\Controllers\Admin\LicenseController::class, "license"])->name('license');
        Route::post('verify-license', [App\Http\Controllers\Admin\LicenseController::class, "verifyLicense"])->name('verify.license')->middleware(['demo.mode']);
       
        // Backup
        Route::get('system/backups', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('system.backups');
        // Route::get('backups/get-database-backup', [App\Http\Controllers\Admin\BackupController::class, 'getDatabaseBackup'])->name('get.database.backup');
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