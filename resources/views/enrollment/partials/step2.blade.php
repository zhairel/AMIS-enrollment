<div x-transition class="student-info-flow">
    {{-- Continuing Student Auto-Filled Notice --}}
    <template x-if="form.student_type === 'Old' && (form.amis_student_id || foundStudent)">
        <div style="background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%); border: 1.5px solid #6ee7b7; border-radius: 14px; padding: 0.9rem 1.15rem; display: flex; align-items: center; justify-content: space-between; gap: 0.85rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="width: 34px; height: 34px; border-radius: 10px; background: #059669; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </span>
                <div>
                    <div style="font-size: 0.84rem; font-weight: 850; color: #065f46;">
                        Continuing Student Record Loaded
                        <span x-show="form.amis_student_id" style="font-weight: 800; color: #047857;" x-text="'(ID #' + form.amis_student_id + ')'"></span>
                    </div>
                    <div style="font-size: 0.77rem; color: #047857; font-weight: 600;">
                        Profile details were auto-filled from AMIS 2026–2027 records. Please review and update any changed fields.
                    </div>
                </div>
            </div>
            <span x-show="form.grade_level" style="background: #ffffff; border: 1px solid #a7f3d0; color: #065f46; font-size: 0.74rem; font-weight: 800; padding: 0.3rem 0.75rem; border-radius: 999px; text-transform: uppercase;" x-text="'Enrolling: ' + form.grade_level"></span>
        </div>
    </template>

    {{-- Card 1: Learner Legal Name --}}
    <section class="enrollment-section-card">
        <div class="enrollment-section-card-header">
            <div class="enrollment-section-card-title-wrap">
                <span class="enrollment-section-card-num">1</span>
                <div>
                    <h3 class="enrollment-section-card-title">Learner's Full Legal Name</h3>
                    <p class="enrollment-section-card-subtitle">Enter the learner's exact legal name as written on the <strong>PSA / NSO Birth Certificate</strong>.</p>
                </div>
            </div>
        </div>

        <div class="enrollment-two-col-grid">
            <x-form-input label="Last Name (Surname)" name="last_name" required :col="1" placeholder="e.g. Compania" x-model="form.last_name" />
            <x-form-input label="First Name (Given Name)" name="first_name" required :col="1" placeholder="e.g. Abdul Nasser" x-model="form.first_name" />
            <x-form-input label="Middle Name" name="middle_name" :col="1" placeholder="e.g. Santos (Mother's Maiden Surname)" x-model="form.middle_name" />
            <x-form-input label="Suffix / Name Ext." name="suffix" :col="1" placeholder="e.g. Jr., III (leave blank if none)" x-model="form.suffix" />
        </div>
    </section>

    {{-- Card 2: DepEd LRN & Birth / Demographic Details --}}
    <section class="enrollment-section-card">
        <div class="enrollment-section-card-header">
            <div class="enrollment-section-card-title-wrap">
                <span class="enrollment-section-card-num">2</span>
                <div>
                    <h3 class="enrollment-section-card-title">DepEd LRN &amp; Birth Information</h3>
                    <p class="enrollment-section-card-subtitle">Official DepEd Learner Reference Number and personal demographic details.</p>
                </div>
            </div>
        </div>

        <div class="enrollment-two-col-grid">
            <div class="form-group">
                <x-form-field-label optional>DepEd LRN (12-Digit Number)</x-form-field-label>
                <input type="text" inputmode="numeric" name="lrn" class="plain-input" placeholder="12-digit LRN from SF9 Report Card (or leave blank)"
                    x-model="form.lrn" maxlength="12" @input="form.lrn = $event.target.value.replace(/\D/g, '').slice(0, 12)">
                <span class="field-hint">Found on the SF9 / Form 138 Report Card. Saved as "NA" if left blank.</span>
            </div>

            <div class="form-group">
                <x-form-field-label required>Sex / Gender</x-form-field-label>
                <select name="gender" class="select-input" :class="{ 'is-invalid-field': isFieldInvalid('gender') }" x-model="form.gender">
                    <option value="">Select Sex</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <span class="field-hint">As recorded on official school and PSA documents.</span>
            </div>

            <div class="form-group">
                <x-form-date label="Date of Birth" name="date_of_birth" required x-model="form.date_of_birth" />
            </div>

            <div class="form-group">
                <x-form-field-label required>Place of Birth (City / Province)</x-form-field-label>
                <input type="text" name="place_of_birth" class="plain-input" :class="{ 'is-invalid-field': isFieldInvalid('place_of_birth') }" placeholder="e.g. Davao City, Davao del Sur" x-model="form.place_of_birth">
                <span class="field-hint">City/Municipality and Province of birth.</span>
            </div>

            <div class="form-group">
                <x-form-field-label required>Religion</x-form-field-label>
                <input type="text" name="religion" class="plain-input" :class="{ 'is-invalid-field': isFieldInvalid('religion') }" placeholder="e.g. Islam" x-model="form.religion">
            </div>

            <div class="form-group">
                <x-form-field-label optional>Mother Tongue / Ethnic Group</x-form-field-label>
                <input type="text" name="ethnicity" class="plain-input" placeholder="e.g. Maranao, Maguindanaon, Tausug, Bisaya" x-model="form.ethnicity">
            </div>
        </div>
    </section>
</div>
