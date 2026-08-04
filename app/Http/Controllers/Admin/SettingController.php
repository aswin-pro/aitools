<?php

namespace App\Http\Controllers\Admin;

use DateTimeZone;
use App\Models\Theme;
use App\Models\Config;
use App\Models\Setting;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // Settings
    public function index()
    {
        // Queries
        $timezonelist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $themes = Theme::get();
        $currencies = Currency::get();
        $settings = Setting::first();
        $config = Config::get();

        // Get image limit
        $image_limit = [
            'SIZE_LIMIT' => env('SIZE_LIMIT', '')
        ];

        $settings['image_limit'] = $image_limit;

        // Get all languages from the config
        $languages = config('app.languages');

        // Define all languages as selected (or you can replace this with any subset of languages)
        $selectedLanguages = array_keys($languages); // This will make all languages selected

        // Get the default language
        $defaultLanguage = config('app.locale');

        return view('admin.pages.settings.index', compact('settings', 'themes', 'timezonelist', 'currencies', 'config', 'languages', 'selectedLanguages', 'defaultLanguage'));
    }

    // Update General Setting
    public function changeGeneralSettings(Request $request)
    {
        Config::where('config_key', 'show_website')->update([
            'config_value' => $request->show_website,
        ]);

        Config::where('config_key', 'timezone')->update([
            'config_value' => $request->timezone,
        ]);

        // This will update the languages array in config/app.php file
        $this->updateLanguages($request->languages, $request->default_language);

        // Get the language
        app()->setLocale($request->default_language);

        // Update the date format
        Config::where('config_key', 'date_time_format')->update([
            'config_value' => $request->date_time_format,
        ]);

        Config::where('config_key', 'currency_format_type')->update([
            'config_value' => $request->currency_format,
        ]);

        Config::where('config_key', 'currency_decimals_place')->update([
            'config_value' => $request->currency_decimals_place,
        ]);

        Config::where('config_key', 'currency')->update([
            'config_value' => $request->currency,
        ]);

        Config::where('config_key', 'term')->update([
            'config_value' => $request->term,
        ]);

        // Set new values using putenv
        $this->updateEnvFile('TIMEZONE', $request->timezone);
        $this->updateEnvFile('SIZE_LIMIT', $request->image_limit);

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('General Settings Updated Successfully!'));
    }

    // Update Website Setting
    public function changeWebsiteSettings(Request $request)
    {
        Setting::where('id', '1')->update([
            'site_name' => $request->site_name
        ]);

        Config::where('config_key', 'site_name')->update([
            'config_value' => $request->site_name
        ]);

        // App name
        $appName = str_replace('"', "", $request->app_name);
        $appName = str_replace("'", "", $appName);

        // Set new values using putenv
        $this->updateEnvFile('APP_NAME', '"'.$appName.'"');

        Config::where('config_key', 'app_theme')->update([
            'config_value' => $request->app_theme
        ]);

        Config::where('config_key', 'default_theme')->update([
            'config_value' => $request->theme_id
        ]);

        // Check website logo
        if (isset($request->site_logo)) {
            $validator = $request->validate([
                'site_logo' => 'mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            $site_logo = '/images/web/elements/' . uniqid() . '.' . $request->site_logo->extension();
            $request->site_logo->move(public_path('images/web/elements'), $site_logo);

            // Update details
            Setting::where('id', '1')->update([
                'site_name' => $request->site_name, 'site_logo' => $site_logo
            ]);
        }

        // Check site logo light
        if (isset($request->site_logo_light)) {
            $validator = $request->validate([
                'site_logo_light' => 'mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            $site_logo_light = '/images/web/elements/' . uniqid() . '.' . $request->site_logo_light->extension();
            $request->site_logo_light->move(public_path('images/web/elements'), $site_logo_light);

            // Update details
            Setting::where('id', '1')->update([
                'site_name' => $request->site_name, 'site_logo_light' => $site_logo_light
            ]);
        }

        // Check favicon
        if (isset($request->favi_icon)) {
            $validator = $request->validate([
                'favi_icon' => 'mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            $favi_icon = '/images/web/elements/' . uniqid() . '.' . $request->favi_icon->extension();
            $request->favi_icon->move(public_path('images/web/elements'), $favi_icon);

            // Update details
            Setting::where('id', '1')->update([
                'site_name' => $request->site_name, 'favicon' => $favi_icon
            ]);
        }

        // Check primary image for website banner
        if (isset($request->primary_image)) {
            $validator = $request->validate([
                'primary_image' => 'mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            $primary_image = '/images/web/elements/' . uniqid() . '.' . $request->primary_image->extension();
            $request->primary_image->move(public_path('/images/web/elements'), $primary_image);

            // Update image
            Config::where('config_key', 'primary_image')->update([
                'config_value' => $primary_image,
            ]);
        }

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('Website Settings Updated Successfully!'));
    }

    // Update Payments Setting
    public function changePaymentsSettings(Request $request)
    {
        // Paypal
        Config::where('config_key', 'paypal_mode')->update([
            'config_value' => $request->paypal_mode
        ]);

        Config::where('config_key', 'paypal_client_id')->update([
            'config_value' => $request->paypal_client_key
        ]);

        Config::where('config_key', 'paypal_secret')->update([
            'config_value' => $request->paypal_secret
        ]);

        // Razorpay
        Config::where('config_key', 'razorpay_key')->update([
            'config_value' => $request->razorpay_client_key
        ]);

        Config::where('config_key', 'razorpay_secret')->update([
            'config_value' => $request->razorpay_secret
        ]);

        // Stripe
        Config::where('config_key', 'stripe_publishable_key')->update([
            'config_value' => $request->stripe_publishable_key
        ]);

        Config::where('config_key', 'stripe_secret')->update([
            'config_value' => $request->stripe_secret
        ]);

        // Paystack
        Config::where('config_key', 'paystack_public_key')->update([
            'config_value' => $request->paystack_public_key
        ]);

        Config::where('config_key', 'paystack_secret_key')->update([
            'config_value' => $request->paystack_secret
        ]);

        Config::where('config_key', 'merchant_email')->update([
            'config_value' => $request->merchant_email
        ]);

        // Mollie
        Config::where('config_key', 'mollie_key')->update([
            'config_value' => $request->mollie_key
        ]);

        // Transaction Cloud
        Config::where('config_key', 'transaction_cloud_api_key')->update([
            'config_value' => $request->transaction_cloud_login
        ]);

        Config::where('config_key', 'transaction_cloud_api_password')->update([
            'config_value' => $request->transaction_cloud_password
        ]);

        // Mercado Pago
        Config::where('config_key', 'mercado_pago_public_key')->update([
            'config_value' => $request->mercado_pago_public_key
        ]);

        Config::where('config_key', 'mercado_pago_access_token')->update([
            'config_value' => $request->mercado_pago_access_token
        ]);

        // Offline
        Config::where('config_key', 'bank_transfer')->update([
            'config_value' => $request->bank_transfer
        ]);

        // Phonepe
        Config::where('config_key', 'merchantId')->update([
            'config_value' => $request->merchantId,
        ]);

        Config::where('config_key', 'saltKey')->update([
            'config_value' => $request->saltKey,
        ]);

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('Payment Settings Updated Successfully!'));
    }

    // Update AI Tools Setting
    public function changeAISettings(Request $request)
    {
        Config::where('config_key', 'openai_model')->update([
            'config_value' => $request->ai_model,
        ]);

        Config::where('config_key', 'image_model')->update([
            'config_value' => $request->image_model,
        ]);

        Config::where('config_key', 'text_speech_model')->update([
            'config_value' => $request->text_speech_model,
        ]);

        Config::where('config_key', 'openai_api_key')->update([
            'config_value' => $request->openai_api_key,
        ]);

        Config::where('config_key', 'share_content')->update([
            'config_value' => $request->word_length,
        ]);

        Config::where('config_key', 'image_length')->update([
            'config_value' => $request->image_length,
        ]);

        Config::where('config_key', 'tiny_api_key')->update([
            'config_value' => $request->tiny_api_key,
        ]);

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('AI Settings Updated Successfully!'));
    }

    // Update S3 Setting
    public function changeS3Settings(Request $request)
    {
        // Access Key
        $awsAccessKey = str_replace('"', "", $request->access_key);
        $awsAccessKey = str_replace("'", "", $awsAccessKey);

        // Secret Key
        $awsSecretKey = str_replace('"', "", $request->secret_key);
        $awsSecretKey = str_replace("'", "", $awsSecretKey);

        // Region
        $awsDefaultRegion = str_replace('"', "", $request->default_region);
        $awsDefaultRegion = str_replace("'", "", $awsDefaultRegion);

        // Bucket
        $bucket = str_replace('"', "", $request->bucket);
        $bucket = str_replace("'", "", $bucket);

        // Set new values using putenv (AWS S3)
        $this->updateEnvFile('AWS_ENABLE', $request->aws_enable);
        $this->updateEnvFile('AWS_ACCESS_KEY_ID', $awsAccessKey);
        $this->updateEnvFile('AWS_SECRET_ACCESS_KEY', $awsSecretKey);
        $this->updateEnvFile('AWS_DEFAULT_REGION', $awsDefaultRegion);
        $this->updateEnvFile('AWS_BUCKET', $bucket);
        $this->updateEnvFile('AWS_USE_PATH_STYLE_ENDPOINT', $request->end_point);

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('AWS configuration settings updated successfully!'));
    }

    // Tax settings
    public function taxSetting()
    {
        // Queries
        $config = Config::get();
        $settings = Setting::first();

        // Page view
        return view('admin.pages.tax.index', compact('config', 'settings'));
    }

    // Update tax setting
    public function updateTaxSetting(Request $request)
    {
        // Update
        Config::where('config_key', 'invoice_prefix')->update([
            'config_value' => $request->invoice_prefix,
        ]);

        Config::where('config_key', 'invoice_name')->update([
            'config_value' => $request->invoice_name,
        ]);

        Config::where('config_key', 'invoice_email')->update([
            'config_value' => $request->invoice_email,
        ]);

        Config::where('config_key', 'invoice_phone')->update([
            'config_value' => $request->invoice_phone,
        ]);

        Config::where('config_key', 'invoice_address')->update([
            'config_value' => $request->invoice_address,
        ]);

        Config::where('config_key', 'invoice_city')->update([
            'config_value' => $request->invoice_city,
        ]);

        Config::where('config_key', 'invoice_state')->update([
            'config_value' => $request->invoice_state,
        ]);

        Config::where('config_key', 'invoice_zipcode')->update([
            'config_value' => $request->invoice_zipcode,
        ]);

        Config::where('config_key', 'invoice_country')->update([
            'config_value' => $request->invoice_country,
        ]);

        Config::where('config_key', 'tax_name')->update([
            'config_value' => $request->tax_name,
        ]);

        Config::where('config_key', 'tax_number')->update([
            'config_value' => $request->tax_number,
        ]);

        Config::where('config_key', 'tax_value')->update([
            'config_value' => $request->tax_value,
        ]);

        Config::where('config_key', 'invoice_footer')->update([
            'config_value' => $request->invoice_footer,
        ]);

        // Page redirect
        return redirect()->route('admin.tax.setting')->with('success', trans('Invoice Setting Updated Successfully!'));
    }

    // Update email setting
    public function updateEmailSetting(Request $request)
    {
        // Update
        Config::where('config_key', 'email_heading')->update([
            'config_value' => $request->email_heading,
        ]);

        Config::where('config_key', 'email_footer')->update([
            'config_value' => $request->email_footer,
        ]);

        // Page redirect
        return redirect()->route('admin.tax.setting')->with('success', trans('Email Setting Updated Successfully!'));
    }

    // Clear cache
    public function clearCache()
    {
        try {
            // Clear application cache
            Cache::flush();

            // Clear caches using Artisan
            Artisan::call('cache:clear');  // Clear application cache
            Artisan::call('route:clear');  // Clear route cache
            Artisan::call('config:clear'); // Clear configuration cache
            Artisan::call('view:clear');   // Clear compiled view files

            // Delete all files in bootstrap/cache except .gitignore
            $cachePath  = base_path('bootstrap/cache');
            $cacheFiles = File::files($cachePath);
            foreach ($cacheFiles as $file) { 
                if ($file->getFilename() !== '.gitignore') {
                    File::delete($file);
                }
            }

            // Delete all files in storage/framework/cache except .gitignore
            $cachePath  = base_path('storage/framework/cache');
            $cacheFiles = File::files($cachePath);
            foreach ($cacheFiles as $file) {
                if ($file->getFilename() !== '.gitignore') {
                    File::delete($file);
                }
            }

            // Delete all files in storage/framework/views except .gitignore
            $cachePath  = base_path('storage/framework/views');
            $cacheFiles = File::files($cachePath);
            foreach ($cacheFiles as $file) {
                if ($file->getFilename() !== '.gitignore') {
                    File::delete($file);
                }
            }

            return redirect()->route('admin.dashboard')->with('success', trans('Application Cache Cleared Successfully!'));
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('failed', trans('Failed to Clear Cache. Due to the following error: ') . ' ' . $e->getMessage());
        }
    }

    // Change .env file
    public function updateEnvFile($key, $value)
    {
        $envPath = base_path('.env');

        // Check if the .env file exists
        if (file_exists($envPath)) {

            // Read the .env file
            $contentArray = file($envPath);

            // Loop through each line to find the key and update its value
            foreach ($contentArray as &$line) {

                // Split the line by '=' to get key and value
                $parts = explode('=', $line, 2);

                // Check if the key matches and update its value
                if (isset($parts[0]) && $parts[0] === $key) {
                    $line = $key . '=' . $value . PHP_EOL;
                }
            }

            // Implode the array back to a string and write it to the .env file
            $newContent = implode('', $contentArray);
            file_put_contents($envPath, $newContent);

            // Reload the environment variables
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }

    /**
     * This will update the languages array in config/app.php file
     *
     * @param array $languages
     * @return void
     */
    private function updateLanguages(array $languageCodes, string $defaultLanguage)
    {
        // Define a mapping of language codes to full names
        $languageMap = [
            'ar' => 'Arabic',
            'bn' => 'Bangla',
            'bg' => 'Bulgarian',
            'zh' => 'Chinese',
            'nl' => 'Dutch',
            'en' => 'English',
            'fr' => 'French',
            'de' => 'German',
            'ht' => 'Haitian Creole',
            'hi' => 'Hindi',
            'he' => 'Hebrew',
            'hu' => 'Hungarian',
            'id' => 'Indonesian',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'lt' => 'Lithuanian',
            'ms' => 'Malay',
            'pt' => 'Portuguese',
            'pl' => 'Polish',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'es' => 'Spanish',
            'si' => 'Sinhala',
            'sv' => 'Swedish',
            'ta' => 'Tamil',
            'th' => 'Thai',
            'tr' => 'Turkish',
            'ur' => 'Urdu',
            'vi' => 'Vietnamese',
        ];

        // Convert indexed array to associative array using the map
        $languagesArray = [];
        foreach ($languageCodes as $code) {
            if (isset($languageMap[$code])) {
                $languagesArray[$code] = $languageMap[$code];
            }
        }

        // Set the first language as the default locale
        $defaultLocale = $defaultLanguage ?? 'en';

        // Update the languages array in config/app.php
        $this->updateConfigFile($languagesArray, $defaultLocale);
    }

    /**
     * Function to update config/app.php file
     */
    private function updateConfigFile(array $languagesArray, string $defaultLocale)
    {
        $configPath = config_path('app.php');

        // Read the config file
        $configContent = file_get_contents($configPath);

        // Convert the array to a PHP string format with short array syntax
        $newLanguagesArray = var_export($languagesArray, true);
        $newLanguagesArray = str_replace("array (", "[", $newLanguagesArray);
        $newLanguagesArray = str_replace(")", "]", $newLanguagesArray);

        // Replace the existing 'languages' array
        $configContent = preg_replace(
            "/'languages'\s*=>\s*\[[^\]]*\]/",
            "'languages' => " . $newLanguagesArray,
            $configContent
        );

        // Update 'locale' and 'fallback_locale' values
        $configContent = preg_replace(
            "/'locale'\s*=>\s*'[^']*'/",
            "'locale' => '$defaultLocale'",
            $configContent
        );

        $configContent = preg_replace(
            "/'fallback_locale'\s*=>\s*'[^']*'/",
            "'fallback_locale' => '$defaultLocale'",
            $configContent
        );

        // Save the updated content back to config/app.php
        file_put_contents($configPath, $configContent);

        try {
            // Clear application cache
            Cache::flush();

            // Clear caches using Artisan
            Artisan::call('cache:clear');  // Clear application cache
            Artisan::call('route:clear');  // Clear route cache
            Artisan::call('config:clear'); // Clear configuration cache
            Artisan::call('view:clear');   // Clear compiled view files

            // Delete all files in bootstrap/cache except .gitignore
            $cachePath  = base_path('bootstrap/cache');
            $cacheFiles = File::files($cachePath);
            foreach ($cacheFiles as $file) {
                if ($file->getFilename() !== '.gitignore') {
                    File::delete($file);
                }
            }

            // Delete all files in storage/framework/cache except .gitignore
            $cachePath  = base_path('storage/framework/cache');
            $cacheFiles = File::files($cachePath);
            foreach ($cacheFiles as $file) {
                if ($file->getFilename() !== '.gitignore') {
                    File::delete($file);
                }
            }

            // Delete all files in storage/framework/views except .gitignore
            $cachePath  = base_path('storage/framework/views');
            $cacheFiles = File::files($cachePath);
            foreach ($cacheFiles as $file) {
                if ($file->getFilename() !== '.gitignore') {
                    File::delete($file);
                }
            }
        } catch (\Exception $e) {
        }
    }
}
