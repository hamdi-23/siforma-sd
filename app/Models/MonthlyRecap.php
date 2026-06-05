<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyRecap extends Model
{
    /** @use HasFactory */
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'year',
        'month',
        'total_days',
        'present_days',
        'absent_days',
        'late_days',
        'sick_days',
        'leave_days',
        'total_reports_submitted',
        'total_reports_reviewed',
        'attendance_percentage',
        'summary',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the teacher that owns the monthly recap.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Scope to get recaps for a specific year and month.
     */
    public function scopeByYearMonth($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    /**
     * Scope to get recaps for a specific year.
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Calculate attendance percentage.
     */
    public function calculateAttendancePercentage()
    {
        if ($this->total_days == 0) {
            return 0;
        }
        return round(($this->present_days / $this->total_days) * 100, 2);
    }
}
