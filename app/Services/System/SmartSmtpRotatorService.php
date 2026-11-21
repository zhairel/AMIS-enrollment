<?php

namespace App\Services\System;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Support\Testing\Fakes\MailFake;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use RuntimeException;

class SmartSmtpRotatorService
{
    /**
     * Conservative zero-penalty thresholds per Gmail account
     * (well below Google's 500/day and burst caps to avoid any penalty or lock).
     */
    private const GMAIL_DAILY_LIMIT = 400;
    private const GMAIL_HOURLY_LIMIT = 90;
    private const GMAIL_MINUTE_LIMIT = 12;

    /**
     * Conservative thresholds for Bluehost/cPanel Domain SMTP
     * (cPanel enforces a hard 200/hour cap per domain).
     */
    private const DOMAIN_DAILY_LIMIT = 1500;
    private const DOMAIN_HOURLY_LIMIT = 170;
    private const DOMAIN_MINUTE_LIMIT = 15;

    /**
     * Rotate to the next Gmail account after every N emails even within the same day
     * so no single Gmail account absorbs sustained bursts.
     */
    private const GMAIL_BATCH_CHUNK_SIZE = 20;

    public function __construct(
        private ?MicrosoftGraphMailService $microsoftGraphMail = null,
    ) {
        $this->microsoftGraphMail ??= app(MicrosoftGraphMailService::class);
        $this->registerDynamicGmailPool();
    }

    /**
     * Send an HTML email with automatic daily Gmail rotation and multi-transport failover.
     *
     * @return array{success: bool, mailer_used: string, message: string}
     */
    public function sendHtml(
        string $to,
        string $subject,
        string $html,
        ?string $fromName = null,
        ?string $replyTo = null,
    ): array {
        $fromName ??= (string) config('mail.from.name', 'Al Munawwara Islamic School');

        // Preserve PHPUnit Mail::fake() test behavior
        if (Mail::getFacadeRoot() instanceof MailFake) {
            Mail::html($html, function ($message) use ($to, $subject, $fromName, $replyTo) {
                $message->to($to)->subject($subject);
                $fromAddress = config('mail.from.address');
                if ($fromAddress) {
                    $message->from($fromAddress, $fromName);
                }
                if ($replyTo) {
                    $message->replyTo($replyTo);
                }
            });

            return [
                'success' => true,
                'mailer_used' => 'mail_fake',
                'message' => 'Captured by MailFake.',
            ];
        }

        $candidates = $this->getOrderedSmtpCandidates();
        $errors = [];

        // 1. Try healthy daily-rotated Gmail pool + Domain SMTP mailers
        foreach ($candidates as $mailer) {
            try {
                $mailerConfig = config("mail.mailers.{$mailer}", []);
                $fromAddress = $mailerConfig['from']['address']
                    ?? $mailerConfig['username']
                    ?? config('mail.from.address', 'amisonlinesupport@gmail.com');
                $resolvedFromName = $mailerConfig['from']['name'] ?? $fromName;

                Mail::mailer($mailer)->html($html, function ($message) use ($to, $subject, $fromAddress, $resolvedFromName, $replyTo) {
                    $message->to($to)->subject($subject);
                    if (filled($fromAddress) && filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
                        $message->from($fromAddress, $resolvedFromName);
                    }
                    if (filled($replyTo) && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                        $message->replyTo($replyTo);
                    }
                });

                $this->recordSuccess($mailer);

                Log::info("SmartSmtpRotator: Sent email to {$to} via [{$mailer}] ({$fromAddress}) | Subject: {$subject}");

                return [
                    'success' => true,
                    'mailer_used' => $mailer,
                    'message' => "Email sent via {$mailer}.",
                ];
            } catch (\Throwable $e) {
                $errors[$mailer] = $e->getMessage();
                $this->handleTransportFailure($mailer, $e);
            }
        }

        // 2. Failover to Microsoft Graph Mail API over HTTPS (Port 443 - immune to cPanel 200/hr SMTP limit)
        if ($this->microsoftGraphMail && $this->microsoftGraphMail->enabled()) {
            try {
                $graphPayload = [
                    'subject' => $subject,
                    'body' => [
                        'contentType' => 'HTML',
                        'content' => $html,
                    ],
                ];

                if (filled($replyTo) && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                    $graphPayload['replyTo'] = [[
                        'emailAddress' => ['address' => $replyTo],
                    ]];
                }

                $result = $this->microsoftGraphMail->send($to, $graphPayload);

                Log::info("SmartSmtpRotator: Sent email to {$to} via [{$result['mailer_used']}] (HTTPS Failover) | Subject: {$subject}");

                return $result;
            } catch (\Throwable $e) {
                $errors['microsoft_graph'] = $e->getMessage();
                Log::warning("SmartSmtpRotator: Microsoft Graph failover failed for {$to}: {$e->getMessage()}");
            }
        }

        // 3. Final Emergency Fallback: Local sendmail binary if not blocked by hourly domain cap
        if (! $this->isInCooldown('sendmail')) {
            try {
                Mail::mailer('sendmail')->html($html, function ($message) use ($to, $subject, $fromName, $replyTo) {
                    $message->to($to)->subject($subject);
                    $fromAddress = config('mail.mailers.inquiries.from.address')
                        ?: config('mail.from.address', 'noreply@amis.edu.ph');
                    if (filled($fromAddress) && filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
                        $message->from($fromAddress, $fromName);
                    }
                    if (filled($replyTo) && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                        $message->replyTo($replyTo);
                    }
                });

                $this->recordSuccess('sendmail');
                Log::info("SmartSmtpRotator: Sent email to {$to} via [sendmail] emergency fallback | Subject: {$subject}");

                return [
                    'success' => true,
                    'mailer_used' => 'sendmail',
                    'message' => 'Email sent via sendmail fallback.',
                ];
            } catch (\Throwable $e) {
                $errors['sendmail'] = $e->getMessage();
                $this->handleTransportFailure('sendmail', $e);
            }
        }

        $summary = collect($errors)
            ->map(fn ($msg, $key) => "[{$key}]: {$msg}")
            ->implode(' | ');

        Log::error("SmartSmtpRotator: All mail transports exhausted for {$to}. Errors: {$summary}");

        throw new RuntimeException("All email transports failed for {$to}. {$summary}");
    }

