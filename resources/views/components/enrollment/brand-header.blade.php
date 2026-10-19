<div class="enrollment-header" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 55%, #047857 100%); padding: 1.25rem clamp(1rem, 3vw, 2.5rem); color: white; position: relative; overflow: hidden; box-shadow: 0 4px 20px -2px rgba(6, 78, 59, 0.25);">
    {{-- Soft ambient background glow circles --}}
    <div style="position: absolute; right: -30px; top: -30px; width: 180px; height: 180px; border-radius: 50%; background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, transparent 70%); pointer-events: none;"></div>
    <div style="position: absolute; right: 180px; bottom: -50px; width: 160px; height: 160px; border-radius: 50%; background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%); pointer-events: none;"></div>

    <div class="enrollment-header-content" style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; position: relative; z-index: 1; flex-wrap: wrap;">
        <div class="enrollment-header-left" style="display: flex; align-items: center; gap: 1.15rem;">
            <div style="position: relative;">
                <img src="{{ asset('images/AMIS_Logo.png') }}" alt="AMIS Logo" style="width: 56px; height: 56px; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3));">
            </div>
            <div class="enrollment-header-text" style="text-align: left;">
                <div class="arabic brand-arabic" lang="ar" dir="rtl" style="font-family: 'Amiri', 'Noto Naskh Arabic', 'Traditional Arabic', serif !important; font-size: 1.15rem; font-weight: 700; color: #fde68a; letter-spacing: 0 !important; word-spacing: normal !important; text-transform: none !important; direction: rtl; unicode-bidi: isolate; line-height: 1.2; margin-bottom: 2px;">
                    المدرسة المنورة الإسلامية
                </div>
                <div class="school" style="font-size: 1.25rem; font-weight: 900; color: #ffffff; letter-spacing: -0.01em; line-height: 1.2;">
                    Al Munawwara Islamic School
                </div>
                <div style="font-size: 0.74rem; font-weight: 600; color: rgba(255, 255, 255, 0.85); margin-top: 2px; letter-spacing: 0.02em;">
                    DepEd Recognized &bull; Integrated Islamic & Arabic Studies
                </div>
            </div>
        </div>

        <div class="enrollment-header-right" style="display: flex; flex-direction: column; align-items: flex-end; text-align: right; gap: 0.35rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; justify-content: flex-end;">
                <span style="background: rgba(254, 243, 199, 0.18); border: 1px solid rgba(253, 230, 138, 0.4); color: #fde68a; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; padding: 0.2rem 0.7rem; border-radius: 999px;">
                    School Year 2026–2027
                </span>
                <a href="{{ route('enrollment.dashboard') }}" 
                   style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.85rem; background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 999px; color: #ffffff; font-size: 0.75rem; font-weight: 700; text-decoration: none; backdrop-filter: blur(6px); transition: all 0.2s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.25)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.12)';">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Dashboard</span>
                </a>
            </div>
            <h1 style="font-size: 1.45rem; font-weight: 900; margin: 0; color: #ffffff; line-height: 1.2; letter-spacing: -0.01em;">
                Online Pre-Enrollment
            </h1>
        </div>
    </div>
</div>
