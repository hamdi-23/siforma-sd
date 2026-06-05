<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['present', 'late', 'absent', 'sick', 'leave']);
        $checkInTime = $status === 'absent' ? null : $this->faker->time();
        $checkOutTime = $status === 'absent' ? null : $this->faker->time();

        return [
            'teacher_id' => Teacher::factory(),
            'date' => Carbon::instance($this->faker->dateTimeBetween('-30 days'))->toDateString(),
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'status' => $status,
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the attendance is present.
     */
    public function present(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'present',
            'check_in_time' => $this->faker->time(),
            'check_out_time' => $this->faker->time(),
        ]);
    }

    /**
     * Indicate that the attendance is late.
     */
    public function late(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'late',
            'check_in_time' => $this->faker->time(),
            'check_out_time' => $this->faker->time(),
        ]);
    }

    /**
     * Indicate that the attendance is absent.
     */
    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'absent',
            'check_in_time' => null,
            'check_out_time' => null,
        ]);
    }
}