    /**
     * Send a Laravel Notification (such as SendOtpCode or AmisVerifyEmail) through the rotator.
     *
     * @return array{success: bool, mailer_used: string, message: string}
     */
    public function sendNotification(string $to, Notification $notification, mixed $notifiable = null): array
    {
        if (
            NotificationFacade::getFacadeRoot() instanceof NotificationFake
            || Mail::getFacadeRoot() instanceof MailFake
        ) {
            if ($notifiable && method_exists($notifiable, 'notify')) {
                $notifiable->notify($notification);
            } else {
                NotificationFacade::route('mail', $to)->notify($notification);
            }

            return [
                'success' => true,
                'mailer_used' => 'notification_fake',
                'message' => 'Captured by test fake.',
            ];
        }

        $targetNotifiable = $notifiable ?? (new AnonymousNotifiable)->route('mail', $to);

        if (! method_exists($notification, 'toMail')) {
            throw new RuntimeException('Notification does not support toMail().');
        }

        /** @var MailMessage $mailMessage */
        $mailMessage = $notification->toMail($targetNotifiable);
        $subject = (string) ($mailMessage->subject ?: config('app.name', 'AMIS Portal').' Notification');
        $html = (string) $mailMessage->render();

        return $this->sendHtml($to, $subject, $html);
    }

    /**
     * Build the ordered list of healthy SMTP candidates:
     * 1. Daily-rotated & load-balanced Gmail accounts first (zero-penalty batching)
     * 2. Domain SMTP (`inquiries`) second (unless Gmail pool is empty or cooling down)
     *
     * @return array<int, string>
     */
    public function getOrderedSmtpCandidates(): array
    {
        $gmailMailers = $this->getOrderedHealthyGmailMailers();
        $domainMailers = $this->getHealthyDomainMailers();

        return array_values(array_unique(array_merge($gmailMailers, $domainMailers)));
    }

