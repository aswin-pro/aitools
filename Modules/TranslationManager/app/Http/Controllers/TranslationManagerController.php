<?php

/*
 |--------------------------------------------------------------------------
 | AI Tools
 |--------------------------------------------------------------------------
 | Developed by NativeCode © 2021 - https://nativecode.in
 | All rights reserved
 | Unauthorized distribution is prohibited
 |--------------------------------------------------------------------------
*/

namespace Modules\TranslationManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Modules\TranslationManager\app\Http\Requests\ImportLanguageRequest;
use Modules\TranslationManager\app\Http\Requests\StoreLanguageRequest;
use Modules\TranslationManager\app\Http\Requests\UpdateTranslationsRequest;
use Modules\TranslationManager\app\Jobs\SyncMissingTranslationsJob;
use Modules\TranslationManager\app\Services\LanguageExportService;
use Modules\TranslationManager\app\Services\LanguageImportService;
use Modules\TranslationManager\app\Services\LanguageService;
use Modules\TranslationManager\app\Services\MissingTranslationService;
use Modules\TranslationManager\app\Services\TranslationReaderService;
use Modules\TranslationManager\app\Services\TranslationWriterService;
use Symfony\Component\Process\Process;

class TranslationManagerController extends Controller
{
    protected LanguageService $languageService;
    protected TranslationReaderService $reader;
    protected TranslationWriterService $writer;
    protected MissingTranslationService $missingService;
    protected LanguageExportService $exportService;
    protected LanguageImportService $importService;

    // Constructor
    public function __construct(
        LanguageService $languageService,
        TranslationReaderService $reader,
        TranslationWriterService $writer,
        MissingTranslationService $missingService,
        LanguageExportService $exportService,
        LanguageImportService $importService
    ) {
        $this->languageService = $languageService;
        $this->reader = $reader;
        $this->writer = $writer;
        $this->missingService = $missingService;
        $this->exportService = $exportService;
        $this->importService = $importService;
    }

