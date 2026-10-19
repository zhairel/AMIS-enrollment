<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Student;
use App\Services\Enrollment\Enrollment2026Service;
use Database\Seeders\HistoricalAcademicYear2025Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AY2026EnrollmentTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test Eloquent relationships between Student and Enrollment.
     */
    public function test_eloquent_relationships(): void
    {
        $student = Student::factory()->create([
            'student_number' => '2025-9999',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
        ]);

        $enrollment1 = Enrollment::create([
            'student_id' => $student->id,
            'academic_year' => '2024-2025',
            'student_type' => 'OLD',
            'grade_level' => 'Grade 7',
            'status' => 'ENROLLED',
        ]);

        $enrollment2 = Enrollment::create([
            'student_id' => $student->id,
            'academic_year' => '2026-2027',
            'student_type' => 'OLD',
            'grade_level' => 'Grade 8',
            'status' => 'ENROLLED',
        ]);

        // Student::enrollments() (hasMany)
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $student->enrollments);
        $this->assertCount(2, $student->enrollments);

        // Student::latestEnrollment() (hasOne)
        $this->assertInstanceOf(Enrollment::class, $student->latestEnrollment);
        $this->assertEquals($enrollment2->id, $student->latestEnrollment->id);
        $this->assertEquals('2026-2027', $student->latestEnrollment->academic_year);

        // Enrollment::student() (belongsTo)
        $this->assertInstanceOf(Student::class, $enrollment1->student);
        $this->assertEquals($student->id, $enrollment1->student->id);
    }

    /**
     * Test unique constraint on student_id and academic_year.
     */
    public function test_database_prevents_duplicate_enrollment_in_same_academic_year(): void
    {
        $student = Student::factory()->create([
            'student_number' => '2025-9998',
        ]);

        Enrollment::create([
            'student_id' => $student->id,
            'academic_year' => '2026-2027',
            'student_type' => 'OLD',
            'grade_level' => 'Grade 10',
            'status' => 'ENROLLED',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        // Attempting to insert duplicate enrollment for the same AY must trigger unique constraint violation
        Enrollment::create([
            'student_id' => $student->id,
            'academic_year' => '2026-2027',
            'student_type' => 'OLD',
            'grade_level' => 'Grade 10',
            'status' => 'ENROLLED',
        ]);
    }

    /**
     * Test Seeder seeds exactly 1,000 old student records for AY 2025-2026.
     */
    public function test_historical_seeder_baseline_seeds_exactly_1000_students(): void
    {
        $seeder = new HistoricalAcademicYear2025Seeder();
        $seeder->run();

        $ay2025Enrollments = Enrollment::where('academic_year', '2025-2026')
            ->where('student_type', 'OLD')
            ->where('status', 'ENROLLED')
            ->count();

        $this->assertEquals(1000, $ay2025Enrollments);

        // Verify student numbers are patterned 2025-XXXX
        $sample = Student::where('student_number', 'LIKE', '2025-%')->first();
        $this->assertNotNull($sample);
        $this->assertMatchesRegularExpression('/^2025-\d{4}$/', $sample->student_number);
        $this->assertNotEmpty($sample->first_name);
        $this->assertNotEmpty($sample->last_name);
        $this->assertNotEmpty($sample->contact_number);
    }

    /**
     * Test validation failure when enrolling an OLD student without or with non-existent student_number.
     */
    public function test_api_validates_old_student_requires_existing_student_number(): void
    {
        // 1. Missing student_number
        $response1 = $this->postJson('/api/v1/enrollment/2026-2027', [
            'student_type' => 'OLD',
            'grade_level' => 'Grade 8',
        ]);

        $response1->assertStatus(422);
        $response1->assertJsonValidationErrors('student_number');

        // 2. Non-existent student_number
        $response2 = $this->postJson('/api/v1/enrollment/2026-2027', [
            'student_type' => 'OLD',
            'student_number' => '9999-9999',
            'grade_level' => 'Grade 8',
        ]);

        $response2->assertStatus(422);
        $response2->assertJsonValidationErrors('student_number');
    }

    /**
     * Test validation failure when enrolling a NEW student without required demographic details.
     */
    public function test_api_validates_new_student_requires_demographics_and_guardian(): void
    {
        $response = $this->postJson('/api/v1/enrollment/2026-2027', [
            'student_type' => 'NEW',
            'grade_level' => 'Grade 1',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'first_name',
            'last_name',
            'email',
            'contact_number',
            'guardian_name',
            'guardian_contact',
        ]);
    }

    /**
     * Test successful OLD student enrollment for AY 2026-2027.
     */
    public function test_api_successful_old_student_enrollment(): void
    {
        // Ensure student 2025-0001 exists
        $student = Student::firstOrCreate(
            ['student_number' => '2025-0001'],
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'contact_number' => '09171234567',
                'email' => 'juan.delacruz@amis.edu.ph',
            ]
        );

        // Remove any prior 2026 enrollment for clean test run
        Enrollment::where('student_id', $student->id)->where('academic_year', '2026-2027')->delete();

        $response = $this->postJson('/api/v1/enrollment/2026-2027', [
            'student_type' => 'OLD',
            'student_number' => '2025-0001',
            'grade_level' => 'Grade 9',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Enrollment for Academic Year 2026-2027 processed successfully.',
            'data' => [
                'student_number' => '2025-0001',
                'academic_year' => '2026-2027',
                'student_type' => 'OLD',
                'grade_level' => 'Grade 9',
            ],
        ]);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'academic_year' => '2026-2027',
            'student_type' => 'OLD',
            'grade_level' => 'Grade 9',
        ]);
    }

    /**
     * Test duplicate enrollment for OLD student in AY 2026-2027 throws validation error.
     */
    public function test_api_blocks_duplicate_enrollment_for_ay_2026_2027(): void
    {
        $student = Student::firstOrCreate(
            ['student_number' => '2025-0002'],
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'contact_number' => '09181234567',
                'email' => 'maria.santos@amis.edu.ph',
            ]
        );

        // Ensure student already enrolled in 2026-2027
        Enrollment::firstOrCreate(
            [
                'student_id' => $student->id,
                'academic_year' => '2026-2027',
            ],
            [
                'student_type' => 'OLD',
                'grade_level' => 'Grade 8',
                'status' => 'ENROLLED',
            ]
        );

        // Attempt second enrollment for the same AY
        $response = $this->postJson('/api/v1/enrollment/2026-2027', [
            'student_type' => 'OLD',
            'student_number' => '2025-0002',
            'grade_level' => 'Grade 8',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('student_number');
    }

    /**
     * Test successful NEW student enrollment with generated 2026-XXXX student number.
     */
    public function test_api_successful_new_student_enrollment_generates_2026_number(): void
    {
        $response = $this->postJson('/api/v1/enrollment/2026-2027', [
            'student_type' => 'NEW',
            'grade_level' => 'Grade 1',
            'first_name' => 'Angelo',
            'last_name' => 'Reyes',
            'middle_name' => 'Cruz',
            'email' => 'angelo.reyes.test@amis.edu.ph',
            'contact_number' => '09191234567',
            'guardian_name' => 'Mark Reyes',
            'guardian_contact' => '09201234567',
            'guardian_relationship' => 'Father',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Enrollment for Academic Year 2026-2027 processed successfully.',
            'data' => [
                'first_name' => 'Angelo',
                'last_name' => 'Reyes',
                'academic_year' => '2026-2027',
                'student_type' => 'NEW',
                'grade_level' => 'Grade 1',
            ],
        ]);

        $data = $response->json('data');
        $this->assertNotEmpty($data['student_number']);
        $this->assertMatchesRegularExpression('/^2026-\d{4}$/', $data['student_number']);

        $this->assertDatabaseHas('students', [
            'student_number' => $data['student_number'],
            'first_name' => 'Angelo',
            'last_name' => 'Reyes',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $data['student_id'],
            'academic_year' => '2026-2027',
            'student_type' => 'NEW',
            'grade_level' => 'Grade 1',
        ]);
    }

    /**
     * Test transferee enrollment via API.
     */
    public function test_api_successful_transferee_enrollment(): void
    {
        $response = $this->postJson('/api/v1/enrollment/2026-2027', [
            'student_type' => 'TRANSFEREE',
            'year_level' => 'Grade 11',
            'first_name' => 'Justine',
            'last_name' => 'Torres',
            'middle_name' => 'Flores',
            'email' => 'justine.torres.test@amis.edu.ph',
            'contact_number' => '09221234567',
            'guardian_name' => 'Elena Torres',
            'guardian_contact' => '09261234567',
            'guardian_relationship' => 'Mother',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'data' => [
                'student_type' => 'TRANSFEREE',
                'grade_level' => 'Grade 11',
            ],
        ]);
    }

    /**
     * Test year_level alias accessor and mutator on Enrollment model.
     */
    public function test_year_level_attribute_alias_on_enrollment(): void
    {
        $student = Student::factory()->create();
        $enrollment = new Enrollment([
            'student_id' => $student->id,
            'academic_year' => '2026-2027',
            'student_type' => 'NEW',
            'year_level' => 'Grade 12',
            'status' => 'DRAFT',
        ]);
        $enrollment->save();

        $this->assertEquals('Grade 12', $enrollment->grade_level);
        $this->assertEquals('Grade 12', $enrollment->year_level);
    }

    /**
     * Test direct service invocation and transaction rollback on failure.
     */
    public function test_direct_service_enrollment_and_duplicate_exception(): void
    {
        $service = app(Enrollment2026Service::class);

        // Enroll a new student directly via service
        $enrollment = $service->enroll([
            'student_type' => 'NEW',
            'grade_level' => 'Kinder 1',
            'first_name' => 'Bea',
            'last_name' => 'Bautista',
            'email' => 'bea.bautista@amis.edu.ph',
            'contact_number' => '09179998888',
        ]);

        $this->assertEquals('2026-2027', $enrollment->academic_year);
        $this->assertEquals('NEW', $enrollment->student_type);
        $this->assertMatchesRegularExpression('/^2026-\d{4}$/', $enrollment->student->student_number);

        // Attempt to enroll the same student number again as OLD student for 2026-2027
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $service->enroll([
            'student_type' => 'OLD',
            'student_number' => $enrollment->student->student_number,
            'grade_level' => 'Kinder 2',
        ]);
    }
}
