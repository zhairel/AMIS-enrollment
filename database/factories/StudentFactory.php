<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    protected static int $sequenceNumber = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
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

        $firstName = fake()->randomElement($filipinoFirstNames);
        $lastName = fake()->randomElement($filipinoLastNames);
        $middleName = fake()->randomElement($filipinoLastNames);

        $seq = static::$sequenceNumber++;
        $studentNumber = sprintf('2025-%04d', $seq);

        // Realistic Philippine mobile prefixes (Smart, Globe, Dito)
        $mobilePrefixes = ['0917', '0918', '0919', '0920', '0921', '0922', '0926', '0927', '0935', '0955', '0966', '0977', '0995', '0998'];
        $contactNumber = fake()->randomElement($mobilePrefixes) . fake()->numerify('#######');

        $cleanFirst = strtolower(preg_replace('/[^a-zA-Z]/', '', $firstName));
        $cleanLast = strtolower(preg_replace('/[^a-zA-Z]/', '', $lastName));
        $email = "{$cleanFirst}.{$cleanLast}{$seq}@amis.edu.ph";

        return [
            'student_number' => $studentNumber,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $middleName,
            'email' => $email,
            'contact_number' => $contactNumber,
            'guardian_name' => fake()->randomElement($filipinoFirstNames) . ' ' . $lastName,
            'guardian_contact' => fake()->randomElement($mobilePrefixes) . fake()->numerify('#######'),
            'guardian_relationship' => fake()->randomElement(['Mother', 'Father', 'Guardian', 'Aunt', 'Uncle']),
        ];
    }

    /**
     * Reset the sequence number for testing or seeder repeatability.
     */
    public static function resetSequence(int $start = 1): void
    {
        static::$sequenceNumber = $start;
    }
}
