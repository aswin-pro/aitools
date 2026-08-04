<?php

namespace App\Http\Controllers\Admin;

use ZipArchive;
use App\Models\Config;
use App\Models\Setting;
use App\Providers\AppConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request as serverReq;

class UpdateController extends Controller
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

    // Check
    public function check()
    {
        // Queries
        $settings = Setting::first();
        $config = Config::get();
        $purchase_code = $config[32]->config_value;

        // Activation email
        $email = $config[70]->config_value;

        // Default message
        $resp_data = [];
        $errorMessage = trans("Something went wrong! Please contact author support team.");

        $server_name = serverReq::server("SERVER_NAME");
        $server_name = $server_name ? $server_name : "LOCAL.TEST";

        // Check update validator
        $client = new \GuzzleHttp\Client();
        $res = $client->post('https://verify.nativecode.in/check-update', [
            'form_params' => [
                'purchase_code' => $config[32]->config_value,
                'server_name' => $server_name,
                'version' => $config[33]->config_value,
                'email' => $email
            ]
        ]);
 
        $resp_data = json_decode($res->getBody(), true);

        if ($resp_data) {
            if ($resp_data['status'] == true) {

                // Queries
                $settings = Setting::first();
                $purchase_code = $config[32]->config_value;

                // Response
                $response = ['message' => $resp_data['message'], 'version' => $resp_data['version'], 'update' => $resp_data['update'], 'notes' => $resp_data['notes'], 'license' => $resp_data['license']];

                return view('admin.pages.update.index', compact('response', 'settings', 'purchase_code', 'config', 'email'));
            } else {
                request()->session()->flash('failed', $resp_data['message']);

                return view('admin.pages.update.index', compact('settings', 'purchase_code', 'config', 'email'));
            }
        } else {
            request()->session()->flash('failed', $errorMessage);

            return view('admin.pages.update.index', compact('settings', 'purchase_code', 'config', 'email'));
        }
    }

    // Check Update
    public function checkUpdate(Request $request)
    {
        // Queries
        $config = Config::get();

        // Default message
        $resp_data = [];
        $errorMessage = trans("Something went wrong! Please contact author support team.");

        $server_name = serverReq::server("SERVER_NAME");
        $server_name = $server_name ? $server_name : "LOCAL.TEST";

        // Update email
        $email = $request->email;

        Config::where('config_key', 'activation_email_address')->update([
            'config_value' => $email,
        ]);

        // Check update validator
        $client = new \GuzzleHttp\Client();
        $res = $client->post('https://verify.nativecode.in/check-update', [
            'form_params' => [
                'purchase_code' => $request->purchase_code,
                'server_name' => $server_name,
                'version' => $config[33]->config_value,
                'email' => $email
            ]
        ]);

        $resp_data = json_decode($res->getBody(), true);

        if ($resp_data) {
            if ($resp_data['status'] == true) {

                // Queries
                $settings = Setting::first();
                $purchase_code = $config[32]->config_value;

                // Response
                $response = ['message' => $resp_data['message'], 'version' => $resp_data['version'], 'update' => $resp_data['update'], 'notes' => $resp_data['notes'], 'license' => $resp_data['license']];
                return view('admin.pages.update.index', compact('response', 'settings', 'purchase_code', 'config', 'email'));
            } else {
                $errorMessage = $resp_data['message'];
                return redirect()->route('admin.check')->with('failed', $errorMessage);
            }
        } else {
            return redirect()->route('admin.check')->with('failed', $errorMessage);
        }
    }

    // Update code
    public function updateCode(Request $request)
    {
        // Queries
        $config = Config::get();
        // Default message
        $resp_data = [];
        $errorMessage = trans("Something went wrong! Please contact author support team.");

        $server_name = serverReq::server("SERVER_NAME");
        $server_name = $server_name ? $server_name : "LOCAL.TEST";

        // Update email
        $email = $request->email;

        Config::where('config_key', 'activation_email_address')->update([
            'config_value' => $email,
        ]);

        // Check update validator
        $client = new \GuzzleHttp\Client();
        $res = $client->post('https://verify.nativecode.in/update-code', [
            'form_params' => [
                'purchase_code' => $config[32]->config_value,
                'server_name' => $server_name,
                'version' => $config[33]->config_value,
                'email' => $email
            ]
        ]);

        // Get status code is "200
        if ($res->getStatusCode() == 200) {
            // Get file
            $download = uniqid();
            file_put_contents(public_path($download . '.zip'), $res->getBody());

            // ZipArchive
            $unzip = new ZipArchive;
            $out = $unzip->open($download . '.zip');

            if ($out === TRUE) {
                // Exact zip
                $unzip->extractTo('../');
                $unzip->close();
                // Delete zip
                unlink($download . '.zip');

                // Update version
                Config::where('config_key', 'app_version')->update([
                    'config_value' => $request->app_version,
                ]);

                $filecode = str_replace(".", "", $request->app_version);
                if (file_exists(app_path("./Classes/AIToolsUpdater$filecode.php"))) {
                    $baseClassName = "\App\Classes\AIToolsUpdater";
                    $dynamicClassName = $baseClassName . $filecode;
                    if (class_exists($dynamicClassName)) {
                        $dynamicClass = new $dynamicClassName();
                        $dynamicClass->runUpdate();
                    }

                    // After update, remove the GoBizUpdater$filecode.php
                    unlink(app_path("./Classes/AIToolsUpdater$filecode.php"));
                }

                try {
                    // Clear application cache
                    Cache::flush();

                    // Delete all files in bootstrap/cache except .gitignore
                    $cachePath = base_path('bootstrap/cache');
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

                // Success message and redirect
                return redirect()->route('admin.check')->with('success', trans('New version installed successfully.'));
            } else {
                // Failed message and redirect
                return redirect()->route('admin.check')->with('failed', trans('Installation failed.'));
            }
        } else {
            // Success message and redirect
            $resp_data = json_decode($res->getBody(), true);
            return redirect()->route('admin.check')->with('failed', $resp_data['message']);
        }

        // Failed message and redirect
        return redirect()->route('admin.check')->with('failed', trans('Purchase code verified failed.'));
    }
}
