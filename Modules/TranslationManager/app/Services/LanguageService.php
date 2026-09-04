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

class LanguageService
{
    protected string $langPath;

    public function __construct()
    {
        $this->langPath = config('translation-manager.lang_path', base_path('resources/lang'));
    }

    public function getLanguages(): array
    {
        if (!File::exists($this->langPath)) {
            return [];
        }

        $locales = [];

        $directories = File::directories($this->langPath);
        foreach ($directories as $dir) {
            $locale = basename($dir);

            // EXCLUDE: Ignore Laravel's published vendor translations directory
            if ($locale === 'vendor') {
                continue;
            }

            $locales[$locale] = [
                'code' => $locale,
                'name' => $this->getLanguageName($locale),
                'type' => 'directory',
            ];
        }

        $jsonFiles = File::files($this->langPath);
        foreach ($jsonFiles as $file) {
            if ($file->getExtension() === 'json') {
                $locale = $file->getFilenameWithoutExtension();

                // EXCLUDE: Ignore vendor configurations if any
                if ($locale === 'vendor') {
                    continue;
                }

                if (!isset($locales[$locale])) {
                    $locales[$locale] = [
                        'code' => $locale,
                        'name' => $this->getLanguageName($locale),
                        'type' => 'json-only',
                    ];
                } else {
                    $locales[$locale]['type'] = 'both';
                }
            }
        }

        return $locales;
    }

    public function createLanguage(string $name, string $code, string $copyFromLocale): bool
    {
        $targetDir = $this->langPath . DIRECTORY_SEPARATOR . $code;
        $sourceDir = $this->langPath . DIRECTORY_SEPARATOR . $copyFromLocale;

        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        if (File::exists($sourceDir)) {
            File::copyDirectory($sourceDir, $targetDir);
        }

        $sourceJson = $this->langPath . DIRECTORY_SEPARATOR . "{$copyFromLocale}.json";
        $targetJson = $this->langPath . DIRECTORY_SEPARATOR . "{$code}.json";

        if (File::exists($sourceJson)) {
            File::copy($sourceJson, $targetJson);
        } else {
            File::put($targetJson, json_encode([], JSON_PRETTY_PRINT));
        }

        return true;
    }

    public function deleteLanguage(string $code): bool
    {
        $dir = $this->langPath . DIRECTORY_SEPARATOR . $code;
        $json = $this->langPath . DIRECTORY_SEPARATOR . "{$code}.json";

        if (File::exists($dir)) {
            File::deleteDirectory($dir);
        }
        if (File::exists($json)) {
            File::delete($json);
        }

        return true;
    }

    public function getLanguageName(string $code): string
    {
        $locales = config('translation-manager.supported_locales', []);
        return $locales[$code] ?? strtoupper($code);
    }
}
