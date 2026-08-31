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

        // return view('admin.pages.plugins.index', compact('settings', 'config', 'plugins'));

        return Inertia::render('admin/plugins/index', compact('settings', 'config', 'plugins'));
    }

    public function deletePlugin($pluginName)
    {
        if ($this->pluginManager->deletePlugin($pluginName)) {
            return redirect()
                ->route('dashboard.admin.plugins.index')
                ->with('success', __('Plugin deleted successfully.'));
        }

        return redirect()->route('dashboard.admin.plugins.index')->with('error', trans('Plugin not found or could not be deleted.'));
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
            trans('Installation failed. File not found!')
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
            trans('Installation failed. File is corrupted!')
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
            trans(
                'Installation failed. This plugin is not compatible with your platform.'
            )
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
            trans(
                'Installation failed. This plugin requires platform version :min_version or later.',
                ['min_version' => $minVersion]
            )
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
            trans('Installation failed. Some files are missing!')
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
        trans('Plugin installation success!')
    );
}



    // public function upload(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'zip_file' => 'required|mimes:zip|max:' . env("SIZE_LIMIT") . '',
    //     ]);

    //     if ($validator->fails()) {
    //         $limit = env("SIZE_LIMIT");

    //         FacadesSession::flash('failed', trans('Please upload a valid zip file. File size should be less than :limit Kb Or Increase the upload size limit in settings Panel!', ['limit' => $limit]));
    //         return response()->json(['message' => trans('Plugin Installation failed!')]);
    //     }

    //     $zipFile = $request->file('zip_file');

    //     // if zip file found
    //     if (! $zipFile) {
    //         FacadesSession::flash('failed', trans('Installation failed. File not found!'));
    //         return response()->json(['message' => trans('Plugin Installation failed!')]);
    //     }

    //     $download = uniqid();
    //     // Store zip file at storage folder
    //     $zipPath = storage_path('./app/plugins/' . $download . '.zip');
    //     file_put_contents($zipPath, $zipFile->get());

    //     $zip = new ZipArchive;
    //     $out = $zip->open($zipPath);

    //     if ($out !== true) {
    //         if ($zip instanceof \ZipArchive) {
    //             $zip->close();
    //         }

    //         if (file_exists($zipPath)) {
    //             unlink($zipPath);
    //         }

    //         FacadesSession::flash('failed', trans('Installation failed. File is corrupted!'));
    //         return response()->json(['message' => trans('Plugin Installation failed!')]);
    //     }


    //     // Check zip file
    //     $fileStrictValidationCount = 0;

    //     for ($i = 0; $i < $zip->numFiles; $i++) {
    //         $fileName = $zip->getNameIndex($i);

    //         // Views/index.blade.php
    //         if (preg_match('#(^|.*/)Views/index\.blade\.php$#i', $fileName)) {
    //             $fileStrictValidationCount++;
    //         }

    //         // routes.php
    //         if (preg_match('#(^|.*/)routes\.php$#i', $fileName)) {
    //             $fileStrictValidationCount++;
    //         }

    //         // plugin.json or template.json
    //         if (preg_match('#(^|.*/)(plugin|template)\.json$#i', $fileName)) {
    //             $fileStrictValidationCount++;
    //         }

    //         // Controllers/ folder
    //         if (preg_match('#(^|.*/)Controllers/$#i', $fileName)) {
    //             $fileStrictValidationCount++;
    //         }

    //         // Views/ folder
    //         if (preg_match('#(^|.*/)Views/$#i', $fileName)) {
    //             $fileStrictValidationCount++;
    //         }

    //         if (preg_match('#(^|.*/)plugin\.json$#i', $fileName)) {
    //             $pluginJsonContent = $zip->getFromName($fileName);
    //         }
    //     }

    //     $pluginData = json_decode($pluginJsonContent, true);

    //     // check platform
    //     if ($pluginData['platform'] != "aitools") {
    //         // Remove zip file
    //         $zip->close();
    //         unlink($zipPath);
    //         FacadesSession::flash('failed', trans('Installation failed. This plugin is not compatible with your platform.'));
    //         return response()->json(['message' => trans('Plugin Installation failed!')]);
    //     }

    //     $current_version = Config::where('config_key', 'app_version')->first()->config_value;
    //     $min_version = $pluginData['min_version'];

    //     // compare version
    //     if (version_compare($current_version, $min_version, '<')) {
    //         // Remove zip file
    //         $zip->close();
    //         unlink($zipPath);
    //         FacadesSession::flash(
    //             'failed',
    //             trans(
    //                 'Installation failed. This plugin requires platform version :min_version or later.',
    //                 ['min_version' => $min_version]
    //             )
    //         );
    //         return response()->json(['message' => trans('Plugin Installation failed!')]);
    //     }

    //     // Check zip file
    //     if ($fileStrictValidationCount < 5) {
    //         // Remove zip file
    //         $zip->close();
    //         unlink($zipPath);
    //         FacadesSession::flash('failed', trans('Installation failed. Some files are missing!'));
    //         return response()->json(['message' => trans('Plugin Installation failed!')]);
    //     }

    //     $extractPath = base_path('plugins');
    //     $zip->extractTo($extractPath);
    //     $zip->close();
    //     unlink($zipPath);

    //     FacadesSession::flash('success', trans('Plugin installation success!'));
    //     return response()->json(['message' => trans('Plugin installation success!')]);
    // }
}
