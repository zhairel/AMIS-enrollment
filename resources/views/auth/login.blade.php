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
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.45); }
        50% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    }
    .pulse-ring {
        animation: pulseGlow 2s infinite;
    }
</style>

<x-guest-layout :show-loader="false">
<div id="login-page" class="login-grid login-page visible">
    {{-- Left Hero Panel: Institutional Campus Pride & Academic Showcase --}}
    <section class="login-info auth-hero-panel" style="justify-content: center; padding: clamp(2rem, 3.8vw, 4rem); gap: 1.15rem;">
        {{-- Branding Block --}}
        <div class="login-brand-block auth-hero-brand">
            <div style="position: relative; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <div style="position: absolute; inset: -4px; border-radius: 50%; background: radial-gradient(circle, rgba(245, 158, 11, 0.35) 0%, transparent 70%);"></div>
                <img src="{{ asset('images/AMIS_Logo.png') }}" alt="AMIS Logo" style="position: relative; z-index: 2; width: 84px; height: 84px; object-fit: contain; filter: drop-shadow(0 8px 18px rgba(0, 0, 0, 0.35));">
            </div>
            <div style="min-width: 0;">
                <div class="auth-hero-arabic" lang="ar" dir="rtl" style="font-family: 'Amiri', 'Tajawal', serif; color: #fef3c7; font-size: clamp(1.2rem, 1.8vw, 1.6rem); font-weight: 700; margin-bottom: 2px;">
                    المدرسة المنورة الإسلامية
                </div>
                <div class="auth-hero-school" style="font-size: clamp(1.15rem, 1.8vw, 1.65rem); font-weight: 900; letter-spacing: 0.02em; color: #ffffff;">
                    AL MUNAWWARA ISLAMIC SCHOOL
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 4px; flex-wrap: wrap;">
                    <span style="font-size: 0.72rem; font-weight: 800; color: #34d399; background: rgba(6, 78, 59, 0.6); border: 1px solid rgba(52, 211, 153, 0.4); padding: 0.15rem 0.55rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.04em; display: inline-flex; align-items: center; gap: 0.35rem;">
                        <span style="width: 5px; height: 5px; border-radius: 50%; background: #34d399;"></span>
                        DepEd Recognized
                    </span>
                    <span style="font-size: 0.72rem; font-weight: 700; color: #fcd34d; background: rgba(180, 83, 9, 0.35); border: 1px solid rgba(245, 158, 11, 0.4); padding: 0.15rem 0.55rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.04em;">
                        ESC Certified
                    </span>
                </div>
            </div>
        </div>

        {{-- Main Left Headline & Academic Tag --}}
        <div class="auth-hero-copy" style="max-width: 580px; margin-top: 0.15rem;">
            <div class="school-hero-tag" style="margin-bottom: 0.65rem;">
                <span style="width: 6px; height: 6px; border-radius: 50%; background: #fbbf24;"></span>
                <span>Academic Year 2026–2027 • Online Pre-Enrollment</span>
            </div>
            <h1 style="font-size: clamp(1.45rem, 2.1vw, 2.05rem); font-weight: 900; line-height: 1.25; color: #ffffff; margin: 0 0 0.45rem 0; letter-spacing: -0.01em;">
                Empowering Minds with Islamic Values & Academic Excellence.
            </h1>
            <p style="font-size: 0.88rem; line-height: 1.55; color: rgba(255, 255, 255, 0.88); font-weight: 500; margin: 0;">
                Welcome to the unified online pre-enrollment gateway for New, Continuing, and Transferee students. Complete your registration, upload credentials, and secure your admission 100% online.
            </p>
        </div>

        {{-- Academic Highlights Strip (Clean single row) --}}
        <div class="school-highlights-row">
            <div class="school-highlight-pill">
                <span>🕌</span>
                <span>Islamic Values & Arabic</span>
            </div>
            <div class="school-highlight-pill">
                <span>🎓</span>
                <span>DepEd K-12 Accredited</span>
            </div>
            <div class="school-highlight-pill">
                <span>🔬</span>
                <span>SHS Strands: STEM &bull; ABM &bull; HUMSS &bull; TVL</span>
            </div>
        </div>

        {{-- Video Guide Showcase Card with Dual Tab Modal --}}
        <div x-data="{
            showVideoModal: false,
            activeTab: 'showreel',
            openVideo(tab = 'showreel') {
                this.activeTab = tab;
                this.showVideoModal = true;
                this.$nextTick(() => {
                    this.playCurrentVideo();
                });
            },
            switchTab(tab) {
                if (this.activeTab === tab) return;
                this.pauseAllVideos();
                this.activeTab = tab;
                this.$nextTick(() => {
                    this.playCurrentVideo();
                });
            },
            playCurrentVideo() {
                const target = this.activeTab === 'showreel' ? this.$refs.showreelVideo : this.$refs.tutorialVideo;
                if (target) {
                    target.currentTime = 0;
                    target.play().catch(() => {});
                }
            },
            pauseAllVideos() {
                if (this.$refs.showreelVideo) this.$refs.showreelVideo.pause();
                if (this.$refs.tutorialVideo) this.$refs.tutorialVideo.pause();
            },
            closeVideo() {
                this.pauseAllVideos();
                this.showVideoModal = false;
            }
        }">
            <div @click="openVideo('showreel')" 
                 class="school-video-preview" 
                 title="Click to watch official enrollment videos">
                
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.9rem;">
                        <div class="pulse-ring" style="width: 46px; height: 46px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: 2px solid rgba(255, 255, 255, 0.4); color: #ffffff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-left: 2px;">
                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                            </svg>
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 2px;">
                                <span style="font-size: 0.7rem; font-weight: 800; color: #fbbf24; letter-spacing: 0.05em; text-transform: uppercase;">★ Official Video Guide</span>
                                <span style="color: rgba(255,255,255,0.4); font-size: 0.7rem;">&bull;</span>
                                <span style="font-size: 0.7rem; color: #6ee7b7; font-weight: 600;">15s Showreel & 35s Walkthrough</span>
                            </div>
                            <h3 style="margin: 0; font-size: 0.98rem; font-weight: 800; color: #ffffff;">
                                Enrollment System Walkthrough & Showreel
                            </h3>
                            <p style="margin: 2px 0 0 0; font-size: 0.78rem; color: rgba(255, 255, 255, 0.8);">
                                See how fast & paperless enrollment works for SY 2026–2027.
                            </p>
                        </div>
                    </div>
                    <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(52, 211, 153, 0.5); color: #6ee7b7; font-size: 0.72rem; font-weight: 800; padding: 0.35rem 0.85rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; display: flex; align-items: center; gap: 0.35rem;">
                        <span>WATCH</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>
            </div>

            <!-- Video Modal Popup with Dual Tabs -->
            <div x-show="showVideoModal" 
                 x-cloak 
                 class="confirm-overlay" 
                 style="z-index: 999999; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(10px); position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem;" 
                 @keydown.escape.window="closeVideo()">
                
                <div class="confirm-dialog" 
                     style="max-width: 900px; width: 100%; border-radius: 20px; box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.7); background: #0f172a; border: 1px solid rgba(255, 255, 255, 0.15); overflow: hidden; display: flex; flex-direction: column;" 
                     @click.outside="closeVideo()">
                    
                    <!-- Modal Header & Tabs -->
                    <div style="padding: 1rem 1.25rem; background: rgba(30, 41, 59, 0.9); border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <img src="{{ asset('images/AMIS_Logo.png') }}" alt="AMIS Logo" style="width: 32px; height: 32px; object-fit: contain;">
                            <div>
                                <h3 style="margin: 0; font-size: 1rem; font-weight: 800; color: #ffffff;">AMIS Online Enrollment Center</h3>
                                <p style="margin: 2px 0 0; font-size: 0.72rem; color: #94a3b8; font-weight: 500;">Academic Year 2026–2027 &bull; aes.amis.edu.ph</p>
                            </div>
                        </div>

                        <!-- Video Mode Tabs -->
                        <div style="display: flex; align-items: center; background: rgba(15, 23, 42, 0.6); padding: 3px; border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.1);">
                            <button type="button" 
                                    @click="switchTab('showreel')" 
                                    :style="activeTab === 'showreel' ? 'background: #059669; color: #ffffff; font-weight: 800; box-shadow: 0 2px 8px rgba(5,150,105,0.4);' : 'background: transparent; color: #94a3b8; font-weight: 600;'"
                                    style="border: none; padding: 0.4rem 0.85rem; border-radius: 7px; font-size: 0.75rem; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.35rem;">
                                <span>⚡ 15s Showreel</span>
                            </button>
                            <button type="button" 
                                    @click="switchTab('tutorial')" 
                                    :style="activeTab === 'tutorial' ? 'background: #059669; color: #ffffff; font-weight: 800; box-shadow: 0 2px 8px rgba(5,150,105,0.4);' : 'background: transparent; color: #94a3b8; font-weight: 600;'"
                                    style="border: none; padding: 0.4rem 0.85rem; border-radius: 7px; font-size: 0.75rem; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.35rem;">
                                <span>🎬 35s Walkthrough</span>
                            </button>
                        </div>

                        <button type="button" 
                                @click="closeVideo()" 
                                aria-label="Close video player" 
                                style="background: rgba(255, 255, 255, 0.1); border: none; color: #e2e8f0; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s ease;" 
                                onmouseover="this.style.background='rgba(239, 68, 68, 0.3)'; this.style.color='#fca5a5';" 
                                onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.color='#e2e8f0';">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Video Container -->
                    <div style="position: relative; padding-top: 56.25%; width: 100%; background: #000000;">
                        <!-- Video 1: 15s Motion Showreel -->
                        <video x-show="activeTab === 'showreel'" 
                               x-ref="showreelVideo" 
                               controls 
                               playsinline 
                               preload="metadata"
                               style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;">
                            <source src="{{ asset('videos/aes-showreel.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>

                        <!-- Video 2: 35s Step-by-Step Tutorial -->
                        <video x-show="activeTab === 'tutorial'" 
                               x-ref="tutorialVideo" 
                               controls 
                               playsinline 
                               preload="metadata"
                               poster="{{ asset('videos/poster.png') }}"
                               style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;">
                            <source src="{{ asset('videos/amis-enrollment-demo.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <!-- Video Caption & Download Bar -->
                    <div style="padding: 0.75rem 1.25rem; background: rgba(30, 41, 59, 0.75); display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; color: #94a3b8; flex-wrap: wrap; gap: 0.5rem;">
                        <span x-show="activeTab === 'showreel'">✨ 15-Second Dynamic Motion Showreel for AMIS Online Enrollment</span>
                        <span x-show="activeTab === 'tutorial'">Step 1: Fill Application &rarr; Step 2: Upload Documents &rarr; Step 3: Instant Digital ID</span>
                        
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <a :href="activeTab === 'showreel' ? '{{ asset('videos/aes-showreel.mp4') }}' : '{{ asset('videos/amis-enrollment-demo.mp4') }}'" 
                               download 
                               style="color: #34d399; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.35rem;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                <span>Download MP4</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enrollment FAQs Component --}}
        <div style="max-width: 580px; width: 100%; margin-top: 0.25rem;">
            <x-enrollment-faq-modal />
        </div>

        {{-- Left Footer Copyright --}}
        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.15); color: rgba(255, 255, 255, 0.75); font-size: 0.78rem; font-weight: 500;">
            © 2026 Al Munawwara Islamic School. All rights reserved. &bull; AMIS Enrollment System
        </div>
    </section>

    {{-- Right Login Panel: Official Unified School Admission Card --}}
    <section class="login-form">
        <div class="login-form-panel">
            <div class="auth-entry-card" x-data="{ 
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
                {{-- Decorative Card Accent Line --}}
                <div style="background: linear-gradient(90deg, #059669 0%, #d97706 50%, #059669 100%); height: 4px; width: 100%;"></div>

                <div class="auth-entry-card-body">
                    {{-- Mobile Brand Header --}}
                    <div class="auth-entry-mobile-brand">
                        <img src="{{ asset('images/AMIS_Logo.png') }}" alt="AMIS" class="auth-entry-mobile-logo">
                        <div class="auth-entry-mobile-arabic" lang="ar" dir="rtl">المدرسة المنورة الإسلامية</div>
                        <div class="auth-entry-mobile-school">AL MUNAWWARA ISLAMIC SCHOOL</div>
                        <div style="color: #059669; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-top: 2px;">AMIS ENROLLMENT SYSTEM</div>
                    </div>

                    {{-- Portal Header --}}
                    <div style="margin-bottom: 1.5rem; text-align: left;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="font-size: 0.72rem; font-weight: 800; color: #047857; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 0.2rem 0.65rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span>
                                Student & Parent Portal
                            </span>
                            <span style="font-size: 0.72rem; color: #64748b; font-weight: 600;">
                                SY 2026–2027
                            </span>
                        </div>
                        <h2 style="font-size: 1.55rem; font-weight: 900; color: #0f172a; margin: 0 0 0.35rem 0; letter-spacing: -0.01em;">
                            <span x-show="step === 'providers' || step === 'email'">Student & Parent Sign In</span>
                            <span x-show="step === 'otp'">Verify 4-Digit Code</span>
                        </h2>
                        <p style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.5;">
                            <span x-show="step === 'providers'">Sign in with Google or your registered email to start or continue your pre-enrollment.</span>
                            <span x-show="step === 'email'">Enter your registered email address to receive a secure login code.</span>
                            <span x-show="step === 'otp'">Enter the 4-digit code sent to <strong x-text="email" style="color: #047857; word-break: break-all;"></strong></span>
                        </p>
                    </div>

                    <!-- Alert Messages -->
                    <div class="auth-success-message" x-show="successMessage" x-text="successMessage" style="display:none; padding: 0.75rem 1rem; border-radius: 10px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; font-size: 0.85rem; font-weight: 600; margin-bottom: 1rem;"></div>
                    <div class="auth-error-message" x-show="errorMessage" x-text="errorMessage" style="display:none; padding: 0.75rem 1rem; border-radius: 10px; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; font-size: 0.85rem; font-weight: 600; margin-bottom: 1rem;"></div>

                    <!-- Step 1: Providers (Google GIS & Email Button) -->
                    <div x-show="step === 'providers'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <!-- Google Sign In via GIS -->
                        <script src="https://accounts.google.com/gsi/client" async defer></script>
                        <div id="g_id_onload"
                             data-client_id="{{ config('services.google.client_id') }}"
                             data-context="signin"
                             data-ux_mode="redirect"
                             data-login_uri="{{ route('auth.google.callback') }}"
                             data-auto_prompt="{{ request('prompt_google') ? 'true' : 'false' }}">
                        </div>
                        <div class="google-gis-container" style="margin-bottom: 0.75rem; width: 100%; display: flex; justify-content: center;">
                            <div class="g_id_signin"
                                 data-type="standard"
                                 data-shape="pill"
                                 data-theme="outline"
                                 data-text="signin_with"
                                 data-size="large"
                                 data-logo_alignment="left"
                                 data-width="380">
                            </div>
                        </div>

                        <!-- Divider -->
                        <div style="display: flex; align-items: center; margin: 1.15rem 0; color: #94a3b8; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase;">
                            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                            <span style="padding: 0 0.85rem; color: #64748b;">OR CONTINUE WITH EMAIL</span>
                            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                        </div>

                        <!-- Sign In with Email Button -->
                        <button type="button" @click="step = 'email'; errorMessage = ''; successMessage = '';" 
                                class="auth-provider-button email-auth-button" 
                                style="width: 100%; min-height: 48px; border-radius: 9999px; border: 1.5px solid #064e3b; background: #ffffff; padding: 0.6rem 1.25rem; display: flex; align-items: center; justify-content: center; gap: 0.65rem; font-weight: 700; color: #064e3b; cursor: pointer; transition: all 0.2s ease;"
                                onmouseover="this.style.background='#ecfdf5'; this.style.borderColor='#059669';"
                                onmouseout="this.style.background='#ffffff'; this.style.borderColor='#064e3b';">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#064e3b" stroke-width="2.2" aria-hidden="true" style="flex-shrink: 0;">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <span style="font-size: 0.92rem;">Sign in with Email (OTP)</span>
                        </button>
                    </div>

                    <!-- Step 2: Email Input Form -->
                    <div x-show="step === 'email'" style="display:none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div style="margin-bottom: 1.15rem;">
                            <a href="#" @click.prevent="step = 'providers'; errorMessage = ''; successMessage = '';" style="display: inline-flex; align-items: center; gap: 0.4rem; color: #047857; font-size: 0.85rem; font-weight: 700; text-decoration: none;">
                                &larr; Back to login options
                            </a>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label for="email" class="premium-input-label" style="display: block; font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 0.4rem;">Email address</label>
                            <div class="premium-input-wrapper" style="position: relative; display: flex; align-items: center; width: 100%;">
                                <svg class="premium-input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" aria-hidden="true" style="position: absolute; left: 1.1rem; top: 50%; transform: translateY(-50%); pointer-events: none; z-index: 10; flex-shrink: 0;">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <input type="email" id="email" x-model="email" placeholder="Enter your email address" class="premium-input-field" style="width: 100%; padding-left: 3.1rem !important; padding-right: 1.25rem !important; padding-top: 0.8rem !important; padding-bottom: 0.8rem !important; border-radius: 9999px; border: 1.5px solid #cbd5e1; font-size: 0.95rem; outline: none; background: #ffffff; color: #0f172a; box-sizing: border-box;" @keydown.enter.prevent="submitEmail()">
                            </div>
                        </div>

                        <button type="button" class="auth-button premium-continue-button" @click="submitEmail()" :disabled="loading || !email" style="width: 100%; min-height: 48px; border-radius: 9999px; background: linear-gradient(135deg, #064e3b 0%, #047857 100%); color: #ffffff; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.6rem; box-shadow: 0 4px 14px rgba(6, 78, 59, 0.25);">
                            <span x-show="!loading">Send OTP Code</span>
                            <span x-show="loading" style="display: flex; align-items: center; justify-content: center; gap: 0.6rem;">
                                <svg class="amis-spinner-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" stroke-dasharray="32" stroke-dashoffset="10" fill="none"/>
                                </svg>
                                <span>Sending OTP Code...</span>
                            </span>
                        </button>
                    </div>

                    <!-- Step 3: OTP Code Form -->
                    <div x-show="step === 'otp'" style="display:none;">
                        <div class="otp-inputs-row" style="display: flex; justify-content: center; gap: 0.75rem; margin: 1.5rem 0;">
                            <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                                   x-model="otp[0]" @input="handleOtpInput($event, 0)" @keydown="handleOtpKeydown($event, 0)" x-ref="otp0"
                                   style="border: 2px solid #cbd5e1; border-radius: 12px; width: 56px; height: 56px; font-size: 1.6rem; font-weight: 800; text-align: center; color: #064e3b; outline: none;">
                            <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                                   x-model="otp[1]" @input="handleOtpInput($event, 1)" @keydown="handleOtpKeydown($event, 1)" x-ref="otp1"
                                   style="border: 2px solid #cbd5e1; border-radius: 12px; width: 56px; height: 56px; font-size: 1.6rem; font-weight: 800; text-align: center; color: #064e3b; outline: none;">
                            <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                                   x-model="otp[2]" @input="handleOtpInput($event, 2)" @keydown="handleOtpKeydown($event, 2)" x-ref="otp2"
                                   style="border: 2px solid #cbd5e1; border-radius: 12px; width: 56px; height: 56px; font-size: 1.6rem; font-weight: 800; text-align: center; color: #064e3b; outline: none;">
                            <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                                   x-model="otp[3]" @input="handleOtpInput($event, 3)" @keydown="handleOtpKeydown($event, 3)" x-ref="otp3"
                                   style="border: 2px solid #cbd5e1; border-radius: 12px; width: 56px; height: 56px; font-size: 1.6rem; font-weight: 800; text-align: center; color: #064e3b; outline: none;">
                        </div>

                        <button type="button" class="auth-button premium-continue-button" @click="verifyOtpCode()" :disabled="loading || otp.join('').length !== 4" style="width: 100%; min-height: 48px; border-radius: 9999px; background: linear-gradient(135deg, #064e3b 0%, #047857 100%); color: #ffffff; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.6rem; box-shadow: 0 4px 14px rgba(6, 78, 59, 0.25);">
                            <span x-show="!loading">Verify Code & Open Portal</span>
                            <span x-show="loading" style="display: flex; align-items: center; justify-content: center; gap: 0.6rem;">
                                <svg class="amis-spinner-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" stroke-dasharray="32" stroke-dashoffset="10" fill="none"/>
                                </svg>
                                <span>Verifying Code...</span>
                            </span>
                        </button>

                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.25rem; font-size:0.85rem;">
                            <a href="#" @click.prevent="step = 'email'; errorMessage = ''; successMessage = '';" style="color: #047857; font-weight: 700; text-decoration: none;">&larr; Back to email</a>
                            <a href="#" @click.prevent="resendOtpCode()" style="color: #047857; font-weight: 700; text-decoration: none;" :style="loading ? 'pointer-events:none; opacity:0.5;' : ''">Resend Code</a>
                        </div>
                    </div>
                </div>

                {{-- Integrated Card Footer: Admissions Office Helpdesk & Accreditation --}}
                <div class="auth-entry-card-footer">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.65rem;">
                        <div style="display: flex; align-items: center; gap: 0.45rem;">
                            <span style="font-size: 0.95rem;">📞</span>
                            <span style="font-size: 0.8rem; font-weight: 800; color: #0f172a;">Admissions Office & Helpdesk</span>
                        </div>
                        <span style="font-size: 0.7rem; font-weight: 700; color: #059669; background: #ecfdf5; padding: 0.15rem 0.5rem; border-radius: 999px;">
                            Mon–Fri 8AM–5PM
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.76rem; margin-bottom: 0.75rem;">
                        <div style="color: #475569;">
                            <span style="color: #059669; font-weight: 700;">Tel:</span>
                            <span style="font-weight: 700; color: #0f172a;">(082) 287-AMIS</span>
                        </div>
                        <div style="color: #475569;">
                            <span style="color: #059669; font-weight: 700;">Mobile:</span>
                            <span style="font-weight: 700; color: #0f172a;">0912 345 6789</span>
                        </div>
                        <div style="grid-column: span 2; color: #475569;">
                            <span style="color: #059669; font-weight: 700;">Email:</span>
                            <span style="font-weight: 600; color: #047857;">enrollment@amis.edu.ph</span>
                        </div>
                    </div>

                    <div style="padding-top: 0.65rem; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <img src="{{ asset('images/deped_logo.png') }}" alt="DepEd" style="width: 24px; height: 24px; object-fit: contain;">
                            <span style="font-size: 0.72rem; color: #475569; font-weight: 600;">DepEd Recognized &bull; ESC Partner</span>
                        </div>
                        <span style="font-size: 0.72rem; color: #64748b; font-weight: 500;">🔒 Secure Portal</span>
                    </div>
                </div>
            </div>

            {{-- Mobile FAQ Section --}}
            <div class="mobile-faq-wrapper" style="display: none; margin-top: 1.5rem;">
                <x-enrollment-faq-modal />
            </div>

            {{-- Mobile Copyright Footer --}}
            <div style="text-align: center; color: #94a3b8; font-size: 0.75rem; font-weight: 500; margin-top: 1.25rem;">
                © 2026 Al Munawwara Islamic School. All rights reserved.
            </div>
        </div>
    </section>
</div>
</x-guest-layout>