    /**
     * Get all configured & healthy Gmail mailers rotated daily and balanced per-minute/batch.
     *
     * @return array<int, string>
     */
    public function getOrderedHealthyGmailMailers(): array
    {
        $configuredGmails = $this->getAllConfiguredGmailMailers();

        if ($configuredGmails === []) {
            return [];
        }

        // Daily automatic rotation anchor based on date (changes primary Gmail every day at midnight)
        $count = count($configuredGmails);
        $dailyAnchor = abs(crc32(now()->format('Y-m-d'))) % $count;

        $dailyRotated = array_merge(
            array_slice($configuredGmails, $dailyAnchor),
            array_slice($configuredGmails, 0, $dailyAnchor),
        );

        // Filter out accounts in cooldown or near zero-penalty limits
        $healthy = array_values(array_filter($dailyRotated, function (string $mailer): bool {
            if ($this->isInCooldown($mailer)) {
                return false;
            }

            if ($this->getCount($mailer, 'day') >= self::GMAIL_DAILY_LIMIT) {
                return false;
            }

            if ($this->getCount($mailer, 'hour') >= self::GMAIL_HOURLY_LIMIT) {
                return false;
            }

            if ($this->getCount($mailer, 'minute') >= self::GMAIL_MINUTE_LIMIT) {
                return false;
            }

            return true;
        }));

        if (count($healthy) <= 1) {
            return $healthy;
        }

        $rotationRank = array_flip($dailyRotated);

        // Sort by:
        // 1. Current minute load (prevents per-minute spikes on a single Gmail)
        // 2. 20-email batch chunk (switches Gmail every 20 emails automatically throughout the day)
        // 3. Today's daily rotation order
        usort($healthy, function (string $a, string $b) use ($rotationRank): int {
            $minCmp = $this->getCount($a, 'minute') <=> $this->getCount($b, 'minute');
            if ($minCmp !== 0) {
                return $minCmp;
            }

            $batchA = intdiv($this->getCount($a, 'day'), self::GMAIL_BATCH_CHUNK_SIZE);
            $batchB = intdiv($this->getCount($b, 'day'), self::GMAIL_BATCH_CHUNK_SIZE);
            if ($batchA !== $batchB) {
                return $batchA <=> $batchB;
            }

            return ($rotationRank[$a] ?? 999) <=> ($rotationRank[$b] ?? 999);
        });

        return $healthy;
    }

    /**
     * Discover all Gmail mailers with valid non-placeholder credentials.
     * Deduplicates by username so the same Gmail account is not tried twice under two names.
     *
     * @return array<int, string>
     */
    public function getAllConfiguredGmailMailers(): array
    {
        $this->registerDynamicGmailPool();

        $candidateNames = [
            'smtp',
            'smtp_backup',
            'smtp_backup_2',
            'smtp_backup_3',
            'smtp_backup_4',
        ];

        foreach (array_keys((array) config('mail.mailers', [])) as $mailerName) {
            if (str_starts_with((string) $mailerName, 'gmail_pool_')) {
                $candidateNames[] = (string) $mailerName;
            }
        }

        $valid = [];
        $seenUsernames = [];

        foreach (array_unique($candidateNames) as $name) {
            $cfg = config("mail.mailers.{$name}");
            if (! is_array($cfg)) {
                continue;
            }

            $host = strtolower((string) ($cfg['host'] ?? ''));
            $username = strtolower(trim((string) ($cfg['username'] ?? '')));
            $password = trim((string) ($cfg['password'] ?? ''));

            if (! $this->isValidCredential($username, $password)) {
                continue;
            }

            // Include Gmail hosts or any smtp_backup* / gmail_pool_* accounts
            $isGmail = str_contains($host, 'gmail.com')
                || str_ends_with($username, '@gmail.com')
                || str_starts_with($name, 'smtp_backup')
                || str_starts_with($name, 'gmail_pool_');

            if (! $isGmail) {
                continue;
            }

            if (isset($seenUsernames[$username])) {
                continue;
            }

            $seenUsernames[$username] = true;
            $valid[] = $name;
        }

        return $valid;
    }

