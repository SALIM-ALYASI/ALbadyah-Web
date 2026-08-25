@props(['governorate'])

@php
    $sitesCount = $governorate->tourist_sites_count ?? 0;
    $servicesCount = $governorate->tourist_services_count ?? 0;
    $wilayatsCount = $governorate->wilayats_count ?? 0;
    $hasContent = $sitesCount > 0 || $servicesCount > 0;
@endphp

<a href="{{ route('tourism.governorate', $governorate->slug ?: $governorate->id) }}"
    class="flex flex-col bg-white border border-ab-border rounded-[22px] overflow-hidden no-underline transition hover:border-ab-teal hover:shadow-[0_16px_34px_rgba(36,59,68,0.1)]">
    <div class="relative aspect-[4/3] bg-ab-cool">
        @if ($governorate->has_image)
            <img src="{{ $governorate->image_url }}" alt="{{ $governorate->name_ar }}" loading="lazy"
                class="w-full h-full object-cover" style="filter:saturate(.9) contrast(.97)">
        @else
            <div class="w-full h-full grid place-items-center">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"></path></svg>
            </div>
        @endif
        <span class="absolute top-3 right-3 bg-white/94 text-ab-navy text-xs font-semibold px-3 py-1.5 rounded-full">{{ $wilayatsCount }} ولاية</span>
    </div>

    <div class="flex flex-col gap-3 p-5 flex-1">
        <h3 class="m-0 text-2xl font-bold text-ab-navy">{{ $governorate->name_ar }}</h3>

        @if ($hasContent)
            <span class="self-start bg-ab-chip-bg text-ab-chip-text text-[12.5px] font-semibold px-3 py-1 rounded-full">{{ $sitesCount }} موقع سياحي · {{ $servicesCount }} خدمة</span>
        @else
            <span class="self-start bg-ab-cool text-[#7C8F94] text-[12.5px] font-semibold px-3 py-1 rounded-full">لا توجد بيانات بعد</span>
        @endif

        <span class="mt-auto flex items-center justify-between gap-2 px-4 py-2.5 rounded-full bg-ab-cool text-ab-navy text-sm font-semibold">
            استكشف الولايات
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
        </span>
    </div>
</a>
