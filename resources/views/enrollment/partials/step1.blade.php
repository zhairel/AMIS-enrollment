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
        <x-form-field-label required>Grade Level</x-form-field-label>
        <select name="grade_level" class="select-input" :class="{ 'is-invalid-field': isFieldInvalid('grade_level') }" x-model="form.grade_level" @change="loadShiftsForGrade()">
            <option value="">Select Grade Level</option>
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

    <!-- Old Student Profile Confirmation Modal / Popup -->
    <div x-show="showOldStudentModal" 
         x-cloak 
         class="student-confirm-overlay" 
         style="position: fixed; inset: 0; z-index: 999999; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; padding: 1.25rem;"
         @keydown.escape.window="closeOldStudentModal()"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="student-confirm-modal"
             @click.outside="closeOldStudentModal()"
             style="max-width: 500px; width: 100%; background: #ffffff; border-radius: 24px; border: 1.5px solid #e2e8f0; box-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.25); overflow: hidden; position: relative;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">

            <!-- Modal Header with Institutional Green Gradient -->
            <div style="background: linear-gradient(135deg, #064e3b 0%, #047857 60%, #065f46 100%); padding: 1.5rem 1.75rem 1.35rem; color: #ffffff; position: relative; overflow: hidden;">
                <!-- Ambient glow circle -->
                <div style="position: absolute; right: -25px; bottom: -25px; width: 120px; height: 120px; border-radius: 50%; background: radial-gradient(circle, rgba(52, 211, 153, 0.25) 0%, transparent 70%); pointer-events: none;"></div>

                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; position: relative; z-index: 1;">
                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); color: #ffffff; flex-shrink: 0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10 12 5 2 10l10 5 10-5Z"/>
                                <path d="M6 12v5c3 2 9 2 12 0v-5"/>
                                <path d="M22 10v6"/>
                            </svg>
                        </span>
                        <div>
                            <span style="font-size: 0.72rem; font-weight: 800; color: #a7f3d0; text-transform: uppercase; letter-spacing: 0.08em; display: block;">Official Student Record</span>
                            <h3 style="margin: 2px 0 0; font-size: 1.25rem; font-weight: 900; color: #ffffff; letter-spacing: -0.01em;">Student Profile Found</h3>
                        </div>
                    </div>
                    <button type="button" 
                            @click="closeOldStudentModal()" 
                            aria-label="Close modal"
                            style="background: rgba(255, 255, 255, 0.15); border: none; color: #ffffff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.background='rgba(239, 68, 68, 0.4)';"
                            onmouseout="this.style.background='rgba(255, 255, 255, 0.15)';">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body with Student Photo & Details -->
            <div style="padding: 1.6rem 1.75rem 1.5rem; background: #ffffff;">
                <template x-if="foundStudent">
                    <div>
                        <!-- Student Photo & Headline Stack -->
                        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 1.35rem;">
                            <!-- Photo Container with Fallback Avatar -->
                            <div style="position: relative; margin-bottom: 0.85rem;">
                                <template x-if="foundStudent.photo_url">
                                    <img :src="foundStudent.photo_url" 
                                         :alt="foundStudent.full_name" 
                                         style="width: 104px; height: 104px; border-radius: 20px; object-fit: cover; border: 3.5px solid #059669; box-shadow: 0 10px 25px -4px rgba(5, 150, 105, 0.25); display: block;"
                                         @error="$el.style.display = 'none'; if ($refs.fallbackAvatar) $refs.fallbackAvatar.style.display = 'flex';">
                                </template>

                                <!-- Fallback Avatar (shown if photo_url is missing or fails to load) -->
                                <div x-ref="fallbackAvatar" 
                                     :style="foundStudent.photo_url ? 'display: none;' : 'display: flex;'"
                                     style="width: 104px; height: 104px; border-radius: 20px; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 3px solid #10b981; color: #047857; font-size: 2rem; font-weight: 900; align-items: center; justify-content: center; box-shadow: 0 10px 25px -4px rgba(5, 150, 105, 0.2);">
                                    <span x-text="((foundStudent.first_name ? foundStudent.first_name[0] : '') + (foundStudent.last_name ? foundStudent.last_name[0] : '')).toUpperCase() || 'S'"></span>
                                </div>

                                <!-- Verified Badge Icon -->
                                <div style="position: absolute; bottom: -4px; right: -4px; width: 30px; height: 30px; border-radius: 50%; background: #059669; border: 2.5px solid #ffffff; color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Full Name -->
                            <h3 style="margin: 0; font-size: 1.35rem; font-weight: 900; color: #0f172a; letter-spacing: -0.015em; text-transform: uppercase;" x-text="foundStudent.full_name"></h3>
                            
                            <!-- Badges Row -->
                            <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 0.35rem; flex-wrap: wrap;">
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.65rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.04em;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669;"></span>
                                    Verified AMIS Student
                                </span>
                                <template x-if="foundStudent.grade_level">
                                    <span style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #334155; font-size: 0.72rem; font-weight: 750; padding: 0.2rem 0.65rem; border-radius: 999px;" x-text="'Record: ' + foundStudent.grade_level"></span>
                                </template>
                            </div>
                        </div>

                        <!-- Info Details Grid -->
                        <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 1rem 1.15rem; display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.85rem; margin-bottom: 1.25rem;">
                            <div>
                                <span style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.15rem;">Student ID</span>
                                <span style="font-size: 0.95rem; font-weight: 850; color: #0f172a;" x-text="foundStudent.student_number || '—'"></span>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.15rem;">LRN</span>
                                <span style="font-size: 0.95rem; font-weight: 850; color: #0f172a;" x-text="foundStudent.lrn || '—'"></span>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.15rem;">Date of Birth</span>
                                <span style="font-size: 0.9rem; font-weight: 750; color: #0f172a;" x-text="foundStudent.date_of_birth ? new Date(foundStudent.date_of_birth).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '—'"></span>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.15rem;">Gender</span>
                                <span style="font-size: 0.9rem; font-weight: 750; color: #0f172a; text-transform: capitalize;" x-text="foundStudent.gender || '—'"></span>
                            </div>
                        </div>

                        <!-- Success Notice -->
                        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 0.7rem 0.95rem; margin-bottom: 1.35rem; display: flex; align-items: center; gap: 0.6rem; color: #065f46; font-size: 0.8rem; font-weight: 600;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                            <span>Existing student record matched! Personal info, addresses, and contacts are auto-populated.</span>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 0.65rem; flex-direction: column;">
                            <button type="button" 
                                    @click="confirmFoundStudent()" 
                                    class="btn-primary" 
                                    style="width: 100%; min-height: 48px; border-radius: 12px; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #ffffff; font-size: 0.92rem; font-weight: 850; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.55rem; text-transform: uppercase; letter-spacing: 0.04em; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35); transition: all 0.2s;"
                                    onmouseover="this.style.boxShadow='0 6px 20px rgba(5, 150, 105, 0.45)'; this.style.transform='translateY(-1px)';"
                                    onmouseout="this.style.boxShadow='0 4px 14px rgba(5, 150, 105, 0.35)'; this.style.transform='none';">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span>YES, THIS IS MY CHILD &bull; CONTINUE</span>
                            </button>

                            <button type="button" 
                                    @click="closeOldStudentModal()" 
                                    style="width: 100%; min-height: 42px; border-radius: 12px; background: #ffffff; border: 1.5px solid #cbd5e1; color: #64748b; font-size: 0.84rem; font-weight: 750; cursor: pointer; text-transform: uppercase; letter-spacing: 0.04em; transition: all 0.15s;"
                                    onmouseover="this.style.background='#f8fafc'; this.style.color='#0f172a'; this.style.borderColor='#94a3b8';"
                                    onmouseout="this.style.background='#ffffff'; this.style.color='#64748b'; this.style.borderColor='#cbd5e1';">
                                <span>CLOSE / EDIT SEARCH</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
