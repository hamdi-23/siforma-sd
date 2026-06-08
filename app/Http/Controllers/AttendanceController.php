<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,late,absent,sick,leave',
            'notes' => 'nullable|string',
        ]);

        $validated['teacher_id'] = $user->teacher->id;

        Attendance::create($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Presensi berhasil dicatat.');
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
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,late,absent,sick,leave',
            'notes' => 'nullable|string',
        ]);

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
}
