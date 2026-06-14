<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isPrincipal()) {
            abort(403);
        }

        // Ambil nilai setting yang ada, atau gunakan nilai default jika belum ada
        $settings = [
            'attendance_time_in' => Setting::where('key', 'attendance_time_in')->first()->value ?? '07:30',
            'attendance_time_out' => Setting::where('key', 'attendance_time_out')->first()->value ?? '14:00',
            'school_latitude' => Setting::where('key', 'school_latitude')->first()->value ?? '-6.200000',
            'school_longitude' => Setting::where('key', 'school_longitude')->first()->value ?? '106.816666',
            'allowed_radius_meters' => Setting::where('key', 'allowed_radius_meters')->first()->value ?? '100',
        ];

        return view('setting.index', compact('settings'));
    }

    /**
     * Store/Update settings.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isPrincipal()) {
            abort(403);
        }

        $validated = $request->validate([
            'attendance_time_in' => 'required|date_format:H:i',
            'attendance_time_out' => 'required|date_format:H:i',
            'school_latitude' => 'required|numeric',
            'school_longitude' => 'required|numeric',
            'allowed_radius_meters' => 'required|integer|min:10',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('setting.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
