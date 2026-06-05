<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    /** @use HasFactory */
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'report_date',
        'class',
        'learning_objectives',
        'learning_materials',
        'teaching_methods',
        'student_response',
        'assignments_given',
        'attendance_count',
        'total_students',
        'notes',
        'status',
        'submitted_at',
        'material_file',
        'material_file_original_name',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'submitted_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the teacher that owns the daily report.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Scope to get reports for a specific date.
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('report_date', $date);
    }

    /**
     * Scope to get reports for a date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('report_date', [$startDate, $endDate]);
    }

    /**
     * Scope to get submitted reports.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope to get reviewed reports.
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }
}