    /**
     * Get healthy domain SMTP mailers (e.g. `inquiries` -> `noreply@amis.edu.ph`).
     *
     * @return array<int, string>
     */
    public function getHealthyDomainMailers(): array
    {
        $domainCandidates = ['inquiries', 'smtp_domain'];
        $healthy = [];

        // Also include `smtp` if it was configured with a custom domain instead of Gmail
        $primaryHost = strtolower((string) config('mail.mailers.smtp.host', ''));
        $primaryUser = strtolower((string) config('mail.mailers.smtp.username', ''));
        if ($primaryHost !== '' && ! str_contains($primaryHost, 'gmail.com') && ! str_ends_with($primaryUser, '@gmail.com')) {
            array_unshift($domainCandidates, 'smtp');
        }

        $seenUsernames = [];

        foreach (array_unique($domainCandidates) as $name) {
            $cfg = config("mail.mailers.{$name}");
            if (! is_array($cfg)) {
                continue;
            }

            $username = strtolower(trim((string) ($cfg['username'] ?? '')));
            $password = trim((string) ($cfg['password'] ?? ''));

            if (! $this->isValidCredential($username, $password)) {
                continue;
            }

            if (isset($seenUsernames[$username])) {
                continue;
            }

            if ($this->isInCooldown($name)) {
                continue;
            }

            if ($this->getCount($name, 'day') >= self::DOMAIN_DAILY_LIMIT) {
                continue;
            }

            if ($this->getCount($name, 'hour') >= self::DOMAIN_HOURLY_LIMIT) {
                continue;
            }

            if ($this->getCount($name, 'minute') >= self::DOMAIN_MINUTE_LIMIT) {
                continue;
            }

            $seenUsernames[$username] = true;
            $healthy[] = $name;
        }

        return $healthy;
    }

    /**
     * Dynamically parse `MAIL_GMAIL_POOL` ("email1:pass1,email2:pass2") into runtime mailers.
     */
    private function registerDynamicGmailPool(): void
    {
        $rawPool = trim((string) config('mail.gmail_pool', ''));
        if ($rawPool === '') {
            return;
        }

        $entries = preg_split('/[\r\n,;]+/', $rawPool) ?: [];
        $index = 1;

        foreach ($entries as $entry) {
            $entry = trim($entry);
            if ($entry === '' || ! str_contains($entry, ':')) {
                continue;
            }

            [$email, $password] = array_map('trim', explode(':', $entry, 2));
            $password = str_replace(' ', '', $password); // Strip spaces from 16-char Google App Passwords

            if (! $this->isValidCredential($email, $password)) {
                continue;
            }

            $mailerName = "gmail_pool_{$index}";
            if (! is_array(config("mail.mailers.{$mailerName}"))) {
                config([
                    "mail.mailers.{$mailerName}" => [
                        'transport' => 'smtp',
                        'host' => 'smtp.gmail.com',
                        'port' => 587,
                        'encryption' => 'tls',
                        'username' => $email,
                        'password' => $password,
                        'timeout' => 15,
                        'from' => [
                            'address' => $email,
                            'name' => config('mail.from.name', 'Al Munawwara Islamic School'),
                        ],
                    ],
                ]);
            }

            $index++;
        }
    }

