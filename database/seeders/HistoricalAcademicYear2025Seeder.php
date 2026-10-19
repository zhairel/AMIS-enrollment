<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Student;
use Database\Factories\StudentFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoricalAcademicYear2025Seeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds exactly 1,000 old student records representing AY 2025–2026.
     */
    public function run(): void
    {
        $targetCount = 1000;
        $academicYear = '2025-2026';
        $studentType = 'OLD';
        $status = 'ENROLLED';

        $gradeLevels = [
            'Kinder 1', 'Kinder 2',
            'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6',
            'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12',
        ];

        $filipinoFirstNames = [
            'Juan', 'Maria', 'Jose', 'Angelo', 'Mark', 'John', 'Princess', 'Angelica',
            'Christian', 'Gabriel', 'Joshua', 'Justine', 'Bea', 'Nicole', 'Jasmine',
            'Althea', 'Daniel', 'Kenneth', 'Jerome', 'Samantha', 'Christine', 'Alden',
            'Kathryn', 'Liza', 'Enrique', 'James', 'Nadine', 'Dingdong', 'Marian',
            'Coco', 'Julia', 'Gerald', 'Kim', 'Piolo', 'Toni', 'Alex', 'Sarah',
            'Matteo', 'Robin', 'Mariel', 'Richard', 'Lucy', 'Zanjoe', 'Ria', 'Carlo',
            'Antonio', 'Eduardo', 'Manuel', 'Fernando', 'Carlos', 'Ramon', 'Danilo',
            'Arlene', 'Maricel', 'Rowena', 'Lourdes', 'Rosario', 'Teresa', 'Corazon',
        ];

        $filipinoLastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza',
            'Torres', 'Ramos', 'Flores', 'Gonzales', 'Lopez', 'Dela Cruz', 'Villanueva',
            'Castro', 'Rivera', 'Corpuz', 'Aquino', 'Mercado', 'Dela Rosa', 'Domingo',
            'Salazar', 'Navarro', 'Valdez', 'Pascual', 'Soriano', 'Tolentino', 'Morales',
            'Manalo', 'Castillo', 'Villafuerte', 'Padilla', 'Panganiban', 'Estrada',
            'Alcantara', 'Magbanua', 'Macaraeg', 'Dimaculangan', 'Dimagiba', 'Batumbakal',
        ];

        $mobilePrefixes = ['0917', '0918', '0919', '0920', '0921', '0922', '0926', '0927', '0935', '0955', '0966', '0977', '0995', '0998'];

        $existingNumbers = Student::where('student_number', 'LIKE', '2025-%')
            ->pluck('id', 'student_number')
            ->all();

        $now = now();
        $studentsToInsert = [];
        $enrollmentPairs = [];

        for ($i = 1; $i <= $targetCount; $i++) {
            $studentNumber = sprintf('2025-%04d', $i);
            $grade = $gradeLevels[($i - 1) % count($gradeLevels)];

            if (isset($existingNumbers[$studentNumber])) {
                $studentId = $existingNumbers[$studentNumber];
                // Ensure enrollment exists for 2025-2026
                Enrollment::firstOrCreate(
                    [
                        'student_id' => $studentId,
                        'academic_year' => $academicYear,
                    ],
                    [
                        'student_type' => $studentType,
                        'grade_level' => $grade,
                        'status' => $status,
                    ]
                );
                continue;
            }

            $firstName = $filipinoFirstNames[array_rand($filipinoFirstNames)];
            $lastName = $filipinoLastNames[array_rand($filipinoLastNames)];
            $middleName = $filipinoLastNames[array_rand($filipinoLastNames)];

            $cleanFirst = strtolower(preg_replace('/[^a-zA-Z]/', '', $firstName));
            $cleanLast = strtolower(preg_replace('/[^a-zA-Z]/', '', $lastName));
            $email = "{$cleanFirst}.{$cleanLast}{$i}@amis.edu.ph";
            $contact = $mobilePrefixes[array_rand($mobilePrefixes)] . sprintf('%07d', mt_rand(1000000, 9999999));

            $studentsToInsert[] = [
                'student_number' => $studentNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'middle_name' => $middleName,
                'email' => $email,
                'contact_number' => $contact,
                'guardian_name' => $filipinoFirstNames[array_rand($filipinoFirstNames)] . ' ' . $lastName,
                'guardian_contact' => $mobilePrefixes[array_rand($mobilePrefixes)] . sprintf('%07d', mt_rand(1000000, 9999999)),
                'guardian_relationship' => ($i % 2 === 0) ? 'Mother' : 'Father',
                'created_at' => $now,
                'updated_at' => $now,
                '_grade_level' => $grade,
            ];
        }

        if (!empty($studentsToInsert)) {
            // Process in chunks of 200 for maximum performance and stability
            foreach (array_chunk($studentsToInsert, 200) as $chunk) {
                DB::transaction(function () use ($chunk, $academicYear, $studentType, $status, $now) {
                    $insertData = array_map(function ($row) {
                        unset($row['_grade_level']);
                        return $row;
                    }, $chunk);

                    DB::table('students')->insert($insertData);

                    $numbers = array_column($chunk, 'student_number');
                    $studentMap = DB::table('students')
                        ->whereIn('student_number', $numbers)
                        ->pluck('id', 'student_number');

                    $enrollments = [];
                    foreach ($chunk as $row) {
                        $num = $row['student_number'];
                        if (isset($studentMap[$num])) {
                            $enrollments[] = [
                                'student_id' => $studentMap[$num],
                                'academic_year' => $academicYear,
                                'student_type' => $studentType,
                                'grade_level' => $row['_grade_level'],
                                'status' => $status,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                    }

                    if (!empty($enrollments)) {
                        DB::table('enrollments')->insert($enrollments);
                    }
                });
            }
        }

        $this->command?->info("Successfully seeded exactly {$targetCount} students and enrollments for AY {$academicYear}.");
    }
}
