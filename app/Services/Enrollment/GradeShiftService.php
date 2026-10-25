<?php

namespace App\Services\Enrollment;

use App\Models\EnrollmentShift;
use App\Models\GradeLevel;
use App\Models\GradeShiftSlot;
use Illuminate\Support\Collection;

class GradeShiftService
{
    public const SCHOOL_YEAR = '2027-2028';

    public static function activeSchoolYear(): string
    {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('school_years')) {
            try {
                $active = \Illuminate\Support\Facades\DB::table('school_years')
                    ->where('is_active', true)
                    ->value('code');
                if ($active) {
                    return $cached = $active;
                }
            } catch (\Throwable) {
            }
        }

        return $cached = self::SCHOOL_YEAR;
    }

    /**
     * Get all active grade levels (no slots shown in dropdown).
     */
    public function getGradeLevels(): Collection
    {
        return GradeLevel::active()
            ->forYear(self::activeSchoolYear())
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get all active shifts (base info only — slots depend on grade).
     */
    public function getShifts(): Collection
    {
        return EnrollmentShift::active()
            ->forYear(self::activeSchoolYear())
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get shift slots for a specific grade level.
     * Returns shifts with their available slots for that grade.
     */
    public function getShiftsForGrade(string $gradeName): Collection
    {
        $year = self::activeSchoolYear();
        $grade = GradeLevel::where('name', $gradeName)
            ->where('school_year', $year)
            ->first();

        if (!$grade) {
            return collect();
        }

        return GradeShiftSlot::where('grade_level_id', $grade->id)
            ->where('school_year', $year)
            ->where('is_active', true)
            ->with('shift')
            ->get()
            ->map(fn ($slot) => [
                'id' => $slot->shift->id,
                'name' => $slot->shift->name,
                'start_time' => substr($slot->shift->start_time, 0, 5),
                'end_time' => substr($slot->shift->end_time, 0, 5),
                'pht_time_range' => $slot->shift->pht_time_range,
                'capacity' => $slot->capacity,
                'available' => $slot->available_slots,
                'is_full' => $slot->isFull(),
            ]);
    }

    /**
     * Increment enrolled count for a grade+shift when enrollment is finalized.
     */
    public function incrementSlotCount(string $gradeName, string $learningMode): void
    {
        if (!str_starts_with($learningMode, 'Flexible Online Learning')) {
            return;
        }

        $parts = explode(' - ', $learningMode);
        $shiftName = end($parts);
        $year = self::activeSchoolYear();

        $grade = GradeLevel::where('name', $gradeName)->where('school_year', $year)->first();
        $shift = EnrollmentShift::where('name', $shiftName)->where('school_year', $year)->first();

        if ($grade && $shift) {
            GradeShiftSlot::where('grade_level_id', $grade->id)
                ->where('enrollment_shift_id', $shift->id)
                ->where('school_year', $year)
                ->increment('enrolled_count');
        }
    }
}
