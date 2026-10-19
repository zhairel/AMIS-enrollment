<?php

namespace App\Services\Enrollment;

use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Enrollment2026Service
{
    public const ACADEMIC_YEAR = '2026-2027';

    /**
     * Process an enrollment application for Academic Year 2026-2027.
     *
     * @param  array  $data
     * @return \App\Models\Enrollment
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function enroll(array $data): Enrollment
    {
        return DB::transaction(function () use ($data) {
            $academicYear = self::ACADEMIC_YEAR;
            $rawType = strtoupper((string) ($data['student_type'] ?? 'NEW'));
            $studentType = in_array($rawType, ['OLD', 'NEW', 'TRANSFEREE'], true) ? $rawType : 'NEW';
            $gradeLevel = $data['grade_level'] ?? $data['year_level'] ?? null;

            if ($studentType === 'OLD') {
                $studentNumber = $data['student_number'] ?? null;

                if (!$studentNumber) {
                    throw ValidationException::withMessages([
                        'student_number' => ['The student number is required for returning (OLD) students.'],
                    ]);
                }

                // Query Student by student_number
                $student = Student::where('student_number', $studentNumber)
                    ->lockForUpdate()
                    ->first();

                if (!$student) {
                    throw ValidationException::withMessages([
                        'student_number' => ["Student record not found for student number: {$studentNumber}"],
                    ]);
                }

                // Check if already enrolled in 2026-2027 (throws validation exception if duplicate)
                $alreadyEnrolled = $student->enrollments()
                    ->where('academic_year', $academicYear)
                    ->exists();

                if ($alreadyEnrolled) {
                    throw ValidationException::withMessages([
                        'student_number' => ["Student is already enrolled for Academic Year {$academicYear}."],
                    ]);
                }

                // Create a new enrollment record for 2026-2027 with student_type = 'OLD'
                $enrollment = $student->enrollments()->create([
                    'academic_year' => $academicYear,
                    'student_type' => 'OLD',
                    'grade_level' => $gradeLevel,
                    'status' => $data['status'] ?? 'ENROLLED',
                ]);

                return $enrollment->load('student');
            }

            // For NEW students (and TRANSFEREE):
            // Generate student_number (format 2026-XXXX)
            $studentNumber = $this->generateStudentNumber('2026');

            // Create new Student master profile
            $student = Student::create([
                'student_number' => $studentNumber,
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'middle_name' => $data['middle_name'] ?? null,
                'email' => $data['email'] ?? null,
                'contact_number' => $data['contact_number'] ?? null,
                'guardian_name' => $data['guardian_name'] ?? null,
                'guardian_contact' => $data['guardian_contact'] ?? null,
                'guardian_relationship' => $data['guardian_relationship'] ?? null,
            ]);

            // Create the enrollment record for 2026-2027 with student_type = 'NEW' (or 'TRANSFEREE')
            $enrollment = $student->enrollments()->create([
                'academic_year' => $academicYear,
                'student_type' => $studentType,
                'grade_level' => $gradeLevel,
                'status' => $data['status'] ?? 'ENROLLED',
            ]);

            return $enrollment->load('student');
        });
    }

    /**
     * Generate sequential student number in 2026-XXXX format.
     */
    public function generateStudentNumber(string $yearPrefix = '2026'): string
    {
        $prefix = $yearPrefix . '-';

        $latest = Student::where('student_number', 'LIKE', "{$prefix}%")
            ->lockForUpdate()
            ->orderByRaw("CAST(SUBSTRING_INDEX(student_number, '-', -1) AS UNSIGNED) DESC")
            ->first();

        if ($latest) {
            $parts = explode('-', $latest->student_number);
            $lastSeq = (int) end($parts);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }

        $candidate = sprintf('%s-%04d', $yearPrefix, $nextSeq);

        while (Student::where('student_number', $candidate)->exists()) {
            $nextSeq++;
            $candidate = sprintf('%s-%04d', $yearPrefix, $nextSeq);
        }

        return $candidate;
    }
}
