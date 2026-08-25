@extends('layouts.tourism')

@section('title', 'المواقع السياحية - البادية')
@section('description', 'اكتشف أفضل المواقع السياحية في سلطنة عُمان')

@php
    $isFiltered = request()->filled('search') || request()->filled('governorate_id') || request()->filled('wilayat_id') || request()->filled('category_id');
@endphp

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'الرئيسية', 'url' => route('tourism.index')],
        ['label' => 'المواقع السياحية'],
    ]" />

    <section class="max-w-[1240px] mx-auto px-5 pt-4" data-listing>
        <div class="max-w-[780px]">
            <span class="inline-flex items-center gap-2 bg-ab-chip-bg text-ab-chip-text text-[13px] font-semibold px-4 py-2 rounded-full">{{ $totalSitesCount }} موقع على المنصة</span>
            <h1 class="mt-4 mb-3 text-ab-navy font-bold" style="font-size:clamp(34px,5.4vw,56px)">اكتشف الأماكن السياحية</h1>
            <p class="m-0 text-ab-body text-lg leading-relaxed">تصفّح القلاع والمتاحف والأسواق والمعالم في جميع محافظات عُمان، وفلتر حسب الموقع أو التصنيف.</p>
        </div>

        <div class="mt-8">
            @include('tourism.partials.listing-filters', [
                'action' => route('tourism.tourist-sites'),
                'countLabel' => $touristSites->total() . ' من ' . $totalSitesCount . ' مواقع',
                'isFiltered' => $isFiltered,
                'selects' => [
                    ['name' => 'governorate_id', 'label' => 'المحافظة', 'selected' => request('governorate_id'), 'options' => $governorates->pluck('name_ar', 'id')],
                    ['name' => 'wilayat_id', 'label' => 'الولاية', 'selected' => request('wilayat_id'), 'options' => $wilayats->pluck('name_ar', 'id')],
                    ['name' => 'category_id', 'label' => 'التصنيف', 'selected' => request('category_id'), 'options' => $categories->pluck('name_ar', 'id')],
                ],
            ])
        </div>

        <div class="mt-8">
            @if ($touristSites->isEmpty())
                <x-empty-state title="لا توجد مواقع مطابقة" body="جرّب تعديل كلمة البحث أو إزالة بعض الفلاتر.">
                    <x-slot:actions>
                        <a href="{{ route('tourism.tourist-sites') }}" class="px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">إزالة الفلاتر</a>
                    </x-slot:actions>
                </x-empty-state>
            @else
                <div data-view-panel="cards" class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(300px,1fr))">
                    @foreach ($touristSites as $site)
                        <x-site-card :site="$site" />
                    @endforeach
                </div>

                <div data-view-panel="map" class="hidden bg-white border border-ab-border rounded-[22px] p-4">
                    <div class="flex items-center gap-3 p-3 border-b border-ab-border mb-2">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#789A9A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
                        <div class="flex flex-col">
                            <span class="font-bold text-ab-navy">مواقع القائمة على الخريطة</span>
                            <span class="text-[13px] text-ab-muted">افتح أي موقع مباشرة في خرائط جوجل</span>
                        </div>
                    </div>
                    @foreach ($touristSites as $site)
                        <a href="{{ $site->maps_url }}" target="_blank" rel="noopener" class="flex items-center gap-3 p-3 rounded-2xl no-underline hover:bg-ab-cool">
                            <span class="w-[46px] h-[46px] shrink-0 rounded-2xl overflow-hidden bg-ab-cool grid place-items-center">
                                @if ($site->images->isNotEmpty())
                                    <img src="{{ $site->featured_image }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
                                @endif
                            </span>
                            <span class="flex flex-col min-w-0 flex-1">
                                <span class="font-semibold text-ab-navy truncate">{{ $site->name_ar }}</span>
                                <span class="text-[13px] text-ab-muted truncate">{{ implode(' · ', array_filter([$site->governorate?->name_ar, $site->wilayat?->name_ar])) }}</span>
                            </span>
                            <span class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-ab-cool text-ab-navy text-xs font-semibold">
                                الاتجاهات
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-8">{{ $touristSites->onEachSide(1)->links() }}</div>
    </section>

    <section class="max-w-[1240px] mx-auto px-5 py-14 md:py-20">
        <div class="rounded-[30px] bg-ab-navy p-8 md:p-12 text-center flex flex-col items-center gap-3">
            <h2 class="m-0 text-2xl md:text-3xl font-bold text-white">تحتاج خدمة قريبة؟</h2>
            <p class="m-0 max-w-xl text-white/70">فنادق وأسواق ومرافق سياحية موزّعة على محافظات عُمان.</p>
            <a href="{{ route('tourism.tourist-services') }}" class="mt-1 inline-flex items-center gap-2 px-6 py-3 rounded-full bg-ab-sand text-ab-navy text-sm font-semibold no-underline">تصفّح الخدمات السياحية</a>
        </div>
    </section>

@endsection
