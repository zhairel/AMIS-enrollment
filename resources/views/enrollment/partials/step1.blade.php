<div x-transition class="setup-flow" :class="{ 'sibling-schedule-active': useSiblingSchedule }">
    <!-- Student Type -->
    <section class="setup-section">
        <x-form-field-label required>Are you an OLD or NEW AMIS student?</x-form-field-label>
        <div class="choice-card-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
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
        <div class="form-divider" style="font-size: 0.95rem; font-weight: 800; color: #059669; text-transform: uppercase; letter-spacing: 0.05em; margin: 1.5rem 0 1rem 0;">OLD Student Record</div>
        <div class="search-student-card" style="padding: 1.5rem; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 18px; display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">Search and Auto-fill Student Profile</h4>
                <p style="font-size: 0.92rem; color: #475569; font-weight: 500; margin: 0.35rem 0 0 0; line-height: 1.5;">
                    Provide your child's Student ID (e.g. 260001) or LRN and Date of Birth to search and auto-populate all form fields.
                </p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.92rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.55rem;">Student ID or LRN</label>
                    <input type="text" x-model="searchStudentNumber" placeholder="e.g. 260001 or LRN" 
                           style="width: 100%; padding: 0.9rem 1.2rem; min-height: 50px; font-size: 1.05rem; font-weight: 600; text-transform: uppercase; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 14px; outline: none; transition: all 0.2s;"
                           onfocus="this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 4px rgba(5,150,105,0.12)';"
                           onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';"
                           :disabled="searchingStudent">
                </div>
                <div>
                    <label style="display: block; font-size: 0.92rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.55rem;">Date of Birth</label>
                    <input type="date" x-model="searchDOB" 
                           style="width: 100%; padding: 0.9rem 1.2rem; min-height: 50px; font-size: 1.05rem; font-weight: 600; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 14px; outline: none; transition: all 0.2s;"
                           onfocus="this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 4px rgba(5,150,105,0.12)';"
                           onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';"
                           :disabled="searchingStudent">
                </div>
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; padding-top: 0.35rem;">
                <div style="font-size: 0.95rem; font-weight: 700;">
                    <span x-show="searchError" x-text="searchError" style="color: #dc2626;" x-cloak></span>
                    <span x-show="searchSuccess" x-text="searchSuccess" style="color: #047857;" x-cloak></span>
                </div>
                <button type="button" @click="searchAndAutofillStudent()" :disabled="searchingStudent || !searchStudentNumber || !searchDOB"
                        style="display: inline-flex; align-items: center; gap: 0.55rem; padding: 0.85rem 1.85rem; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #ffffff; border: none; border-radius: 14px; font-size: 1rem; font-weight: 800; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);"
                        onmouseover="this.style.boxShadow='0 6px 18px rgba(5, 150, 105, 0.4)';"
                        onmouseout="this.style.boxShadow='0 4px 12px rgba(5, 150, 105, 0.3)';">
                    <svg x-show="searchingStudent" style="width: 18px; height: 18px; animation: spin 0.8s linear infinite;" fill="none" viewBox="0 0 24 24" x-cloak>
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="searchingStudent ? 'Searching...' : 'Search & Auto-fill'"></span>
                </button>
            </div>
        </div>
    </section>

    <!-- Grade Level: Appears after Student Type is chosen -->
    <section class="setup-section grade-level-section" x-show="form.student_type" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
        <x-form-field-label required>Grade Level</x-form-field-label>
        <select name="grade_level" class="select-input" :class="{ 'is-invalid-field': isFieldInvalid('grade_level') }" x-model="form.grade_level" @change="loadShiftsForGrade()">
            <option value="">Select</option>
            @foreach($gradeLevels as $grade)
                <option value="{{ $grade->name }}">{{ $grade->name }}</option>
            @endforeach
        </select>
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
