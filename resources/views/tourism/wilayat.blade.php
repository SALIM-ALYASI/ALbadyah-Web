@extends('layouts.tourism')

@section('title', 'ولاية ' . $wilayat->name_ar . ' - البادية')
@section('description', 'اكتشف المواقع والخدمات السياحية في ولاية ' . $wilayat->name_ar)

@php
    $categories = $wilayat->touristSites->pluck('category')->filter()->unique('id')->values();
@endphp

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'الرئيسية', 'url' => route('tourism.index')],
        ['label' => $wilayat->governorate?->name_ar, 'url' => $wilayat->governorate ? route('tourism.governorate', $wilayat->governorate->slug ?: $wilayat->governorate->id) : null],
        ['label' => $wilayat->name_ar],
    ]" />

    {{-- Hero --}}
    <section class="max-w-[1240px] mx-auto px-5 pt-4">
        <div class="relative overflow-hidden rounded-[34px] bg-ab-navy p-8 md:p-14" style="min-height:min(460px,62vh)">
            <span class="absolute -top-[80px] -left-[100px] w-[300px] h-[300px] rounded-full bg-white/5"></span>
            <div class="relative flex flex-col gap-5">
                <span class="inline-flex self-start items-center gap-2 bg-ab-sand/15 text-ab-sand text-[13px] font-semibold px-4 py-2 rounded-full">
                    ولاية @if ($wilayat->governorate) · محافظة {{ $wilayat->governorate->name_ar }} @endif
                </span>
                <h1 class="m-0 text-white font-bold" style="font-size:clamp(38px,6.6vw,68px)">ولاية {{ $wilayat->name_ar }}</h1>

                <div class="grid gap-1 pt-4 max-w-sm border-t border-white/15" style="grid-template-columns:repeat(auto-fit, minmax(110px,1fr))">
                    @foreach ([['value' => $wilayat->tourist_sites_count, 'label' => 'موقع سياحي'], ['value' => $wilayat->tourist_services_count, 'label' => 'خدمة سياحية']] as $stat)
                        <div class="flex flex-col gap-1 pt-3">
                            <span class="text-[26px] font-bold text-ab-sand">{{ $stat['value'] }}</span>
                            <span class="text-[13px] text-white/70">{{ $stat['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                <a href="{{ $wilayat->maps_url }}" target="_blank" rel="noopener" class="self-start mt-2 px-6 py-3 rounded-full border border-white/30 text-white text-sm font-semibold no-underline">عرض على الخريطة</a>
            </div>
        </div>
    </section>

    {{-- بطاقتا التنقل --}}
    <section class="max-w-[1240px] mx-auto px-5 pt-10">
        <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(320px,1fr))">
            <a href="#places" class="rounded-[30px] p-7 bg-ab-navy flex flex-col gap-3 no-underline">
                <h3 class="m-0 text-xl font-bold text-white">أماكن تستحق الزيارة</h3>
                <p class="m-0 text-white/70 leading-relaxed">
                    @if ($wilayat->tourist_sites_count > 0)
                        {{ $wilayat->tourist_sites_count }} {{ $wilayat->tourist_sites_count == 1 ? 'موقع سياحي' : 'مواقع سياحية' }} في {{ $wilayat->name_ar }}.
                    @else
                        لا توجد مواقع سياحية مسجّلة بعد في {{ $wilayat->name_ar }}.
                    @endif
                </p>
            </a>
            <a href="#services" class="rounded-[30px] p-7 flex flex-col gap-3 no-underline" style="background:#F3E7D0">
                <h3 class="m-0 text-xl font-bold text-ab-navy">خدمات قد تحتاجها</h3>
                <p class="m-0 text-ab-navy/70 leading-relaxed">
                    @if ($wilayat->tourist_services_count > 0)
                        {{ $wilayat->tourist_services_count }} {{ $wilayat->tourist_services_count == 1 ? 'خدمة سياحية' : 'خدمات سياحية' }} في {{ $wilayat->name_ar }}.
                    @else
                        لا توجد خدمات مسجّلة في {{ $wilayat->name_ar }} — تصفح خدمات المحافظة.
                    @endif
                </p>
            </a>
        </div>
    </section>

    {{-- الأماكن --}}
    <section id="places" class="max-w-[1240px] mx-auto px-5 pt-14 md:pt-20" data-listing>
        <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy mb-5">أماكن تستحق الزيارة</h2>

        @if ($wilayat->touristSites->isNotEmpty())
            @if ($categories->isNotEmpty())
                <div class="flex flex-wrap items-center gap-2 mb-6" data-category-filter>
                    <button type="button" data-cat="all" class="ab-cat-btn px-4 py-2 rounded-full text-sm font-semibold bg-ab-navy text-white">الكل</button>
                    @foreach ($categories as $category)
                        <button type="button" data-cat="{{ $category->id }}" class="ab-cat-btn px-4 py-2 rounded-full text-sm font-semibold border border-ab-border text-ab-navy">{{ $category->name_ar }}</button>
                    @endforeach
                </div>
            @endif

            <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(300px,1fr))">
                @foreach ($wilayat->touristSites as $site)
                    <div data-site-item data-cat="{{ $site->tourist_site_category_id }}">
                        <x-site-card :site="$site" badge="category" :show-location="false" :show-description="true" />
                    </div>
                @endforeach
            </div>

            <div data-no-results class="hidden mt-6">
                <x-empty-state title="لا توجد نتائج في هذا التصنيف">
                    <x-slot:actions>
                        <button type="button" data-reset-filter class="px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold">عرض كل الأماكن</button>
                    </x-slot:actions>
                </x-empty-state>
            </div>
        @else
            <x-empty-state title="لا توجد بيانات بعد" body="لم تُسجَّل مواقع سياحية في ولاية {{ $wilayat->name_ar }} حتى الآن.">
                <x-slot:actions>
                    <a href="{{ route('tourism.tourist-sites') }}" class="px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">كل المواقع السياحية</a>
                </x-slot:actions>
            </x-empty-state>
        @endif
    </section>

    {{-- الخدمات --}}
    <section id="services" class="mt-14 md:mt-20 bg-ab-warm py-14 md:py-20">
        <div class="max-w-[1240px] mx-auto px-5">
            <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy mb-6">خدمات قد تحتاجها</h2>

            @if ($wilayat->touristServices->isNotEmpty())
                <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(320px,1fr))">
                    @foreach ($wilayat->touristServices as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            @else
                <x-empty-state title="لا توجد بيانات بعد" body="لم تُسجَّل خدمات في ولاية {{ $wilayat->name_ar }} حتى الآن. أقرب الخدمات في محافظة {{ $wilayat->governorate?->name_ar }}.">
                    <x-slot:actions>
                        <a href="{{ route('tourism.tourist-services', ['governorate_id' => $wilayat->governorate_id]) }}" class="px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">خدمات محافظة {{ $wilayat->governorate?->name_ar }}</a>
                    </x-slot:actions>
                </x-empty-state>
            @endif
        </div>
    </section>

    {{-- ولاية أخرى --}}
    @if ($wilayat->governorate)
        <section class="max-w-[1240px] mx-auto px-5 py-14 md:py-20">
            <div class="rounded-[30px] bg-ab-navy p-8 md:p-10 text-center flex flex-col items-center gap-3">
                <h3 class="m-0 text-xl md:text-2xl font-bold text-white">ولاية أخرى في {{ $wilayat->governorate->name_ar }}؟</h3>
                <p class="m-0 max-w-xl text-white/70">تصفّح باقي ولايات محافظة {{ $wilayat->governorate->name_ar }}.</p>
                <a href="{{ route('tourism.governorate', $wilayat->governorate->slug ?: $wilayat->governorate->id) }}" class="mt-1 inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white/12 text-white text-sm font-semibold no-underline">عرض ولايات {{ $wilayat->governorate->name_ar }}</a>
            </div>
        </section>
    @endif

@endsection

@push('scripts')
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-category-filter] .ab-cat-btn') || (e.target.closest('[data-reset-filter]') ? document.querySelector('[data-cat="all"]') : null);
    if (!btn) return;
    const root = document.getElementById('places') || document.querySelector('[data-listing]');
    const cat = btn.dataset.cat;
    let visibleCount = 0;
    root.querySelectorAll('[data-site-item]').forEach(function (item) {
        const show = cat === 'all' || item.dataset.cat === cat;
        item.classList.toggle('hidden', !show);
        if (show) visibleCount++;
    });
    root.querySelectorAll('.ab-cat-btn').forEach(function (b) {
        const active = b.dataset.cat === cat;
        b.classList.toggle('bg-ab-navy', active);
        b.classList.toggle('text-white', active);
        b.classList.toggle('border', !active);
        b.classList.toggle('border-ab-border', !active);
        b.classList.toggle('text-ab-navy', !active);
    });
    const noResults = root.querySelector('[data-no-results]');
    if (noResults) noResults.classList.toggle('hidden', visibleCount > 0);
});
</script>
@endpush
