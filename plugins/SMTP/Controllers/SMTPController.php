<?php

namespace Plugins\SMTP\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class SMTPController extends Controller
{
    public function smtpSettings(Request $request)
    {
        $settings = Setting::where('id', 1)->first();

        $email_configuration = [
            'driver'     => env('MAIL_MAILER', 'smtp'),
            'host'       => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port'       => env('MAIL_PORT', 587),
            'username'   => env('MAIL_USERNAME', ''),
            'password'   => env('MAIL_PASSWORD', ''),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'address'    => env('MAIL_FROM_ADDRESS', ''),
            'name'       => env('MAIL_FROM_NAME', $settings->site_name),
        ];

        $config = Config::get();

        return Inertia::render('admin/plugins/smtp', [
            'settings' => $settings,
            'email_configuration' => $email_configuration,
            'config' => $config,
        ]);
    }

    public function smtpSettingsUpdate(Request $request)
    {
        $validated = $request->validate([
            'mail_driver' => ['required', 'string', 'in:smtp,sendmail'],
            'mail_host' => ['required', 'string', 'max:255'],
            'mail_port' => ['required', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['required', 'string', 'max:255'],
            'mail_password' => ['required', 'string', 'max:255'],
            'mail_encryption' => ['required', 'string', 'in:tls,ssl'],
            'mail_address' => ['required', 'email', 'max:255'],
            'mail_sender' => ['required', 'string', 'max:255'],
            'disable_user_email_verification' => ['required', 'in:yes,no'],
        ]);

        $this->updateEnvFile(
            'MAIL_MAILER',
            $this->cleanValue($validated['mail_driver'])
        );

        $this->updateEnvFile(
            'MAIL_HOST',
            $this->cleanValue($validated['mail_host'])
        );

        $this->updateEnvFile(
            'MAIL_PORT',
            $this->cleanValue($validated['mail_port'])
        );

        $this->updateEnvFile(
            'MAIL_USERNAME',
            '"' . $this->cleanValue($validated['mail_username']) . '"'
        );

        $this->updateEnvFile(
            'MAIL_PASSWORD',
            '"' . $this->cleanValue($validated['mail_password']) . '"'
        );

        $this->updateEnvFile(
            'MAIL_ENCRYPTION',
            $this->cleanValue($validated['mail_encryption'])
        );

        $this->updateEnvFile(
            'MAIL_FROM_ADDRESS',
            $this->cleanValue($validated['mail_address'])
        );

        $this->updateEnvFile(
            'MAIL_FROM_NAME',
            '"' . $this->cleanValue($validated['mail_sender']) . '"'
        );

        Config::where(
            'config_key',
            'disable_user_email_verification'
        )->update([
            'config_value' => $validated['disable_user_email_verification'],
        ]);

        return redirect()
            ->route('admin.plugin.smtp.settings')
            ->with('success', __('SMTP Settings Updated Successfully!'));
    }

    public function testEmail()
    {
        try {
            Mail::to(env('MAIL_FROM_ADDRESS'))
                ->send(new \App\Mail\TestMail([
                    'msg' => 'Test mail',
                ]));

            return redirect()
                ->route('admin.plugin.smtp.settings')
                ->with('success', __('Test mail sent successfully.'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.plugin.smtp.settings')
                ->with('error', $e->getMessage());
        }
    }

    private function cleanValue($value)
    {
        return str_replace(
            ['"', "'"],
            '',
            $value
        );
    }

    public function updateEnvFile($key, $value)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $contentArray = file($envPath);

        foreach ($contentArray as &$line) {
            $parts = explode('=', $line, 2);

            if (isset($parts[0]) && $parts[0] === $key) {
                $line = $key . '=' . $value . PHP_EOL;
            }
        }

        file_put_contents(
            $envPath,
            implode('', $contentArray)
        );

        putenv($key . '=' . $value);

        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}
