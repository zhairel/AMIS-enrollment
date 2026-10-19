<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::updated(function ($student) {
            if ($student->wasChanged('grade_level') && $student->applicant) {
                $applicant = $student->applicant;
                $applicant->grade_level = $student->grade_level;
                $applicant->saveQuietly();
            }
            if ($student->wasChanged('grade_level') && $student->account) {
                $account = $student->account;
                $account->grade_level = $student->grade_level;
                $account->saveQuietly();
            }
        });
    }

    protected $fillable = [
        'user_id',
        'enrollment_applicant_id',
        'student_number',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'contact_number',
        'guardian_name',
        'guardian_contact',
        'guardian_relationship',
        'school_email',
        'temp_password',
        'grade_level',
        'school_year',
        'section',
        'student_id_url',
        'credentials_sent_at',
    ];

    protected $casts = [
        'credentials_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(EnrollmentApplicant::class, 'enrollment_applicant_id');
    }

    public function account(): HasOne
    {
        return $this->hasOne(StudentAccount::class);
    }

    /**
     * Term-based enrollments for this student.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * The most recent enrollment record for this student.
     */
    public function latestEnrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class)->latestOfMany();
    }

    /**
     * Full name attribute accessor.
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name]);
        return implode(' ', $parts);
    }
}
