@props([
    'service',
    'showType' => true,
])

@php
    $locationParts = array_filter([$service->governorate?->name_ar, $service->wilayat?->name_ar]);
@endphp

<div class="flex flex-col gap-3 bg-white border border-ab-border rounded-[22px] p-4 transition hover:border-ab-teal hover:shadow-[0_16px_34px_rgba(36,59,68,0.1)]">
    <div class="flex items-start gap-4">
        <div class="relative w-[104px] h-[104px] shrink-0 rounded-[18px] overflow-hidden bg-ab-cool">
            @if ($service->has_image)
                <img src="{{ $service->image_url }}" alt="{{ $service->name_ar }}" loading="lazy"
                    class="w-full h-full object-cover" style="filter:saturate(.9) contrast(.97)">
            @else
                <div class="w-full h-full grid place-items-center">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"></path></svg>
                </div>
            @endif
        </div>
        <div class="flex flex-col gap-1.5 min-w-0">
            @if ($showType && $service->serviceType)
                <span class="self-start bg-ab-chip-bg text-ab-chip-text text-xs font-semibold px-3 py-1 rounded-full">{{ $service->serviceType->name_ar }}</span>
            @endif
            <h3 class="m-0 text-[17.5px] font-bold text-ab-navy truncate">{{ $service->name_ar }}</h3>
            @if ($service->name_en)
                <p class="m-0 text-[13px] text-ab-muted truncate" dir="ltr">{{ $service->name_en }}</p>
            @endif
        </div>
    </div>

    @if ($locationParts)
        <p class="m-0 flex items-center gap-1.5 text-sm text-ab-body">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#789A9A" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
            {{ implode(' · ', $locationParts) }}
        </p>
    @endif

    <div class="flex items-center gap-2">
        <a href="{{ $service->maps_url }}" target="_blank" rel="noopener"
            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-ab-teal text-white text-sm font-semibold no-underline">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
            الاتجاهات
        </a>
        <a href="{{ route('tourism.tourist-service', $service->slug ?: $service->id) }}"
            class="flex-1 inline-flex items-center justify-center px-4 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">
            التفاصيل
        </a>
    </div>
</div>
