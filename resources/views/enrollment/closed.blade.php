<x-guest-layout :show-loader="false">
    <x-site-header />

    <div class="enrollment-closed-page" style="min-height: calc(100vh - 200px); padding: 3rem 1.25rem 4rem; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at 50% 10%, #ecfdf5 0%, #f8fafc 45%, #f1f5f9 100%);">
        <div class="enrollment-closed-card" style="max-width: 540px; width: 100%; background: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; border-top: 4px solid #059669; padding: 2.5rem 2rem; box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.08); text-align: center;">
            <div class="closed-logo-section" style="margin-bottom: 1.5rem;">
                <img src="/logo.png" alt="AMIS Logo" width="72" height="72" style="width: 72px !important; height: 72px !important; max-width: 72px !important; max-height: 72px !important; object-fit: contain !important; margin: 0 auto; display: block;" onerror="this.src='{{ asset('images/AMIS_Logo.png') }}'">
            </div>

            <div class="closed-icon" style="width: 56px; height: 56px; border-radius: 50%; background: #fef3c7; border: 1px solid #fde68a; color: #d97706; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>

            <h1 style="font-size: 1.6rem; font-weight: 900; color: #0f172a; margin-bottom: 0.5rem; letter-spacing: -0.01em;">Online Pre-Enrollment Closed</h1>
            <p class="closed-subtitle" style="font-size: 0.95rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem;">
                Thank you for your interest in <strong>Al Munawwara Islamic School</strong>! The online pre-enrollment portal is currently closed or scheduled for maintenance.
            </p>

            <div class="closed-contact" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.25rem; margin-bottom: 1.75rem; text-align: left;">
                <h3 style="font-size: 0.92rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem 0;">Admissions Inquiries:</h3>
                <div class="contact-items" style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.88rem; color: #475569;">
                    <div class="contact-item" style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <a href="mailto:inquiries@amis.edu.ph" style="color: #059669; text-decoration: none; font-weight: 600;">inquiries@amis.edu.ph</a>
                    </div>
                    <div class="contact-item" style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                        <a href="tel:+639272991833" style="color: #059669; text-decoration: none; font-weight: 600;">+63 927 299 1833</a>
                    </div>
                </div>
            </div>

            <a href="https://amis.edu.ph" class="btn-primary" style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border-radius: 999px; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; text-decoration: none; font-weight: 750; font-size: 0.92rem; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Return to AMIS Main Website
            </a>
        </div>
    </div>

    <x-site-footer />
</x-guest-layout>
