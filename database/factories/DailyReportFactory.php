<?php

namespace Database\Factories;

use App\Models\DailyReport;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyReport>
 */
class DailyReportFactory extends Factory
{
    protected $model = DailyReport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teacher_id' => Teacher::factory(),
            'report_date' => Carbon::instance($this->faker->dateTimeBetween('-30 days'))->toDateString(),
            'class' => $this->faker->randomElement(['I A', 'I B', 'II A', 'II B', 'III A', 'III B', 'IV A', 'IV B', 'V A', 'V B', 'VI A', 'VI B']),
            'learning_objectives' => $this->faker->sentences(2, true),
            'learning_materials' => $this->faker->sentences(3, true),
            'teaching_methods' => $this->faker->randomElement(['Ceramah', 'Diskusi', 'Praktik', 'Tanya Jawab', 'Proyek']) . ', ' . $this->faker->randomElement(['Ceramah', 'Diskusi', 'Praktik', 'Tanya Jawab', 'Proyek']),
            'student_response' => $this->faker->optional(0.7)->sentences(2, true),
            'assignments_given' => $this->faker->optional(0.8)->sentences(1, true),
            'attendance_count' => $this->faker->numberBetween(25, 35),
            'total_students' => 35,
            'notes' => $this->faker->optional(0.4)->sentences(1, true),
            'status' => $this->faker->randomElement(['draft', 'submitted', 'reviewed']),
            'submitted_at' => $this->faker->optional(0.7)->dateTime(),
        ];
    }

    /**
     * Indicate that the report is draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'submitted_at' => null,
        ]);
    }

    /**
     * Indicate that the report is submitted.
     */
    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
    }

    /**
     * Indicate that the report is reviewed.
     */
    public function reviewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'reviewed',
            'submitted_at' => now()->subDays(1),
        ]);
    }
}
