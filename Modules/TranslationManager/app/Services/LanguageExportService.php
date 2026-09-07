<?php

/*
 |--------------------------------------------------------------------------
 | GoBiz vCard SaaS
 |--------------------------------------------------------------------------
 | Developed by NativeCode © 2021 - https://nativecode.in
 | All rights reserved
 | Unauthorized distribution is prohibited
 |--------------------------------------------------------------------------
*/

namespace Modules\TranslationManager\app\Services;

use Illuminate\Support\Facades\File;
use ZipArchive;
use RuntimeException;

class LanguageExportService
{
    protected string $langPath;

    public function __construct()
    {
        $this->langPath = config('translation-manager.lang_path', base_path('resources/lang'));
    }

public function exportLocale(string $locale): string
{
    $tempDir = storage_path('app/temp_exports');

    if (!File::exists($tempDir)) {
        File::makeDirectory($tempDir, 0755, true);
    }

    $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . "{$locale}.zip";

    if (File::exists($zipFilePath)) {
        File::delete($zipFilePath);
    }

    $zip = new ZipArchive();

    if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
        throw new RuntimeException('Unable to create ZIP file.');
    }

    $localeDir = $this->langPath . DIRECTORY_SEPARATOR . $locale;

    if (File::isDirectory($localeDir)) {
        foreach (File::allFiles($localeDir) as $file) {
            $zip->addFile(
                $file->getRealPath(),
                $locale . '/' . $file->getRelativePathname()
            );
        }
    }

    $jsonFile = $this->langPath . DIRECTORY_SEPARATOR . "{$locale}.json";

    if (File::exists($jsonFile)) {
        $zip->addFile($jsonFile, "{$locale}.json");
    }

    $zip->close();

    if (!File::exists($zipFilePath)) {
        throw new RuntimeException('ZIP file was not created.');
    }

    return $zipFilePath;
}

    // public function exportLocale(string $locale): string
    // {
    //     $zip = new ZipArchive();
    //     $tempDir = storage_path('app/temp_exports');

    //     if (!File::exists($tempDir)) {
    //         File::makeDirectory($tempDir, 0755, true);
    //     }

    //     $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . "{$locale}.zip";

    //     if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    //         throw new RuntimeException("Cannot create zip archive: {$zipFilePath}");
    //     }

    //     $localeDir = $this->langPath . DIRECTORY_SEPARATOR . $locale;
    //     if (File::exists($localeDir)) {
    //         $files = File::allFiles($localeDir);
    //         foreach ($files as $file) {
    //             $relativePath = $locale . '/' . $file->getRelativePathname();
    //             $zip->addFile($file->getRealPath(), $relativePath);
    //         }
    //     }

    //     $jsonFile = $this->langPath . DIRECTORY_SEPARATOR . "{$locale}.json";
    //     if (File::exists($jsonFile)) {
    //         $zip->addFile($jsonFile, "{$locale}.json");
    //     }

    //     $zip->close();

    //     return $zipFilePath;
    // }
}
