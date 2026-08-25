@props([
    'site',
    'badge' => 'wilayat',
    'showLocation' => true,
    'showDescription' => false,
    'directions' => true,
])

@php
    $badgeText = $badge === 'category' ? $site->category?->name_ar : $site->wilayat?->name_ar;
    $hasImage = $site->images->isNotEmpty();
    $locationParts = array_filter([$site->governorate?->name_ar, $site->wilayat?->name_ar]);
@endphp

<div class="flex flex-col bg-white border border-ab-border rounded-[22px] overflow-hidden transition hover:border-ab-teal hover:shadow-[0_16px_34px_rgba(36,59,68,0.1)]">
    <div class="relative aspect-[4/3] bg-ab-cool">
        @if ($hasImage)
            <img src="{{ $site->featured_image }}" alt="{{ $site->name_ar }}" loading="lazy"
                class="w-full h-full object-cover" style="filter:saturate(.9) contrast(.97)">
        @else
            <div class="w-full h-full grid place-items-center">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
            </div>
        @endif
        @if ($badgeText)
            <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 bg-ab-navy/86 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
                {{ $badgeText }}
            </span>
        @endif
    </div>

    <div class="flex flex-col gap-2 p-5">
        <h3 class="m-0 text-xl font-bold text-ab-navy">{{ $site->name_ar }}</h3>
        @if ($site->name_en)
            <p class="m-0 text-[13px] text-ab-muted" dir="ltr">{{ $site->name_en }}</p>
        @endif

        @if ($showLocation && $locationParts)
            <p class="m-0 flex items-center gap-1.5 text-sm text-ab-body">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
                {{ implode(' · ', $locationParts) }}
            </p>
        @endif

        @if ($showDescription && $site->description_ar)
            <p class="m-0 text-sm text-ab-body leading-relaxed">{{ \Illuminate\Support\Str::limit($site->description_ar, 90) }}</p>
        @endif

        <div class="flex items-center gap-2 mt-2">
            <a href="{{ route('tourism.tourist-site', $site->slug ?: $site->id) }}"
                class="flex-1 inline-flex items-center justify-between gap-2 px-5 py-3 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">
                عرض التفاصيل
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
            </a>
            @if ($directions)
                <a href="{{ $site->maps_url }}" target="_blank" rel="noopener" aria-label="الاتجاهات"
                    class="shrink-0 w-12 h-12 grid place-items-center rounded-full border border-ab-border-2 text-ab-navy no-underline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                </a>
            @endif
        </div>
    </div>
</div>