    private function isValidCredential(string $username, string $password): bool
    {
        if ($username === '' || $password === '') {
            return false;
        }

        if (filter_var($username, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        $placeholders = [
            'your-email@gmail.com',
            'your-app-password',
            'null',
            'none',
            'changeme',
            'example@gmail.com',
        ];

        if (in_array(strtolower($username), $placeholders, true) || in_array(strtolower($password), $placeholders, true)) {
            return false;
        }

        return true;
    }

    private function recordSuccess(string $mailer): void
    {
        $this->incrementCount($mailer, 'minute', now()->addMinutes(2));
        $this->incrementCount($mailer, 'hour', now()->addHours(2));
        $this->incrementCount($mailer, 'day', now()->addDays(2));
    }

    private function handleTransportFailure(string $mailer, \Throwable $e): void
    {
        $msg = strtolower($e->getMessage());

        // Detect hard hourly/daily quota or authentication errors vs transient connection errors
        $isDailyQuota = str_contains($msg, 'daily user sending limit exceeded')
            || str_contains($msg, '5.4.5');

        $isHourlyDomainQuota = str_contains($msg, 'exceeded the max emails per hour')
            || (str_contains($msg, '550') && str_contains($msg, 'hour'));

        $isAuthError = str_contains($msg, '535')
            || str_contains($msg, 'username and password not accepted')
            || str_contains($msg, 'authentication failed');

        if ($isDailyQuota) {
            $cooldownMinutes = 360; // 6 hours cooldown for daily limit
        } elseif ($isHourlyDomainQuota) {
            $cooldownMinutes = 60; // 60 minutes cooldown for Bluehost 200/hr domain limit
            // If sendmail or inquiries hits the domain cap, cool down both since they share the domain hourly cap
            if (in_array($mailer, ['inquiries', 'sendmail', 'smtp_domain'], true)) {
                $this->setCooldown('inquiries', 60, $e->getMessage());
                $this->setCooldown('sendmail', 60, $e->getMessage());
            }
        } elseif ($isAuthError) {
            $cooldownMinutes = 180; // 3 hours cooldown for bad credentials
        } else {
            $cooldownMinutes = 10; // 10 minutes cooldown for transient rate/connection errors
        }

        $this->setCooldown($mailer, $cooldownMinutes, $e->getMessage());

        Log::warning("SmartSmtpRotator: Mailer [{$mailer}] failed and placed on {$cooldownMinutes}m cooldown. Error: {$e->getMessage()}");
    }

    public function isInCooldown(string $mailer): bool
    {
        return Cache::has($this->cooldownKey($mailer));
    }

    public function setCooldown(string $mailer, int $minutes, string $reason = ''): void
    {
        Cache::put($this->cooldownKey($mailer), [
            'mailer' => $mailer,
            'reason' => $reason,
            'until' => now()->addMinutes($minutes)->toIso8601String(),
        ], now()->addMinutes($minutes));
    }

    public function clearCooldown(string $mailer): void
    {
        Cache::forget($this->cooldownKey($mailer));
    }

    public function getCount(string $mailer, string $window): int
    {
        return (int) Cache::get($this->counterKey($mailer, $window), 0);
    }

    private function incrementCount(string $mailer, string $window, \DateTimeInterface $expiresAt): void
    {
        $key = $this->counterKey($mailer, $window);
        Cache::add($key, 0, $expiresAt);
        Cache::increment($key);
    }

    private function counterKey(string $mailer, string $window): string
    {
        $suffix = match ($window) {
            'minute' => now()->format('YmdHi'),
            'hour' => now()->format('YmdH'),
            default => now()->format('Ymd'),
        };

        return "smart_smtp_quota_{$mailer}_{$window}_{$suffix}";
    }

    private function cooldownKey(string $mailer): string
    {
        return "smart_smtp_cooldown_{$mailer}";
    }

    /**
     * Diagnostic summary of all mail transports, today's active primary Gmail, and current quota usage.
     */
    public function statusReport(): array
    {
        $gmails = $this->getAllConfiguredGmailMailers();
        $orderedHealthyGmails = $this->getOrderedHealthyGmailMailers();

        $report = [
            'date' => now()->format('Y-m-d'),
            'active_primary_gmail' => $orderedHealthyGmails[0] ?? null,
            'healthy_gmail_order' => $orderedHealthyGmails,
            'healthy_domain_mailers' => $this->getHealthyDomainMailers(),
            'microsoft_graph_enabled' => $this->microsoftGraphMail?->enabled() ?? false,
            'mailers' => [],
        ];

        foreach (array_unique(array_merge($gmails, ['inquiries', 'sendmail'])) as $mailer) {
            $cfg = config("mail.mailers.{$mailer}", []);
            $report['mailers'][$mailer] = [
                'username' => $cfg['username'] ?? ($mailer === 'sendmail' ? 'local-sendmail' : null),
                'in_cooldown' => $this->isInCooldown($mailer),
                'cooldown_info' => Cache::get($this->cooldownKey($mailer)),
                'sent_minute' => $this->getCount($mailer, 'minute'),
                'sent_hour' => $this->getCount($mailer, 'hour'),
                'sent_today' => $this->getCount($mailer, 'day'),
            ];
        }

        return $report;
    }
}
