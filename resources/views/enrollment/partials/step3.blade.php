<div x-transition class="student-info-flow">
    <input type="hidden" name="address" :value="compiledPresentAddress">

    {{-- Card 1: Present Residential Address --}}
    <section class="enrollment-section-card">
        <div class="enrollment-section-card-header">
            <div class="enrollment-section-card-title-wrap">
                <span class="enrollment-section-card-num">1</span>
                <div>
                    <h3 class="enrollment-section-card-title">Present Residential Address</h3>
                    <p class="enrollment-section-card-subtitle">Where the learner currently resides during the school year.</p>
                </div>
            </div>
        </div>

        <div class="address-contact-grid">
            <div class="form-group address-country-field">
                <x-form-field-label required>Country of Residence</x-form-field-label>
                <div class="country-combobox" x-data="{ open: false, search: '' }" @click.outside="open = false">
                    <button type="button" class="country-combobox-trigger" :class="{ 'is-invalid-field': isFieldInvalid('country') }" @click="open = !open">
                        <template x-if="selectedCountry?.flagPng">
                            <img :src="selectedCountry.flagPng" alt="" class="country-flag-img-static">
                        </template>
                        <span class="country-combobox-value" x-text="selectedCountry ? selectedCountry.name : (countriesLoading ? 'Loading countries...' : 'Search country...')"></span>
                        <svg class="country-combobox-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div x-show="open" x-transition class="country-combobox-menu">
                        <input type="text" class="country-combobox-search" placeholder="Search country..." x-model="search" @keydown.escape="open = false">
                        <div class="country-combobox-list">
                            <template x-for="country in filteredCountries(search)" :key="country.code">
                                <button type="button" class="country-combobox-option" @click="selectCountry(country); open = false; search = ''">
                                    <img :src="country.flagPng" alt="" class="country-option-flag">
                                    <span class="country-option-name" x-text="country.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="country" :value="form.country">
            </div>

            <div class="form-group address-postal-field">
                <x-form-field-label optional>Region / Province / State</x-form-field-label>
                <div class="country-combobox" x-data="{ open: false, search: '' }" @click.outside="open = false">
                    <button type="button" class="country-combobox-trigger" @click="open = !open; if (open) $nextTick(() => $refs.regionSearch?.focus())">
                        <span class="country-combobox-value" :style="!form.state_province ? 'color: #94a3b8; font-weight: 400;' : ''" x-text="form.state_province || 'Select or type Region / Province (e.g. Davao Region, BARMM, NCR)...'"></span>
                        <svg class="country-combobox-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div x-show="open" x-transition class="country-combobox-menu">
                        <input type="text" x-ref="regionSearch" class="country-combobox-search" placeholder="Search or type Region / Province..." x-model="search" @keydown.escape="open = false" @keydown.enter.prevent="if (search.trim()) { selectRegion(search.trim()); open = false; search = ''; }">
                        <div class="country-combobox-list">
                            <template x-if="search.trim() && !filteredRegions(form.country, search).some(r => r.toLowerCase() === search.trim().toLowerCase())">
                                <button type="button" class="country-combobox-option" @click="selectRegion(search.trim()); open = false; search = ''">
                                    <span class="country-option-name" x-text="'Use &quot;' + search.trim().toUpperCase() + '&quot;'"></span>
                                </button>
                            </template>
                            <template x-for="region in filteredRegions(form.country, search)" :key="'region-' + region">
                                <button type="button" class="country-combobox-option" @click="selectRegion(region); open = false; search = ''">
                                    <span class="country-option-name" x-text="region"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="state_province" :value="form.state_province">
            </div>

            <div class="form-group address-country-field">
                <x-form-field-label optional>City / Municipality</x-form-field-label>
                <div class="country-combobox" x-data="{ open: false, search: '' }" @click.outside="open = false">
                    <button type="button" class="country-combobox-trigger" @click="open = !open; if (open) $nextTick(() => $refs.citySearch?.focus())">
                        <span class="country-combobox-value" :style="!form.city ? 'color: #94a3b8; font-weight: 400;' : ''" x-text="form.city || 'Select or type City / Municipality (e.g. Davao City, Cotabato City)...'"></span>
                        <svg class="country-combobox-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div x-show="open" x-transition class="country-combobox-menu">
                        <input type="text" x-ref="citySearch" class="country-combobox-search" placeholder="Search or type City..." x-model="search" @keydown.escape="open = false" @keydown.enter.prevent="if (search.trim()) { selectCity(search.trim()); open = false; search = ''; }">
                        <div class="country-combobox-list">
                            <template x-if="search.trim() && !filteredCities(form.country, form.state_province, search).some(c => c.toLowerCase() === search.trim().toLowerCase())">
                                <button type="button" class="country-combobox-option" @click="selectCity(search.trim()); open = false; search = ''">
                                    <span class="country-option-name" x-text="'Use &quot;' + search.trim().toUpperCase() + '&quot;'"></span>
                                </button>
                            </template>
                            <template x-for="cityName in filteredCities(form.country, form.state_province, search)" :key="'city-' + cityName">
                                <button type="button" class="country-combobox-option" @click="selectCity(cityName); open = false; search = ''">
                                    <span class="country-option-name" x-text="cityName"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="city" :value="form.city">
            </div>

            <div class="form-group address-postal-field">
                <x-form-field-label optional>ZIP / Postal Code</x-form-field-label>
                <input type="text" inputmode="numeric" name="postal_code" class="plain-input" placeholder="e.g. 8000" x-model="form.postal_code">
            </div>

            <div class="form-group address-full-field">
                <x-form-field-label required>House No. / Street / Purok / Subdivision / Barangay</x-form-field-label>
                <input type="text" name="street_address" class="plain-input" :class="{ 'is-invalid-field': isFieldInvalid('street_address') }" placeholder="e.g. Blk 4 Lot 12, Purok 3, Brgy. Buhangin (or District / Street)" x-model="form.street_address">
            </div>
        </div>
    </section>

    {{-- Card 2: Learner / Primary Contact Information --}}
    <section class="enrollment-section-card">
        <div class="enrollment-section-card-header">
            <div class="enrollment-section-card-title-wrap">
                <span class="enrollment-section-card-num">2</span>
                <div>
                    <h3 class="enrollment-section-card-title">Primary Contact Number</h3>
                    <p class="enrollment-section-card-subtitle">Active mobile number for SMS and school announcements.</p>
                </div>
            </div>
        </div>

        <div class="form-group">
            <x-form-field-label required>Mobile Number</x-form-field-label>
            <div class="contact-number-grid">
                <div class="country-combobox phone-code-combobox" x-data="{ open: false, search: '' }" @click.outside="open = false">
                    <button type="button" class="country-combobox-trigger phone-code-trigger" :class="{ 'is-invalid-field': isFieldInvalid('mobile_country_code') }" @click="open = !open">
                        <template x-if="selectedMobileCodeCountry?.flagPng">
                            <img :src="selectedMobileCodeCountry.flagPng" alt="" class="country-flag-img-static">
                        </template>
                        <span class="country-combobox-value" x-text="form.mobile_country_code || '+63'"></span>
                        <svg class="country-combobox-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div x-show="open" x-transition class="country-combobox-menu phone-code-menu">
                        <input type="text" class="country-combobox-search" placeholder="Search code or country..." x-model="search" @keydown.escape="open = false">
                        <div class="country-combobox-list">
                            <template x-for="country in filteredCallingCountries(search)" :key="'student-code-' + country.code">
                                <button type="button" class="country-combobox-option" @click="selectCallingCode(country, 'mobile_country_code'); open = false; search = ''">
                                    <img :src="country.flagPng" alt="" class="country-option-flag">
                                    <span class="country-option-name" x-text="country.name"></span>
                                    <span class="country-option-code" x-text="country.callingCode"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="mobile_country_code" :value="form.mobile_country_code">
                <input type="tel" name="mobile_number" class="plain-input phone-number-input" :class="{ 'is-invalid-field': isFieldInvalid('mobile_number') }" :placeholder="getPhonePlaceholder(form.mobile_country_code)" x-model="form.mobile_number"
                    @input="form.mobile_number = formatPhoneNumber($event.target.value, form.mobile_country_code)">
            </div>
            <span class="field-hint">Philippine numbers starting with 09 (e.g. 0917 123 4567) are formatted automatically.</span>
        </div>
    </section>
</div>
