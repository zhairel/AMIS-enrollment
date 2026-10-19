<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollments';

    protected $fillable = [
        'student_id',
        'academic_year',
        'student_type',
        'grade_level',
        'year_level',
        'status',
    ];

    /**
     * Relationship: The student belonging to this enrollment record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Support year_level alias for grade_level.
     */
    public function getYearLevelAttribute(): ?string
    {
        return $this->attributes['grade_level'] ?? null;
    }

    public function setYearLevelAttribute($value): void
    {
        $this->attributes['grade_level'] = $value;
    }
}
