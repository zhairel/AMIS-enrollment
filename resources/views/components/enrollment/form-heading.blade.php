<div class="enrollment-form-header enrollment-form-header-with-action" style="display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 1.35rem; margin-bottom: 1.85rem; gap: 1rem;">
    <div>
        <div style="display: inline-flex; align-items: center; gap: 0.45rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; padding: 0.25rem 0.75rem; border-radius: 999px; margin-bottom: 0.45rem;">
            <span>Step <span x-text="step"></span> of <span x-text="totalSteps"></span></span>
        </div>
        <h2 x-text="stepTitles[step - 1]" style="font-size: 1.65rem; font-weight: 900; color: #0f172a; margin: 0; line-height: 1.2; letter-spacing: -0.01em;"></h2>
    </div>
    <a href="{{ route('enrollment.dashboard') }}" title="Exit to Dashboard" 
       style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 50%; background: #f8fafc; border: 1.5px solid #e2e8f0; color: #64748b; text-decoration: none; transition: all 0.2s ease; flex-shrink: 0;"
       onmouseover="this.style.background='#fef2f2'; this.style.borderColor='#fecaca'; this.style.color='#b91c1c'; this.style.transform='rotate(90deg)';"
       onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.color='#64748b'; this.style.transform='none';">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </a>
</div>
