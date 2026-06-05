<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\DailyReport;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $adminUser = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Create principal user
        $principalUser = User::factory()->create([
            'name' => 'Kepala Sekolah',
            'email' => 'principal@example.com',
            'role' => 'principal',
        ]);

        // Create 5 teachers with their data
        for ($i = 1; $i <= 5; $i++) {
            // Create user
            $teacherUser = User::factory()->create([
                'name' => "Guru {$i}",
                'email' => "guru{$i}@example.com",
                'role' => 'teacher',
            ]);

            // Create teacher profile
            $teacher = Teacher::factory()->create([
                'user_id' => $teacherUser->id,
            ]);

            // Create attendance records for last 30 days
            for ($day = 1; $day <= 30; $day++) {
                Attendance::factory()->create([
                    'teacher_id' => $teacher->id,
                    'date' => now()->subDays($day)->toDateString(),
                ]);
            }

            // Create daily reports for last 20 days (only working days, not all days)
            for ($day = 1; $day <= 20; $day++) {
                DailyReport::factory()->create([
                    'teacher_id' => $teacher->id,
                    'report_date' => now()->subDays($day)->toDateString(),
                ]);
            }
        }

        // Create one additional test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'teacher',
        ]);
    }
}