    // Index page
    public function index(Request $request)
    {
        $languages = collect($this->languageService->getLanguages());

        $languages = $languages
            ->when($request->search, function ($collection, $search) {
                return $collection->filter(function ($language) use ($search) {
                    return str_contains(
                        strtolower($language['name']),
                        strtolower($search)
                    ) || str_contains(
                        strtolower($language['code']),
                        strtolower($search)
                    );
                });
            })
            ->values();

        $perPage = $request->integer('per_page', 10);

        $languages = new LengthAwarePaginator(
            $languages->forPage(
                $request->integer('page', 1),
                $perPage
            ),
            $languages->count(),
            $perPage,
            $request->integer('page', 1),
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $settings = Setting::where('status', 1)->first();

        return Inertia::render('admin/system/translations/index', [
            'languages' => $languages,
            'allLanguages' => $this->languageService->getLanguages(),
            'settings' => $settings,
        ]);
    }

    // Create language
    // public function create()
    // {
    //     $languages = $this->languageService->getLanguages();
    //     $settings = Setting::where('status', 1)->first();

    //     return view('translationmanager::create', compact('languages', 'settings'));
    // }

    // Store language
    public function store(StoreLanguageRequest $request)
    {
        $validated = $request->validated();

        $this->languageService->createLanguage(
            $validated['name'],
            $validated['code'],
            $validated['copy_from']
        );

        return redirect()->route('translation-manager.index')
            ->with('success', __("Language variant '{name}' added successfully.", ['name' => $validated['name']]));
    }

    // Edit language
    public function edit(Request $request, string $locale)
    {
        $settings = Setting::where('status', 1)->first();

        $languages = $this->languageService->getLanguages();

        if (!isset($languages[$locale])) {
            return redirect()
                ->route('translation-manager.index')
                ->with('failed', __('Language not found.'));
        }

        $files = $this->reader->getFiles($locale);

        $selectedGroup = $request->query('group', 'all');
        $selectedType = $request->query('type', 'all');
        $search = trim((string) $request->query('search', ''));

        $sourceLocale = config(
            'translation-manager.default_locale',
            'en'
        );

        /*
     * Keep the original Translation Manager logic.
     */
        $translations = [];
        $sourceTranslations = [];

        if ($selectedGroup === 'all') {
            foreach ($files as $file) {
                $group = $file['name'];
                $type = $file['type'];

                $source = $this->reader->readTranslations(
                    $sourceLocale,
                    $group,
                    $type
                );

                $target = $this->reader->readTranslations(
                    $locale,
                    $group,
                    $type
                );

                foreach ($source as $key => $value) {
                    $compoundKey = "{$group}|||{$type}|||{$key}";

                    $sourceTranslations[$compoundKey] = $value;

                    $translations[$compoundKey] =
                        $target[$key] ?? '';
                }
            }
        } else {
            $translations = $this->reader->readTranslations(
                $locale,
                $selectedGroup,
                $selectedType
            );

            $sourceTranslations = $this->reader->readTranslations(
                $sourceLocale,
                $selectedGroup,
                $selectedType
            );
        }

        /*
     * Keep the original search behaviour.
     */
        if ($search !== '') {
            $searchLower = strtolower($search);

            $filteredSource = [];

            foreach ($sourceTranslations as $compoundKey => $sourceValue) {
                $cleanKey = $compoundKey;

                if (str_contains($compoundKey, '|||')) {
                    [,, $cleanKey] = explode(
                        '|||',
                        $compoundKey,
                        3
                    );
                }

                $targetValue = $translations[$compoundKey] ?? '';

                $sourceText = is_array($sourceValue)
                    ? json_encode(
                        $sourceValue,
                        JSON_UNESCAPED_UNICODE
                    )
                    : (string) $sourceValue;

                $targetText = is_array($targetValue)
                    ? json_encode(
                        $targetValue,
                        JSON_UNESCAPED_UNICODE
                    )
                    : (string) $targetValue;

                if (
                    str_contains(
                        strtolower($cleanKey),
                        $searchLower
                    ) ||
                    str_contains(
                        strtolower($sourceText),
                        $searchLower
                    ) ||
                    str_contains(
                        strtolower($targetText),
                        $searchLower
                    )
                ) {
                    $filteredSource[$compoundKey] =
                        $sourceValue;
                }
            }

            $sourceTranslations = $filteredSource;
        }

        /*
     * Server-side pagination.
     */
        $perPage = $request->integer('per_page', 10);
        $perPage = max(1, min($perPage, 100));

        $page = max(
            1,
            $request->integer('page', 1)
        );

        $total = count($sourceTranslations);

        $paginatedItems = collect($sourceTranslations)
            ->slice(
                ($page - 1) * $perPage,
                $perPage
            );

        /*
     * Convert the current page into normal
     * DataTable rows.
     */
        $rows = $paginatedItems
            ->map(function ($sourceValue, $compoundKey) use (
                $translations,
                $selectedGroup,
                $selectedType
            ) {
                $group = $selectedGroup;
                $type = $selectedType;
                $cleanKey = $compoundKey;

                if (str_contains($compoundKey, '|||')) {
                    [$group, $type, $cleanKey] =
                        explode('|||', $compoundKey, 3);
                }

                $translation =
                    $translations[$compoundKey] ?? '';

                return [
                    'id' => $compoundKey,

                    'category' => $group,

                    'key' => $cleanKey,

                    'source' => is_array($sourceValue)
                        ? json_encode(
                            $sourceValue,
                            JSON_UNESCAPED_UNICODE
                        )
                        : (string) $sourceValue,

                    'translation' => is_array($translation)
                        ? json_encode(
                            $translation,
                            JSON_UNESCAPED_UNICODE
                        )
                        : (string) $translation,
                ];
            })
            ->values()
            ->all();

        $paginatedSourceKeys = new LengthAwarePaginator(
            $rows,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return Inertia::render(
            'admin/system/translations/edit',
            [
                'locale' => $locale,
                'languages' => array_values($languages),
                'files' => $files,
                'selectedGroup' => $selectedGroup,
                'selectedType' => $selectedType,
                'search' => $search,
                'sourceLocale' => $sourceLocale,
                'paginatedSourceKeys' => $paginatedSourceKeys,
                'settings' => $settings,
            ]
        );
    }

    // Update translations
    // public function update(UpdateTranslationsRequest $request, string $locale)
    // {
    //     dd($request->all());
    //     $group = $request->input('group');
    //     $type = $request->input('type');
    //     $updatedTranslations = $request->input('translations', []);

    //     $groupedUpdates = [];

    //     foreach ($updatedTranslations as $compoundKey => $value) {
    //         if (str_contains($compoundKey, '|||')) {
    //             [$actualGroup, $actualType, $cleanKey] = explode('|||', $compoundKey, 3);

    //             $groupedUpdates[$actualGroup]['type'] = $actualType;
    //             $groupedUpdates[$actualGroup]['translations'][$cleanKey] = $value;
    //         } else {
    //             $groupedUpdates[$group]['type'] = $type;
    //             $groupedUpdates[$group]['translations'][$compoundKey] = $value;
    //         }
    //     }

    //     foreach ($groupedUpdates as $targetGroup => $data) {
    //         $targetType = $data['type'];
    //         $translationsToSave = $data['translations'];

    //         $existingTranslations = $this->reader->readTranslations($locale, $targetGroup, $targetType);
    //         $mergedTranslations = array_merge($existingTranslations, $translationsToSave);

    //         $this->writer->writeTranslations($locale, $targetGroup, $targetType, $mergedTranslations);
    //     }

    //     return redirect()->route('translation-manager.edit', [
    //         'locale' => $locale,
    //         'group' => $group,
    //         'type' => $type,
    //         'page' => $request->input('page', 1),
    //         'search' => $request->input('search')
    //     ])->with('success', __('Translations saved successfully.'));
    // }

    public function update(UpdateTranslationsRequest $request, string $locale)
    {
        $group = $request->input('group');
        $type = $request->input('type');
        $updatedTranslations = $request->input('translations', []);

        $groupedUpdates = [];

        foreach ($updatedTranslations as $compoundKey => $value) {
            if (str_contains($compoundKey, '|||')) {
                [$actualGroup, $actualType, $cleanKey] =
                    explode('|||', $compoundKey, 3);

                $groupedUpdates[$actualGroup]['type'] = $actualType;

                $groupedUpdates[$actualGroup]['translations'][$cleanKey] =
                    $value;
            } else {
                $groupedUpdates[$group]['type'] = $type;

                $groupedUpdates[$group]['translations'][$compoundKey] =
                    $value;
            }
        }

        foreach ($groupedUpdates as $targetGroup => $data) {
            $targetType = $data['type'];
            $translationsToSave = $data['translations'];

            $existingTranslations = $this->reader->readTranslations(
                $locale,
                $targetGroup,
                $targetType
            );

            $mergedTranslations = array_merge(
                $existingTranslations,
                $translationsToSave
            );

            $saved = $this->writer->writeTranslations(
                $locale,
                $targetGroup,
                $targetType,
                $mergedTranslations
            );
        }

        return redirect()->route('translation-manager.index', [
            'locale' => $locale,
            'group' => $group,
            'type' => $type,
            'page' => $request->input('page', 1),
            'search' => $request->input('search')
        ])->with('success', __('Translations saved successfully.'));
    }

    // Add new word
    public function addKey(Request $request)
    {
        $request->validate([
            'source_value' => 'required|string',
            'target_value' => 'nullable|string',
            'locale' => 'required|string',
        ]);

        $sourceVal = trim($request->input('source_value'));
        $targetVal = $request->input('target_value');
        $targetVal = $targetVal !== null ? trim($targetVal) : null;
        $locale = $request->input('locale');
        $sourceLocale = config('translation-manager.default_locale', 'en');

        if ($sourceVal === '') {
            $message = __('Translation key cannot be empty.');

            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : redirect()->back()->with('failed', $message);
        }

        $key = $sourceVal;
        $type = 'json';

        try {
            Log::info("addKey: adding '{$key}' for locale {$locale}");

            $sourceTrans = $this->reader->readTranslations($sourceLocale, $sourceLocale, $type);
            $sourceTrans[$key] = $sourceVal;
            $this->writer->writeTranslations($sourceLocale, $sourceLocale, $type, $sourceTrans);

            $finalTargetVal = $sourceVal;

            if ($locale !== $sourceLocale) {
                $finalTargetVal = ($targetVal === null || $targetVal === '')
                    ? $this->missingService->translateValue($sourceVal, $sourceLocale, $locale)
                    : $targetVal;

                $targetTrans = $this->reader->readTranslations($locale, $locale, $type);
                $targetTrans[$key] = $finalTargetVal;
                $this->writer->writeTranslations($locale, $locale, $type, $targetTrans);
            }

            Log::info("addKey: successfully added '{$key}' -> '{$finalTargetVal}' for {$locale}");

            $message = __('New translation key added successfully.');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'key' => $key,
                    'target_value' => $finalTargetVal,
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            Log::error("addKey FAILED for '{$key}' locale {$locale}: " . $e->getMessage());

            $message = 'Error adding translation key: ' . $e->getMessage();

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }

            return redirect()->back()->with('failed', $message);
        }
    }

    // Missing keys page
    public function missing(Request $request, string $locale)
    {
        $settings = Setting::where('status', 1)->first();

        $sourceLocale = $request->query('source', config('translation-manager.default_locale', 'en'));
        $missingKeys = $this->missingService->detectMissingKeys($sourceLocale, $locale);

        $syncStatus = Cache::get(
            SyncMissingTranslationsJob::statusCacheKey($locale),
            ['state' => 'idle', 'message' => trans('No sync in progress.')]
        );

        $syncInProgress = in_array($syncStatus['state'] ?? '', ['queued', 'running'], true);

        return view('translationmanager::missing', compact(
            'locale',
            'sourceLocale',
            'missingKeys',
            'settings',
            'syncStatus',
            'syncInProgress'
        ));
    }

    // Sync missing keys page
    public function syncMissing(Request $request, string $locale)
    {
        $sourceLocale = $request->post('source_locale', config('translation-manager.default_locale', 'en'));

        Log::info("syncMissing triggered: {$sourceLocale} -> {$locale}");

        Cache::put(
            SyncMissingTranslationsJob::statusCacheKey($locale),
            ['state' => 'queued', 'message' => 'Sync queued…'],
            now()->addHour()
        );

        SyncMissingTranslationsJob::dispatch($sourceLocale, $locale);

        Log::info("SyncMissingTranslationsJob dispatched for {$locale}");

        // On Windows local dev, spawning queue:work from inside a web
        // request is unreliable — the child process can die along with
        // the parent PHP-FPM/dev-server worker. Only auto-spawn on
        // non-Windows; on Windows, run "php artisan queue:work" manually
        // in its own terminal instead.
        if (PHP_OS_FAMILY !== 'Windows') {
            $this->startQueueWorker();
        } else {
            Log::info('Skipping auto-spawned queue:work on Windows — run it manually in a terminal.');
        }

        return redirect()->route('translation-manager.edit', $locale)
            ->with('success', trans('Syncing missing translations in the background - this can take a while for many keys. You can keep working; the page will reflect the update once it finishes.'));
    }

    /**
     * Spawns a one-shot "php artisan queue:work" process so queued
     * translation jobs run right away instead of waiting on cron/Supervisor.
     * Requires proc_open/exec to be enabled on the host. Not used on Windows.
     */
    protected function startQueueWorker(): void
    {
        $process = new Process([
            PHP_BINARY,
            base_path('artisan'),
            'queue:work',
            '--queue=default',
            '--stop-when-empty',
            '--tries=1',
            '--timeout=1800',
        ]);

        $process->setWorkingDirectory(base_path());
        $process->setTimeout(null);
        $process->disableOutput();

        try {
            $process->start();
            Log::info('queue:work background process started, PID: ' . $process->getPid());
        } catch (\Throwable $e) {
            Log::error('Failed to start queue:work: ' . $e->getMessage());
        }
    }

    /**
     * Returns active sync messages across all locales, for the global
     * info alert to poll and update live (used by the site-wide layout).
     */
    public function activeSyncStatuses()
    {
        $languages = $this->languageService->getLanguages();
        $activeMessages = [];

        foreach ($languages as $language) {
            $locale = is_array($language) ? $language['code'] : $language->code;

            $status = Cache::get(SyncMissingTranslationsJob::statusCacheKey($locale));

            if (!$status) {
                continue;
            }

            $state = $status['state'] ?? '';

            if (in_array($state, ['queued', 'running'], true)) {
                $activeMessages[] = strtoupper($locale) . ': ' . ($status['message'] ?? 'Sync in progress…');
            }
        }

        return response()->json([
            'active' => !empty($activeMessages),
            'message' => implode(' | ', $activeMessages),
        ]);
    }

    /**
     * Poll endpoint for the frontend to check on a background sync's progress.
     * e.g. GET /translation-manager/{locale}/sync-status
     */
    public function syncStatus(string $locale)
    {
        $status = Cache::get(
            SyncMissingTranslationsJob::statusCacheKey($locale),
            ['state' => 'idle', 'message' => 'No sync in progress.']
        );

        return response()->json($status);
    }

    // Export language pack
    public function export(string $locale)
    {
        try {
            $zipPath = $this->exportService->exportLocale($locale);
            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->route('translation-manager.index')
                ->with('failed', 'Error exporting files: ' . $e->getMessage());
        }
    }

    // Import language pack
    public function import(ImportLanguageRequest $request)
    {
        $validated = $request->validated();
        $zipFile = $request->file('zip_file');

        try {
            $this->importService->importLocale($validated['locale'], $zipFile->getRealPath());
            return redirect()->route('translation-manager.index')
                ->with('success', trans('Translations successfully updated via imported ZIP package.'));
        } catch (\Exception $e) {
            return redirect()->route('translation-manager.index')
                ->with('failed', __('Error during package extraction: ' . $e->getMessage()));
        }
    }

    // Delete language
    public function destroy(string $locale)
    {
        $this->languageService->deleteLanguage($locale);
        return redirect()->route('translation-manager.index')
            ->with('success', __("Language files for '{locale}' removed.", ['locale' => $locale]));
    }
}
