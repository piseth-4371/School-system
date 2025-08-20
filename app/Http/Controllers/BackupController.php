<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backupFiles = Storage::files('backups');
        return view('settings.backup', compact('backupFiles'));
    }

    public function create()
    {
        Artisan::call('backup:run');
        return redirect()->route('backup.index')
                        ->with('success', 'Backup created successfully.');
    }

    public function download($filename)
    {
        return Storage::download('backups/' . $filename);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file'
        ]);

        // Handle restore logic here
        return redirect()->route('backup.index')
                        ->with('success', 'Restore process started.');
    }
}