<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\DailyReport;
use App\Models\MonthlyRecap;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            return $this->teacherDashboard($user);
        } else {
            return $this->adminDashboard($user);
        }
    }

    /**
     * Teacher dashboard.
     */
    private function teacherDashboard($user)
    {
        $teacher = $user->teacher;
        $today = now()->toDateString();

        // Today's attendance
        $todayAttendance = $teacher->attendances()
            ->whereDate('date', $today)
            ->first();

        // Recent attendance (last 7 days)
        $recentAttendance = $teacher->attendances()
            ->whereBetween('date', [now()->subDays(7)->toDateString(), $today])
            ->orderBy('date', 'desc')
            ->get();

        // Attendance statistics (current month)
        $currentMonthAttendances = $teacher->attendances()
            ->whereBetween('date', [
                now()->startOfMonth()->toDateString(),
                now()->toDateString()
            ])
            ->get();

        $monthStats = [
            'total_days' => $currentMonthAttendances->count(),
            'present_days' => $currentMonthAttendances->whereIn('status', ['present', 'late'])->count(),
            'absent_days' => $currentMonthAttendances->where('status', 'absent')->count(),
            'late_days' => $currentMonthAttendances->where('status', 'late')->count(),
        ];

        // Recent daily reports (last 5)
        $recentReports = $teacher->dailyReports()
            ->orderBy('report_date', 'desc')
            ->limit(5)
            ->get();

        // Draft reports count
        $draftReportsCount = $teacher->dailyReports()
            ->where('status', 'draft')
            ->count();

        // Latest monthly recap
        $latestRecap = $teacher->monthlyRecaps()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        return view('dashboard.teacher', compact(
            'todayAttendance',
            'recentAttendance',
            'monthStats',
            'recentReports',
            'draftReportsCount',
            'latestRecap'
        ));
    }

    /**
     * Admin/Principal dashboard.
     */
    private function adminDashboard($user)
    {
        // Teacher statistics
        $totalTeachers = Teacher::where('status', 'active')->count();
        $onLeaveTeachers = Teacher::where('status', 'on_leave')->count();

        // Today's attendance summary
        $today = now()->toDateString();
        $todayAttendances = Attendance::whereDate('date', $today)->get();
        $todayStats = [
            'total' => $todayAttendances->count(),
            'present' => $todayAttendances->whereIn('status', ['present', 'late'])->count(),
            'absent' => $todayAttendances->where('status', 'absent')->count(),
            'late' => $todayAttendances->where('status', 'late')->count(),
            'sick' => $todayAttendances->where('status', 'sick')->count(),
            'leave' => $todayAttendances->where('status', 'leave')->count(),
        ];

        // Reports summary
        $totalReports = DailyReport::count();
        $submittedReports = DailyReport::where('status', 'submitted')->count();
        $reviewedReports = DailyReport::where('status', 'reviewed')->count();

        // Teacher with lowest attendance (current month)
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lowAttendanceTeachers = Teacher::where('status', 'active')
            ->with(['attendances' => function ($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('date', $currentMonth)
                      ->whereYear('date', $currentYear);
            }])
            ->get()
            ->map(function ($teacher) {
                $attendances = $teacher->attendances;
                $total = $attendances->count();
                $present = $attendances->whereIn('status', ['present', 'late'])->count();
                $percentage = $total > 0 ? ($present / $total) * 100 : 0;
                $teacher->attendance_percentage = $percentage;
                return $teacher;
            })
            ->sortBy('attendance_percentage')
            ->take(5);

        // Monthly recap status for current month
        $recapsCount = MonthlyRecap::where('year', $currentYear)
            ->where('month', $currentMonth)
            ->count();

        // Teachers yet to submit report today
        $teachersYetToReport = Teacher::where('status', 'active')
            ->whereDoesntHave('dailyReports', function ($query) use ($today) {
                $query->whereDate('report_date', $today)
                      ->where('status', '!=', 'draft');
            })
            ->count();

        return view('dashboard.admin', compact(
            'totalTeachers',
            'onLeaveTeachers',
            'todayStats',
            'totalReports',
            'submittedReports',
            'reviewedReports',
            'lowAttendanceTeachers',
            'recapsCount',
            'teachersYetToReport',
            'currentMonth',
            'currentYear'
        ));
    }
}
