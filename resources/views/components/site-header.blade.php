<!-- AMIS Site Header (Unified with amis.edu.ph) -->
<header class="site-header">
    <!-- 1. Sleek Utility Topbar -->
    <div class="top-utility-bar">
        <div class="top-utility-content">
            <div class="utility-left">
                <a href="tel:+639272991833" class="utility-item">
                    <svg class="utility-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span>+63 927 299 1833</span>
                </a>
                <span class="utility-divider"></span>
                <a href="mailto:inquiries@amis.edu.ph" class="utility-item">
                    <svg class="utility-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>inquiries@amis.edu.ph</span>
                </a>
                <span class="utility-divider"></span>
                <div class="utility-social">
                    <a href="https://www.facebook.com/almunawwaraislamicschool" target="_blank" rel="noopener noreferrer" class="utility-social-link" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="https://www.youtube.com/@amistv3214" target="_blank" rel="noopener noreferrer" class="utility-social-link" aria-label="YouTube">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="utility-right">
                <span class="utility-accreditation">
                    <span class="accreditation-dot"></span>
                    DepEd Recognized &amp; ESC Participating School
                </span>
            </div>
        </div>
    </div>

    <!-- 2. Main Navigation Bar (Unified & Sticky) -->
    <div class="main-navbar" id="mainNavbar">
        <div class="navbar-container">
            <!-- Brand / Logo Area -->
            <a href="https://amis.edu.ph" class="brand-link" title="Al Munawwara Islamic School Home">
                <img src="/logo.png" alt="AMIS Official Seal" class="brand-logo" width="68" height="68" style="width: 68px !important; height: 68px !important; max-width: 68px !important; max-height: 68px !important; object-fit: contain !important; flex-shrink: 0 !important; display: block;" onerror="this.src='{{ asset('images/AMIS_Logo.png') }}'">
                <div class="brand-text-stack">
                    <span class="brand-arabic">المدرسة المنورة الإسلامية</span>
                    <span class="brand-title">AL MUNAWWARA ISLAMIC SCHOOL</span>
                    <span class="brand-tagline">Enabling Our Students to Learn in Fid Dunya Wal Akhira</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="desktop-nav" aria-label="Main Navigation">
                <ul class="nav-links-list">
                    <li>
                        <a href="https://amis.edu.ph/contact" class="nav-link">
                            Contact
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Nav Actions & Mobile Toggle -->
            <div class="nav-actions">
                <span class="portal-status-pill">
                    <span class="accreditation-dot"></span>
                    <span>Pre-Enrollment S.Y. 2026–2027</span>
                </span>

                <!-- Mobile Hamburger Button -->
                <button class="mobile-toggle-btn" id="mobileMenuBtn" aria-label="Open Navigation Menu" aria-expanded="false">
                    <span class="hamburger-bar"></span>
                    <span class="hamburger-bar"></span>
                    <span class="hamburger-bar"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. Mobile Slide-Over Drawer Navigation -->
    <div class="mobile-drawer-backdrop" id="mobileDrawerBackdrop"></div>
    <aside class="mobile-drawer" id="mobileDrawer" aria-label="Mobile Navigation Drawer">
        <div class="drawer-header">
            <div class="drawer-brand">
                <img src="/logo.png" alt="AMIS Logo" class="drawer-logo" width="40" height="40" style="width: 40px !important; height: 40px !important; max-width: 40px !important; max-height: 40px !important; object-fit: contain !important; flex-shrink: 0 !important; display: block;" onerror="this.src='{{ asset('images/AMIS_Logo.png') }}'">
                <div>
                    <span class="drawer-title">AL MUNAWWARA</span>
                    <span class="drawer-subtitle">ISLAMIC SCHOOL</span>
                </div>
            </div>
            <button class="drawer-close-btn" id="mobileDrawerClose" aria-label="Close Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="drawer-body">
            <nav class="drawer-nav">
                <a href="https://amis.edu.ph/contact" class="drawer-link">
                    <svg class="drawer-link-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Contact
                </a>
            </nav>
        </div>

        <div class="drawer-footer">
            <div class="drawer-contact-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <a href="tel:+639272991833">+63 927 299 1833</a>
            </div>
            <div class="drawer-contact-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <a href="mailto:inquiries@amis.edu.ph">inquiries@amis.edu.ph</a>
            </div>
            <div class="drawer-social-row">
                <a href="https://www.facebook.com/almunawwaraislamicschool" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://www.youtube.com/@amistv3214" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
            </div>
        </div>
    </aside>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const mobileDrawer = document.getElementById('mobileDrawer');
        const backdrop = document.getElementById('mobileDrawerBackdrop');
        const closeBtn = document.getElementById('mobileDrawerClose');

        function openDrawer() {
            if (mobileDrawer) mobileDrawer.classList.add('open');
            if (backdrop) backdrop.classList.add('active');
            if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            if (mobileDrawer) mobileDrawer.classList.remove('open');
            if (backdrop) backdrop.classList.remove('active');
            if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        if (mobileBtn) mobileBtn.addEventListener('click', openDrawer);
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        if (backdrop) backdrop.addEventListener('click', closeDrawer);

        // Accordion handling in mobile drawer
        document.querySelectorAll('.drawer-accordion-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.closest('.drawer-accordion');
                const isOpen = parent.classList.contains('open');
                
                document.querySelectorAll('.drawer-accordion').forEach(acc => {
                    if (acc !== parent) {
                        acc.classList.remove('open');
                        const b = acc.querySelector('.drawer-accordion-btn');
                        if (b) b.setAttribute('aria-expanded', 'false');
                    }
                });

                if (isOpen) {
                    parent.classList.remove('open');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    parent.classList.add('open');
                    this.setAttribute('aria-expanded', 'true');
                }
            });
        });

        // Scroll listener for sticky navbar shadow
        const mainNav = document.getElementById('mainNavbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 20) {
                if (mainNav) mainNav.classList.add('navbar-scrolled');
            } else {
                if (mainNav) mainNav.classList.remove('navbar-scrolled');
            }
        }, { passive: true });
    });
</script>
