<x-guest-layout>
<div x-data="enrollmentForm()" @open-affidavit-builder.window="openAffidavitBuilder()" class="enrollment-page">
    <!-- Dynamic Floating Toast Notifications Stack -->
    <div class="toast-stack" style="position: fixed; top: 1.25rem; right: 1.25rem; z-index: 100000; display: flex; flex-direction: column; gap: 0.75rem; width: min(380px, calc(100vw - 2rem)); pointer-events: none;">
        <template x-for="t in toasts" :key="t.id">
            <div
                class="toast"
                :class="'toast-' + (t.type || 'error')"
                x-transition:enter="toast-enter"
                x-transition:leave="toast-leave"
                style="pointer-events: auto; background: white; border-radius: 12px; padding: 0.85rem 1rem; box-shadow: 0 20px 45px rgba(15, 23, 42, 0.18); border-left: 4px solid #ef4444; display: flex; align-items: flex-start; gap: 0.75rem;"
                :style="t.type === 'success' ? 'border-left-color: #10b981;' : (t.type === 'warning' ? 'border-left-color: #f59e0b;' : 'border-left-color: #ef4444;')"
                role="status"
            >
                <span class="toast-icon" style="display: flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0; margin-top: 1px;"
                      :style="t.type === 'success' ? 'background: #dcfce7; color: #059669;' : (t.type === 'warning' ? 'background: #fef3c7; color: #d97706;' : 'background: #fee2e2; color: #dc2626;')"
                >
                    <svg x-show="t.type === 'success'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    <svg x-show="t.type === 'warning'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4m0 4h.01"/></svg>
                    <svg x-show="!t.type || t.type === 'error'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6m0-6 6 6"/></svg>
                </span>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-weight: 700; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.15rem;" x-text="t.type === 'success' ? 'Success' : (t.type === 'warning' ? 'Notice' : 'Required Field Missing')"></div>
                    <span class="toast-message" style="font-size: 0.82rem; color: #334155; line-height: 1.4; display: block;" x-text="t.message"></span>
                </div>
                <button type="button" class="toast-close" @click="removeToast(t.id)" aria-label="Dismiss notification" style="background: transparent; border: none; color: #94a3b8; cursor: pointer; padding: 2px; border-radius: 4px; line-height: 1;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>
    <style>
        /* Visually uppercase all text input fields and textareas on the enrollment form */
        .enrollment-page input[type="text"],
        .enrollment-page input[type="email"],
        .enrollment-page textarea {
            text-transform: uppercase !important;
        }

        /* Prevent placeholders from being converted to uppercase */
        .enrollment-page input[type="text"]::placeholder,
        .enrollment-page input[type="email"]::placeholder,
        .enrollment-page textarea::placeholder {
            text-transform: none !important;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes indeterminate-bar {
            0% { transform: translateX(-100%) scaleX(0.2); }
            50% { transform: translateX(0%) scaleX(0.6); }
            100% { transform: translateX(100%) scaleX(0.2); }
        }
        .indeterminate-progress-bar {
            animation: indeterminate-bar 1.5s ease-in-out infinite;
            transform-origin: left;
        }

        /* ── Modern Enrollment Theme Enhancements ── */
        .enrollment-page {
            background: #f8fafc !important;
            background-image: radial-gradient(at 10% 15%, rgba(5, 150, 105, 0.04) 0px, transparent 55%),
                              radial-gradient(at 90% 85%, rgba(16, 185, 129, 0.04) 0px, transparent 55%) !important;
            min-height: 100vh;
            color: #1e293b;
        }

        /* Modern Loading Progress Bar */
        .enrollment-steps-bar {
            background: #ffffff !important;
            border-bottom: 1.5px solid #e2e8f0 !important;
            padding: 1.1rem clamp(1rem, 3vw, 2.5rem) 1.25rem !important;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04) !important;
            position: sticky;
            top: 0;
            z-index: 30;
            backdrop-filter: blur(12px);
        }

        .enrollment-progress-wrapper {
            max-width: 960px !important;
            margin: 0 auto !important;
        }

        .enrollment-progress-top {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 1rem !important;
            margin-bottom: 0.85rem !important;
        }

        .enrollment-progress-left {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            flex-wrap: wrap;
        }

        .enrollment-progress-back {
            display: inline-flex;
            align-items: center !important;
            gap: 0.35rem !important;
            padding: 0.38rem 0.85rem !important;
            background: #f1f5f9 !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 999px !important;
            color: #334155 !important;
            font-size: 0.78rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            line-height: 1 !important;
        }

        .enrollment-progress-back:hover {
            background: #e2e8f0 !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
            transform: translateX(-2px);
        }

        .enrollment-step-pill {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            padding: 0.38rem 0.95rem !important;
            border-radius: 999px !important;
            background: #ecfdf5 !important;
            border: 1.5px solid #a7f3d0 !important;
            color: #047857 !important;
            font-size: 0.82rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.05em !important;
            text-transform: uppercase !important;
            box-shadow: 0 1px 2px rgba(5, 150, 105, 0.06) !important;
            line-height: 1 !important;
        }

        .step-live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.3);
            display: inline-block;
            animation: pulse-dot 2s infinite ease-in-out;
        }

        @keyframes pulse-dot {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.35);
                opacity: 0.7;
            }
        }

        .enrollment-step-sep {
            color: #cbd5e1;
            font-weight: 700;
            font-size: 1rem;
            user-select: none;
        }

        .enrollment-step-name {
            color: #0f172a !important;
            font-size: 1.1rem !important;
            font-weight: 800 !important;
            letter-spacing: -0.01em !important;
            text-transform: uppercase !important;
        }

        .enrollment-progress-right {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }

        .enrollment-completion-badge {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.45rem !important;
            padding: 0.38rem 0.9rem !important;
            border-radius: 999px !important;
            background: #f8fafc !important;
            border: 1.5px solid #e2e8f0 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03) !important;
            line-height: 1 !important;
        }

        .completion-percent {
            color: #047857 !important;
            font-size: 0.92rem !important;
            font-weight: 900 !important;
            letter-spacing: 0.02em !important;
        }

        .completion-text {
            color: #64748b !important;
            font-size: 0.75rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.05em !important;
            text-transform: uppercase !important;
        }

        /* Modern Progress Track */
        .enrollment-progress-track {
            max-width: 960px !important;
            height: 9px !important;
            background: #e2e8f0 !important;
            border-radius: 999px !important;
            overflow: hidden !important;
            position: relative !important;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.06) !important;
            margin: 0 !important;
        }

        .enrollment-progress-fill {
            height: 100% !important;
            background: linear-gradient(90deg, #059669 0%, #10b981 60%, #34d399 100%) !important;
            border-radius: 999px !important;
            transition: width 0.45s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative !important;
            overflow: hidden !important;
            box-shadow: 0 1px 6px rgba(16, 185, 129, 0.35) !important;
        }

        .enrollment-progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                90deg,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.45) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            background-size: 200% 100%;
            animation: progress-shimmer 2.2s infinite linear;
        }

        @keyframes progress-shimmer {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        @media (max-width: 640px) {
            .enrollment-steps-bar {
                padding: 0.85rem 1rem 0.95rem !important;
            }
            .enrollment-progress-top {
                margin-bottom: 0.65rem !important;
                gap: 0.5rem !important;
            }
            .enrollment-progress-left {
                gap: 0.45rem !important;
            }
            .enrollment-step-pill {
                padding: 0.3rem 0.75rem !important;
                font-size: 0.76rem !important;
            }
            .enrollment-step-sep {
                display: none !important;
            }
            .enrollment-step-name {
                font-size: 0.92rem !important;
                width: 100% !important;
                order: 3 !important;
                margin-top: 0.15rem !important;
            }
            .enrollment-progress-right {
                margin-left: auto !important;
            }
            .enrollment-completion-badge {
                padding: 0.28rem 0.65rem !important;
            }
            .completion-percent {
                font-size: 0.82rem !important;
            }
            .completion-text {
                font-size: 0.7rem !important;
            }
        }

        /* Main Form Container */
        .enrollment-main {
            padding: 2.25rem clamp(1rem, 2.5vw, 2rem) 4rem !important;
        }

        .enrollment-form-container {
            max-width: 960px !important;
            background: #ffffff !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 22px !important;
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.06), 0 1px 3px rgba(0, 0, 0, 0.03) !important;
            padding: clamp(1.75rem, 3.5vw, 2.75rem) !important;
            margin-top: 0 !important;
            position: relative;
            overflow: hidden;
        }

        .enrollment-form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #059669 0%, #10b981 50%, #f59e0b 100%);
        }

        /* Choice Cards */
        .choice-card-grid {
            gap: 1.15rem !important;
        }

        .choice-card {
            min-height: 94px !important;
            padding: 1.25rem 1.4rem !important;
            background: #ffffff !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 16px !important;
            opacity: 1 !important;
            display: flex !important;
            align-items: center !important;
            gap: 1.15rem !important;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            text-align: left;
        }

        .choice-card:hover {
            border-color: #10b981 !important;
            background: #f0fdf4 !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px -4px rgba(5, 150, 105, 0.12) !important;
        }

        .choice-card.is-selected {
            border-color: #059669 !important;
            background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 100%) !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.16), 0 12px 28px -4px rgba(5, 150, 105, 0.18) !important;
        }

        .choice-card-icon {
            width: 48px !important;
            height: 48px !important;
            border-radius: 14px !important;
            background: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #e2e8f0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            transition: all 0.2s ease;
        }

        .choice-card:hover .choice-card-icon,
        .choice-card.is-selected .choice-card-icon {
            background: #dcfce7 !important;
            color: #047857 !important;
            border-color: #a7f3d0 !important;
        }

        .choice-card-copy strong {
            font-size: 1.1rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            line-height: 1.3 !important;
            letter-spacing: -0.01em;
            display: block !important;
        }

        .choice-card-copy small {
            font-size: 0.92rem !important;
            color: #475569 !important;
            font-weight: 500 !important;
            line-height: 1.45 !important;
            margin-top: 0.25rem !important;
            display: block !important;
        }

        .choice-card-radio {
            margin-left: auto !important;
            width: 24px !important;
            height: 24px !important;
            border-radius: 50% !important;
            border: 2px solid #cbd5e1 !important;
            background: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            color: transparent;
            transition: all 0.2s ease;
        }

        .choice-card.is-selected .choice-card-radio {
            border-color: #059669 !important;
            background: #059669 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.4) !important;
        }

        /* Form Labels & Fields */
        .setup-section {
            padding-top: 2rem !important;
            border-top: 1.5px solid #f1f5f9 !important;
            margin-top: 0 !important;
        }

        .field-label,
        .form-group label {
            font-size: 1rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            letter-spacing: 0.035em !important;
            text-transform: uppercase !important;
            margin-bottom: 0.75rem !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.45rem !important;
        }

        .field-label .required,
        .form-group label .required {
            color: #ef4444 !important;
            font-size: 1.15rem !important;
            font-weight: 900 !important;
            margin-left: 2px !important;
        }

        .field-optional-badge {
            font-size: 0.78rem !important;
            padding: 0.2rem 0.65rem !important;
            font-weight: 700 !important;
            border-radius: 999px !important;
            background: #e0f2fe !important;
            color: #0369a1 !important;
            text-transform: none !important;
            letter-spacing: normal !important;
        }

        .field-hint {
            font-size: 0.9rem !important;
            color: #64748b !important;
            line-height: 1.5 !important;
            margin-top: 0.45rem !important;
            display: block !important;
        }

        .plain-input,
        .select-input,
        .textarea-input,
        .date-input {
            width: 100% !important;
            padding: 0.9rem 1.2rem !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 14px !important;
            font-size: 1.05rem !important;
            font-weight: 600 !important;
            color: #0f172a !important;
            background: #ffffff !important;
            min-height: 52px !important;
            transition: all 0.2s ease !important;
            outline: none !important;
            line-height: 1.4 !important;
        }

        .plain-input:hover,
        .select-input:hover,
        .textarea-input:hover,
        .date-input:hover {
            border-color: #94a3b8 !important;
        }

        .plain-input:focus,
        .select-input:focus,
        .textarea-input:focus,
        .date-input:focus {
            border-color: #059669 !important;
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.12) !important;
        }

        .plain-input::placeholder,
        .textarea-input::placeholder {
            color: #94a3b8 !important;
            font-weight: 400 !important;
        }

        .select-input {
            cursor: pointer !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%23059669' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 1.15rem center !important;
            padding-right: 3rem !important;
        }

        /* Shift Cards and Location in Step 1 */
        .location-button {
            padding: 0.85rem 1.25rem !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            border-radius: 14px !important;
        }

        .shift-card {
            padding: 1.15rem 1.25rem !important;
            border-radius: 16px !important;
        }

        .shift-card-title {
            font-size: 1.1rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
        }

        .shift-slot-badge {
            font-size: 0.82rem !important;
            font-weight: 700 !important;
        }

        .shift-slot-copy {
            font-size: 0.9rem !important;
        }

        .shift-card-primary-time {
            font-size: 0.95rem !important;
            font-weight: 700 !important;
        }

        .shift-card-local-time {
            font-size: 0.92rem !important;
        }

        /* Action Buttons */
        .form-actions-row {
            border-top: 1.5px solid #f1f5f9 !important;
            padding-top: 2rem !important;
            margin-top: 2.5rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 1rem !important;
            flex-wrap: wrap !important;
        }

        .form-actions-row .btn-secondary {
            padding: 0.85rem 1.65rem !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 14px !important;
            background: #ffffff !important;
            color: #334155 !important;
            font-size: 1rem !important;
            font-weight: 700 !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
        }

        .form-actions-row .btn-secondary:hover {
            background: #f8fafc !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
        }

        .form-actions-row .btn-primary {
            padding: 0.9rem 2.25rem !important;
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            border: none !important;
            border-radius: 14px !important;
            color: #ffffff !important;
            font-size: 1.05rem !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35) !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center !important;
            gap: 0.6rem !important;
            cursor: pointer !important;
        }

        .form-actions-row .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.45) !important;
        }
    </style>
    <!-- Full Page Loading Skeleton -->
    <div x-show="initialLoading" x-cloak>
        <x-skeleton-enrollment />
    </div>

    <!-- Actual Enrollment Form -->
    <div x-show="!initialLoading" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <x-enrollment.brand-header />

        <!-- Modern Loading Progress Bar -->
        <div class="enrollment-steps-bar">
            <div class="enrollment-progress-wrapper">
                <div class="enrollment-progress-top">
                    <div class="enrollment-progress-left">
                        <template x-if="step > 1">
                            <button type="button" @click="prevStep()" class="enrollment-progress-back" aria-label="Go to previous step">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                                <span>BACK</span>
                            </button>
                        </template>

                        <div class="enrollment-step-pill">
                            <span class="step-live-dot" aria-hidden="true"></span>
                            <span x-text="`STEP ${step} OF ${totalSteps}`"></span>
                        </div>

                        <span class="enrollment-step-sep" aria-hidden="true">•</span>

                        <span class="enrollment-step-name" x-text="(stepTitles && stepTitles[step - 1]) ? stepTitles[step - 1] : currentStepLabel()"></span>
                    </div>

                    <div class="enrollment-progress-right">
                        <div class="enrollment-completion-badge">
                            <span class="completion-percent" x-text="`${Math.round((step / totalSteps) * 100)}%`"></span>
                            <span class="completion-text">COMPLETED</span>
                        </div>
                    </div>
                </div>

                <div class="enrollment-progress-track" role="progressbar" :aria-valuenow="Math.round((step / totalSteps) * 100)" aria-valuemin="0" aria-valuemax="100">
                    <div class="enrollment-progress-fill" :style="`width: ${Math.round((step / totalSteps) * 100)}%`"></div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="enrollment-main">
            <div class="enrollment-form-container">

            <x-enrollment.form-heading />

            <x-enrollment.autosave-status />

            @if ($applicant && $applicant->status === 'rejected')
                <div x-show="currentFixMessage()" x-cloak class="enrollment-error" style="align-items:flex-start;background:#fff1f2;border-color:#fecdd3;color:#991b1b;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="margin-top:0.15rem;flex-shrink:0;">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="7" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <div>
                        <strong style="display:block;margin-bottom:0.25rem;">Needs fixing on this step</strong>
                        <span x-text="currentFixMessage()"></span>
                        @if ($applicant->review_remarks)
                            <div style="margin-top:0.45rem;color:#7f1d1d;font-weight:600;">{{ $applicant->review_remarks }}</div>
                        @endif
                    </div>
                </div>
            @endif



            <form
                method="POST"
                action="{{ route('enrollment.submit') }}"
                enctype="multipart/form-data"
                @submit="handleSubmit($event)"
                novalidate
                autocomplete="off"
                data-no-browser-autofill
            >
                @csrf
                {{-- Dummy fields to prevent browser autofill --}}
                <input type="text" name="prevent_autofill" class="hidden-autofill-field" tabindex="-1" autocomplete="off">
                <input type="password" name="prevent_autofill_pwd" class="hidden-autofill-field" tabindex="-1" autocomplete="new-password">
                {{-- Pass account email silently so it gets saved --}}
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                @if ($applicant)
                    <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                @endif

                <!-- Skeleton Loading (during step transitions) -->
                <div x-show="pageLoading" class="skeleton-container">
                    <div class="skeleton-header">
                        <div class="skeleton skeleton-title"></div>
                        <div class="skeleton skeleton-subtitle"></div>
                    </div>
                    <div class="skeleton-form">
                        <div class="skeleton skeleton-input"></div>
                        <div class="skeleton skeleton-input"></div>
                        <div class="skeleton skeleton-input"></div>
                        <div class="skeleton skeleton-input-large"></div>
                        <div class="skeleton skeleton-input"></div>
                        <div class="skeleton skeleton-input"></div>
                    </div>
                </div>

                <div x-show="!pageLoading">
                    <!-- STEP 1: Enrollment Setup -->
                    <template x-if="step === 1">
                        @include('enrollment.partials.step1')
                    </template>

                    <!-- STEP 2: Student Information -->
                    <template x-if="step === 2">
                        @include('enrollment.partials.step2')
                    </template>

                    <!-- STEP 3: Address & Contact -->
                    <template x-if="step === 3">
                        @include('enrollment.partials.step3')
                    </template>

                    <!-- STEP 4: Parent / Guardian Information -->
                    <template x-if="step === 4">
                        @include('enrollment.partials.step4')
                    </template>

                    <!-- STEP 5: Medical & Emergency -->
                    <template x-if="step === 5">
                        @include('enrollment.partials.step5')
                    </template>

                    <!-- STEP 6: Documents -->
                    <div x-show="step === 6" x-cloak class="space-y-5">
                        @include('enrollment.partials.step6')
                    </div>

                    <!-- STEP 7: Payment & Proof of Payment Upload -->
                    <div x-show="step === 7" x-cloak class="space-y-5">
                        @include('enrollment.partials.step7')
                    </div>

                    <!-- STEP 8: Final Review & Confirmation -->
                    <div x-show="step === 8" x-cloak class="space-y-5">
                        @include('enrollment.partials.step8')
                    </div>

                    <!-- Hidden fields — always in DOM for final form submission -->
                    @include('enrollment.partials.hidden_fields')

                </div><!-- end x-show="!pageLoading" -->

                <!-- Form Actions -->
                <div class="form-actions form-actions-row" x-show="!pageLoading">
                    {{-- Left: Cancel & Save Draft --}}
                    <div style="display: flex; gap: 0.65rem; align-items: center;">
                        <button type="button" @click="openCancelPrompt()" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.45rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            <span>Cancel</span>
                        </button>
                        <button type="button" @click="saveDraft({ force: true, showStatus: true })" class="btn-secondary" :disabled="stepSaving" :class="{ 'is-disabled': stepSaving }" style="display: inline-flex; align-items: center; gap: 0.45rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            <span>Save Draft</span>
                        </button>
                    </div>
                    {{-- Right: Back + Next/Submit --}}
                    <div class="form-actions-group" style="display: flex; gap: 0.65rem; align-items: center;">
                        <template x-if="step > 1">
                            <button type="button" @click="prevStep()" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.45rem;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                                <span>Back</span>
                            </button>
                        </template>

                        <template x-if="step < totalSteps">
                            <button type="button" @click="nextStep()" class="btn-primary"
                                :disabled="stepSaving"
                                :class="{ 'is-disabled': stepSaving }">
                                <span>Next</span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </button>
                        </template>

                        <template x-if="step === totalSteps">
                            <button type="button" @click="openSubmitConfirmModal()" class="btn-primary"
                                style="background: linear-gradient(135deg, #059669 0%, #047857 100%) !important; border: none !important;"
                                :disabled="loading || hasFilePreparationPending() || draftSaving"
                                :class="{ 'is-disabled': loading || hasFilePreparationPending() || draftSaving }">
                                <span x-text="hasFilePreparationPending() ? 'Preparing files...' : (draftSaving ? 'Saving files...' : 'CONFIRM & FINAL SUBMIT ENROLLMENT')"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Confirm Final Submission Pop-up Modal --}}
                <div x-show="showSubmitConfirmModal" x-cloak class="confirm-overlay" @keydown.escape.window="showSubmitConfirmModal = false">
                    <div class="confirm-dialog" @click.outside="showSubmitConfirmModal = false" style="max-width: 480px;">
                        <button type="button" class="confirm-close-button" @click="showSubmitConfirmModal = false" aria-label="Close dialog">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                        <div class="confirm-dialog-copy">
                            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;">
                                <div style="width:38px;height:38px;border-radius:10px;background:#dcfce7;color:#15803d;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <h3 style="margin:0;font-size:1.25rem;color:#0f172a;font-weight:900;">Confirm Final Submission?</h3>
                            </div>
                            <p style="font-size:0.88rem;line-height:1.5;color:#475569;margin-top:0.5rem;">
                                Are you sure all student information, uploaded documents, and payment receipt details are complete and authentic?
                            </p>
                            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:0.85rem;margin-top:0.85rem;font-size:0.82rem;line-height:1.4;color:#334155;">
                                <strong>Note:</strong> Once submitted, your application will be locked for review by the Admissions & Finance office and recorded automatically in the AMIS Admin Portal.
                            </div>
                        </div>
                        <div class="confirm-dialog-actions" style="margin-top:1.25rem;display:flex;gap:0.75rem;justify-content:flex-end;">
                            <button type="button" class="btn-secondary" @click="showSubmitConfirmModal = false">Edit Application</button>
                            <button type="button" class="btn-primary" style="background-color:#059669 !important;" @click="confirmAndSubmitForm($event)">Yes, Submit Enrollment Now</button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Full-Screen Submitting Enrollment Loading Overlay --}}
            <div x-show="submittingEnrollmentOverlay" x-cloak class="confirm-overlay" style="z-index: 999999; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(10px); position: fixed; inset: 0; display: flex; align-items: center; justify-content: center;">
                <div class="confirm-dialog" style="max-width: 460px; width: 90%; text-align: center; padding: 2.5rem 2rem; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4); border: 1px solid rgba(255,255,255,0.15); background: white;">
                    <div style="margin-bottom: 1.5rem;">
                        <div style="width: 60px; height: 60px; margin: 0 auto 1.25rem; border: 4px solid #e2e8f0; border-top-color: #059669; border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                        <h3 style="margin: 0; font-size: 1.35rem; color: #0f172a; font-weight: 900;" x-text="submitProgressTitle || 'Submitting Enrollment'"></h3>
                        <p style="margin: 0.5rem 0 0; font-size: 0.88rem; color: #475569; font-weight: 600;" x-text="submitProgressStatus || 'Saving application...'"></p>
                    </div>

                    <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 999px; overflow: hidden; margin-bottom: 1.5rem; position: relative;">
                        <div class="indeterminate-progress-bar" style="height: 100%; background: linear-gradient(90deg, #059669, #10b981); border-radius: 999px;"></div>
                    </div>

                    <p style="margin: 0; font-size: 0.8rem; color: #94a3b8; font-weight: 600;">
                        Please do not close or refresh this page.
                    </p>
                </div>
            </div>

            {{-- Dedicated Duplicate Enrollment / Validation Error Modal Pop-up --}}
            <div x-show="showDuplicateErrorModal" x-cloak class="confirm-overlay" style="z-index: 999998; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); position: fixed; inset: 0; display: flex; align-items: center; justify-content: center;" @keydown.escape.window="showDuplicateErrorModal = false">
                <div class="confirm-dialog" style="max-width: 520px; width: 92%; padding: 2rem; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); background: white; border: 1px solid #fee2e2;" @click.outside="showDuplicateErrorModal = false">
                    <button type="button" class="confirm-close-button" @click="showDuplicateErrorModal = false" aria-label="Close dialog">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                    <div class="confirm-dialog-copy">
                        <div style="display:flex; align-items:center; gap:0.85rem; margin-bottom:1rem;">
                            <div :style="isDuplicateRecord ? 'background:#fee2e2; color:#dc2626;' : 'background:#fef3c7; color:#d97706;'" style="width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="margin:0; font-size:1.3rem; color:#9a3412; font-weight:900; line-height:1.2;" x-text="isDuplicateRecord ? 'Already Enrolled / Applied' : 'Submission Incomplete'"></h3>
                                <span style="font-size:0.75rem; font-weight:700; color:#c2410c; text-transform:uppercase; tracking-wider:0.05em;" x-text="isDuplicateRecord ? 'Existing Student Record Found' : 'Please Check Requirements'"></span>
                            </div>
                        </div>

                        <p style="font-size:0.92rem; line-height:1.55; color:#334155; font-weight:600; margin:0 0 0.85rem 0;" x-text="isDuplicateRecord ? 'This student already has an active application on file for the current school year. You do not need to create another application.' : 'Some required fields or uploaded files need your attention before your application can be finalized.'">
                        </p>

                        <div style="background:#fff7ed; border:1px solid #ffedd5; border-radius:14px; padding:1rem 1.15rem; margin-bottom:1rem;">
                            <span style="font-size:0.75rem; font-weight:800; color:#9a3412; text-transform:uppercase; display:block; margin-bottom:0.35rem;" x-text="isDuplicateRecord ? 'Application Details:' : 'Requirement / Issue Details:'"></span>
                            <p style="font-size:0.88rem; line-height:1.5; color:#7c2d12; font-weight:600; margin:0;" x-text="duplicateErrorMessage"></p>
                        </div>

                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem 1rem; font-size:0.82rem; line-height:1.45; color:#475569;">
                            <strong style="color:#0f172a;">What happens next?</strong>
                            <template x-if="isDuplicateRecord">
                                <ul style="margin:0.35rem 0 0 1.15rem; padding:0; list-style-type:disc; space-y:0.2rem;">
                                    <li>Your current entry is saved as a <strong>Draft</strong> (no duplicate application was created).</li>
                                    <li>To check your existing application, click <strong>EXIT TO DASHBOARD</strong> below.</li>
                                    <li>If you made a typo in the student's name or grade, click <strong>Edit Draft</strong> to fix it.</li>
                                </ul>
                            </template>
                            <template x-if="!isDuplicateRecord">
                                <ul style="margin:0.35rem 0 0 1.15rem; padding:0; list-style-type:disc; space-y:0.2rem;">
                                    <li>Your current progress remains safely saved as a <strong>Draft</strong>.</li>
                                    <li>Click <strong>Review & Fix</strong> below to correct the indicated fields or re-upload files.</li>
                                </ul>
                            </template>
                        </div>
                    </div>

                    <div class="confirm-dialog-actions" style="margin-top:1.5rem; display:flex; gap:0.75rem; justify-content:flex-end; align-items:center;">
                        <button type="button" class="btn-secondary" style="padding: 0.65rem 1.25rem; font-weight: 700;" @click="showDuplicateErrorModal = false" x-text="isDuplicateRecord ? 'Edit Draft' : 'Review & Fix'">
                        </button>
                        <template x-if="isDuplicateRecord">
                            <a href="{{ route('enrollment.dashboard') }}" class="btn-primary" style="background-color:#ea580c !important; border-color:#c2410c !important; padding: 0.65rem 1.4rem; font-weight: 800; text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                <span>EXIT TO DASHBOARD</span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <div x-show="showDuplicateModal" x-cloak class="confirm-overlay" @keydown.escape.window="closeDuplicateModal()">
                <div class="confirm-dialog duplicate-dialog" @click.outside="closeDuplicateModal()">
                    <button type="button" class="confirm-close-button" @click="closeDuplicateModal()" aria-label="Close dialog">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                    <div class="confirm-dialog-copy">
                        <h3 style="color:#991b1b;font-size:1.45rem;line-height:1.25;">Possible duplicate enrollment record found.</h3>
                        <p>An enrollment, application, or student record with the same full name and birthdate already exists in the system. Please review the student details before continuing.</p>
                    </div>
                    <div class="confirm-dialog-actions" style="grid-template-columns:1fr;">
                        <button type="button" class="btn-primary duplicate-dialog-button" style="width:100%;" @click="closeDuplicateModal()">OK</button>
                    </div>
                </div>
            </div>

            <div x-show="showCancelPrompt" x-cloak class="confirm-overlay" @keydown.escape.window="closeCancelPrompt()">
                <div class="confirm-dialog" @click.outside="closeCancelPrompt()">
                    <button type="button" class="confirm-close-button" @click="closeCancelPrompt()" aria-label="Close dialog">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                    <div class="confirm-dialog-copy">
                        <h3>Leave this enrollment form?</h3>
                        <p>Save your latest changes, stay on this page, or discard the draft completely so it will not come back when you reopen the form.</p>
                    </div>
                    <div class="confirm-dialog-actions">
                        @if ($applicant && $applicant->status === 'draft')
                            <form method="POST" action="{{ route('enrollment.draft.discard') }}" data-clear-draft-form>
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                <button type="submit" class="danger-text-button">Discard Draft</button>
                            </form>
                        @else
                            <button type="button" class="danger-text-button" @click="discardUnsavedDraft()">Discard Unsaved Draft</button>
                        @endif
                        <button type="button" class="btn-primary" @click="cancelAndSave()">Save & Exit</button>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="enrollment-footer">
            &copy; {{ date('Y') }} Al Munawwara Islamic School. All rights reserved.
        </div>
    </div>
</div>

@push('scripts')
<script>
    @include('enrollment.partials.script')
</script>
@endpush
</x-guest-layout>
