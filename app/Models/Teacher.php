<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /** @use HasFactory */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'subject',
        'phone',
        'address',
        'status',
        'hire_date',
    ];

    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendances for the teacher.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the daily reports for the teacher.
     */
    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    /**
     * Get the monthly recaps for the teacher.
     */
    public function monthlyRecaps()
    {
        return $this->hasMany(MonthlyRecap::class);
    }

    /**
     * Get today's attendance.
     */
    public function todayAttendance()
    {
        return $this->attendances()->whereDate('date', today());
    }
}
