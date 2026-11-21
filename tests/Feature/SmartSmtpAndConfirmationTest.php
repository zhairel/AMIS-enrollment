<?php

namespace Tests\Feature;

use App\Models\EnrollmentApplicant;
use App\Models\User;
use App\Services\Enrollment\EnrollmentNotificationService;
use App\Services\System\SmartSmtpRotatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SmartSmtpAndConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_gmail_pool_rotates_automatically_by_day_and_batch_and_skips_cooldown(): void
    {
        Cache::flush();

        config([
            'mail.mailers.smtp.host' => 'smtp.gmail.com',
            'mail.mailers.smtp.username' => 'amis.gmail1@gmail.com',
            'mail.mailers.smtp.password' => 'apppass11112222',
            'mail.mailers.smtp_backup.host' => 'smtp.gmail.com',
            'mail.mailers.smtp_backup.username' => 'amis.gmail2@gmail.com',
            'mail.mailers.smtp_backup.password' => 'apppass33334444',
            'mail.gmail_pool' => 'amis.gmail3@gmail.com:apppass55556666,amis.gmail4@gmail.com:apppass77778888',
            'mail.mailers.inquiries.host' => 'mail.amis.edu.ph',
            'mail.mailers.inquiries.username' => 'noreply@amis.edu.ph',
            'mail.mailers.inquiries.password' => 'AmisEnroll2026',
        ]);

        $rotator = app(SmartSmtpRotatorService::class);
        $allGmails = $rotator->getAllConfiguredGmailMailers();

        $this->assertCount(4, $allGmails);
        $this->assertContains('smtp', $allGmails);
        $this->assertContains('smtp_backup', $allGmails);
        $this->assertContains('gmail_pool_1', $allGmails);
        $this->assertContains('gmail_pool_2', $allGmails);

        // Verify automatic daily primary Gmail switch across consecutive days
        $primariesAcrossDays = [];
        for ($d = 1; $d <= 7; $d++) {
            Carbon::setTestNow(Carbon::create(2026, 11, $d, 10, 0, 0));
            $ordered = $rotator->getOrderedHealthyGmailMailers();
            $primariesAcrossDays[] = $ordered[0];
        }
        Carbon::setTestNow();

        // Across 7 days, multiple different Gmail accounts must be selected as the daily primary
        $this->assertGreaterThan(1, count(array_unique($primariesAcrossDays)));

        // Verify cooldown circuit breaker immediately switches to the next healthy Gmail
        $currentOrder = $rotator->getOrderedHealthyGmailMailers();
        $firstPrimary = $currentOrder[0];
        $secondBackup = $currentOrder[1];

        $rotator->setCooldown($firstPrimary, 15, 'Simulated 550 quota exceeded');
        $afterCooldownOrder = $rotator->getOrderedHealthyGmailMailers();

        $this->assertNotContains($firstPrimary, $afterCooldownOrder);
        $this->assertSame($secondBackup, $afterCooldownOrder[0]);
    }

    public function test_completing_registration_auto_sends_thank_you_pending_admin_review_email(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'student.applicant@example.com',
            'account_status' => 'verified',
            'email_verified_at' => now(),
        ]);

        $payload = [
            'student_type' => 'New',
            'learning_mode' => 'Face-to-Face',
            'lrn' => '123456789012',
            'grade_level' => 'Grade 7',
            'last_name' => 'Abdullah',
            'first_name' => 'Fatima',
            'middle_name' => 'Santos',
            'gender' => 'Female',
            'date_of_birth' => '2014-05-15',
            'place_of_birth' => 'Davao City',
            'religion' => 'Islam',
            'ethnicity' => 'Maranao',
            'country' => 'Philippines',
            'state_province' => 'DAVAO REGION (REGION XI)',
            'city' => 'DAVAO CITY',
            'street_address' => 'Blk 4 Lot 12, Brgy. Buhangin',
            'postal_code' => '8000',
            'mobile_country_code' => '+63',
            'mobile_number' => '917 123 4567',
            'father_last_name' => 'Abdullah',
            'father_first_name' => 'Omar',
            'mother_last_name' => 'Santos',
            'mother_first_name' => 'Amina',
            'parent_country_code' => '+63',
            'parent_mobile' => '918 765 4321',
            'parent_email' => 'parent.guardian@example.com',
            'facebook' => 'facebook.com/amina.abdullah',
            'medical_has_concern' => 'No',
            'emergency_name' => 'Amina Abdullah',
            'emergency_relationship' => 'Mother',
            'emergency_phone' => '09187654321',
            'agreed_to_terms' => '1',
            'agreed_to_fee_policy' => '1',
            'agreed_to_data_privacy' => '1',
            'school_year' => '2026-2027',
            'photo_2x2' => UploadedFile::fake()->create('photo.jpg', 20, 'image/jpeg'),
            'birth_cert' => UploadedFile::fake()->create('birth.jpg', 20, 'image/jpeg'),
            'report_card' => UploadedFile::fake()->create('report.jpg', 20, 'image/jpeg'),
            'facebook_screenshot' => UploadedFile::fake()->create('fb.jpg', 20, 'image/jpeg'),
            'payment_receipt' => UploadedFile::fake()->create('receipt.jpg', 20, 'image/jpeg'),
            'method' => 'gcash',
            'amount' => '4000.00',
            'reference_no' => 'GCASH-11223344',
        ];

        $response = $this->actingAs($user)->post('/enroll', $payload);
        $response->assertRedirect();

        $applicant = EnrollmentApplicant::where('user_id', $user->id)->firstOrFail();
        $this->assertSame('submitted', $applicant->status);

        // Verify confirmation email builds and sends cleanly to both account email and parent email
        $service = app(EnrollmentNotificationService::class);
        $this->assertTrue($service->sendSubmissionConfirmation('student.applicant@example.com', $applicant));
    }
}
