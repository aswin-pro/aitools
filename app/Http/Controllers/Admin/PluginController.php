<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Setting;
use App\Services\PluginManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use ZipArchive;

class PluginController extends Controller
{
    protected $pluginManager;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(PluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // Index
    public function index()
    {
        // Queries
        $settings = Setting::first();
        $config   = Config::get();

        // Load plugins
        $this->pluginManager->loadPlugins();

        // Get all plugins
        $plugins = $this->pluginManager->getPlugins();


        return Inertia::render('admin/plugins/index', compact('settings', 'config', 'plugins'));
    }

    public function deletePlugin($pluginName)
    {
        if ($this->pluginManager->deletePlugin($pluginName)) {
            return redirect()
                ->route('dashboard.admin.plugins.index')
                ->with('success', 'Plugin deleted successfully.');
        }

        return redirect()->route('dashboard.admin.plugins.index')->with('error','Plugin not found or could not be deleted.');
    }


public function upload(Request $request)
{
    $validator = Validator::make($request->all(), [
        'zip_file' => 'required|mimes:zip|max:' . env('SIZE_LIMIT'),
    ]);

    if ($validator->fails()) {
        $limit = env('SIZE_LIMIT');

        return redirect()->back()->with(
            'error',
            trans(
                'Please upload a valid zip file. File size should be less than :limit Kb Or Increase the upload size limit in settings Panel!',
                ['limit' => $limit]
            )
        );
    }

    $zipFile = $request->file('zip_file');

    // File not found
    if (! $zipFile) {
        return redirect()->back()->with(
            'failed',
            'Installation failed. File not found!'
        );
    }

    $download = uniqid();

    // Store zip file at storage folder
    $zipPath = storage_path('./app/plugins/' . $download . '.zip');

    file_put_contents($zipPath, $zipFile->get());

    $zip = new ZipArchive;
    $out = $zip->open($zipPath);

    // Corrupted ZIP
    if ($out !== true) {
        if ($zip instanceof \ZipArchive) {
            $zip->close();
        }

        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        return redirect()->back()->with(
            'error',
            'Installation failed. File is corrupted!'
        );
    }

    // Check ZIP contents
    $fileStrictValidationCount = 0;
    $pluginJsonContent = null;

    for ($i = 0; $i < $zip->numFiles; $i++) {
        $fileName = $zip->getNameIndex($i);

        // Views/index.blade.php
        if (preg_match(
            '#(^|.*/)Views/index\.blade\.php$#i',
            $fileName
        )) {
            $fileStrictValidationCount++;
        }

        // routes.php
        if (preg_match(
            '#(^|.*/)routes\.php$#i',
            $fileName
        )) {
            $fileStrictValidationCount++;
        }

        // plugin.json or template.json
        if (preg_match(
            '#(^|.*/)(plugin|template)\.json$#i',
            $fileName
        )) {
            $fileStrictValidationCount++;
        }

        // Controllers/ folder
        if (preg_match(
            '#(^|.*/)Controllers/$#i',
            $fileName
        )) {
            $fileStrictValidationCount++;
        }

        // Views/ folder
        if (preg_match(
            '#(^|.*/)Views/$#i',
            $fileName
        )) {
            $fileStrictValidationCount++;
        }

        // plugin.json
        if (preg_match(
            '#(^|.*/)plugin\.json$#i',
            $fileName
        )) {
            $pluginJsonContent = $zip->getFromName($fileName);
        }
    }

    $pluginData = json_decode($pluginJsonContent, true);

    // Check platform
    if (($pluginData['platform'] ?? null) !== 'aitools') {
        $zip->close();

        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        return redirect()->back()->with(
            'error',
            
                'Installation failed. This plugin is not compatible with your platform.'
            
        );
    }

    $currentVersion = Config::where(
        'config_key',
        'app_version'
    )->first()->config_value;

    $minVersion = $pluginData['min_version'];

    // Compare version
    if (version_compare($currentVersion, $minVersion, '<')) {
        $zip->close();

        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        return redirect()->back()->with(
            'error',
            
                'Installation failed. This plugin requires platform version :min_version or later.',
                ['min_version' => $minVersion]
            
        );
    }

    // Check required files
    if ($fileStrictValidationCount < 5) {
        $zip->close();

        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        return redirect()->back()->with(
            'error',
            'Installation failed. Some files are missing!'
        );
    }

    // Extract plugin
    $extractPath = base_path('plugins');

    $zip->extractTo($extractPath);
    $zip->close();

    if (file_exists($zipPath)) {
        unlink($zipPath);
    }

    return redirect()->back()->with(
        'success',
        'Plugin installation success!'
    );
}
}