<?php

namespace Plugins\GoogleRecaptcha\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GoogleRecaptchaController extends Controller
{
    public function googleRecaptchaSettings(Request $request)
    {
        $settings = Setting::where('id', 1)->first();

        $recaptchaConfiguration = [
            'RECAPTCHA_ENABLE'     => env('RECAPTCHA_ENABLE', 'off'),
            'RECAPTCHA_SITE_KEY'   => env('RECAPTCHA_SITE_KEY', ''),
            'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY', ''),
        ];

        return Inertia::render(
            'admin/plugins/google-recaptcha',
            [
                'recaptchaConfiguration' => $recaptchaConfiguration,
            ]
        );
    }

    public function googleRecaptchaSettingsUpdate(Request $request)
    {
        $this->updateEnv(
            'RECAPTCHA_ENABLE',
            $request->recaptcha_enable
        );

        $this->updateEnv(
            'RECAPTCHA_SITE_KEY',
            $request->recaptcha_site_key
        );

        $this->updateEnv(
            'RECAPTCHA_SECRET_KEY',
            $request->recaptcha_secret_key
        );

        return redirect()
            ->route('admin.plugin.google_recaptcha.settings')
            ->with(
                'success',
                __('Google reCAPTCHA Settings Updated Successfully!')
            );
    }

    public function updateEnv($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $envContent = file_get_contents($path);

            $pattern = "/^" . preg_quote($key) . "=.*/m";
            $replacement = $key . '=' . $value;

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace(
                    $pattern,
                    $replacement,
                    $envContent
                );
            } else {
                $envContent .= "\n" . $replacement;
            }

            file_put_contents($path, $envContent);
        }
    }
}
