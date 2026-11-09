@props([
    'shift',
    'phTime',
    'start',
    'end',
    'capacity' => 40,
    'available' => 0,
])

<button
    type="button"
    @click="toggleLearningShift('{{ $shift }}')"
    class="shift-card"
    :class="{ 'is-selected': form.learning_mode_shift === '{{ $shift }}' }"
>
    <div class="shift-card-topline">
        <div class="shift-card-title">{{ $shift }}</div>
        <span class="choice-card-radio" aria-hidden="true">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </span>
    </div>

    <div class="shift-card-primary-time">
        <img src="https://flagcdn.com/16x12/ph.png" width="16" height="12" alt="PH" class="flag-icon">
        {{ $phTime }} <span>(PHT / UTC+8)</span>
    </div>
</button>
