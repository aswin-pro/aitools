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
    require __DIR__ . '/admin.php';

    // User routes
    require __DIR__ . '/user.php';

    // Google auth routes
    Route::get('/google-login', [App\Http\Controllers\Auth\LoginController::class, "redirectToProvider"])->name('login.google');
    Route::get('/sign-in-with-google', [App\Http\Controllers\Auth\LoginController::class, "handleProviderCallback"]);

    // Payment routes
    require __DIR__ . '/payment.php';

});