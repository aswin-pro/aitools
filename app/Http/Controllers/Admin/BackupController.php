<?php

namespace App\Http\Controllers\Admin;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Backup;
use App\Models\Config;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class BackupController extends Controller
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

    // Get File Backup
    // public function index(Request $request)
    // {
    //     // Backups
    //     if ($request->ajax()) {
    //         $data = Backup::where('status', 1)->where('type', 'file')->get();

    //         // Files Backups
    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('version', function ($row) {
    //                 return $row->version;
    //             })
    //             ->addColumn('status', function ($row) {
    //                 return $row->status == 0
    //                     ? '<span class="badge bg-red text-white text-white">' . __('Not Backuped') . '</span>'
    //                     : '<span class="badge bg-green text-white text-white">' . __('Backuped') . '</span>';
    //             })
    //             ->addColumn('action', function ($row) {
    //                 $downloadButton = '<a href="' . route('admin.backup.download') . '?id=' . $row->backup_id . '" class="dropdown-item">' . __('Download') . '</a>';

    //                 return '<span class="dropdown">
    //                             <button class="btn small-btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">' . __('Actions') . '</button>
    //                             <div class="actions dropdown-menu dropdown-menu-end">
    //                                 <a class="dropdown-item" href="#" onclick="deleteBackup(\'' . $row->backup_id . '\'); return false;">' . __('Delete') . '</a>
    //                             </div>
    //                         </span>';
    //             })
    //             ->rawColumns(['version', 'status', 'action'])
    //             ->make(true);
    //     }

    //     // Queries
    //     $settings = Setting::where('status', 1)->first();
    //     $config = Config::get();

    //     return view('admin.pages.backups.index', compact('settings', 'config'));
    // }

    // public function index()
    // {
    //     $fileBackups = Backup::where('status', 1)
    //         ->where('type', 'file')
    //         ->orderBy('id', 'desc')
    //         ->get();

    //     $databaseBackups = Backup::where('status', 1)
    //         ->where('type', 'database')
    //         ->orderBy('id', 'desc')
    //         ->get();

    //     $settings = Setting::where('status', 1)->first();

    //     $config = Config::get();

    //     return Inertia::render('admin/system/backups/index', [
    //         'fileBackups' => $fileBackups,
    //         'databaseBackups' => $databaseBackups,
    //         'settings' => $settings,
    //         'config' => $config,
    //     ]);
    // }


    public function index(Request $request)
{
    $fileSearch = $request->input('file_search');

    $databaseSearch = $request->input('database_search');

    $fileBackups = Backup::where('status', 1)
        ->where('type', 'file')
        ->when($fileSearch, function ($query) use ($fileSearch) {
            $query->where(function ($query) use ($fileSearch) {
                $query->where('version', 'like', "%{$fileSearch}%")
                   ->orWhereDate('created_at', "%{$fileSearch}%"); 
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(
            $request->integer('file_per_page', 10),
            ['*'],
            'file_page'
        )
        ->withQueryString();

    $databaseBackups = Backup::where('status', 1)
        ->where('type', 'database')
        ->when($databaseSearch, function ($query) use ($databaseSearch) {
            $query->where(function ($query) use ($databaseSearch) {
                $query->where('version', 'like', "%{$databaseSearch}%")
                    ->orWhere('created_at', 'like', "%{$databaseSearch}%");
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(
            $request->integer('database_per_page', 10),
            ['*'],
            'database_page'
        )
        ->withQueryString();

    $settings = Setting::where('status', 1)->first();

    $config = Config::get();

    return Inertia::render('admin/system/backups/index', [
        'fileBackups' => $fileBackups,
        'databaseBackups' => $databaseBackups,
        'settings' => $settings,
        'config' => $config,
        'filters' => [
            'file_search' => $fileSearch,
            'database_search' => $databaseSearch,
        ],
    ]);
}

    // Get Database Backup
    public function getDatabaseBackup(Request $request)
    {
        // Database Backups
        if ($request->ajax()) {
            $data = Backup::where('status', 1)->where('type', 'database')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('version', function ($row) {
                    return $row->version;
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 0
                        ? '<span class="badge bg-red text-white text-white">' . __('Not Backuped') . '</span>'
                        : '<span class="badge bg-green text-white text-white">' . __('Backuped') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $downloadButton = '<a href="' . route('admin.backup.download') . '?id=' . $row->backup_id . '" class="dropdown-item">' . __('Download') . '</a>';

                    return '<a class="btn act-btn dropdown-toggle actions-buttons-column" href="#" data-toggle="dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">' . __('Actions') . '</a>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <div class="nav-item dropdown">
                                    <a class="dropdown-item" href="#" onclick="deleteBackup(\'' . $row->backup_id . '\'); return false;">' . __('Delete') . '</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['version', 'status', 'action'])
                ->make(true);
        }

        // Queries
        $settings = Setting::where('status', 1)->first();
        $config = Config::get();

        return view('admin.pages.backups.index', compact('settings', 'config'));
    }

    // Create File Backup
    public function createFileBackup()


    {
            set_time_limit(300);

        // Fetch the application version from the config table
        $version = Config::where('config_key', 'app_version')->value('config_value');

        // Check if the version exists
        if (!$version) {
            return redirect()->route('dashboard.admin.system.backups')->with('failed', trans('Version not found!'));
        }

        try {
            // Step 1: Prepare file backup
            $zipVersion = str_replace('.', '', $version);
            $zipFileName = 'file_backup_v' . $zipVersion . '_' . date('Y-m-d') . '.zip';
            $zipFilePath = storage_path('app/backups/' . $zipFileName);

            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Define the folder containing product files
                $productFolder = base_path(); // Adjusted folder path
                if (is_dir($productFolder)) {
                    $this->addFolderToZip($productFolder, $zip, $version);
                } else {
                    return redirect()->route('dashboard.admin.system.backups')->with('failed', trans('Backup folder not found!'));
                }
                $zip->close();
            } else {
                return redirect()->route('dashboard.admin.system.backups')->with('failed', trans('First, back up the database, and then try to create a new backup.'));
            }

            // Step 5: Store backup details in the database
            $backup = new Backup;
            $backup->backup_id = uniqid();
            $backup->type = 'file';
            $backup->version = $version;
            $backup->file_name = $zipFileName;
            $backup->path = 'backups/' . $zipFileName; // Corrected to store the relative path
            $backup->save();

            return redirect()->route('dashboard.admin.system.backups')->with('success', trans('Created!'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard.admin.system.backups')->with('failed', trans('First, back up the database, and then try to create a new backup.'));
        }
    }

    // Create Database Backup
    public function createDatabaseBackup()
    {
        // Fetch the application version from the config table
        $version = Config::where('config_key', 'app_version')->value('config_value');

        // Check if the version exists
        if (!$version) {
            return redirect()->route('dashboard.admin.system.backups')->with('failed', trans('Version not found!'));
        }

        try {
            // Get database connection settings from .env
            $dbName = env('DB_DATABASE');

            // Generate a backup file name with timestamp
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');

            // File name and path
            $includeVersionName = str_replace('.', '', $version);
            $backupFileName = "database_backup_v{$includeVersionName}_{$timestamp}.sql";

            // Create a new backup record
            $backup = new Backup();
            $backup->backup_id = uniqid();
            $backup->type = 'database';
            $backup->version = $version;
            $backup->file_name = $backupFileName;
            $backup->path = 'backups/database/' . $backupFileName; // Corrected to store the relative path
            $backup->save();

            // Start the SQL backup file content
            $backupContent = "-- Database backup for {$dbName}\n";
            $backupContent .= "-- Created on {$timestamp}\n\n";

            // Get all tables from the database
            $tables = DB::select('SHOW TABLES');

            foreach ($tables as $table) {
                $tableName = $table->{"Tables_in_{$dbName}"};

                // Escape the table name to handle reserved words
                $escapedTableName = "`{$tableName}`";  // Correct escape with backticks

                // Get the CREATE TABLE statement with escaped table name
                $createTable = DB::select("SHOW CREATE TABLE {$escapedTableName}");
                $backupContent .= "--\n-- Create table {$tableName}\n--\n";
                $backupContent .= $createTable[0]->{"Create Table"} . ";\n\n";

                // Get all rows from the table
                $rows = DB::table(str_replace('`', '', $escapedTableName))->get();

                // Insert rows into the backup
                foreach ($rows as $row) {
                    $columns = array_keys((array) $row); // Get column names
                    $values = array_map(function ($value) {
                        if (is_null($value)) {
                            return 'NULL'; // Use SQL NULL for null values
                        }
                        return DB::getPdo()->quote($value); // Quote other values
                    }, (array) $row);

                    $backupContent .= "INSERT INTO {$escapedTableName} (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ");\n";
                }

                $backupContent .= "\n";
            }

            // Save the backup content to the file
            Storage::put("backups/database/{$backupFileName}", $backupContent);

            // Return the success message
            return redirect()->route('dashboard.admin.system.backups')->with('success', __('Created!'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard.admin.system.backups')->with('failed', $e->getMessage());
        }
    }

    // Restore Backup File
    public function restore(Request $request)
    {
        // Get the backup
        $backup = Backup::where('backup_id', $request->query('id'))->first();

        if (!$backup) {
            return redirect()->route('admin.backups')->with('failed', __('Not Found!'));
        }

        try {
            if ($backup->type == 'file') {
                // Unzip the file
                $zip = new ZipArchive();
                $zip->open(storage_path('app/' . $backup->path));
                $zip->extractTo(base_path());
                $zip->close();
            } else {
                // Import database
                $sql = Storage::get($backup->path);

                // Disable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                // Drop all tables except the ones to skip
                $tablesToSkip = ['users', 'config', 'settings'];
                $tables = DB::select('SHOW TABLES');
                $dbName = env('DB_DATABASE');
                foreach ($tables as $table) {
                    $tableName = $table->{"Tables_in_{$dbName}"};
                    if (!in_array($tableName, $tablesToSkip)) {
                        Schema::drop($tableName);
                    }
                }

                // Execute the SQL file
                DB::unprepared($sql);

                // Re-enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.backups')->with('failed', __('Restore failed: ' . $e->getMessage()));
        }

        return redirect()->route('admin.backups')->with('success', __($backup->version . ' restored successfully!'));
    }

    // Download Backup
    public function download(Request $request)
    {
        // Get the backup
        $backup = Backup::where('backup_id', $request->query('id'))->first();

        if ($backup) {
            // Zip existing files
            try {
                return response()->download(storage_path('app/' . $backup->path));
            } catch (\Exception $e) {
                return redirect()->route('admin.backups')->with('failed', trans('Not Found!'));
            }
        }

        return redirect()->route('admin.backups')->with('failed', trans('Not Found!'));
    }

    // Delete backup
    public function delete(Request $request)
    {
        // Get the backup
        $backup = Backup::where('backup_id', $request->query('id'))->first();

        if ($backup) {
            // Delete backup
            try {
                unlink(storage_path('app/' . $backup->path));
            } catch (\Exception $e) {
                // Check if the backup file not found
                if (strpos($e->getMessage(), 'No such file or directory') !== false) {
                    // Delete backup details from the database
                    Backup::where('backup_id', $request->query('id'))->update(['status' => 0]);

                    return redirect()->route('admin.backups')->with('success', trans('Deleted!'));
                }
                return redirect()->route('admin.backups')->with('failed', trans('Failed to delete backup!'));
            }

            // Delete backup details from the database
            Backup::where('backup_id', $request->query('id'))->update(['status' => 0]);

            return redirect()->route('dashboard.admin.system.backups')->with('success', trans('Deleted!'));
        }
        return redirect()->route('dashboard.admin.system.backups')->with('failed', trans('Not Found!'));
    }

    // Helper function to add folders to a zip file
    //     private function addFolderToZip($folder, $zip, $folderInZip)
    //     {
    //         $files = scandir($folder);

    //         foreach ($files as $file) {
    //             if ($file === '.' || $file === '..') {
    //                 continue;
    //             }

    //             $filePath = $folder . DIRECTORY_SEPARATOR . $file;

    //             if (is_dir($filePath)) {
    //                 $this->addFolderToZip($filePath, $zip, $folderInZip . '/' . $file);
    //             } else {
    //                 $zip->addFile($filePath, $folderInZip . '/' . $file);
    //             }
    //         }
    //     }
    // }

    private function addFolderToZip($folder, $zip, $folderInZip)
    {
        $excludeFolders = [
            'node_modules',
            'vendor',
            '.git',
        ];

        $files = scandir($folder);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (in_array($file, $excludeFolders, true)) {
                continue;
            }

            $filePath = $folder . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                $this->addFolderToZip(
                    $filePath,
                    $zip,
                    $folderInZip . '/' . $file
                );
            } else {
                $zip->addFile(
                    $filePath,
                    $folderInZip . '/' . $file
                );
            }
        }
    }
}
