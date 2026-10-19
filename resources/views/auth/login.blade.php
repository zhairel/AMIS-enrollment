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
</style>
<x-guest-layout :show-loader="false">
<div id="login-page" class="login-grid login-page visible">
    {{-- Left Hero Panel --}}
    <section class="login-info auth-hero-panel" style="justify-content: center; padding: clamp(2.5rem, 4vw, 4rem); gap: 1rem;">
        {{-- Branding Block --}}
        <div class="login-brand-block auth-hero-brand">
            <img src="{{ asset('images/AMIS_Logo.png') }}" alt="AMIS Logo">
            <div style="min-width: 0;">
                <div class="auth-hero-arabic" lang="ar" dir="rtl">المدرسة المنورة الإسلامية</div>
                <div class="auth-hero-school">AL MUNAWWARA ISLAMIC SCHOOL</div>
            </div>
        </div>

        {{-- Main Left Headline & Subtitle --}}
        <div class="auth-hero-copy" style="max-width: 580px; margin-top: 0.25rem;">
            <h1 style="font-size: clamp(1.5rem, 2.2vw, 2.1rem); font-weight: 900; line-height: 1.25; color: #ffffff; margin: 0 0 0.4rem 0;">
                AMIS Enrollment System
            </h1>
            <p style="font-size: 0.9rem; line-height: 1.5; color: rgba(255, 255, 255, 0.88); font-weight: 500; margin: 0;">
                Watch our enrollment guide and review the frequently asked questions before starting your application.
            </p>
        </div>

        {{-- Video Guide Preview Card with Interactive Modal Player --}}
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
        }">
            <div @click="openVideo()" 
                 class="video-guide-preview-card" 
                 style="margin-top: 0.85rem; background: rgba(15, 23, 42, 0.45); border: 1px solid rgba(255, 255, 255, 0.22); border-radius: 18px; padding: 1.35rem 1.25rem; text-align: center; backdrop-filter: blur(14px); position: relative; overflow: hidden; max-width: 580px; box-shadow: 0 12px 32px rgba(0,0,0,0.22); cursor: pointer; transition: all 0.25s ease;"
                 onmouseover="this.style.background='rgba(15, 23, 42, 0.65)'; this.style.borderColor='rgba(52, 211, 153, 0.6)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(15, 23, 42, 0.45)'; this.style.borderColor='rgba(255, 255, 255, 0.22)'; this.style.transform='translateY(0)';"
                 title="Click to watch tutorial video">
                
                <div style="position: absolute; top: 12px; right: 12px; background: rgba(16, 185, 129, 0.25); border: 1px solid rgba(52, 211, 153, 0.5); color: #6ee7b7; font-size: 0.68rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.06em; display: flex; align-items: center; gap: 0.35rem;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #34d399; display: inline-block;"></span>
                    WATCH TUTORIAL
                </div>
                
                <div style="width: 50px; height: 50px; margin: 0 auto 0.75rem; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: 2px solid rgba(255, 255, 255, 0.35); color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35); transition: transform 0.2s ease;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" style="margin-left: 3px;">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                </div>

                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #ffffff; letter-spacing: 0.03em;">
                    ENROLLMENT VIDEO GUIDE
                </h3>
                <p style="margin: 0.25rem 0 0 0; font-size: 0.8rem; color: rgba(255, 255, 255, 0.85); font-weight: 500;">
                    Watch our step-by-step video guide for Academic Year 2026–2027 (35s walkthrough).
                </p>
            </div>

            <!-- Video Modal Popup -->
            <div x-show="showVideoModal" 
                 x-cloak 
                 class="confirm-overlay" 
                 style="z-index: 999999; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem;" 
                 @keydown.escape.window="closeVideo()">
                
                <div class="confirm-dialog" 
                     style="max-width: 900px; width: 100%; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); background: #0f172a; border: 1px solid rgba(255, 255, 255, 0.15); overflow: hidden; display: flex; flex-direction: column;" 
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
                                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #ffffff;">AMIS Online Enrollment Tutorial</h3>
                                <p style="margin: 2px 0 0; font-size: 0.75rem; color: #94a3b8; font-weight: 500;">Academic Year 2026–2027 Walkthrough &bull; aes.amis.edu.ph</p>
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
                        <span>Step 1: Application Form &rarr; Step 2: Admin Approval &rarr; Step 3: Instant Student ID</span>
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
        <div style="max-width: 580px; width: 100%; margin-top: 0.85rem;">
            <x-enrollment-faq-modal />
        </div>

        {{-- Left Footer Copyright --}}
        <div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid rgba(255, 255, 255, 0.15); color: rgba(255, 255, 255, 0.75); font-size: 0.8rem; font-weight: 500;">
            © 2026 Al Munawwara Islamic School. All rights reserved. &bull; AMIS Enrollment System
        </div>
    </section>

    {{-- Right Login Panel --}}
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
                {{-- Mobile Brand Header --}}
                <div class="auth-entry-mobile-brand">
                    <img src="{{ asset('images/AMIS_Logo.png') }}" alt="AMIS" class="auth-entry-mobile-logo">
                    <div class="auth-entry-mobile-arabic" lang="ar" dir="rtl">المدرسة المنورة الإسلامية</div>
                    <div class="auth-entry-mobile-school">AL MUNAWWARA ISLAMIC SCHOOL</div>
                    <div style="color: #059669; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-top: 2px;">AMIS ENROLLMENT SYSTEM</div>
                </div>

                <div class="auth-entry-heading">
                    <span class="auth-entry-kicker">AMIS ENROLLMENT SYSTEM</span>
                    <h2 x-show="step === 'email'">Log in or sign up</h2>
                    <h2 x-show="step === 'otp'">Verify email</h2>
                    <p x-show="step === 'email'">Choose an option below to open your pre-enrollment dashboard.</p>
                    <p x-show="step === 'otp'">We sent a 4-digit verification code to <strong x-text="email" style="color:#0f172a; word-break:break-all;"></strong>. Enter the code to continue.</p>
                </div>

                <!-- Messages -->
                <div class="auth-success-message" x-show="successMessage" x-text="successMessage" style="display:none;"></div>
                <div class="auth-error-message" x-show="errorMessage" x-text="errorMessage" style="display:none;"></div>

                <!-- Step 1: Login Providers Options -->
                <div x-show="step === 'providers'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <!-- Google Sign In (Google Identity Services to bypass ModSecurity) -->
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
                             data-width="360">
                        </div>
                    </div>

                    <!-- Active Email Sign In Button (Transitions to Step 2 Card) -->
                    <button type="button" @click="step = 'email'; errorMessage = ''; successMessage = '';" class="auth-provider-button email-auth-button" style="margin-bottom: 0.75rem; width: 100%; min-height: 50px; border-radius: 9999px; border: 1.5px solid #064e3b; background: #ffffff; padding: 0.6rem 1.25rem; display: flex; align-items: center; justify-content: center; gap: 0.65rem; font-weight: 600; color: #064e3b; cursor: pointer; transition: all 0.2s ease;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#064e3b" stroke-width="2" aria-hidden="true" style="flex-shrink: 0;">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <span style="font-size: 0.9rem; font-weight: 700;">Sign in with Email</span>
                    </button>
                </div>

                <!-- Step 2: Dedicated Email Card View -->
                <div x-show="step === 'email'" style="display:none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div style="margin-bottom: 1.25rem;">
                        <a href="#" @click.prevent="step = 'providers'; errorMessage = ''; successMessage = '';" style="display: inline-flex; align-items: center; gap: 0.4rem; color: #064e3b; font-size: 0.85rem; font-weight: 700; text-decoration: none;">
                            &larr; Back to login options
                        </a>
                    </div>

                    <h2 style="font-size: 1.35rem; font-weight: 800; color: #064e3b; margin-bottom: 0.35rem; font-family: 'Outfit', sans-serif;">Sign in with Email</h2>
                    <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">Enter your email address below to receive a 4-digit verification code.</p>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="email" class="premium-input-label" style="display: block; font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 0.4rem;">Email address</label>
                        <div class="premium-input-wrapper" style="position: relative; display: flex; align-items: center; width: 100%;">
                            <svg class="premium-input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" aria-hidden="true" style="position: absolute; left: 1.1rem; top: 50%; transform: translateY(-50%); pointer-events: none; z-index: 10; flex-shrink: 0;">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input type="email" id="email" x-model="email" placeholder="Enter your email address" class="premium-input-field" style="width: 100%; padding-left: 3.1rem !important; padding-right: 1.25rem !important; padding-top: 0.8rem !important; padding-bottom: 0.8rem !important; border-radius: 9999px; border: 1.5px solid #cbd5e1; font-size: 0.95rem; outline: none; background: #ffffff; color: #0f172a; box-sizing: border-box;" @keydown.enter.prevent="submitEmail()">
                        </div>
                    </div>

                    <button type="button" class="auth-button premium-continue-button" @click="submitEmail()" :disabled="loading || !email" style="width: 100%; min-height: 48px; border-radius: 9999px; background: #064e3b; color: #ffffff; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.6rem;">
                        <span x-show="!loading">Send OTP Code</span>
                        <span x-show="loading" style="display: flex; align-items: center; justify-content: center; gap: 0.6rem;">
                            <svg class="amis-spinner-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" stroke-dasharray="32" stroke-dashoffset="10" fill="none"/>
                            </svg>
                            <span>Sending OTP Code...</span>
                        </span>
                    </button>
                </div>

                
                <!-- Step 2: OTP View -->
                <div x-show="step === 'otp'" style="display:none;">
                    <div class="otp-inputs-row">
                        <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                               x-model="otp[0]" @input="handleOtpInput($event, 0)" @keydown="handleOtpKeydown($event, 0)" x-ref="otp0">
                        <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                               x-model="otp[1]" @input="handleOtpInput($event, 1)" @keydown="handleOtpKeydown($event, 1)" x-ref="otp1">
                        <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                               x-model="otp[2]" @input="handleOtpInput($event, 2)" @keydown="handleOtpKeydown($event, 2)" x-ref="otp2">
                        <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                               x-model="otp[3]" @input="handleOtpInput($event, 3)" @keydown="handleOtpKeydown($event, 3)" x-ref="otp3">
                    </div>

                    <button type="button" class="auth-button premium-continue-button" @click="verifyOtpCode()" :disabled="loading || otp.join('').length !== 4" style="width: 100%; min-height: 48px; border-radius: 9999px; background: #064e3b; color: #ffffff; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.6rem; margin-top: 1.5rem;">
                        <span x-show="!loading">Verify Code & Sign In</span>
                        <span x-show="loading" style="display: flex; align-items: center; justify-content: center; gap: 0.6rem;">
                            <svg class="amis-spinner-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" stroke-dasharray="32" stroke-dashoffset="10" fill="none"/>
                            </svg>
                            <span>Verifying Code...</span>
                        </span>
                    </button>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.5rem; font-size:0.85rem;">
                        <a href="#" @click.prevent="step = 'email'; errorMessage = ''; successMessage = '';" class="otp-back-link">&larr; Back to email</a>
                        <a href="#" @click.prevent="resendOtpCode()" class="otp-resend-link" :style="loading ? 'pointer-events:none; opacity:0.5;' : ''">Resend Code</a>
                    </div>
                </div>

                <p class="auth-entry-note" style="margin-top: 1.5rem; text-align: center; color: #64748b; font-size: 0.8rem;">
                    Sign in options are protected by AMIS security policies.
                </p>

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
