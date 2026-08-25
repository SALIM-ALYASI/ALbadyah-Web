@props(['title', 'body' => null])

<div class="flex flex-col items-center text-center gap-3 border-2 border-dashed border-ab-border-2 rounded-[22px] p-10 bg-white">
    <span class="w-14 h-14 grid place-items-center rounded-full bg-ab-cool text-ab-teal">
        @isset($icon)
            {!! $icon !!}
        @else
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><ellipse cx="12" cy="5" rx="8" ry="3"></ellipse><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"></path></svg>
        @endisset
    </span>
    <h3 class="m-0 text-lg font-bold text-ab-navy">{{ $title }}</h3>
    @if ($body)
        <p class="m-0 max-w-md text-sm text-ab-body leading-relaxed">{{ $body }}</p>
    @endif
    @isset($actions)
        <div class="flex flex-wrap items-center justify-center gap-3 mt-2">{{ $actions }}</div>
    @endisset
</div>
