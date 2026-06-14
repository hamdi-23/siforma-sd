<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\DataExport;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    // Get all exports older than 7 days
    $oldExports = DataExport::where('created_at', '<', now()->subDays(7))->get();

    foreach ($oldExports as $export) {
        // Delete the physical file if it exists
        if ($export->file_path && Storage::disk('public')->exists($export->file_path)) {
            Storage::disk('public')->delete($export->file_path);
        }
        
        // Delete the database record
        $export->delete();
    }
})->daily()->name('cleanup:old-exports')->onOneServer();
