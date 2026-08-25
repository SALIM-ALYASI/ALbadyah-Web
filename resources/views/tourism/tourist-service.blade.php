@extends('layouts.tourism')

@section('title', $touristService->name_ar . ' - البادية')
@section('description', $touristService->description_ar ? \Illuminate\Support\Str::limit(strip_tags($touristService->description_ar), 150) : $touristService->name_ar)

@php
    $infoRows = [
        ['glyph' => 'ن', 'label' => 'نوع الخدمة', 'value' => $touristService->serviceType?->name_ar],
        ['glyph' => 'م', 'label' => 'المحافظة', 'value' => $touristService->governorate?->name_ar],
        ['glyph' => 'و', 'label' => 'الولاية', 'value' => $touristService->wilayat?->name_ar],
        ['glyph' => 'س', 'label' => 'ساعات العمل', 'value' => $touristService->opening_hours],
        ['glyph' => 'ه', 'label' => 'الهاتف', 'value' => $touristService->phone],
        ['glyph' => 'و', 'label' => 'الموقع الإلكتروني', 'value' => $touristService->website_url],
    ];
@endphp

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'الرئيسية', 'url' => route('tourism.index')],
        ['label' => 'الخدمات السياحية', 'url' => route('tourism.tourist-services')],
        ['label' => $touristService->name_ar],
    ]" />

    <section class="max-w-[1240px] mx-auto px-5 pt-4">
        <div class="relative rounded-[34px] overflow-hidden bg-ab-cool" style="aspect-ratio:21/9; min-height:240px">
            @if ($touristService->has_image)
                <img src="{{ $touristService->image_url }}" alt="{{ $touristService->name_ar }}" class="w-full h-full object-cover">
            @elseif ($touristService->serviceType?->placeholder_image)
                <img src="{{ $touristService->image_url }}" alt="{{ $touristService->name_ar }}" class="w-full h-full object-contain p-6">
                <span class="absolute bottom-4 right-4 bg-white/90 text-ab-body text-xs font-semibold px-2.5 py-1 rounded-full">رسم توضيحي</span>
            @else
                <div class="w-full h-full grid place-items-center">
                    <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"></path></svg>
                </div>
            @endif
        </div>

        <div class="grid gap-8 mt-10" style="grid-template-columns:repeat(auto-fit, minmax(300px,1fr))">
            <div class="flex flex-col gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    @if ($touristService->serviceType)
                        <span class="bg-ab-chip-bg text-ab-chip-text text-xs font-semibold px-3 py-1.5 rounded-full">{{ $touristService->serviceType->name_ar }}</span>
                    @endif
                    @if ($touristService->governorate)
                        <a href="{{ route('tourism.governorate', $touristService->governorate->slug ?: $touristService->governorate->id) }}" class="bg-ab-cool text-ab-navy text-xs font-semibold px-3 py-1.5 rounded-full no-underline">{{ $touristService->governorate->name_ar }}</a>
                    @endif
                    @if ($touristService->wilayat)
                        <span class="bg-ab-cool text-ab-navy text-xs font-semibold px-3 py-1.5 rounded-full">{{ $touristService->wilayat->name_ar }}</span>
                    @endif
                </div>

                <h1 class="m-0 text-ab-navy font-bold" style="font-size:clamp(30px,4.6vw,50px)">{{ $touristService->name_ar }}</h1>
                @if ($touristService->name_en)
                    <p class="m-0 text-ab-muted" dir="ltr">{{ $touristService->name_en }}</p>
                @endif

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ $touristService->maps_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                        الاتجاهات
                    </a>

                    @if ($touristService->phone)
                        <a href="tel:{{ $touristService->phone }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline" dir="ltr">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2Z"></path></svg>
                            {{ $touristService->phone }}
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-ab-border bg-ab-warm text-ab-muted text-sm font-semibold">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2Z"></path></svg>
                            اتصال — لا يوجد رقم
                        </span>
                    @endif

                    @if ($touristService->website_url)
                        <a href="{{ $touristService->website_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20 15.3 15.3 0 0 1 0-20Z"></path></svg>
                            الموقع الإلكتروني
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-ab-border bg-ab-warm text-ab-muted text-sm font-semibold">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20 15.3 15.3 0 0 1 0-20Z"></path></svg>
                            الموقع الإلكتروني — غير متوفر
                        </span>
                    @endif
                </div>

                <p class="m-0 text-xs text-ab-muted leading-relaxed">تُعرض بيانات التواصل وساعات العمل فقط عند توفرها فعلياً — لا نعرض بيانات غير مؤكدة.</p>

                @if ($touristService->description_ar)
                    <div class="pt-2">
                        <h2 class="text-lg font-bold text-ab-navy mb-2">عن الخدمة</h2>
                        <p class="m-0 text-ab-body leading-relaxed">{{ $touristService->description_ar }}</p>
                    </div>
                @endif
            </div>

            <div class="bg-ab-warm border border-ab-border rounded-[30px] p-6 h-fit">
                <h2 class="m-0 text-lg font-bold text-ab-navy mb-3">معلومات الخدمة</h2>
                <x-info-list :rows="$infoRows" />
                <a href="{{ $touristService->maps_url }}" target="_blank" rel="noopener" class="mt-4 w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full bg-ab-teal text-white text-sm font-semibold no-underline">عرض الموقع في خرائط جوجل</a>
            </div>
        </div>
    </section>

    {{-- مواقع سياحية قريبة --}}
    @if ($nearbySites->isNotEmpty())
        <section class="mt-14 md:mt-20 bg-ab-warm py-14 md:py-20">
            <div class="max-w-[1240px] mx-auto px-5">
                <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy mb-6">أماكن سياحية قريبة</h2>
                <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(300px,1fr))">
                    @foreach ($nearbySites as $site)
                        <x-site-card :site="$site" :directions="false" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
