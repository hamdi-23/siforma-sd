<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\DailyReport;
use App\Models\MonthlyRecap;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonthlyRecapController extends Controller
{
    /**
     * Display a listing of monthly recaps.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            $recaps = $user->teacher->monthlyRecaps()
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->paginate(12);
        } else {
            $recaps = MonthlyRecap::with('teacher.user')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->paginate(15);
        }

        return view('monthly-recap.index', compact('recaps'));
    }

    /**
     * Display the specified monthly recap.
     */
    public function show(MonthlyRecap $monthlyRecap)
    {
        $user = Auth::user();

        if ($user->isTeacher() && $user->teacher->id !== $monthlyRecap->teacher_id) {
            abort(403);
        }

        return view('monthly-recap.show', compact('monthlyRecap'));
    }

    /**
     * Generate monthly recap for a specific teacher, year and month.
     */
    public function generate($teacher_id, $year, $month)
    {
        $user = Auth::user();

        // Hanya admin/principal yang bisa generate recap
        if (!$user->isAdmin() && !$user->isPrincipal()) {
            abort(403);
        }

        $teacher = Teacher::findOrFail($teacher_id);

        // Cek jika recap sudah ada
        $recap = MonthlyRecap::byYearMonth($year, $month)
            ->where('teacher_id', $teacher_id)
            ->first();

        if ($recap) {
            return redirect()->route('monthly-recap.show', $recap)
                ->with('info', 'Recap untuk bulan ini sudah ada.');
        }

        // Hitung statistik attendance
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate)
            ->endOfMonth()
            ->toDateString();

        $attendances = $teacher->attendances()
            ->byDateRange($startDate, $endDate)
            ->get();

        $presentDays = $attendances->whereIn('status', ['present', 'late'])->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateDays = $attendances->where('status', 'late')->count();
        $sickDays = $attendances->where('status', 'sick')->count();
        $leaveDays = $attendances->where('status', 'leave')->count();
        $totalDays = $attendances->count();

        // Hitung statistik laporan harian
        $reports = $teacher->dailyReports()
            ->byDateRange($startDate, $endDate)
            ->get();

        $submittedReports = $reports->where('status', '!=', 'draft')->count();
        $reviewedReports = $reports->where('status', 'reviewed')->count();

        // Hitung persentase attendance
        $attendancePercentage = $totalDays > 0
            ? round(($presentDays / $totalDays) * 100, 2)
            : 0;

        // Generate summary
        $summary = "Rekap bulan {$month}/{$year} untuk guru {$teacher->user->name}. " .
                   "Kehadiran: {$presentDays} hari hadir, {$absentDays} hari absen, " .
                   "{$lateDays} hari terlambat, {$sickDays} hari sakit, {$leaveDays} hari cuti. " .
                   "Laporan: {$submittedReports} laporan dikirim, {$reviewedReports} laporan di-review.";

        $recap = MonthlyRecap::create([
            'teacher_id' => $teacher_id,
            'year' => $year,
            'month' => $month,
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'sick_days' => $sickDays,
            'leave_days' => $leaveDays,
            'total_reports_submitted' => $submittedReports,
            'total_reports_reviewed' => $reviewedReports,
            'attendance_percentage' => $attendancePercentage,
            'summary' => $summary,
            'generated_at' => now(),
        ]);

        return redirect()->route('monthly-recap.show', $recap)
            ->with('success', 'Recap bulanan berhasil dibuat.');
    }

    /**
     * Generate recaps for all teachers in a specific month.
     */
    public function generateAll($year, $month)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isPrincipal()) {
            abort(403);
        }

        $teachers = Teacher::where('status', 'active')->get();
        $created = 0;

        foreach ($teachers as $teacher) {
            $existing = MonthlyRecap::byYearMonth($year, $month)
                ->where('teacher_id', $teacher->id)
                ->first();

            if (!$existing) {
                $this->generateRecapForTeacher($teacher, $year, $month);
                $created++;
            }
        }

        return redirect()->route('monthly-recap.index')
            ->with('success', "Recap bulanan berhasil dibuat untuk {$created} guru.");
    }

    /**
     * Helper method to generate recap for a single teacher.
     */
    private function generateRecapForTeacher($teacher, $year, $month)
    {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate)
            ->endOfMonth()
            ->toDateString();

        $attendances = $teacher->attendances()
            ->byDateRange($startDate, $endDate)
            ->get();

        $presentDays = $attendances->whereIn('status', ['present', 'late'])->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateDays = $attendances->where('status', 'late')->count();
        $sickDays = $attendances->where('status', 'sick')->count();
        $leaveDays = $attendances->where('status', 'leave')->count();
        $totalDays = $attendances->count();

        $reports = $teacher->dailyReports()
            ->byDateRange($startDate, $endDate)
            ->get();

        $submittedReports = $reports->where('status', '!=', 'draft')->count();
        $reviewedReports = $reports->where('status', 'reviewed')->count();

        $attendancePercentage = $totalDays > 0
            ? round(($presentDays / $totalDays) * 100, 2)
            : 0;

        MonthlyRecap::create([
            'teacher_id' => $teacher->id,
            'year' => $year,
            'month' => $month,
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'sick_days' => $sickDays,
            'leave_days' => $leaveDays,
            'total_reports_submitted' => $submittedReports,
            'total_reports_reviewed' => $reviewedReports,
            'attendance_percentage' => $attendancePercentage,
            'generated_at' => now(),
        ]);
    }
}
