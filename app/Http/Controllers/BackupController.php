<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $backupFiles = $this->getBackupFiles();
        return view('settings.backup', compact('backupFiles'));
    }

    public function create()
    {
        try {
            // Simple database backup implementation
            $this->createDatabaseBackup();
            
            return redirect()->route('settings.backup.index')
                            ->with('success', 'Backup created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('settings.backup.index')
                            ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    private function createDatabaseBackup()
    {
        $tables = DB::select('SHOW TABLES');
        $backup = [];
        
        foreach ($tables as $table) {
            $tableName = reset($table);
            $tableData = DB::table($tableName)->get();
            $backup[$tableName] = $tableData;
        }
        
        $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.json';
        Storage::put('backups/' . $filename, json_encode($backup, JSON_PRETTY_PRINT));
    }

    private function getBackupFiles()
    {
        $files = Storage::files('backups');
        $backupFiles = [];
        
        foreach ($files as $file) {
            $backupFiles[] = [
                'name' => basename($file),
                'size' => $this->formatSize(Storage::size($file)),
                'date' => Carbon::createFromTimestamp(Storage::lastModified($file))->format('Y-m-d H:i:s')
            ];
        }
        
        return $backupFiles;
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function download($filename)
    {
        if (Storage::exists('backups/' . $filename)) {
            return Storage::download('backups/' . $filename);
        }
        
        return redirect()->route('settings.backup.index')
                        ->with('error', 'Backup file not found.');
    }

    public function restore(Request $request)
    {
        // Implement restore logic here
        return redirect()->route('settings.backup.index')
                        ->with('success', 'Restore functionality will be implemented soon.');
    }
}