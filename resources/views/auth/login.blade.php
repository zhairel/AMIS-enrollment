<x-guest-layout :show-loader="false">
    <!-- AMIS Site Header (Unified with amis.edu.ph) -->
    <x-site-header />

    <!-- Pre-Enrollment Gateway Section (Matching amis.edu.ph UI/UX) -->
    <main class="aes-gateway-main" style="position: relative; background: radial-gradient(circle at 50% 10%, #ecfdf5 0%, #f8fafc 45%, #f1f5f9 100%); min-height: calc(100vh - 140px); padding: clamp(2rem, 5vw, 4rem) 1.25rem 4.5rem; overflow: hidden;">
        
        <!-- Subtle Architectural Motif Watermark -->
        <div aria-hidden="true" style="position: absolute; inset: 0; pointer-events: none; display: flex; align-items: center; justify-content: center; overflow: hidden; opacity: 0.035;">
            <svg width="720" height="820" viewBox="0 0 680 780" fill="none" stroke="#064e3b" stroke-width="1.6">
                <path d="M 140 780 V 380 C 140 220 230 100 340 60 C 450 100 540 220 540 380 V 780"/>
                <path d="M 180 780 V 390 C 180 250 250 140 340 105 C 430 140 500 250 500 390 V 780"/>
                <circle cx="340" cy="200" r="40"/>
                <circle cx="340" cy="200" r="20"/>
            </svg>
        </div>

        <div class="aes-gateway-container" style="max-width: 1240px; margin: 0 auto; position: relative; z-index: 10;">
            
            <!-- Gateway Grid Showcase -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 460px), 1fr)); gap: clamp(2rem, 4vw, 3.5rem); align-items: start;">
                
                <!-- Left Column: Institutional Welcome, Video Guide & Process -->
                <div class="aes-left-showcase" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    
                    <!-- Kicker Badge & Headline -->
                    <div>
                        <div style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; border-radius: 9999px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1rem; box-shadow: 0 2px 6px rgba(5, 150, 105, 0.08);">
                            <span class="accreditation-dot"></span>
                            <span>Admissions S.Y. 2026–2027</span>
                        </div>
                        
                        <h1 style="font-size: clamp(2rem, 3.2vw, 2.75rem); font-weight: 900; line-height: 1.15; color: #064e3b; margin: 0 0 0.85rem 0; letter-spacing: -0.02em;">
                            Begin Your Journey with Al Munawwara
                        </h1>
                        
                        <p style="font-size: 1.02rem; line-height: 1.65; color: #475569; font-weight: 500; margin: 0; max-width: 560px;">
                            Welcome to the official <strong>AMIS Enrollment System (AES)</strong>. Experience a modern, paperless pre-enrollment process for Kindergarten, Elementary, Junior High School, and Senior High School.
                        </p>
                    </div>

                    <!-- Video Tutorial Guide Card with Interactive Modal Player -->
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
                    }" style="width: 100%;">
                        
                        <div @click="openVideo()" 
                             class="video-guide-preview-card" 
                             style="background: linear-gradient(135deg, #064e3b 0%, #047857 55%, #0f172a 100%); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px; padding: 1.5rem 1.6rem; color: white; position: relative; overflow: hidden; box-shadow: 0 16px 36px -10px rgba(6, 78, 59, 0.35); cursor: pointer; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);"
                             onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 22px 42px -10px rgba(6, 78, 59, 0.45)';"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 16px 36px -10px rgba(6, 78, 59, 0.35)';"
                             title="Click to watch tutorial video">
                            
                            <!-- Ambient Glow Circle -->
                            <div style="position: absolute; right: -20px; bottom: -20px; width: 140px; height: 140px; border-radius: 50%; background: radial-gradient(circle, rgba(52, 211, 153, 0.25) 0%, transparent 70%); pointer-events: none;"></div>

                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #ffffff; color: #059669; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2); flex-shrink: 0;">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" style="margin-left: 3px;">
                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                        </svg>
                                    </div>
                                    <div>
                                        <span style="font-size: 0.72rem; font-weight: 800; color: #a7f3d0; text-transform: uppercase; letter-spacing: 0.08em; display: block;">Video Walkthrough</span>
                                        <h3 style="margin: 2px 0 0; font-size: 1.12rem; font-weight: 800; color: #ffffff; letter-spacing: 0.01em;">Online Enrollment Guide</h3>
                                    </div>
                                </div>
                                <span style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(52, 211, 153, 0.4); color: #6ee7b7; font-size: 0.68rem; font-weight: 800; padding: 0.25rem 0.75rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.06em;">
                                    35s Video
                                </span>
                            </div>

                            <p style="margin: 0; font-size: 0.88rem; color: rgba(255, 255, 255, 0.9); line-height: 1.5; font-weight: 450;">
                                Watch our fast step-by-step tutorial on how to submit applicant details, upload requirements, and receive your official student ID.
                            </p>
                        </div>

                        <!-- Video Modal Popup -->
                        <div x-show="showVideoModal" 
                             x-cloak 
                             class="confirm-overlay" 
                             style="z-index: 999999; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(10px); position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1.25rem;" 
                             @keydown.escape.window="closeVideo()">
                            
                            <div class="confirm-dialog" 
                                 style="max-width: 900px; width: 100%; border-radius: 20px; box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.6); background: #0f172a; border: 1px solid rgba(255, 255, 255, 0.15); overflow: hidden; display: flex; flex-direction: column;" 
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
                                <div style="padding: 0.85rem 1.25rem; background: rgba(30, 41, 59, 0.6); display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; color: #94a3b8; flex-wrap: wrap; gap: 0.5rem;">
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

                    <!-- 3 Simple Enrollment Steps Bar -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 1.4rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                        <div style="font-size: 0.75rem; font-weight: 800; color: #059669; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.85rem;">
                            How It Works &bull; 3 Simple Steps
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #047857; font-weight: 800; font-size: 0.82rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
                                    1
                                </div>
                                <div>
                                    <div style="font-size: 0.92rem; font-weight: 750; color: #0f172a; line-height: 1.3;">Sign In to Pre-Enrollment Portal</div>
                                    <div style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">Use your Google account or email OTP for instant, secure authentication.</div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #047857; font-weight: 800; font-size: 0.82rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
                                    2
                                </div>
                                <div>
                                    <div style="font-size: 0.92rem; font-weight: 750; color: #0f172a; line-height: 1.3;">Fill Student Details &amp; Upload Requirements</div>
                                    <div style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">Submit basic student info, grade level, PSA birth certificate, and 2x2 ID photo.</div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #047857; font-weight: 800; font-size: 0.82rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
                                    3
                                </div>
                                <div>
                                    <div style="font-size: 0.92rem; font-weight: 750; color: #0f172a; line-height: 1.3;">Get Instant Student ID &amp; Track Status</div>
                                    <div style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">Track review progress, download the pre-enrollment form, and proceed to payment.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrollment FAQs Component -->
                    <div>
                        <x-enrollment-faq-modal theme="light" />
                    </div>

                </div>

                <!-- Right Column: Premium Authentication Card -->
                <div class="aes-right-showcase" style="display: flex; justify-content: center;">
                    <div class="auth-entry-card" 
                         style="width: 100%; max-width: 460px; padding: clamp(2rem, 3.5vw, 2.75rem) clamp(1.5rem, 3vw, 2.25rem); border-radius: 24px; border: 1px solid #e2e8f0; border-top: 4px solid #059669; background: #ffffff; box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(15, 23, 42, 0.02);" 
                         x-data="{ 
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
                                            if (this.$refs.otp0) this.$refs.otp0.focus();
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
                                    const next = this.$refs['otp' + (index + 1)];
                                    if (next) next.focus();
                                }

                                if (this.otp.join('').length === 4) {
                                    this.verifyOtpCode();
                                }
                            },
                            handleOtpKeydown(event, index) {
                                if (event.key === 'Backspace') {
                                    if (!this.otp[index] && index > 0) {
                                        this.otp[index - 1] = '';
                                        const prev = this.$refs['otp' + (index - 1)];
                                        if (prev) prev.focus();
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
                                            if (this.$refs.otp0) this.$refs.otp0.focus();
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
                                            if (this.$refs.otp0) this.$refs.otp0.focus();
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
                        
                        <!-- Card Brand Header -->
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <img src="/logo.png" alt="AMIS Seal" width="64" height="64" style="width: 64px !important; height: 64px !important; max-width: 64px !important; max-height: 64px !important; object-fit: contain !important; margin: 0 auto 0.65rem; filter: drop-shadow(0 3px 6px rgba(5, 150, 105, 0.2)); display: block;" onerror="this.src='{{ asset('images/AMIS_Logo.png') }}'">
                            
                            <div style="font-family: 'Amiri', 'Traditional Arabic', serif; font-size: 1.25rem; font-weight: 700; color: #047857; margin-bottom: 2px; direction: rtl; unicode-bidi: isolate;">
                                المدرسة المنورة الإسلامية
                            </div>
                            <div style="font-family: 'Outfit', 'Inter', sans-serif; font-size: 0.95rem; font-weight: 900; color: #0f172a; letter-spacing: 0.5px;">
                                AL MUNAWWARA ISLAMIC SCHOOL
                            </div>
                        </div>

                        <!-- Pill Kicker -->
                        <div style="text-align: center; margin-bottom: 1.35rem;">
                            <span style="display: inline-flex; align-items: center; gap: 0.4rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; font-size: 0.72rem; font-weight: 800; padding: 0.32rem 0.85rem; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.06em;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669; display: inline-block;"></span>
                                AMIS ENROLLMENT SYSTEM
                            </span>
                            <h2 x-show="step === 'providers'" style="font-size: 1.45rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem; letter-spacing: -0.01em;">Sign in to Enroll</h2>
                            <h2 x-show="step === 'email'" style="font-size: 1.45rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem; letter-spacing: -0.01em;">Log in with Email</h2>
                            <h2 x-show="step === 'otp'" style="font-size: 1.45rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem; letter-spacing: -0.01em;">Verify 4-Digit Code</h2>
                            <p x-show="step === 'providers' || step === 'email'" style="font-size: 0.86rem; color: #64748b; margin: 0; line-height: 1.5;">Choose an option below to open your pre-enrollment dashboard.</p>
                            <p x-show="step === 'otp'" style="font-size: 0.86rem; color: #64748b; margin: 0; line-height: 1.5;">We sent a 4-digit verification code to <strong x-text="email" style="color:#0f172a; word-break:break-all;"></strong>. Enter the code to continue.</p>
                        </div>

                        <!-- Feedback Messages -->
                        <div class="auth-success-message" x-show="successMessage" x-text="successMessage" style="display:none; margin-bottom: 1rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 0.65rem 1rem; border-radius: 10px; font-size: 0.85rem; font-weight: 600; text-align: center;"></div>
                        <div class="auth-error-message" x-show="errorMessage" x-text="errorMessage" style="display:none; margin-bottom: 1rem; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 0.65rem 1rem; border-radius: 10px; font-size: 0.85rem; font-weight: 600; text-align: center;"></div>

                        <!-- Step 1: Providers Options (Google GIS + Email) -->
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

                            <!-- Divider -->
                            <div style="display: flex; align-items: center; margin: 1.15rem 0; color: #94a3b8; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                                <span style="flex: 1; height: 1px; background: #e2e8f0;"></span>
                                <span style="padding: 0 0.75rem;">or continue with email</span>
                                <span style="flex: 1; height: 1px; background: #e2e8f0;"></span>
                            </div>

                            <!-- Active Email Sign In Button -->
                            <button type="button" @click="step = 'email'; errorMessage = ''; successMessage = '';" class="auth-provider-button email-auth-button" style="width: 100%; min-height: 48px; border-radius: 9999px; border: 1.5px solid #064e3b; background: #ffffff; padding: 0.6rem 1.25rem; display: flex; align-items: center; justify-content: center; gap: 0.65rem; font-weight: 750; color: #064e3b; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);" onmouseover="this.style.background='#f0fdf4'; this.style.borderColor='#047857'; this.style.transform='translateY(-1px)';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#064e3b'; this.style.transform='translateY(0)';">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#064e3b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <span style="font-size: 0.92rem;">Sign in with Email OTP</span>
                            </button>
                        </div>

                        <!-- Step 2: Dedicated Email Card View -->
                        <div x-show="step === 'email'" style="display:none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                            <div style="margin-bottom: 1.15rem;">
                                <a href="#" @click.prevent="step = 'providers'; errorMessage = ''; successMessage = '';" style="display: inline-flex; align-items: center; gap: 0.4rem; color: #064e3b; font-size: 0.85rem; font-weight: 700; text-decoration: none;">
                                    &larr; Back to login options
                                </a>
                            </div>

                            <div class="form-group" style="margin-bottom: 1.15rem;">
                                <label for="email" class="premium-input-label" style="display: block; font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 0.45rem;">Email address</label>
                                <div class="premium-input-wrapper" style="position: relative; display: flex; align-items: center; width: 100%;">
                                    <svg class="premium-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" aria-hidden="true" style="position: absolute; left: 1.15rem; top: 50%; transform: translateY(-50%); pointer-events: none; z-index: 10; flex-shrink: 0;">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                    <input type="email" id="email" x-model="email" placeholder="Enter your email address" class="premium-input-field" style="width: 100%; min-height: 48px; padding-left: 3.1rem !important; padding-right: 1.25rem !important; padding-top: 0.85rem !important; padding-bottom: 0.85rem !important; border-radius: 9999px; border: 1.5px solid #cbd5e1; font-size: 0.95rem; outline: none; background: #ffffff; color: #0f172a; box-sizing: border-box;" @keydown.enter.prevent="submitEmail()">
                                </div>
                            </div>

                            <button type="button" class="auth-button premium-continue-button" @click="submitEmail()" :disabled="loading || !email" style="width: 100%; min-height: 48px; border-radius: 9999px; background: #064e3b; color: #ffffff; font-weight: 750; font-size: 0.95rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.6rem; transition: all 0.2s ease;" onmouseover="this.style.background='#047857'; this.style.transform='translateY(-1px)';" onmouseout="this.style.background='#064e3b'; this.style.transform='translateY(0)';">
                                <span x-show="!loading">Send 4-Digit Code</span>
                                <span x-show="loading" style="display: flex; align-items: center; justify-content: center; gap: 0.6rem;">
                                    <svg class="amis-spinner-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" stroke-dasharray="32" stroke-dashoffset="10" fill="none"/>
                                    </svg>
                                    <span>Sending OTP Code...</span>
                                </span>
                            </button>
                        </div>

                        <!-- Step 3: OTP Code Inputs View -->
                        <div x-show="step === 'otp'" style="display:none;">
                            <div class="otp-inputs-row" style="display: flex; gap: 0.65rem; justify-content: center; margin: 1.25rem 0;">
                                <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                                       x-model="otp[0]" @input="handleOtpInput($event, 0)" @keydown="handleOtpKeydown($event, 0)" x-ref="otp0"
                                       style="width: 54px; height: 58px; font-size: 1.4rem; font-weight: 800; text-align: center; border: 2px solid #cbd5e1; border-radius: 14px; outline: none; background: #f8fafc; color: #0f172a;">
                                <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                                       x-model="otp[1]" @input="handleOtpInput($event, 1)" @keydown="handleOtpKeydown($event, 1)" x-ref="otp1"
                                       style="width: 54px; height: 58px; font-size: 1.4rem; font-weight: 800; text-align: center; border: 2px solid #cbd5e1; border-radius: 14px; outline: none; background: #f8fafc; color: #0f172a;">
                                <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                                       x-model="otp[2]" @input="handleOtpInput($event, 2)" @keydown="handleOtpKeydown($event, 2)" x-ref="otp2"
                                       style="width: 54px; height: 58px; font-size: 1.4rem; font-weight: 800; text-align: center; border: 2px solid #cbd5e1; border-radius: 14px; outline: none; background: #f8fafc; color: #0f172a;">
                                <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" class="otp-input-box" 
                                       x-model="otp[3]" @input="handleOtpInput($event, 3)" @keydown="handleOtpKeydown($event, 3)" x-ref="otp3"
                                       style="width: 54px; height: 58px; font-size: 1.4rem; font-weight: 800; text-align: center; border: 2px solid #cbd5e1; border-radius: 14px; outline: none; background: #f8fafc; color: #0f172a;">
                            </div>

                            <button type="button" class="auth-button premium-continue-button" @click="verifyOtpCode()" :disabled="loading || otp.join('').length !== 4" style="width: 100%; min-height: 48px; border-radius: 9999px; background: #064e3b; color: #ffffff; font-weight: 750; font-size: 0.95rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.6rem; margin-top: 1.25rem; transition: all 0.2s ease;" onmouseover="this.style.background='#047857'; this.style.transform='translateY(-1px)';" onmouseout="this.style.background='#064e3b'; this.style.transform='translateY(0)';">
                                <span x-show="!loading">Verify Code &amp; Sign In</span>
                                <span x-show="loading" style="display: flex; align-items: center; justify-content: center; gap: 0.6rem;">
                                    <svg class="amis-spinner-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" stroke-dasharray="32" stroke-dashoffset="10" fill="none"/>
                                    </svg>
                                    <span>Verifying Code...</span>
                                </span>
                            </button>

                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.25rem; font-size:0.85rem;">
                                <a href="#" @click.prevent="step = 'email'; errorMessage = ''; successMessage = '';" class="otp-back-link" style="color: #64748b; font-weight: 600; text-decoration: none;">&larr; Back to email</a>
                                <a href="#" @click.prevent="resendOtpCode()" class="otp-resend-link" style="color: #047857; font-weight: 700; text-decoration: none;" :style="loading ? 'pointer-events:none; opacity:0.5;' : ''">Resend Code</a>
                            </div>
                        </div>

                        <!-- Security Policy Notice -->
                        <div style="margin-top: 1.5rem; padding: 0.75rem 1rem; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: #64748b; font-size: 0.78rem; font-weight: 500;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            <span>Protected by AMIS Institutional Security &amp; Privacy Policies</span>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- AMIS Institutional Footer (Unified with amis.edu.ph) -->
    <x-site-footer />
</x-guest-layout>
