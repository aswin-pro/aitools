<?php

namespace Plugins\GoogleOAuth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GoogleOAuthController extends Controller
{
    public function googleOAuthSettings(Request $request)
    {
        $google_configuration = [
            'GOOGLE_ENABLE'        => env('GOOGLE_ENABLE', 'off'),
            'GOOGLE_CLIENT_ID'     => env('GOOGLE_CLIENT_ID', ''),
            'GOOGLE_CLIENT_SECRET' => env('GOOGLE_CLIENT_SECRET', ''),
            'GOOGLE_REDIRECT'      => env('GOOGLE_REDIRECT', ''),
        ];

        return Inertia::render('admin/plugins/google-oauth', [
            'googleConfiguration' => $google_configuration,
        ]);
    }

    public function googleOAuthSettingsUpdate(Request $request)
    {
        $this->updateEnv(
            'GOOGLE_ENABLE',
            $request->google_auth_enable
        );

        $this->updateEnv(
            'GOOGLE_CLIENT_ID',
            '"' . str_replace('"', "'", $request->google_client_id) . '"'
        );

        $this->updateEnv(
            'GOOGLE_CLIENT_SECRET',
            '"' . str_replace('"', "'", $request->google_client_secret) . '"'
        );

        $this->updateEnv(
            'GOOGLE_REDIRECT',
            '"' . str_replace('"', "'", $request->google_redirect) . '"'
        );

        return redirect()
            ->route('admin.plugin.google_oauth.settings')
            ->with(
                'success',
                __('Google OAuth Settings Updated Successfully!')
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