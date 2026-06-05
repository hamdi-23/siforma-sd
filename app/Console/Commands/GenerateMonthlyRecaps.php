<?php

namespace App\Console\Commands;

use App\Models\MonthlyRecap;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyRecaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recap:generate-monthly
                            {--year= : Tahun (default: tahun sekarang)}
                            {--month= : Bulan (default: bulan sebelumnya)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly recaps untuk semua guru active';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $year = $this->option('year') ?? now()->year;
        $month = $this->option('month') ?? now()->subMonth()->month;

        $this->info("Generating monthly recaps untuk {$month}/{$year}...");

        $teachers = Teacher::where('status', 'active')->get();
        $created = 0;
        $skipped = 0;

        foreach ($teachers as $teacher) {
            $existing = MonthlyRecap::where('teacher_id', $teacher->id)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            if ($existing) {
                $this->warn("Skipped: Recap untuk {$teacher->user->name} sudah ada");
                $skipped++;
                continue;
            }

            $this->generateRecapForTeacher($teacher, $year, $month);
            $this->line("✓ Created recap untuk {$teacher->user->name}");
            $created++;
        }

        $this->info("Generated $created recaps, skipped $skipped");
        return Command::SUCCESS;
    }

    /**
     * Generate monthly recap for a single teacher.
     */
    private function generateRecapForTeacher($teacher, $year, $month)
    {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = Carbon::createFromFormat('Y-m-d', $startDate)
            ->endOfMonth()
            ->toDateString();

        $attendances = $teacher->attendances()
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $presentDays = $attendances->whereIn('status', ['present', 'late'])->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateDays = $attendances->where('status', 'late')->count();
        $sickDays = $attendances->where('status', 'sick')->count();
        $leaveDays = $attendances->where('status', 'leave')->count();
        $totalDays = $attendances->count();

        $reports = $teacher->dailyReports()
            ->whereBetween('report_date', [$startDate, $endDate])
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
