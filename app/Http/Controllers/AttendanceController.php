<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataExport;
use App\Jobs\ProcessAttendanceExport;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            // Guru melihat presensi mereka sendiri
            $attendances = $user->teacher->attendances()
                ->orderBy('date', 'desc')
                ->paginate(10);
        } else {
            // Admin/Principal melihat semua presensi
            $attendances = Attendance::with('teacher.user')
                ->orderBy('date', 'desc')
                ->paginate(15);
        }

        return view('attendance.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function create()
    {
        // Hanya teacher yang bisa membuat/update presensi sendiri
        if (!Auth::user()->isTeacher()) {
            abort(403);
        }

        return view('attendance.create');
    }

    /**
     * Store a newly created attendance record.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isTeacher()) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'status' => 'required|in:present,late,absent,sick,leave',
            'notes' => 'nullable|string',
            'latitude' => 'required_if:status,present,late|numeric',
            'longitude' => 'required_if:status,present,late|numeric',
        ]);

        $validated['teacher_id'] = $user->teacher->id;

        // Cek duplikasi absensi
        $existing = Attendance::where('teacher_id', $user->teacher->id)
                             ->where('date', $validated['date'])
                             ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors(['date' => 'Anda sudah mengisi presensi untuk tanggal ini.']);
        }

        // Automatic time capture & GPS Check
        if (in_array($validated['status'], ['present', 'late'])) {
            $schoolLat = Setting::where('key', 'school_latitude')->first()->value ?? '-6.200000';
            $schoolLng = Setting::where('key', 'school_longitude')->first()->value ?? '106.816666';
            $radius = Setting::where('key', 'allowed_radius_meters')->first()->value ?? '100';

            $distance = $this->calculateDistance($validated['latitude'], $validated['longitude'], $schoolLat, $schoolLng);

            if ($distance > $radius) {
                return back()
                    ->withInput()
                    ->withErrors(['location' => 'Gagal! Anda terdeteksi berada sejauh ' . round($distance) . ' meter dari sekolah. Anda harus berada dalam radius ' . $radius . ' meter.']);
            }

            $currentTime = now()->format('H:i');
            $validated['check_in_time'] = $currentTime;

            // Enforce rules
            $timeInRule = Setting::where('key', 'attendance_time_in')->first()->value ?? '07:30';
            if ($currentTime > $timeInRule) {
                $validated['status'] = 'late';
            } else {
                $validated['status'] = 'present';
            }
        } else {
            $validated['check_in_time'] = null;
            $validated['check_out_time'] = null;
        }

        // Remove latitude and longitude before saving as they are not in the model
        unset($validated['latitude'], $validated['longitude']);

        Attendance::create($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Data presensi berhasil diperbarui.');
    }

    /**
     * Check Out logic.
     */
    public function checkout(Request $request, Attendance $attendance)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        if ($user->isTeacher() && $attendance->teacher_id !== $user->teacher->id) {
            abort(403);
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Anda sudah melakukan Check-Out sebelumnya.');
        }

        $schoolLat = Setting::where('key', 'school_latitude')->first()->value ?? '-6.200000';
        $schoolLng = Setting::where('key', 'school_longitude')->first()->value ?? '106.816666';
        $radius = Setting::where('key', 'allowed_radius_meters')->first()->value ?? '100';

        $distance = $this->calculateDistance($request->latitude, $request->longitude, $schoolLat, $schoolLng);

        if ($distance > $radius) {
            return back()->with('error', 'Check-Out gagal! Anda terdeteksi berada sejauh ' . round($distance) . ' meter dari sekolah.');
        }

        $currentTime = now()->format('H:i');
        $attendance->check_out_time = $currentTime;

        $timeOutRule = Setting::where('key', 'attendance_time_out')->first()->value ?? '14:00';
        if ($currentTime < $timeOutRule) {
            $note = 'Pulang terlalu cepat (Tidak tepat waktu)';
            $attendance->notes = $attendance->notes ? $attendance->notes . ' | ' . $note : $note;
        }

        $attendance->save();

        return back()->with('success', 'Berhasil melakukan Check-Out jam ' . $currentTime);
    }

    /**
     * Display the specified attendance record.
     */
    public function show(Attendance $attendance)
    {
        $user = Auth::user();

        // Guru hanya bisa melihat presensi mereka sendiri
        if ($user->isTeacher() && $user->teacher->id !== $attendance->teacher_id) {
            abort(403);
        }

        return view('attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified attendance record.
     */
    public function edit(Attendance $attendance)
    {
        $user = Auth::user();

        // Guru hanya bisa edit presensi mereka sendiri
        if ($user->isTeacher() && $user->teacher->id !== $attendance->teacher_id) {
            abort(403);
        }

        return view('attendance.edit', compact('attendance'));
    }

    /**
     * Update the specified attendance record.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $user = Auth::user();

        if ($user->isTeacher() && $user->teacher->id !== $attendance->teacher_id) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'status' => 'required|in:present,late,absent,sick,leave',
            'notes' => 'nullable|string',
        ]);

        // Cek duplikasi jika tanggal diubah
        if ($validated['date'] != $attendance->date) {
            $existing = Attendance::where('teacher_id', $attendance->teacher_id)
                                 ->where('date', $validated['date'])
                                 ->first();
            if ($existing) {
                return back()
                    ->withInput()
                    ->withErrors(['date' => 'Presensi untuk tanggal ini sudah ada.']);
            }
        }

        // Jika mengubah status dari hadir ke absen/sakit/cuti
        if (!in_array($validated['status'], ['present', 'late'])) {
            $validated['check_in_time'] = null;
            $validated['check_out_time'] = null;
        } elseif (in_array($validated['status'], ['present', 'late']) && !$attendance->check_in_time) {
            // Jika sebelumnya absen, lalu diubah ke hadir, capture waktu sekarang
            $currentTime = now()->format('H:i');
            $validated['check_in_time'] = $currentTime;

            $timeInRule = Setting::where('key', 'attendance_time_in')->first()->value ?? '07:30';
            if ($currentTime > $timeInRule) {
                $validated['status'] = 'late';
            } else {
                $validated['status'] = 'present';
            }
        }

        $attendance->update($validated);

        return redirect()->route('attendance.show', $attendance)
            ->with('success', 'Presensi berhasil diperbarui.');
    }

    /**
     * Get attendance statistics for teacher or period.
     */
    public function statistics(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());

        if ($user->isTeacher()) {
            $stats = $user->teacher->attendances()
                ->byDateRange($startDate, $endDate)
                ->selectRaw("
                    COUNT(*) as total_days,
                    SUM(CASE WHEN status IN ('present', 'late') THEN 1 ELSE 0 END) as present_days,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_days,
                    SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick_days,
                    SUM(CASE WHEN status = 'leave' THEN 1 ELSE 0 END) as leave_days
                ")
                ->first();
        } else {
            $stats = Attendance::byDateRange($startDate, $endDate)
                ->selectRaw("
                    COUNT(*) as total_days,
                    SUM(CASE WHEN status IN ('present', 'late') THEN 1 ELSE 0 END) as present_days,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_days,
                    SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick_days,
                    SUM(CASE WHEN status = 'leave' THEN 1 ELSE 0 END) as leave_days
                ")
                ->first();
        }

        return view('attendance.statistics', [
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Calculate distance between two coordinates in meters.
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
        
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }

    /**
     * Export attendance data to Excel.
     */
    public function exportExcel(Request $request)
    {
        $export = DataExport::create([
            'user_id' => Auth::id(),
            'type' => 'excel',
            'status' => 'pending'
        ]);

        ProcessAttendanceExport::dispatch($export, $request->all());

        return redirect()->route('exports.index')
            ->with('success', 'Ekspor Excel sedang diproses di background. Anda dapat mengunduhnya saat status selesai.');
    }

    /**
     * Export attendance data to PDF.
     */
    public function exportPdf(Request $request)
    {
        $export = DataExport::create([
            'user_id' => Auth::id(),
            'type' => 'pdf',
            'status' => 'pending'
        ]);

        ProcessAttendanceExport::dispatch($export, $request->all());

        return redirect()->route('exports.index')
            ->with('success', 'Ekspor PDF sedang diproses di background. Anda dapat mengunduhnya saat status selesai.');
    }
}
