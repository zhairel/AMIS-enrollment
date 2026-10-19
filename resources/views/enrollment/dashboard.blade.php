<x-guest-layout>
    <style>
        @keyframes active-pulse {
            0% { transform: scale(0.9); opacity: 0.6; }
            50% { transform: scale(1.25); opacity: 1; }
            100% { transform: scale(0.9); opacity: 0.6; }
        }
        .dashboard-page {
            min-height: 100vh;
            background: #f8fafc !important;
            background-image: radial-gradient(at 10% 15%, rgba(5, 150, 105, 0.04) 0px, transparent 55%),
                              radial-gradient(at 90% 85%, rgba(16, 185, 129, 0.04) 0px, transparent 55%) !important;
            color: #1e293b;
        }
        .dashboard-content {
            max-width: 960px;
            margin: 0 auto;
            padding: 2.25rem clamp(1rem, 2.5vw, 2rem) 4rem;
        }
        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 40;
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.95) !important;
        }
    </style>
    <div class="dashboard-page" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 1200)">
        <!-- Loading Skeleton -->
        <div x-show="loading" x-cloak>
            <x-skeleton-dashboard />
        </div>

        <!-- Actual Dashboard Content -->
        <div x-show="!loading" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <!-- Modernized AMIS Header -->
            <header class="dashboard-header" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem clamp(1.25rem, 3vw, 2.5rem); display: flex; justify-content: space-between; align-items: center; gap: 1.25rem; flex-wrap: wrap; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03);">
                <div class="dashboard-logo" style="display: flex; align-items: center; gap: 0.95rem;">
                    <div style="position: relative;">
                        <img src="{{ asset('images/AMIS_Logo.png') }}" alt="AMIS Logo" style="width: 48px; height: 48px; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.06));">
                    </div>
                    <div>
                        <div style="font-size: 0.72rem; font-weight: 800; color: #059669; text-transform: uppercase; letter-spacing: 0.08em; line-height: 1.1;">
                            Al Munawwara Islamic School
                        </div>
                        <h1 style="font-size: 1.2rem; font-weight: 900; color: #0f172a; margin: 2px 0 0 0; line-height: 1.2; letter-spacing: -0.01em;">
                            AMIS Enrollment System
                        </h1>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.85rem; flex-wrap: wrap;">
                    <!-- User Profile & Status Badges -->
                    <div style="display: flex; align-items: center; gap: 0.75rem; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 999px; padding: 0.35rem 0.95rem 0.35rem 0.45rem;">
                        <div style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #ffffff; font-weight: 800; font-size: 0.88rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 6px rgba(5, 150, 105, 0.3);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 0.85rem; font-weight: 800; color: #0f172a; line-height: 1.2;">
                                {{ $user->name }}
                            </span>
                            <div style="display: flex; align-items: center; gap: 0.45rem; font-size: 0.72rem; color: #64748b; line-height: 1;">
                                @if($user->isActive())
                                    <span style="display: inline-flex; align-items: center; gap: 0.3rem; color: #059669; font-weight: 700;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; animation: active-pulse 1.5s infinite;"></span>
                                        Active
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 0.3rem; color: #64748b; font-weight: 600;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #94a3b8;"></span>
                                        Online
                                    </span>
                                @endif
                                <span>&bull;</span>
                                @if($user->hasVerifiedEmail() && $user->account_status === 'verified')
                                    <span style="color: #047857; font-weight: 700; display: inline-flex; align-items: center; gap: 0.2rem;">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        Verified
                                    </span>
                                @else
                                    <span style="color: #b45309; font-weight: 600;">Unverified</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sign Out Button -->
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="logout-btn" 
                                style="display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.6rem 1.15rem; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 999px; font-size: 0.85rem; font-weight: 700; color: #475569; cursor: pointer; transition: all 0.2s ease;"
                                onmouseover="this.style.background='#fef2f2'; this.style.borderColor='#fecaca'; this.style.color='#b91c1c';"
                                onmouseout="this.style.background='#ffffff'; this.style.borderColor='#e2e8f0'; this.style.color='#475569';">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </header>

            <div class="dashboard-content">

                <!-- Smart Status Banner -->
                @php
                    $applicants = $applicants ?? collect([$applicant])->filter();
                    
                    // Sibling Banner Prioritisation Logic:
                    // 1. Rejected (requires urgent user correction)
                    // 2. Ready for Submission (requires finalizing and paying)
                    // 3. Draft (work in progress)
                    // 4. Approved (all enrolled)
                    // 5. Pending (under administrative review)
                    $rejectedApplicant = $applicants->where('status', 'rejected')->first();
                    $readyApplicant = $applicants->where('status', 'ready_for_submission')->first();
                    $draftApplicant = $applicants->where('status', 'draft')->first();
                    $approvedCount = $applicants->where('status', 'approved')->count();
                    
                    if ($rejectedApplicant) {
                        $bannerType = 'rejected';
                        $activeApplicant = $rejectedApplicant;
                    } elseif ($readyApplicant) {
                        $bannerType = 'ready_for_submission';
                        $activeApplicant = $readyApplicant;
                    } elseif ($applicants->count() === 0 || $draftApplicant) {
                        $bannerType = 'draft';
                        $activeApplicant = $draftApplicant;
                    } elseif ($applicants->count() > 0 && $approvedCount === $applicants->count()) {
                        $bannerType = 'approved';
                        $activeApplicant = $applicants->first();
                    } else {
                        $bannerType = 'pending';
                        $activeApplicant = $applicants->first();
                    }
                    
                    $photo = $activeApplicant?->photo_2x2_url;
                    $firstName = $activeApplicant?->first_name ?? $user->name;
                    $studentDisplayName = $activeApplicant ? (trim(($activeApplicant->last_name ?? '') . ', ' . ($activeApplicant->first_name ?? '') . ' ' . ($activeApplicant->middle_name ?? '')) ?: $firstName) : $user->name;
                    $docStatuses = $activeApplicant?->document_statuses ?? [];
                    
                    $rejectedDocs = collect([
                        'photo_2x2'   => '2x2 Photo',
                        'birth_cert'  => 'Birth Certificate',
                        'report_card' => 'Report Card',
                    ])->filter(fn($l, $k) => ($docStatuses[$k] ?? '') === 'rejected');
                    
                    $requiredGuide = [
                        ['label' => 'Student profile', 'done' => $activeApplicant && $activeApplicant->first_name && $activeApplicant->last_name && $activeApplicant->grade_level],
                        ['label' => 'Religion, country, and contact number', 'done' => $activeApplicant && $activeApplicant->religion && $activeApplicant->country && $activeApplicant->mobile_number],
                        ['label' => 'Parent or guardian contact', 'done' => $activeApplicant && $activeApplicant->parent_mobile],
                        ['label' => 'Emergency contact', 'done' => $activeApplicant && $activeApplicant->emergency_name && $activeApplicant->emergency_phone],
                        ['label' => 'Recent 1:1 or annual photo', 'done' => $activeApplicant && $activeApplicant->photo_2x2_url],
                        ['label' => 'Report card or signed temporary proof', 'done' => $activeApplicant && (in_array($activeApplicant->grade_level, ['Kinder 1', 'Kinder 2'], true) || $activeApplicant->report_card_url || $activeApplicant->affidavit_url)],
                    ];
                    
                    $optionalGuide = [
                        ['label' => 'Birth certificate copy, if available', 'done' => $activeApplicant && $activeApplicant->birth_cert_url],
                        ['label' => 'Medical record or health history', 'done' => $activeApplicant && ($activeApplicant->medical_record_url || $activeApplicant->psych_testing || $activeApplicant->prescription_med)],
                        ['label' => 'Marriage contract, if applicable', 'done' => $activeApplicant && $activeApplicant->marriage_contract_url],
                        ['label' => 'Physician details, if available', 'done' => $activeApplicant && ($activeApplicant->family_physician || $activeApplicant->physician_phone)],
                    ];
                    
                    $canAddAnotherChild = $canAddAnotherChild ?? false;
                    $readyApplications = $readyApplications ?? collect();
                    $draftApplications = $draftApplications ?? collect();
                    $multipleApplicants = $applicants->count() > 1;
                    $submittedApplications = $applicants->filter(fn ($item) => in_array($item->status, ['pending', 'submitted', 'under_review'], true));
                    $rejectedApplications = $applicants->where('status', 'rejected');
                    $approvedApplications = $applicants->where('status', 'approved');
                    
                    $familyStatusSummary = collect([
                        $submittedApplications->count() ? $submittedApplications->count() . ' under review' : null,
                        $readyApplications->count() ? $readyApplications->count() . ' ready' : null,
                        $draftApplications->count() ? $draftApplications->count() . ' draft' : null,
                        $rejectedApplications->count() ? $rejectedApplications->count() . ' needs fixing' : null,
                        $approvedApplications->count() ? $approvedApplications->count() . ' approved' : null,
                    ])->filter()->implode(', ');

                    $totalSiblings = $applicants->count();
                    $pendingCount = $submittedApplications->count();
                    $approvedCount = $approvedApplications->count();
                    $draftCount = $draftApplications->count();
                    $rejectedCount = $rejectedApplications->count();
                    $readyCount = $applicants->where('status', 'ready_for_submission')->count();
                @endphp

                @if ($bannerType === 'draft')
                    {{-- Emerald: Welcome / Draft in progress --}}
                    <div style="background: linear-gradient(135deg, #064e3b 0%, #065f46 55%, #047857 100%); border-radius: 20px; padding: 1.85rem 1.75rem; margin-bottom: 1.5rem; color: white; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; position: relative; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.25);">
                        {{-- Ambient geometric / soft radial accents --}}
                        <div style="position: absolute; right: -20px; top: -20px; width: 180px; height: 180px; border-radius: 50%; background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, transparent 70%); pointer-events: none;"></div>
                        <div style="position: absolute; right: 120px; bottom: -40px; width: 140px; height: 140px; border-radius: 50%; background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%); pointer-events: none;"></div>

                        <div style="display: flex; align-items: center; gap: 1.25rem; position: relative; z-index: 1; flex: 1; min-width: 260px;">
                            <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.14); border: 1.5px solid rgba(255,255,255,0.25); border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; backdrop-filter: blur(8px);">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                </svg>
                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem; flex-wrap: wrap;">
                                    <span style="background: rgba(254, 243, 199, 0.18); border: 1px solid rgba(253, 230, 138, 0.4); color: #fde68a; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; padding: 0.2rem 0.65rem; border-radius: 999px;">
                                        Academic Year 2026–2027
                                    </span>
                                </div>
                                <div style="font-size: 1.35rem; font-weight: 900; line-height: 1.2; margin-bottom: 0.3rem;">
                                    Assalamu Alaikum, {{ $user->name }}!
                                </div>
                                <div style="font-size: 0.9rem; opacity: 0.92; line-height: 1.45; font-weight: 400;">
                                    @if ($activeApplicant && $activeApplicant->status === 'draft')
                                        You have an enrollment application in progress. You may continue where you left off.
                                    @else
                                        Welcome to the AMIS Student Portal. DepEd K to 12 & Integrated Islamic Studies.
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($activeApplicant && $activeApplicant->status === 'draft')
                            <div style="position: relative; z-index: 1; flex-shrink: 0;">
                                <a href="{{ route('enrollment.form.child', $activeApplicant) }}"
                                   style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.4rem; background: #ffffff; color: #065f46; border-radius: 12px; font-size: 0.88rem; font-weight: 800; text-decoration: none; white-space: nowrap; box-shadow: 0 4px 14px rgba(0,0,0,0.15); transition: all 0.2s ease;"
                                   onmouseover="this.style.background='#f0fdf4'; this.style.transform='translateY(-1px)';"
                                   onmouseout="this.style.background='#ffffff'; this.style.transform='none';">
                                    <span>Continue Application</span>
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>

                @elseif ($bannerType === 'rejected')
                    {{-- Red: Rejected --}}
                    <div style="background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%); border-radius: 20px; padding: 1.85rem 1.75rem; margin-bottom: 1.5rem; color: white; display: flex; align-items: center; gap: 1.25rem; box-shadow: 0 10px 25px -5px rgba(185, 28, 28, 0.25);">
                        <div style="flex-shrink: 0;">
                            @if ($photo)
                                <img src="{{ asset('storage/' . $photo) }}" alt="Photo" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%; border: 2.5px solid rgba(255,255,255,0.5);">
                            @else
                                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.18); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 1.25rem; font-weight: 800; margin-bottom: 0.25rem;">Attention Needed: {{ $studentDisplayName }}</div>
                            <div style="font-size: 0.9rem; opacity: 0.92; margin-bottom: 0.5rem;">Your application requires document corrections. Please re-upload the following:</div>
                            @if ($rejectedDocs->count())
                                <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                                    @foreach ($rejectedDocs as $docName)
                                        <span style="background: rgba(255,255,255,0.22); border: 1px solid rgba(255,255,255,0.35); border-radius: 999px; padding: 0.25rem 0.85rem; font-size: 0.8rem; font-weight: 700;">
                                            {{ $docName }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('enrollment.form.child', $activeApplicant) }}"
                           style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.35rem; background: #ffffff; color: #b91c1c; border-radius: 12px; font-size: 0.88rem; font-weight: 800; text-decoration: none; white-space: nowrap; flex-shrink: 0; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
                            <span>Re-upload</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                    </div>

                @elseif ($bannerType === 'ready_for_submission')
                    @php
                        $readyNames = $applicants->where('status', 'ready_for_submission')->pluck('first_name')->filter()->map(fn($n) => strtoupper($n))->toArray();
                        $readyCount = count($readyNames);
                        $totalSiblings = $applicants->count();
                        
                        $pendingCount = $applicants->whereIn('status', ['pending', 'submitted', 'under_review'])->count();
                        $approvedCount = $applicants->where('status', 'approved')->count();
                        $draftCount = $applicants->where('status', 'draft')->count();
                        $rejectedCount = $applicants->where('status', 'rejected')->count();
                    @endphp
                    <div style="background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); border-radius: 20px; padding: 1.85rem 1.75rem; margin-bottom: 1.5rem; color: white; text-align: left; box-shadow: 0 10px 25px -5px rgba(15, 118, 110, 0.25);">
                        <div style="text-align: left;">
                            @if ($readyCount >= 2)
                                <h2 style="font-size: 1.35rem; font-weight: 900; margin: 0 0 0.5rem 0; line-height: 1.25; color: white;">{{ $readyCount }} siblings are ready for submission.</h2>
                                <div style="font-size: 0.92rem; opacity: 0.95; line-height: 1.45; margin-bottom: 0.75rem;">
                                    <strong>Dear Parents,</strong> the enrollment applications for <strong style="text-decoration: underline;">{{ implode(', ', $readyNames) }}</strong> are fully completed and ready to be submitted.
                                </div>
                            @else
                                <h2 style="font-size: 1.35rem; font-weight: 900; margin: 0 0 0.5rem 0; line-height: 1.25; color: white;">{{ strtoupper($studentDisplayName) }} IS READY FOR SUBMISSION</h2>
                                <div style="font-size: 0.92rem; opacity: 0.95; line-height: 1.45; margin-bottom: 0.75rem;">
                                    <strong>Dear Parents,</strong> the enrollment application for <strong style="text-decoration: underline;">{{ strtoupper($studentDisplayName) }}</strong> is fully completed and ready to be submitted.
                                </div>
                            @endif
                            
                            <div style="font-size: 0.88rem; opacity: 0.9; line-height: 1.45;">
                                Please proceed to <strong>Finalize & Submit</strong> below to settle the payment and complete the enrollment process. You may also add another child first if you wish to enroll multiple siblings together.
                            </div>

                            {{-- Dynamic sibling status summary --}}
                            @if ($totalSiblings > 1)
                                <div style="margin-top: 1.15rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.18); font-size: 0.84rem; opacity: 0.95; display: flex; flex-direction: column; gap: 0.4rem;">
                                    <div style="font-weight: 800; color: #fde68a; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.15rem;">Sibling Application Status Summary</div>
                                    
                                    @if ($pendingCount > 0)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="display: inline-block; width: 6px; height: 6px; background: #fde68a; border-radius: 50%;"></span>
                                            <span>{{ $pendingCount }} sibling {{ \Illuminate\Support\Str::plural('application', $pendingCount) }}: <strong style="color: #fde68a;">Pending Review by Admin/Registrar</strong> (verification takes 1–2 banking/business days). No action is required. We are currently verifying the details.</span>
                                        </div>
                                    @endif
                                    @if ($approvedCount > 0)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="display: inline-block; width: 6px; height: 6px; background: #34d399; border-radius: 50%;"></span>
                                            <span>{{ $approvedCount }} sibling {{ \Illuminate\Support\Str::plural('application', $approvedCount) }}: <strong style="color: #a7f3d0;">Approved & Enrolled</strong>. Check your email inbox for welcome packs and updates.</span>
                                        </div>
                                    @endif
                                    @if ($draftCount > 0)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="display: inline-block; width: 6px; height: 6px; background: #60a5fa; border-radius: 50%;"></span>
                                            <span>{{ $draftCount }} sibling {{ \Illuminate\Support\Str::plural('application', $draftCount) }}: <strong>Draft Stage</strong> (in progress). You can complete their profiles to submit them.</span>
                                        </div>
                                    @endif
                                    @if ($rejectedCount > 0)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="display: inline-block; width: 6px; height: 6px; background: #f87171; border-radius: 50%;"></span>
                                            <span style="color: #fecaca;">{{ $rejectedCount }} sibling {{ \Illuminate\Support\Str::plural('application', $rejectedCount) }}: <strong style="color: #fca5a5;">Returned for Correction</strong>. Click "Re-upload" on their cards below to fix.</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif ($bannerType === 'approved')
                    {{-- Green: Approved + animated confetti --}}
                    <style>
                        @keyframes confettiFall {
                            0%   { transform: translateY(-10px) rotate(0deg); opacity: 1; }
                            100% { transform: translateY(80px) rotate(360deg); opacity: 0; }
                        }
                        .confetti-piece {
                            position: absolute;
                            width: 8px; height: 8px;
                            border-radius: 2px;
                            animation: confettiFall linear infinite;
                        }
                    </style>
                    <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 20px; padding: 1.85rem 1.75rem; margin-bottom: 1.5rem; color: white; display: flex; align-items: center; gap: 1.25rem; position: relative; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.25);">
                        {{-- Animated confetti --}}
                        <div style="position: absolute; inset: 0; pointer-events: none;">
                            @php
                                $pieces = [
                                    ['8%',  '5%',  '#fde68a', '2.1s', '0s',   'rect'],
                                    ['18%', '80%', '#a7f3d0', '2.4s', '0.3s', 'circle'],
                                    ['30%', '20%', '#fca5a5', '1.9s', '0.6s', 'rect'],
                                    ['42%', '65%', '#bfdbfe', '2.6s', '0.1s', 'circle'],
                                    ['55%', '10%', '#fde68a', '2.2s', '0.8s', 'rect'],
                                    ['65%', '50%', '#a7f3d0', '2.0s', '0.4s', 'circle'],
                                    ['75%', '30%', '#fca5a5', '2.5s', '0.2s', 'rect'],
                                    ['85%', '75%', '#bfdbfe', '1.8s', '0.7s', 'circle'],
                                    ['92%', '45%', '#fde68a', '2.3s', '0.5s', 'rect'],
                                    ['50%', '90%', '#fca5a5', '2.1s', '0.9s', 'circle'],
                                    ['22%', '40%', '#bfdbfe', '2.7s', '0.3s', 'rect'],
                                    ['70%', '15%', '#a7f3d0', '1.9s', '0.6s', 'circle'],
                                ];
                            @endphp
                            @foreach ($pieces as [$top, $left, $color, $dur, $delay, $shape])
                                <div class="confetti-piece" style="
                                    top:{{ $top }}; left:{{ $left }};
                                    background:{{ $color }};
                                    border-radius:{{ $shape === 'circle' ? '50%' : '2px' }};
                                    animation-duration:{{ $dur }};
                                    animation-delay:{{ $delay }};
                                    width:{{ $shape === 'circle' ? '7px' : '9px' }};
                                    height:{{ $shape === 'circle' ? '7px' : '6px' }};
                                    opacity:0.75;
                                "></div>
                            @endforeach
                        </div>

                        <div style="flex-shrink: 0; position: relative; z-index: 1;">
                            @if ($photo)
                                <img src="{{ asset('storage/' . $photo) }}" alt="Photo" style="width: 64px; height: 64px; object-fit: cover; border-radius: 50%; border: 3px solid rgba(255,255,255,0.6);">
                            @else
                                <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                            @endif
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div style="font-size: 0.78rem; font-weight: 800; opacity: 0.9; margin-bottom: 0.25rem; letter-spacing: 0.06em; text-transform: uppercase; color: #fde68a;">OFFICIALLY ENROLLED — SY {{ $activeApplicant->school_year }}</div>
                            <div style="font-size: 1.45rem; font-weight: 900; line-height: 1.2; margin-bottom: 0.25rem;">Congratulations, {{ $studentDisplayName }}!</div>
                            <div style="font-size: 0.92rem; opacity: 0.92; line-height: 1.45;">Your enrollment has been approved. Please check your personal email inbox for important enrollment details, class schedule, and further school updates.</div>
                        </div>
                    </div>

                @else
                    {{-- Default: pending/under review --}}
                    <div style="background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); border-radius: 20px; padding: 1.85rem 1.75rem; margin-bottom: 1.5rem; color: white; text-align: left; box-shadow: 0 10px 25px -5px rgba(15, 118, 110, 0.25);">
                        <div style="text-align: left;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                                <span style="background: rgba(254, 243, 199, 0.18); border: 1px solid rgba(253, 230, 138, 0.4); color: #fde68a; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; padding: 0.2rem 0.65rem; border-radius: 999px;">
                                    Application Status: Under Review
                                </span>
                            </div>
                            <h2 style="font-size: 1.35rem; font-weight: 900; margin: 0 0 0.5rem 0; line-height: 1.25; color: white; letter-spacing: -0.01em;">ENROLLMENT APPLICATION UNDER REVIEW</h2>
                            <div style="font-size: 0.92rem; opacity: 0.95; line-height: 1.5; margin-bottom: 0.75rem;">
                                <strong>Dear Parents,</strong> your enrollment {{ $totalSiblings > 1 ? 'applications have' : 'application has' }} been successfully submitted and {{ $totalSiblings > 1 ? 'are' : 'is' }} currently pending review by the school registrar and admin office. We will verify your submitted documents and transaction references shortly. <strong>Please wait 1–2 banking/business days for verification.</strong> No further action is required at this stage.
                            </div>

                            <!-- Fast-Track Approval / Facebook Follow-Up Card -->
                            <div style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 14px; padding: 1rem 1.25rem; margin-top: 1rem; backdrop-filter: blur(8px);">
                                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #ffffff; color: #0f766e; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="font-size: 0.75rem; font-weight: 800; color: #fde68a; text-transform: uppercase; letter-spacing: 0.05em;">Need Faster Approval?</div>
                                        <div style="font-size: 0.9rem; color: #ffffff; font-weight: 500; margin-top: 0.15rem; line-height: 1.4;">
                                            Message <strong>Sir Mohaymen Unos</strong> directly on Facebook to fast-track your enrollment approval.
                                        </div>
                                        <a href="https://web.facebook.com/sirmo.amis" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 0.35rem; margin-top: 0.6rem; background: #ffffff; color: #0f766e; font-size: 0.84rem; font-weight: 800; padding: 0.5rem 1rem; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 6px rgba(0,0,0,0.1); transition: background 0.15s ease;">
                                            <span>Message Sir Mohaymen on Facebook</span>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="7" y1="17" x2="17" y2="7"></line>
                                                <polyline points="7 7 17 7 17 17"></polyline>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Dynamic sibling status summary --}}
                            @if ($totalSiblings > 1)
                                <div style="margin-top: 1.15rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.18); font-size: 0.84rem; opacity: 0.95; display: flex; flex-direction: column; gap: 0.4rem;">
                                    <div style="font-weight: 800; color: #fde68a; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.15rem;">Sibling Application Status Summary</div>
                                    
                                    @if ($pendingCount > 0)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="display: inline-block; width: 6px; height: 6px; background: #fde68a; border-radius: 50%;"></span>
                                            <span>{{ $pendingCount }} sibling {{ \Illuminate\Support\Str::plural('application', $pendingCount) }}: <strong style="color: #fde68a;">Pending Review by Admin/Registrar</strong> (verification takes 1–2 banking/business days). No action is required. We are currently verifying the details.</span>
                                        </div>
                                    @endif
                                    @if ($approvedCount > 0)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="display: inline-block; width: 6px; height: 6px; background: #34d399; border-radius: 50%;"></span>
                                            <span>{{ $approvedCount }} sibling {{ \Illuminate\Support\Str::plural('application', $approvedCount) }}: <strong style="color: #a7f3d0;">Approved & Enrolled</strong>. Check your email inbox for welcome packs and updates.</span>
                                        </div>
                                    @endif
                                    @if ($draftCount > 0)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="display: inline-block; width: 6px; height: 6px; background: #60a5fa; border-radius: 50%;"></span>
                                            <span>{{ $draftCount }} sibling {{ \Illuminate\Support\Str::plural('application', $draftCount) }}: <strong>Draft Stage</strong> (in progress). You can complete their profiles to submit them.</span>
                                        </div>
                                    @endif
                                    @if ($rejectedCount > 0)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="display: inline-block; width: 6px; height: 6px; background: #f87171; border-radius: 50%;"></span>
                                            <span style="color: #fecaca;">{{ $rejectedCount }} sibling {{ \Illuminate\Support\Str::plural('application', $rejectedCount) }}: <strong style="color: #fca5a5;">Returned for Correction</strong>. Click "Re-upload" on their cards below to fix.</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif


                @if ($applicants->count())
                    <x-dashboard.family-group
                        :user="$user"
                        :applicants="$applicants"
                        :applicant="$applicant"
                        :can-add-another-child="$canAddAnotherChild"
                        :ready-applications="$readyApplications"
                        :draft-applications="$draftApplications"
                    />
                @endif

                @if (false)
                    {{-- legacy draft card disabled; progress now lives in each child card --}}
                    <div class="dashboard-action-grid enrollment-progress-grid">
                    <div class="enrollment-progress-card" x-data="{ pct: {{ $applicant->completion_percentage }}, lastSaved: '{{ $applicant->updated_at->diffForHumans() }}', step: {{ $applicant->last_step }} }"
                         x-init="
                            fetch('{{ route('enrollment.status', ['applicant' => $applicant->id]) }}')
                                .then(r => r.json())
                                .then(d => { if (d.percentage !== undefined) pct = d.percentage; if (d.last_step) step = d.last_step; if (d.last_saved) lastSaved = d.last_saved; })
                                .catch(() => {});
                         "
                         style="background:white;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:0;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                        <div style="padding:1.25rem 1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                            <div style="flex:1;min-width:200px;">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
                                    <span style="font-weight:700;font-size:0.9375rem;color:#111827;">Enrollment in Progress</span>
                                    <span style="font-weight:700;font-size:0.9375rem;color:#059669;" x-text="pct + '%'"></span>
                                </div>
                                <div style="font-size:0.92rem;color:#111827;font-weight:800;margin:-0.15rem 0 0.55rem;">
                                    {{ $applicant->full_name ?: 'Student Name' }}
                                </div>
                                <div style="height:8px;background:#e5e7eb;border-radius:999px;overflow:hidden;">
                                    <div :style="'height:100%;width:' + pct + '%;background:linear-gradient(90deg,#059669,#34d399);border-radius:999px;transition:width 0.6s;'"></div>
                                </div>
                                <div style="font-size:0.8125rem;color:#6b7280;margin-top:0.5rem;">
                                    Last saved: <span x-text="lastSaved"></span> &nbsp;·&nbsp; Step <span x-text="Math.min(step, 7)"></span> of 7
                                </div>
                            </div>
                            <div class="draft-action-group">
                                <a href="{{ route('enrollment.form.child', $applicant) }}" class="draft-action-primary">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                    Continue Draft
                                </a>
                                <form method="POST" action="{{ route('enrollment.draft.discard') }}" data-clear-draft-form data-confirm-message="Discard changes? This will remove the saved draft for this child.">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                    <button type="submit" class="draft-action-danger">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14H6L5 6"/>
                                            <path d="M10 11v6"/>
                                            <path d="M14 11v6"/>
                                            <path d="M9 6V4h6v2"/>
                                        </svg>
                                        Delete Draft
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>

                @elseif (false)
                    <x-dashboard.application-summary :applicant="$applicant" />
                @elseif (!$applicants->count())
                    {{-- ── Elevated Hero Action Card (Enroll Now!) ── --}}
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 2rem 1.75rem; box-shadow: 0 10px 30px -4px rgba(15, 23, 42, 0.05); position: relative; overflow: hidden; margin-bottom: 2rem;">
                        {{-- Emerald top accent bar --}}
                        <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #059669 0%, #10b981 50%, #f59e0b 100%);"></div>

                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1.75rem; flex-wrap: wrap;">
                            <div style="display: flex; align-items: flex-start; gap: 1.25rem; flex: 1; min-width: 280px;">
                                <div style="width: 58px; height: 58px; border-radius: 16px; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1.5px solid #a7f3d0; color: #059669; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.12);">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem; flex-wrap: wrap;">
                                        <span style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; padding: 0.2rem 0.6rem; border-radius: 999px;">
                                            Admissions Open
                                        </span>
                                        <span style="font-size: 0.78rem; color: #64748b; font-weight: 600;">
                                            Academic Year 2026–2027
                                        </span>
                                    </div>
                                    <h3 style="font-size: 1.35rem; font-weight: 900; color: #0f172a; margin: 0 0 0.4rem 0; line-height: 1.2;">
                                        Enroll Now!
                                    </h3>
                                    <p style="font-size: 0.92rem; color: #475569; margin: 0 0 1rem 0; line-height: 1.5; font-weight: 500;">
                                        Submit an official enrollment application for <strong>New</strong>, <strong>Transferee</strong>, or <strong>Returning</strong> students across all grade levels (Nursery to Grade 12).
                                    </p>
                                    
                                    {{-- Feature Highlights / Badges --}}
                                    <div style="display: flex; align-items: center; gap: 0.85rem; flex-wrap: wrap;">
                                        <div style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; font-weight: 700; color: #047857;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            DepEd K to 12 & Integrated Islamic Studies
                                        </div>
                                        <div style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; font-weight: 700; color: #047857;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            Fast 10-Minute Process
                                        </div>
                                        <div style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; font-weight: 700; color: #047857;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            Multi-Child / Sibling Ready
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Single Clear Primary Start CTA Button --}}
                            <div style="flex-shrink: 0;">
                                <a href="{{ route('enrollment.form', ['fresh' => 1]) }}" 
                                   style="display: inline-flex; align-items: center; gap: 0.65rem; padding: 0.85rem 1.65rem; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #ffffff; font-size: 0.95rem; font-weight: 800; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35); transition: all 0.2s ease;"
                                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(5, 150, 105, 0.45)';"
                                   onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 14px rgba(5, 150, 105, 0.35)';">
                                    <span>Start Application</span>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── Payment for Enrollment Fee Card ── --}}
                @if (false)
                <div class="dashboard-action-grid" style="margin-top:0;">
                    @php
                        $paymentEnabled = $applicant && in_array($applicant->status, ['pending','submitted','under_review','approved']);
                    @endphp

                    @if ($payment)
                        {{-- Payment submitted — show status card --}}
                        @php
                            $pmColors = [
                                'pending'  => ['bg'=>'#fef9c3','color'=>'#854d0e','label'=>'Pending Verification'],
                                'verified' => ['bg'=>'#dcfce7','color'=>'#166534','label'=>'Verified'],
                                'rejected' => ['bg'=>'#fee2e2','color'=>'#991b1b','label'=>'Rejected'],
                            ];
                            $pm = $pmColors[$payment->status] ?? ['bg'=>'#f3f4f6','color'=>'#374151','label'=>ucfirst($payment->status)];
                            $methodLogos = ['gcash'=>'GCASH.png','maya'=>'MAYA.png','bdo'=>'BDO.png'];
                            $logo = $methodLogos[$payment->method] ?? null;
                        @endphp
                        <div style="background:white;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;">
                                <div style="display:flex;align-items:center;gap:0.75rem;">
                                    <div style="width:40px;height:40px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
                                            <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div style="font-weight:700;font-size:1rem;color:#111827;">Enrollment Fee Payment</div>
                                        <div style="font-size:0.8125rem;color:#6b7280;">Submitted {{ $payment->paid_at->format('F j, Y \a\t g:i A') }}</div>
                                    </div>
                                </div>
                                <span style="padding:0.3rem 0.875rem;border-radius:999px;font-size:0.8125rem;font-weight:600;background:{{ $pm['bg'] }};color:{{ $pm['color'] }};">
                                    {{ $pm['label'] }}
                                </span>
                            </div>
                            <div style="padding:1.25rem 1.5rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;align-items:center;">
                                <div>
                                    <div style="font-size:0.75rem;color:#9ca3af;font-weight:500;margin-bottom:0.2rem;">Amount Paid</div>
                                    <div style="font-size:1rem;font-weight:800;color:#059669;">₱{{ number_format($payment->amount, 2) }}</div>
                                </div>
                                <div>
                                    <div style="font-size:0.75rem;color:#9ca3af;font-weight:500;margin-bottom:0.2rem;">Payment Method</div>
                                    @if ($logo)
                                        <img src="{{ asset('images/mode_of_payments/' . $logo) }}" alt="{{ $payment->method }}" style="height:22px;object-fit:contain;">
                                    @else
                                        <div style="font-size:0.875rem;font-weight:600;color:#111827;">{{ strtoupper($payment->method) }}</div>
                                    @endif
                                </div>
                                @if ($payment->receipt_url)
                                <div>
                                    <div style="font-size:0.75rem;color:#9ca3af;font-weight:500;margin-bottom:0.2rem;">Receipt</div>
                                    <a href="{{ asset('storage/' . $payment->receipt_url) }}" target="_blank"
                                       style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.3rem 0.75rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;font-size:0.8125rem;color:#065f46;text-decoration:none;font-weight:500;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        View
                                    </a>
                                </div>
                                @endif
                                @if ($payment->status === 'verified' && $payment->verified_at)
                                <div>
                                    <div style="font-size:0.75rem;color:#9ca3af;font-weight:500;margin-bottom:0.2rem;">Verified On</div>
                                    <div style="font-size:0.875rem;font-weight:600;color:#111827;">{{ $payment->verified_at->format('M j, Y') }}</div>
                                </div>
                                @endif
                            </div>
                            @if ($payment->status === 'pending')
                            <div style="padding:0.75rem 1.5rem;background:#fffbeb;border-top:1px solid #fde68a;display:flex;align-items:center;gap:0.5rem;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span style="font-size:0.8125rem;color:#92400e;">Your payment is being reviewed. The Finance Office will verify it within 1–2 business days.</span>
                            </div>
                            @elseif ($payment->status === 'rejected')
                            <div style="padding:0.75rem 1.5rem;background:#fff1f2;border-top:1px solid #fecdd3;display:flex;align-items:center;justify-content:space-between;gap:0.75rem;flex-wrap:wrap;">
                                <span style="font-size:0.8125rem;color:#be123c;">{{ $payment->remarks ?? 'Your payment was rejected. Please resubmit with a valid receipt.' }}</span>
                                <a href="{{ route('enrollment.payment', ['applicant' => $applicant->id]) }}" style="font-size:0.8125rem;font-weight:600;color:#059669;text-decoration:none;">Resubmit →</a>
                            </div>
                            @endif
                        </div>

                    @elseif ($paymentEnabled)
                        <a href="{{ route('enrollment.payment', ['applicant' => $applicant->id]) }}" class="dashboard-card">
                            <div class="card-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                    <line x1="1" y1="10" x2="23" y2="10"/>
                                </svg>
                            </div>
                            <div class="card-content">
                                <h3>Payment for Enrollment Fee</h3>
                                <p>Pay your enrollment fee for School Year 2026–2027</p>
                            </div>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="card-arrow">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </a>
                    @else
                        <div class="dashboard-card disabled">
                            <div class="card-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                    <line x1="1" y1="10" x2="23" y2="10"/>
                                </svg>
                            </div>
                            <div class="card-content">
                                <h3>Payment for Enrollment Fee</h3>
                                <p>Available after submitting your enrollment application</p>
                            </div>
                            <span class="coming-soon">Locked</span>
                        </div>
                    @endif

                </div>
                @endif

                <!-- Enrollment FAQ Section -->
                <x-enrollment-faq-modal />

                <!-- Help & Technical Support Section -->
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 1.65rem 1.75rem; margin-top: 1.5rem; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03);">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                <div style="width: 32px; height: 32px; border-radius: 10px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                                    </svg>
                                </div>
                                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                                    Need Help or Assistance?
                                </h3>
                            </div>
                            <p style="margin: 0.25rem 0 1.25rem 0; font-size: 0.85rem; color: #64748b; font-weight: 500;">
                                If you experience any technical issues or need help with document submission, message our technical support staff.
                            </p>
                        </div>
                    </div>

                    <div style="max-width: 500px;">
                        <a href="https://web.facebook.com/sirmo.amis" target="_blank" rel="noopener" 
                           style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; text-decoration: none; transition: all 0.2s ease;"
                           onmouseover="this.style.background='#ffffff'; this.style.borderColor='#a7f3d0'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 8px 20px rgba(15, 23, 42, 0.06)';"
                           onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.transform='none'; this.style.boxShadow='none';">
                            <div style="width: 46px; height: 46px; border-radius: 12px; background: #eff6ff; border: 1.5px solid #bfdbfe; color: #1d4ed8; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: center; gap: 0.45rem;">
                                    <span style="font-size: 0.72rem; font-weight: 800; color: #059669; text-transform: uppercase; letter-spacing: 0.05em;">Technical Support Staff</span>
                                    <span style="width: 5px; height: 5px; border-radius: 50%; background: #10b981;"></span>
                                </div>
                                <div style="font-size: 1.02rem; font-weight: 800; color: #0f172a; margin-top: 0.1rem;">Sir Mohaymen Unos</div>
                                <div style="font-size: 0.82rem; color: #1d4ed8; font-weight: 600; margin-top: 0.25rem; display: flex; align-items: center; gap: 0.35rem;">
                                    <span>Chat directly on Facebook Messenger</span>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="7" y1="17" x2="17" y2="7"></line>
                                        <polyline points="7 7 17 7 17 17"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
