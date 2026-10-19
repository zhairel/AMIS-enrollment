<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gradeLevels = [
            'Kinder 1', 'Kinder 2',
            'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6',
            'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12',
        ];

        return [
            'student_id' => Student::factory(),
            'academic_year' => '2025-2026',
            'student_type' => 'OLD',
            'grade_level' => fake()->randomElement($gradeLevels),
            'status' => 'ENROLLED',
        ];
    }

    /**
     * State for Academic Year 2025-2026 enrolled old student.
     */
    public function ay2025Old(): static
    {
        return $this->state(fn (array $attributes) => [
            'academic_year' => '2025-2026',
            'student_type' => 'OLD',
            'status' => 'ENROLLED',
        ]);
    }

    /**
     * State for Academic Year 2026-2027.
     */
    public function ay2026(string $type = 'OLD', string $status = 'ENROLLED'): static
    {
        return $this->state(fn (array $attributes) => [
            'academic_year' => '2026-2027',
            'student_type' => $type,
            'status' => $status,
        ]);
    }
}
