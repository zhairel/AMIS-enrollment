<style>
    @keyframes amisSpin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .amis-spinner-icon {
        animation: amisSpin 0.85s linear infinite;
        flex-shrink: 0;
    }
    .premium-input-wrapper input#email {
        padding-left: 3.2rem !important;
        padding-right: 1.25rem !important;
    }
    .enrollment-step-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.28);
        color: #ffffff;
        font-weight: 800;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .level-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.76rem;
        font-weight: 650;
        white-space: nowrap;
    }
    .admissions-contact-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        color: rgba(255, 255, 255, 0.88);
        font-size: 0.78rem;
        font-weight: 600;
    }
</style>

<x-guest-layout :show-loader="false">
<div id="login-page" class="login-grid login-page visible">
    {{-- Left Hero Panel: Official Enrollment Hub & Academic Guidance --}}
    <section class="login-info auth-hero-panel" style="justify-content: flex-start; padding: clamp(2rem, 3.5vw, 3.75rem); gap: 1.25rem; overflow-y: auto;">
        
        {{-- School Branding & Calligraphy Header --}}
        <div class="login-brand-block auth-hero-brand">
            <img src="{{ asset('images/AMIS_Logo.png') }}" alt="AMIS Logo" style="width: 82px; height: 82px; object-fit: contain;">
            <div style="min-width: 0;">
                <div class="auth-hero-arabic" lang="ar" dir="rtl" style="font-size: 1.35rem; color: #a7f3d0; margin-bottom: 2px;">
                    المدرسة المنورة الإسلامية
                </div>
                <div class="auth-hero-school" style="font-size: 1.35rem; font-weight: 900; letter-spacing: 0.02em;">
                    AL MUNAWWARA ISLAMIC SCHOOL
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.4rem; flex-wrap: wrap;">
                    <span style="display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(52, 211, 153, 0.4); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.74rem; font-weight: 800; color: #6ee7b7; letter-spacing: 0.04em; text-transform: uppercase;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #34d399; box-shadow: 0 0 6px #34d399;"></span>
                        Academic Year 2026–2027
                    </span>
                    <span style="font-size: 0.74rem; color: rgba(255, 255, 255, 0.7); font-weight: 600;">
                        Official Online Enrollment
                    </span>
                </div>
            </div>
        </div>

        {{-- Main Headline & Purpose --}}
        <div class="auth-hero-copy" style="max-width: 600px; margin-top: 0.15rem;">
            <h1 style="font-size: clamp(1.65rem, 2.4vw, 2.25rem); font-weight: 900; line-height: 1.2; color: #ffffff; margin: 0 0 0.5rem 0;">
                Welcome to AMIS Enrollment
            </h1>
            <p style="font-size: 0.92rem; line-height: 1.55; color: rgba(255, 255, 255, 0.88); font-weight: 500; margin: 0;">
                Register new students, submit transferee credentials, or re-enroll continuing students online. Follow the 4-step guide below to complete your registration.
            </p>
        </div>

        {{-- Levels Open for Enrollment (Distinctive Educational Pill Row) --}}
        <div style="max-width: 600px; width: 100%;">
            <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255, 255, 255, 0.65); margin-bottom: 0.45rem;">
                Available Academic Levels:
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 0.45rem;">
                <span class="level-pill">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    Kindergarten (Ages 4–5)
                </span>
                <span class="level-pill">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Elementary (Grades 1–6)
                </span>
                <span class="level-pill">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Junior High (Grades 7–10)
                </span>
                <span class="level-pill">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fde68a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    Senior High (STEM • ABM • HUMSS • TVL)
                </span>
            </div>
        </div>

        {{-- 4-Step Enrollment Roadmap Cards --}}
        <div style="max-width: 600px; width: 100%; display: grid; grid-template-columns: 1fr; gap: 0.55rem; margin-top: 0.25rem;">
            <div style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 14px; padding: 0.75rem 0.95rem; display: flex; align-items: center; gap: 0.85rem;">
                <div class="enrollment-step-badge">1</div>
                <div style="flex: 1; min-width: 0;">
                    <div style="color: #ffffff; font-weight: 800; font-size: 0.86rem; line-height: 1.2;">Sign In with Parent or Student Account</div>
                    <div style="color: rgba(255, 255, 255, 0.72); font-size: 0.76rem; margin-top: 1px;">Access your secure pre-enrollment dashboard via Google or Email OTP.</div>
                </div>
            </div>

            <div style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 14px; padding: 0.75rem 0.95rem; display: flex; align-items: center; gap: 0.85rem;">
                <div class="enrollment-step-badge">2</div>
                <div style="flex: 1; min-width: 0;">
                    <div style="color: #ffffff; font-weight: 800; font-size: 0.86rem; line-height: 1.2;">Complete Student & Family Profile</div>
                    <div style="color: rgba(255, 255, 255, 0.72); font-size: 0.76rem; margin-top: 1px;">Enter student details, select incoming grade level, and choose academic strand.</div>
                </div>
            </div>

            <div style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 14px; padding: 0.75rem 0.95rem; display: flex; align-items: center; gap: 0.85rem;">
                <div class="enrollment-step-badge">3</div>
                <div style="flex: 1; min-width: 0;">
                    <div style="color: #ffffff; font-weight: 800; font-size: 0.86rem; line-height: 1.2;">Submit Documents Online</div>
                    <div style="color: rgba(255, 255, 255, 0.72); font-size: 0.76rem; margin-top: 1px;">Upload PSA Birth Certificate and latest Report Card (Form 138/137).</div>
                </div>
            </div>

            <div style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 14px; padding: 0.75rem 0.95rem; display: flex; align-items: center; gap: 0.85rem;">
                <div class="enrollment-step-badge" style="background: rgba(245, 158, 11, 0.25); border-color: rgba(251, 191, 36, 0.5); color: #fde68a;">4</div>
                <div style="flex: 1; min-width: 0;">
                    <div style="color: #ffffff; font-weight: 800; font-size: 0.86rem; line-height: 1.2;">Tuition Assessment & Section Assignment</div>
                    <div style="color: rgba(255, 255, 255, 0.72); font-size: 0.76rem; margin-top: 1px;">Choose your payment scheme and receive your official enrollment confirmation.</div>
                </div>
            </div>
        </div>

        {{-- Video Walkthrough Tutorial Modal --}}
        <div x-data="{
            showVideoModal: false,
            openVideo() {
                this.showVideoModal = true;
                this.$nextTick(() => {
                    const video = this.$refs.enrollmentVideo;
                    if (video) {
                        video.currentTime = 0;
                        video.play().catch(() => {});
                    }
                });
            },
            closeVideo() {
                const video = this.$refs.enrollmentVideo;
                if (video) {
                    video.pause();
                }
                this.showVideoModal = false;
            }
        }" style="max-width: 600px; width: 100%;">
            <div @click="openVideo()" 
                 class="video-guide-preview-card" 
                 style="background: rgba(15, 23, 42, 0.45); border: 1px solid rgba(255, 255, 255, 0.22); border-radius: 16px; padding: 1.15rem 1.25rem; backdrop-filter: blur(14px); position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.22); cursor: pointer; transition: all 0.25s ease;"
                 onmouseover="this.style.background='rgba(15, 23, 42, 0.65)'; this.style.borderColor='rgba(52, 211, 153, 0.6)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(15, 23, 42, 0.45)'; this.style.borderColor='rgba(255, 255, 255, 0.22)'; this.style.transform='translateY(0)';"
                 title="Click to watch tutorial walkthrough">
                
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: 2px solid rgba(255, 255, 255, 0.35); color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35); flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-left: 2px;">
                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                            </svg>
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 0.98rem; font-weight: 800; color: #ffffff; letter-spacing: 0.02em;">
                                Step-by-Step Enrollment Video Guide
                            </h3>
                            <p style="margin: 0.15rem 0 0 0; font-size: 0.78rem; color: rgba(255, 255, 255, 0.8); font-weight: 500;">
                                35-second walkthrough of the online registration process.
                            </p>
                        </div>
                    </div>

                    <div style="background: rgba(16, 185, 129, 0.25); border: 1px solid rgba(52, 211, 153, 0.5); color: #6ee7b7; font-size: 0.68rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.06em; flex-shrink: 0;">
                        Watch Video
                    </div>
                </div>
            </div>

            <!-- Video Modal Popup -->
            <div x-show="showVideoModal" 
                 x-cloak 
                 class="confirm-overlay" 
                 style="z-index: 999999; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem;" 
                 @keydown.escape.window="closeVideo()">
                
                <div class="confirm-dialog" 
                     style="max-width: 880px; width: 100%; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); background: #0f172a; border: 1px solid rgba(255, 255, 255, 0.15); overflow: hidden; display: flex; flex-direction: column;" 
                     @click.outside="closeVideo()">
                    
                    <!-- Modal Header -->
                    <div style="padding: 1rem 1.25rem; background: rgba(30, 41, 59, 0.85); border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #059669; color: #ffffff; display: flex; align-items: center; justify-content: center;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #ffffff;">AMIS Online Enrollment Walkthrough</h3>
                                <p style="margin: 2px 0 0; font-size: 0.75rem; color: #94a3b8; font-weight: 500;">Academic Year 2026–2027 &bull; aes.amis.edu.ph</p>
                            </div>
                        </div>
                        <button type="button" 
                                @click="closeVideo()" 
                                aria-label="Close video player" 
                                style="background: rgba(255, 255, 255, 0.1); border: none; color: #e2e8f0; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s ease;" 
                                onmouseover="this.style.background='rgba(239, 68, 68, 0.3)'; this.style.color='#fca5a5';" 
                                onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.color='#e2e8f0';">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Video Container -->
                    <div style="position: relative; padding-top: 56.25%; width: 100%; background: #000000;">
                        <video x-ref="enrollmentVideo" 
                               controls 
                               playsinline 
                               preload="metadata"
                               poster="{{ asset('videos/poster.png') }}"
                               style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;">
                            <source src="{{ asset('videos/amis-enrollment-demo.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <!-- Video Controls / Caption Bar -->
                    <div style="padding: 0.75rem 1.25rem; background: rgba(30, 41, 59, 0.6); display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; color: #94a3b8; flex-wrap: wrap; gap: 0.5rem;">
                        <span>Step 1: Application Form &rarr; Step 2: Verification &rarr; Step 3: Official Enrollment Confirmation</span>
                        <a href="{{ asset('videos/amis-enrollment-demo.mp4') }}" download style="color: #34d399; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.35rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Download Video (MP4)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enrollment FAQs Component --}}
        <div style="max-width: 600px; width: 100%; margin-top: 0.25rem;">
            <x-enrollment-faq-modal />
        </div>

        {{-- Admissions Office & Registrar Helpdesk (Pure SVG vector icons, ZERO emojis) --}}
        <div style="max-width: 600px; width: 100%; background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.18); border-radius: 16px; padding: 1.15rem 1.25rem; margin-top: 0.25rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a7f3d0" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    <span style="font-size: 0.85rem; font-weight: 800; color: #ffffff; letter-spacing: 0.02em;">Admissions & Registrar Support</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.74rem; font-weight: 700; color: #6ee7b7; background: rgba(16, 185, 129, 0.25); border: 1px solid rgba(52, 211, 153, 0.4); padding: 0.2rem 0.6rem; border-radius: 999px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>Mon–Fri 8:00 AM – 5:00 PM</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.65rem; font-size: 0.8rem; margin-bottom: 0.85rem;">
                <div class="admissions-contact-pill">
                    <span style="color: #6ee7b7; font-weight: 700;">Landline:</span>
                    <span style="color: #ffffff; font-weight: 700;">(082) 287-AMIS</span>
                </div>
                <div class="admissions-contact-pill">
                    <span style="color: #6ee7b7; font-weight: 700;">Mobile:</span>
                    <span style="color: #ffffff; font-weight: 700;">0912 345 6789</span>
                </div>
                <div class="admissions-contact-pill" style="grid-column: 1 / -1;">
                    <span style="color: #6ee7b7; font-weight: 700;">Email:</span>
                    <span style="color: #ffffff; font-weight: 600;">admissions@amis.edu.ph</span>
                </div>
            </div>

            <div style="padding-top: 0.75rem; border-top: 1px solid rgba(255, 255, 255, 0.12); display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <img src="{{ asset('images/deped_logo.png') }}" alt="DepEd" style="width: 22px; height: 22px; object-fit: contain;">
                    <span style="font-size: 0.74rem; color: rgba(255, 255, 255, 0.8); font-weight: 600;">DepEd Recognized &bull; ESC Participating School</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.74rem; color: #a7f3d0; font-weight: 600;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <span>Secure Student Data</span>
                </div>
            </div>
        </div>

        {{-- Left Footer Copyright --}}
        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.12); color: rgba(255, 255, 255, 0.65); font-size: 0.76rem; font-weight: 500;">
            © 2026 Al Munawwara Islamic School. All rights reserved. &bull; AMIS Enrollment Portal (aes.amis.edu.ph)
        </div>
    </section>

    {{-- Right Login Panel --}}
    <section class="login-form" style="background: #f8fafc; display: flex; align-items: center; justify-content: center; padding: clamp(1.5rem, 4vw, 3rem);">
        <div class="login-form-panel" style="width: min(100%, 460px);">
            <div class="auth-entry-card" style="border-radius: 20px; border: 1px solid #e2e8f0; background: #ffffff; padding: clamp(1.75rem, 3vw, 2.5rem); box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.08);" x-data="{ 
                email: '', 
                otp: ['', '', '', ''], 
                step: 'providers',
                showEmailForm: false, 
                loading: false, 
                errorMessage: '', 
                successMessage: '',
                submitEmail() {
                    if (!this.email || !this.email.includes('@')) {
                        this.errorMessage = 'Please enter a valid email address.';
                        return;
                    }
                    this.loading = true;
                    this.errorMessage = '';
                    this.successMessage = '';

                    fetch('{{ route('auth.send-otp') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json, text/plain, */*',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                        },
                        body: JSON.stringify({ email: this.email })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, data })))
                    .then(res => {
                        this.loading = false;
                        if (res.status === 200 && res.data.status === 'success') {
                            this.step = 'otp';
                            this.successMessage = res.data.message;
                            this.$nextTick(() => {
                                this.$refs.otp0.focus();
                            });
                        } else {
                            this.errorMessage = res.data.message || 'An error occurred. Please try again.';
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        this.errorMessage = 'Network error. Please check your internet connection.';
                    });
                },
                handleOtpInput(event, index) {
                    const val = event.target.value;
                    this.otp[index] = val.replace(/[^0-9]/g, '');

                    if (this.otp[index] && index < 3) {
                        this.$refs['otp' + (index + 1)].focus();
                    }

                    if (this.otp.join('').length === 4) {
                        this.verifyOtpCode();
                    }
                },
                handleOtpKeydown(event, index) {
                    if (event.key === 'Backspace') {
                        if (!this.otp[index] && index > 0) {
                            this.otp[index - 1] = '';
                            this.$refs['otp' + (index - 1)].focus();
                        }
                    }
                },
                verifyOtpCode() {
                    const code = this.otp.join('');
                    if (code.length !== 4) return;

                    this.loading = true;
                    this.errorMessage = '';
                    this.successMessage = '';

                    fetch('{{ route('auth.verify-otp') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json, text/plain, */*',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                        },
                        body: JSON.stringify({ email: this.email, code: code })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, data })))
                    .then(res => {
                        this.loading = false;
                        if (res.status === 200 && res.data.status === 'success') {
                            window.location.href = res.data.redirectUrl;
                        } else {
                            this.errorMessage = res.data.message || 'Invalid verification code.';
                            this.otp = ['', '', '', ''];
                            this.$nextTick(() => {
                                this.$refs.otp0.focus();
                            });
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        this.errorMessage = 'Network error. Please try again.';
                    });
                },
                resendOtpCode() {
                    this.loading = true;
                    this.errorMessage = '';
                    this.successMessage = '';

                    fetch('{{ route('auth.send-otp') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json, text/plain, */*',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                        },
                        body: JSON.stringify({ email: this.email })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, data })))
                    .then(res => {
                        this.loading = false;
                        if (res.status === 200 && res.data.status === 'success') {
                            this.successMessage = 'A new 4-digit code has been sent!';
                            this.otp = ['', '', '', ''];
                            this.$nextTick(() => {
                                this.$refs.otp0.focus();
                            });
                        } else {
                            this.errorMessage = res.data.message || 'Could not resend the code.';
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        this.errorMessage = 'Network error. Please try again.';
                    });
                }
            }">
                {{-- Mobile Brand Header --}}
                <div class="auth-entry-mobile-brand" style="margin-bottom: 1.25rem;">
                    <img src="{{ asset('images/AMIS_Logo.png') }}" alt="AMIS" class="auth-entry-mobile-logo">
                    <div class="auth-entry-mobile-arabic" lang="ar" dir="rtl" style="color: #064e3b; font-size: 1.15rem;">المدرسة المنورة الإسلامية</div>
                    <div class="auth-entry-mobile-school" style="color: #0f172a; font-size: 1.05rem;">AL MUNAWWARA ISLAMIC SCHOOL</div>
                    <div style="color: #059669; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-top: 2px;">ONLINE ENROLLMENT PORTAL</div>
                </div>

                <div class="auth-entry-heading" style="margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                        <span class="auth-entry-kicker" style="color: #059669; font-size: 0.74rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase;">
                            ONLINE ENROLLMENT
                        </span>
                        <span style="font-size: 0.72rem; font-weight: 700; color: #475569; background: #f1f5f9; padding: 0.2rem 0.55rem; border-radius: 999px;">
                            SY 2026–2027
                        </span>
                    </div>

                    <h2 x-show="step === 'providers'" style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem 0; line-height: 1.25;">
                        Sign In to Enroll
                    </h2>
                    <h2 x-show="step === 'email'" style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem 0; line-height: 1.25;">
                        Sign In with Email
                    </h2>
                    <h2 x-show="step === 'otp'" style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem 0; line-height: 1.25;">
                        Verify Your Email
                    </h2>

                    <p x-show="step === 'providers'" style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.45;">
                        Access the enrollment system using your Google account or email address to start a new application or continue your saved draft.
                    </p>
                    <p x-show="step === 'email'" style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.45;">
                        Enter the email address where you wish to receive your application updates and confirmation.
                    </p>
                    <p x-show="step === 'otp'" style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.45;">
                        We sent a 4-digit verification code to <strong x-text="email" style="color:#0f172a; word-break:break-all;"></strong>. Enter it below to proceed.
                    </p>
                </div>

                <!-- Messages -->
                <div class="auth-success-message" x-show="successMessage" x-text="successMessage" style="display:none; margin-bottom: 1rem;"></div>
                <div class="auth-error-message" x-show="errorMessage" x-text="errorMessage" style="display:none; margin-bottom: 1rem;"></div>

                <!-- Step 1: Login Providers Options -->
                <div x-show="step === 'providers'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <!-- Google Sign In (Google Identity Services) -->
                    <script src="https://accounts.google.com/gsi/client" async defer></script>
                    <div id="g_id_onload"
                         data-client_id="{{ config('services.google.client_id') }}"
                         data-context="signin"
                         data-ux_mode="redirect"
                         data-login_uri="{{ route('auth.google.callback') }}"
                         data-auto_prompt="{{ request('prompt_google') ? 'true' : 'false' }}">
                    </div>
                    <div class="google-gis-container" style="margin-bottom: 0.85rem; width: 100%; display: flex; justify-content: center;">
                        <div class="g_id_signin"
                             data-type="standard"
                             data-shape="pill"
                             data-theme="outline"
                             data-text="signin_with"
                             data-size="large"
                             data-logo_alignment="left"
                             data-width="360">
                        </div>
                    </div>

                    <!-- Clean Divider -->
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin: 0.95rem 0;">
                        <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                        <span style="font-size: 0.76rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">or sign in with email</span>
                        <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                    </div>

                    <!-- Active Email Sign In Button -->
                    <button type="button" @click="step = 'email'; errorMessage = ''; successMessage = '';" class="auth-provider-button email-auth-button" style="width: 100%; min-height: 50px; border-radius: 9999px; border: 1.5px solid #064e3b; background: #ffffff; padding: 0.6rem 1.25rem; display: flex; align-items: center; justify-content: center; gap: 0.65rem; font-weight: 700; color: #064e3b; cursor: pointer; transition: all 0.2s ease;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#064e3b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <span style="font-size: 0.92rem;">Sign in with Email</span>
                    </button>
                </div>

                <!-- Step 2: Dedicated Email Input View -->
                <div x-show="step === 'email'" style="display:none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div style="margin-bottom: 1.15rem;">
                        <a href="#" @click.prevent="step = 'providers'; errorMessage = ''; successMessage = '';" style="display: inline-flex; align-items: center; gap: 0.4rem; color: #064e3b; font-size: 0.85rem; font-weight: 700; text-decoration: none;">
                            &larr; Back to sign-in options
                        </a>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.15rem;">
                        <label for="email" class="premium-input-label" style="display: block; font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 0.45rem;">
                            Email Address
                        </label>
                        <div class="premium-input-wrapper" style="position: relative; display: flex; align-items: center; width: 100%;">
                            <svg class="premium-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" aria-hidden="true" style="position: absolute; left: 1.15rem; top: 50%; transform: translateY(-50%); pointer-events: none; z-index: 10; flex-shrink: 0;">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input type="email" id="email" x-model="email" placeholder="e.g. parent.name@example.com" class="premium-input-field" style="width: 100%; padding-left: 3.1rem !important; padding-right: 1.25rem !important; padding-top: 0.85rem !important; padding-bottom: 0.85rem !important; border-radius: 9999px; border: 1.5px solid #cbd5e1; font-size: 0.95rem; outline: none; background: #ffffff; color: #0f172a; box-sizing: border-box;" @keydown.enter.prevent="submitEmail()">
                        </div>
                    </div>

                    <button type="button" class="auth-button premium-continue-button" @click="submitEmail()" :disabled="loading || !email" style="width: 100%; min-height: 50px; border-radius: 9999px; background: #064e3b; color: #ffffff; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.6rem; transition: background 0.15s ease;">
                        <span x-show="!loading">Send Verification Code</span>
                        <span x-show="loading" style="display: flex; align-items: center; justify-content: center; gap: 0.6rem;">
                            <svg class="amis-spinner-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" stroke-dasharray="32" stroke-dashoffset="10" fill="none"/>
                            </svg>
                            <span>Sending Verification Code...</span>
                        </span>
                    </button>
                </div>

                <!-- Step 3: OTP Code Input View -->
                <div x-show="step === 'otp'" style="display:none;">
                    <div class="otp-inputs-row" style="display: flex; gap: 0.65rem; justify-content: center; margin: 1.25rem 0;">
                        <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                               x-model="otp[0]" @input="handleOtpInput($event, 0)" @keydown="handleOtpKeydown($event, 0)" x-ref="otp0"
                               style="width: 54px; height: 58px; font-size: 1.4rem; font-weight: 800; text-align: center; border: 2px solid #cbd5e1; border-radius: 12px; outline: none; background: #f8fafc; color: #0f172a;">
                        <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                               x-model="otp[1]" @input="handleOtpInput($event, 1)" @keydown="handleOtpKeydown($event, 1)" x-ref="otp1"
                               style="width: 54px; height: 58px; font-size: 1.4rem; font-weight: 800; text-align: center; border: 2px solid #cbd5e1; border-radius: 12px; outline: none; background: #f8fafc; color: #0f172a;">
                        <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                               x-model="otp[2]" @input="handleOtpInput($event, 2)" @keydown="handleOtpKeydown($event, 2)" x-ref="otp2"
                               style="width: 54px; height: 58px; font-size: 1.4rem; font-weight: 800; text-align: center; border: 2px solid #cbd5e1; border-radius: 12px; outline: none; background: #f8fafc; color: #0f172a;">
                        <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                               x-model="otp[3]" @input="handleOtpInput($event, 3)" @keydown="handleOtpKeydown($event, 3)" x-ref="otp3"
                               style="width: 54px; height: 58px; font-size: 1.4rem; font-weight: 800; text-align: center; border: 2px solid #cbd5e1; border-radius: 12px; outline: none; background: #f8fafc; color: #0f172a;">
                    </div>

                    <button type="button" class="auth-button premium-continue-button" @click="verifyOtpCode()" :disabled="loading || otp.join('').length !== 4" style="width: 100%; min-height: 50px; border-radius: 9999px; background: #064e3b; color: #ffffff; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.6rem; margin-top: 1.25rem;">
                        <span x-show="!loading">Verify Code & Enter Dashboard</span>
                        <span x-show="loading" style="display: flex; align-items: center; justify-content: center; gap: 0.6rem;">
                            <svg class="amis-spinner-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" stroke-dasharray="32" stroke-dashoffset="10" fill="none"/>
                            </svg>
                            <span>Verifying Code...</span>
                        </span>
                    </button>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.25rem; font-size:0.85rem;">
                        <a href="#" @click.prevent="step = 'email'; errorMessage = ''; successMessage = '';" class="otp-back-link" style="color: #64748b; font-weight: 600; text-decoration: none;">&larr; Back to email</a>
                        <a href="#" @click.prevent="resendOtpCode()" class="otp-resend-link" style="color: #059669; font-weight: 700; text-decoration: none;" :style="loading ? 'pointer-events:none; opacity:0.5;' : ''">Resend Code</a>
                    </div>
                </div>

                {{-- Trust & Privacy Notice (Pure SVG icon, NO emojis, NO chips) --}}
                <div style="margin-top: 1.75rem; padding-top: 1.15rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: #64748b; font-size: 0.78rem; text-align: center;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <span>Protected by AMIS Security Policies &bull; Data Privacy Compliant</span>
                </div>

                {{-- Mobile FAQ Section --}}
                <div class="mobile-faq-wrapper" style="display: none; margin-top: 1.5rem;">
                    <x-enrollment-faq-modal />
                </div>

                {{-- Mobile Copyright Footer --}}
                <div style="margin-top: 1.5rem; text-align: center; color: #94a3b8; font-size: 0.78rem; font-weight: 500;">
                    © 2026 Al Munawwara Islamic School. All rights reserved.
                </div>
            </div>
        </div>
    </section>
</div>
</x-guest-layout>
