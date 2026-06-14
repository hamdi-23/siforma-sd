<?php

namespace App\Http\Controllers;

use App\Models\DataExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DataExportController extends Controller
{
    /**
     * Display a listing of the exports.
     */
    public function index()
    {
        $exports = DataExport::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('exports.index', compact('exports'));
    }

    /**
     * Download the specified export.
     */
    public function download(DataExport $export)
    {
        // Check authorization
        if ($export->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($export->status !== 'completed' || !$export->file_path) {
            return redirect()->back()->with('error', 'File ekspor belum siap atau gagal diproses.');
        }

        if (!Storage::disk('public')->exists($export->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server. Mungkin sudah dihapus.');
        }

        return Storage::disk('public')->download($export->file_path);
    }
}
