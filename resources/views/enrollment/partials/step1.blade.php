<div x-transition class="setup-flow" :class="{ 'sibling-schedule-active': useSiblingSchedule }">
    <!-- Student Type -->
    <section class="setup-section">
        <x-form-field-label required>Are you an OLD or NEW AMIS student?</x-form-field-label>
        <div class="choice-card-grid">
            <x-enrollment.choice-card
                label="OLD AMIS Student"
                description="Previously enrolled at AMIS"
                icon="school"
                click="toggleStudentType('Old')"
                selected="form.student_type === 'Old'"
                invalid="isFieldInvalid('student_type')"
            />
            <x-enrollment.choice-card
                label="NEW AMIS Student"
                description="First time enrolling here"
                icon="spark"
                click="toggleStudentType('New')"
                selected="form.student_type === 'New'"
                invalid="isFieldInvalid('student_type')"
            />
            <x-enrollment.choice-card
                label="TRANSFER Student"
                description="Transferring from another school"
                icon="transfer"
                click="toggleStudentType('Transferee')"
                selected="form.student_type === 'Transferee'"
                invalid="isFieldInvalid('student_type')"
            />
        </div>
        <input type="hidden" name="student_type" :value="form.student_type">
    </section>

    <section class="setup-section old-student-record-section" x-show="form.student_type === 'Old'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
        <div style="display: flex; align-items: center; gap: 0.5rem; margin: 0.5rem 0 0.85rem;">
            <span style="background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #047857; font-size: 0.76rem; font-weight: 800; padding: 0.28rem 0.85rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.05em;">Old Student Record</span>
        </div>
        <div class="search-student-card" style="padding: 1.4rem 1.5rem; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 18px; display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">Search and Auto-fill Student Profile</h4>
                <p style="font-size: 0.88rem; color: #64748b; font-weight: 450; margin: 0.35rem 0 0 0; line-height: 1.5;">
                    Provide your child's Student ID (e.g. 260001) or LRN and Date of Birth to search and auto-populate all form fields.
                </p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 750; color: #334155; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.45rem;">
                        Student ID or LRN <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" x-model="searchStudentNumber" placeholder="e.g. 260101 or LRN" 
                           class="plain-input"
                           style="text-transform: uppercase; font-weight: 600;"
                           :disabled="searchingStudent"
                           @keydown.enter.prevent="searchAndAutofillStudent()">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 750; color: #334155; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.45rem;">
                        Date of Birth <span style="font-size:0.75rem; font-weight:600; color:#64748b; text-transform:none;">(Optional verification)</span>
                    </label>
                    <input type="date" x-model="searchDOB" 
                           class="date-input"
                           style="font-weight: 600;"
                           :disabled="searchingStudent"
                           @keydown.enter.prevent="searchAndAutofillStudent()">
                </div>
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; padding-top: 0.35rem;">
                <div style="font-size: 0.9rem; font-weight: 700;">
                    <span x-show="searchError" x-text="searchError" style="color: #dc2626;" x-cloak></span>
                    <span x-show="searchSuccess" style="color: #047857;" x-cloak>
                        <span x-text="searchSuccess"></span>
                        <button type="button" @click="showOldStudentModal = true" style="margin-left: 0.5rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; font-size: 0.74rem; font-weight: 800; padding: 0.22rem 0.65rem; border-radius: 6px; cursor: pointer; text-transform: uppercase; letter-spacing: 0.04em;">
                            VIEW PROFILE
                        </button>
                    </span>
                </div>
                <button type="button" @click="searchAndAutofillStudent()" :disabled="searchingStudent || !searchStudentNumber"
                        class="btn-search-autofill"
                        :class="{ 'is-disabled': !searchStudentNumber, 'is-loading': searchingStudent }">
                    <svg x-show="searchingStudent" style="width: 16px; height: 16px; animation: spin 0.8s linear infinite;" fill="none" viewBox="0 0 24 24" x-cloak>
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="searchingStudent ? 'SEARCHING...' : 'SEARCH & AUTO-FILL'">SEARCH &amp; AUTO-FILL</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Grade Level: Appears after Student Type is chosen -->
    <section class="setup-section grade-level-section" x-show="form.student_type" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem; flex-wrap: wrap; gap: 0.5rem;">
            <x-form-field-label required>Grade Level</x-form-field-label>
            <template x-if="form.student_type === 'Old' && completedGradeLevel">
                <span style="font-size: 0.72rem; font-weight: 800; color: #047857; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 0.2rem 0.65rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.04em; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669;"></span>
                    Completed: <span x-text="completedGradeLevel"></span> &bull; Advancing to <span x-text="form.grade_level"></span>
                </span>
            </template>
        </div>
        <select name="grade_level" class="select-input" :class="{ 'is-invalid-field': isFieldInvalid('grade_level') }" x-model="form.grade_level" @change="loadShiftsForGrade()">
            <option value="">Select Grade Level</option>
            @foreach($gradeLevels as $grade)
                <option value="{{ $grade->name }}" 
                        :disabled="isGradeDisabled('{{ $grade->name }}')"
                        x-text="getGradeOptionLabel('{{ $grade->name }}')">
                    {{ $grade->name }}
                </option>
            @endforeach
        </select>
        <template x-if="form.student_type === 'Old' && completedGradeLevel">
            <span style="display: block; font-size: 0.78rem; color: #64748b; font-weight: 600; margin-top: 0.35rem;">
                ℹ️ Completed grades (<span x-text="(disabledGrades || []).join(', ')"></span>) are locked to ensure correct academic progression.
            </span>
        </template>
    </section>

    <!-- Learning Mode: Appears after Grade Level is chosen -->
    <section class="setup-section learning-mode-section" x-show="form.student_type && form.grade_level" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
        <x-form-field-label required>Which type do you prefer?</x-form-field-label>

        <div class="choice-card-grid">
            <x-enrollment.choice-card
                label="Face-to-Face"
                description="Attend onsite classes"
                icon="building"
                click="toggleLearningMode('Face-to-Face')"
                selected="form.learning_mode_main === 'Face-to-Face'"
                invalid="isFieldInvalid('learning_mode_main')"
            />
            <x-enrollment.choice-card
                label="Flexible Online Learning"
                description="Online classes with shift selection"
                icon="monitor"
                click="toggleLearningMode('Flexible Online Learning')"
                selected="form.learning_mode_main === 'Flexible Online Learning'"
                invalid="isFieldInvalid('learning_mode_main')"
            />
        </div>

        <div x-show="form.learning_mode_main === 'Flexible Online Learning'" x-cloak>
            <div class="setup-section setup-section-nested timezone-picker">
                <x-form-field-label required>Your timezone</x-form-field-label>
                <div class="timezone-control">
                    <select class="select-input" :class="{ 'is-invalid-field': isFieldInvalid('timezone') }" x-model="form.timezone">
                        <option value="">Select timezone</option>
                        <option value="Asia/Manila">Philippines (PHT / UTC+8)</option>
                        <option value="Asia/Dubai">UAE / Dubai (UTC+4)</option>
                        <option value="Asia/Riyadh">Saudi Arabia / Riyadh (UTC+3)</option>
                        <option value="Asia/Qatar">Qatar / Doha (UTC+3)</option>
                        <option value="Asia/Kuwait">Kuwait (UTC+3)</option>
                        <option value="America/New_York">Eastern Time - US/Canada</option>
                        <option value="America/Los_Angeles">Pacific Time - US/Canada</option>
                        <option value="Other">Others</option>
                    </select>
                    <button type="button" class="location-button" @click="detectTimezone()" :disabled="detectingTimezone" aria-label="Detect timezone">
                        <svg class="location-button-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4"/><path d="M12 18v4"/><path d="M2 12h4"/><path d="M18 12h4"/><circle cx="12" cy="12" r="4"/></svg>
                        <span x-text="detectingTimezone ? 'Detecting...' : 'Detect timezone'"></span>
                    </button>
                </div>
                <p class="field-hint" x-show="timezoneMessage" x-text="timezoneMessage"></p>
            </div>

            <div class="setup-section setup-section-nested setup-shift-section">
                <x-form-field-label required>Which shift do you prefer?</x-form-field-label>

                <x-enrollment.schedule-notice />
                <div class="shift-card-grid">
                    <template x-if="gradeShifts.length === 0 && form.grade_level">
                        <div style="grid-column:1/-1;text-align:center;padding:1rem;color:#6b7280;font-size:0.85rem;">
                            Loading shifts...
                        </div>
                    </template>
                    <template x-for="shift in gradeShifts" :key="shift.name">
                        <button type="button"
                            :disabled="shift.is_full"
                            @click="toggleLearningShift(shift.name)"
                            class="shift-card"
                            :class="{ 'is-selected': form.learning_mode_shift === shift.name, 'is-full': shift.is_full, 'is-limited': !shift.is_full && shift.available <= 5 }">
                            <div class="shift-card-topline">
                                <div class="shift-card-title" x-text="shift.name"></div>
                                <span class="shift-slot-badge" x-text="shift.is_full ? 'Full' : (shift.available <= 5 ? 'Limited slots' : 'Open slots')"></span>
                            </div>
                            <div class="shift-slot-meter">
                                <span class="shift-slot-meter-fill" :style="'width:' + Math.min(100, Math.round(((shift.capacity - shift.available) / shift.capacity) * 100)) + '%'"></span>
                            </div>
                            <div class="shift-slot-copy">
                                <strong x-text="shift.available"></strong> of <span x-text="shift.capacity"></span> slots available
                            </div>
                            <div class="shift-card-primary-time">
                                <img src="https://flagcdn.com/16x12/ph.png" width="16" height="12" alt="PH" class="flag-icon">
                                <span x-text="shift.pht_time_range"></span> <span>(PHT / UTC+8)</span>
                            </div>
                            <div class="shift-card-local-time">
                                <span>Local time guide</span>
                                <strong x-text="formatShiftTime(shift.start_time, shift.end_time)"></strong>
                            </div>
                        </button>
                    </template>
                    <template x-if="!form.grade_level">
                        <div style="grid-column:1/-1;text-align:center;padding:1rem;color:#9ca3af;font-size:0.85rem;">
                            Select a grade level first to see available shifts.
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <input type="hidden" name="learning_mode" :value="form.learning_mode">
    </section>
</div>

