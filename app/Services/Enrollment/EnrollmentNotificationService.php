<?php

namespace App\Services\Enrollment;

use App\Models\EnrollmentApplicant;
use App\Services\System\SmartSmtpRotatorService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Testing\Fakes\MailFake;

class EnrollmentNotificationService
{
    public function __construct(
        private ?SmartSmtpRotatorService $mailRotator = null,
    ) {
        $this->mailRotator ??= app(SmartSmtpRotatorService::class);
    }

    /**
     * Send the post-registration "Thank You & Wait for Administration Review" email
     * to all unique email addresses associated with the applicant (account, student, parent/guardian).
     */
    public function sendSubmissionConfirmationToAll(EnrollmentApplicant $applicant, ?string $accountEmail = null): void
    {
        $recipients = collect([
            $accountEmail,
            $applicant->user?->email ?? null,
            $applicant->email ?? null,
            $applicant->parent_email ?? null,
        ])
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter(fn ($email) => $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->unique()
            ->values();

        foreach ($recipients as $recipient) {
            $this->sendSubmissionConfirmation($recipient, $applicant);
        }
    }

    /**
     * Send the post-registration confirmation email to a specific recipient using
     * the Smart Daily Gmail Rotator & Multi-Transport Failover service.
     */
    public function sendSubmissionConfirmation(string $email, EnrollmentApplicant $applicant): bool
    {
        $normalizedEmail = strtolower(trim($email));
        if ($normalizedEmail === '' || filter_var($normalizedEmail, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        $dedupeKey = "enrollment_confirm_sent_{$applicant->id}_".sha1($normalizedEmail);
        $isFake = Mail::getFacadeRoot() instanceof MailFake;

        if (! $isFake && Cache::has($dedupeKey)) {
            return true;
        }

        try {
            $fullName = trim(collect([
                $applicant->first_name,
                $applicant->middle_name,
                $applicant->last_name,
                $applicant->suffix,
            ])->filter()->implode(' '));

            $displayName = $fullName !== '' ? $fullName : 'Learner';
            $grade = (string) ($applicant->grade_level ?: 'Selected Grade Level');
            $schoolYear = (string) ($applicant->school_year ?: '2026-2027');
            $studentType = (string) ($applicant->student_type ?: 'New');
            $learningMode = (string) ($applicant->learning_mode ?: 'Face-to-Face');
            $lrn = filled($applicant->lrn) ? (string) $applicant->lrn : 'To be verified by Registrar';
            $refNo = 'AMIS-'.str_pad((string) $applicant->id, 5, '0', STR_PAD_LEFT);
            $submitted = ($applicant->submitted_at ?? now())->timezone('Asia/Manila')->format('F j, Y \a\t g:i A').' (PHT)';

            $applicant->loadMissing('payment');
            $paymentStatusLabel = $applicant->payment
                ? 'Attached ('.strtoupper((string) $applicant->payment->method).' — Pending Finance Verification)'
                : 'Pending Payment / For Accounting Verification';

            $portalUrl = rtrim((string) config('app.url', 'https://aes.amis.edu.ph'), '/').'/enrollment/dashboard';
            $logoUrl = asset('images/AMIS_Logo.png');

            $html = '<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"></head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:\'Plus Jakarta Sans\',Inter,Arial,sans-serif;color:#0f172a;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:32px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 10px 25px rgba(15,23,42,0.08);border:1px solid #e2e8f0;">
    <tr><td style="background:linear-gradient(135deg,#065f46 0%,#059669 60%,#0d9488 100%);padding:32px 28px;text-align:center;">
        <img src="'.htmlspecialchars($logoUrl).'" alt="AMIS Logo" width="68" height="68" style="margin-bottom:12px;border-radius:50%;background:#ffffff;padding:4px;">
        <div style="color:#a7f3d0;font-size:12px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;margin-bottom:4px;">Office of the School Registrar &amp; Admissions</div>
        <h1 style="color:#ffffff;font-size:22px;font-weight:800;margin:0;">Al Munawwara Islamic School</h1>
        <p style="color:rgba(255,255,255,0.88);font-size:13px;margin:6px 0 0;">Official Online Enrollment System &bull; S.Y. '.htmlspecialchars($schoolYear).'</p>
    </td></tr>

    <tr><td style="padding:32px 36px;">
        <div style="text-align:center;margin-bottom:22px;">
            <span style="display:inline-block;background:#ecfdf5;border:1.5px solid #6ee7b7;border-radius:999px;padding:7px 18px;font-size:12px;font-weight:800;color:#047857;letter-spacing:0.4px;text-transform:uppercase;">
                &#10003; Registration Complete &bull; Pending Administration Review
            </span>
        </div>

        <h2 style="color:#0f172a;font-size:20px;font-weight:800;margin:0 0 12px;">Assalamu Alaikum &amp; Thank You for Registering!</h2>
        <p style="color:#334155;font-size:14px;line-height:1.75;margin:0 0 16px;">
            Thank you for completing the online enrollment registration for <strong>'.htmlspecialchars($displayName).'</strong> for <strong>'.htmlspecialchars($grade).' (S.Y. '.htmlspecialchars($schoolYear).')</strong> at <strong>Al Munawwara Islamic School (AMIS)</strong>.
        </p>
        <p style="color:#475569;font-size:14px;line-height:1.75;margin:0 0 24px;">
            Your registration form and uploaded requirements have been received and are now queued for review. <strong>Please wait while our School Administration, Registrar, and Finance Office verify your submitted credentials.</strong>
        </p>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:18px 20px;margin-bottom:24px;">
            <tr>
                <td colspan="2" style="padding:0 0 10px;border-bottom:1px solid #e2e8f0;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.8px;color:#059669;">
                    Learner Registration Summary
                </td>
            </tr>
            <tr>
                <td style="padding:10px 0 6px;font-size:13px;color:#64748b;">Application Reference No.</td>
                <td style="padding:10px 0 6px;font-size:13px;font-weight:800;color:#0f172a;text-align:right;">'.htmlspecialchars($refNo).'</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-size:13px;color:#64748b;">Learner\'s Full Name</td>
                <td style="padding:6px 0;font-size:13px;font-weight:700;color:#0f172a;text-align:right;">'.htmlspecialchars($displayName).'</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-size:13px;color:#64748b;">DepEd LRN</td>
                <td style="padding:6px 0;font-size:13px;font-weight:600;color:#0f172a;text-align:right;">'.htmlspecialchars($lrn).'</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-size:13px;color:#64748b;">Learner Classification</td>
                <td style="padding:6px 0;font-size:13px;font-weight:700;color:#0f172a;text-align:right;">'.htmlspecialchars($studentType).' Learner</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-size:13px;color:#64748b;">Grade Level &amp; School Year</td>
                <td style="padding:6px 0;font-size:13px;font-weight:700;color:#0f172a;text-align:right;">'.htmlspecialchars($grade).' &bull; S.Y. '.htmlspecialchars($schoolYear).'</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-size:13px;color:#64748b;">Learning Modality</td>
                <td style="padding:6px 0;font-size:13px;font-weight:700;color:#0f172a;text-align:right;">'.htmlspecialchars($learningMode).'</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-size:13px;color:#64748b;">Payment / Receipt Status</td>
                <td style="padding:6px 0;font-size:12px;font-weight:700;color:#0369a1;text-align:right;">'.htmlspecialchars($paymentStatusLabel).'</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-size:13px;color:#64748b;">Date &amp; Time Submitted</td>
                <td style="padding:6px 0;font-size:13px;font-weight:600;color:#0f172a;text-align:right;">'.htmlspecialchars($submitted).'</td>
            </tr>
            <tr>
                <td style="padding:8px 0 2px;font-size:13px;color:#64748b;">Current Application Status</td>
                <td style="padding:8px 0 2px;text-align:right;">
                    <span style="background:#fef3c7;color:#92400e;border:1px solid #fcd34d;font-size:11px;font-weight:800;padding:4px 12px;border-radius:999px;display:inline-block;">
                        Pending Administration Review
                    </span>
                </td>
            </tr>
        </table>

        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:18px 20px;margin-bottom:26px;">
            <div style="font-size:13px;font-weight:800;color:#065f46;margin-bottom:8px;">What Happens Next?</div>
            <ol style="margin:0;padding-left:18px;color:#1e293b;font-size:13px;line-height:1.7;">
                <li style="margin-bottom:6px;"><strong>Administration &amp; Registrar Check:</strong> Our admissions staff will inspect your learner profile, PSA Birth Certificate, SF9 / Form 138 Report Card, and 2x2 ID photo.</li>
                <li style="margin-bottom:6px;"><strong>Finance Verification:</strong> Our accounting office will validate your payment proof / reference number.</li>
                <li style="margin-bottom:0;"><strong>Approval Notification (1&ndash;2 Working Days):</strong> You will receive an email update once your application is officially approved or if any document needs clarification.</li>
            </ol>
        </div>

        <div style="text-align:center;margin-bottom:22px;">
            <a href="'.htmlspecialchars($portalUrl).'" style="display:inline-block;background:linear-gradient(135deg,#059669,#047857);color:#ffffff;text-decoration:none;font-size:14px;font-weight:700;padding:13px 28px;border-radius:10px;box-shadow:0 4px 10px rgba(5,150,105,0.25);">
                View Application Status on AMIS Portal
            </a>
        </div>

        <p style="color:#64748b;font-size:12.5px;line-height:1.6;text-align:center;margin:0;">
            If you have any questions or need immediate assistance, you may reply to this email or contact the AMIS Admissions Office through your portal dashboard at <a href="'.htmlspecialchars($portalUrl).'" style="color:#059669;font-weight:700;">aes.amis.edu.ph</a>.
        </p>
    </td></tr>

    <tr><td style="background:#f8fafc;padding:18px 32px;text-align:center;border-top:1px solid #e2e8f0;">
        <p style="color:#64748b;font-size:11.5px;font-weight:600;margin:0 0 4px;">Al Munawwara Islamic School (AMIS) &bull; Davao City, Philippines</p>
        <p style="color:#94a3b8;font-size:11px;margin:0;">&copy; '.date('Y').' Al Munawwara Islamic School. All rights reserved.</p>
    </td></tr>
</table>
</td></tr>
</table>
</body></html>';

            $subject = "Thank You for Registering at AMIS — Application {$refNo} ({$displayName}) Pending Administration Review";

            $this->mailRotator->sendHtml(
                to: $normalizedEmail,
                subject: $subject,
                html: $html,
                fromName: 'Al Munawwara Islamic School Admissions',
                replyTo: 'noreply@amis.edu.ph',
            );

            if (! $isFake) {
                Cache::put($dedupeKey, true, now()->addSeconds(45));
            }

            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to send enrollment confirmation email to {$normalizedEmail}: ".$e->getMessage());

            return false;
        }
    }
}
