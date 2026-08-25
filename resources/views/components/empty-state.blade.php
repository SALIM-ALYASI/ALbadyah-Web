@props(['title', 'body' => null])

<div class="flex flex-col items-center text-center gap-3 border-2 border-dashed border-ab-border-2 rounded-[22px] p-10 bg-ab-warm/60">
    <span class="w-14 h-14 grid place-items-center rounded-full bg-white border border-ab-border text-ab-teal">
        @isset($icon)
            {{ $icon }}
        @else
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
        @endisset
    </span>
    <h3 class="m-0 text-lg font-bold text-ab-navy">{{ $title }}</h3>
    @if ($body)
        <p class="m-0 max-w-md text-sm text-ab-body leading-relaxed">{{ $body }}</p>
    @endif
    @isset($actions)
        <div class="flex flex-wrap items-center justify-center gap-3 mt-2">
            {{ $actions }}
        </div>
    @endisset
</div>
