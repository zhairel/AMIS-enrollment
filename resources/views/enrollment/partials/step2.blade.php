<div x-transition class="student-info-flow">
    <section class="student-section">
        <div class="form-group">
            <x-form-field-label optional>DepEd LRN (12-Digit Learner Reference Number)</x-form-field-label>
            <input type="text" inputmode="numeric" name="lrn" class="plain-input" placeholder="Enter 12-digit DepEd LRN (or leave blank if Kindergarten / Transferee without LRN)"
                x-model="form.lrn" maxlength="12" @input="form.lrn = $event.target.value.replace(/\D/g, '').slice(0, 12)">
            <span class="field-hint">Found on the learner's SF9 / Form 138 Report Card. Will be saved as "NA" if left blank.</span>
        </div>
    </section>

    <section class="student-section">
        <div style="font-size: 0.78rem; font-weight: 700; color: #047857; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; padding: 0.55rem 0.85rem; margin-bottom: 0.25rem;">
            Please enter the learner's full legal name exactly as written on the <strong>PSA / NSO Birth Certificate</strong>.
        </div>
        <div class="student-name-grid">
            <x-form-input label="Last Name" name="last_name" required :col="1" placeholder="e.g. Dela Cruz" x-model="form.last_name" />
            <x-form-input label="First Name" name="first_name" required :col="1" placeholder="e.g. Ahmad" x-model="form.first_name" />
            <x-form-input label="Middle Name" name="middle_name" :col="1" placeholder="Mother's Maiden Surname" x-model="form.middle_name" />
            <x-form-input label="Suffix / Ext." name="suffix" :col="1" placeholder="Jr., III" x-model="form.suffix" />
        </div>
    </section>

    <section class="student-section">
        <div class="student-identity-grid">
            <div class="form-group">
                <x-form-field-label required>Sex / Gender</x-form-field-label>
                <select name="gender" class="select-input" :class="{ 'is-invalid-field': isFieldInvalid('gender') }" x-model="form.gender">
                    <option value="">Select Sex</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="form-group student-grid-date">
                <x-form-date label="Date of Birth" name="date_of_birth" required x-model="form.date_of_birth" />
            </div>
        </div>
    </section>

    <section class="student-section">
        <div class="student-origin-grid">
            <div class="form-group">
                <x-form-field-label required>Place of Birth (City / Province)</x-form-field-label>
                <input type="text" name="place_of_birth" class="plain-input" :class="{ 'is-invalid-field': isFieldInvalid('place_of_birth') }" placeholder="e.g. Davao City, Davao del Sur" x-model="form.place_of_birth">
            </div>

            <div class="form-group">
                <x-form-field-label required>Religion</x-form-field-label>
                <input type="text" name="religion" class="plain-input" :class="{ 'is-invalid-field': isFieldInvalid('religion') }" placeholder="e.g. Islam" x-model="form.religion">
            </div>

            <div class="form-group">
                <x-form-field-label optional>Mother Tongue / Ethnic Group</x-form-field-label>
                <input type="text" name="ethnicity" class="plain-input" placeholder="e.g. Maranao, Maguindanaon, Tausug, Tagalog, Bisaya" x-model="form.ethnicity">
                <span class="field-hint">Optional. Cultural, Moro, Indigenous, or ethnolinguistic group.</span>
            </div>
        </div>
    </section>
</div>
