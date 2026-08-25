@extends('layouts.tourism')

@section('title', $touristSite->name_ar . ' - البادية')
@section('description', \Illuminate\Support\Str::limit(strip_tags($touristSite->description_ar), 150))

@php
    $images = $touristSite->images->isNotEmpty() ? $touristSite->images : collect();
    $infoRows = [
        ['glyph' => 'م', 'label' => 'المحافظة', 'value' => $touristSite->governorate?->name_ar],
        ['glyph' => 'و', 'label' => 'الولاية', 'value' => $touristSite->wilayat?->name_ar],
        ['glyph' => 'ت', 'label' => 'التصنيف', 'value' => $touristSite->category?->name_ar],
        ['glyph' => 'س', 'label' => 'ساعات العمل', 'value' => null],
        ['glyph' => 'ا', 'label' => 'معلومات الاتصال', 'value' => null],
    ];
@endphp

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'الرئيسية', 'url' => route('tourism.index')],
        ['label' => 'المواقع السياحية', 'url' => route('tourism.tourist-sites')],
        ['label' => $touristSite->wilayat?->name_ar ?? $touristSite->name_ar, 'url' => $touristSite->wilayat ? route('tourism.wilayat', $touristSite->wilayat->slug ?: $touristSite->wilayat->id) : null],
        ['label' => $touristSite->name_ar],
    ]" />

    <section class="max-w-[1240px] mx-auto px-5 pt-4">
        {{-- المعرض --}}
        @if ($images->isNotEmpty())
            <div class="ab-gallery grid gap-3" style="grid-template-columns:2fr 1fr">
                <div class="rounded-[30px] overflow-hidden bg-ab-cool" style="aspect-ratio:16/11">
                    <img id="gallery-main" src="{{ $images->first()->image_url }}" alt="{{ $touristSite->name_ar }}" class="w-full h-full object-cover">
                </div>
                <div class="ab-thumbs grid gap-3" style="grid-template-rows:repeat({{ min($images->count(), 3) }}, 1fr)">
                    @foreach ($images->take(3) as $index => $image)
                        <button type="button" data-thumb="{{ $image->image_url }}"
                            class="ab-thumb-btn rounded-[22px] overflow-hidden border-[3px] {{ $index === 0 ? 'border-ab-teal' : 'border-transparent' }}">
                            <img src="{{ $image->image_url }}" alt="" class="w-full h-full object-cover" style="aspect-ratio:16/11">
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            <div class="rounded-[30px] bg-ab-cool grid place-items-center" style="aspect-ratio:16/11">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
            </div>
        @endif

        {{-- المحتوى --}}
        <div class="grid gap-8 mt-10" style="grid-template-columns:repeat(auto-fit, minmax(300px,1fr))">
            <div class="flex flex-col gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    @if ($touristSite->category)
                        <span class="bg-ab-chip-bg text-ab-chip-text text-xs font-semibold px-3 py-1.5 rounded-full">{{ $touristSite->category->name_ar }}</span>
                    @endif
                    @if ($touristSite->governorate)
                        <a href="{{ route('tourism.governorate', $touristSite->governorate->slug ?: $touristSite->governorate->id) }}" class="bg-ab-cool text-ab-navy text-xs font-semibold px-3 py-1.5 rounded-full no-underline">{{ $touristSite->governorate->name_ar }}</a>
                    @endif
                    @if ($touristSite->wilayat)
                        <a href="{{ route('tourism.wilayat', $touristSite->wilayat->slug ?: $touristSite->wilayat->id) }}" class="bg-ab-cool text-ab-navy text-xs font-semibold px-3 py-1.5 rounded-full no-underline">{{ $touristSite->wilayat->name_ar }}</a>
                    @endif
                </div>

                <h1 class="m-0 text-ab-navy font-bold" style="font-size:clamp(30px,4.6vw,50px)">{{ $touristSite->name_ar }}</h1>
                @if ($touristSite->name_en)
                    <p class="m-0 text-ab-muted" dir="ltr">{{ $touristSite->name_en }}</p>
                @endif

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ $touristSite->maps_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                        الاتجاهات
                    </a>
                    <button type="button" id="share-btn" data-url="{{ url()->current() }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><path d="m8.6 10.6 6.8-3.8M8.6 13.4l6.8 3.8"></path></svg>
                        <span id="share-label">مشاركة</span>
                    </button>
                </div>

                @if ($touristSite->description_ar)
                    <div class="pt-2">
                        <h2 class="text-lg font-bold text-ab-navy mb-2">عن المكان</h2>
                        <p class="m-0 text-ab-body leading-relaxed">{{ $touristSite->description_ar }}</p>
                    </div>
                @endif
            </div>

            <div class="bg-ab-warm border border-ab-border rounded-[30px] p-6 h-fit">
                <h2 class="m-0 text-lg font-bold text-ab-navy mb-3">معلومات الزيارة</h2>
                <x-info-list :rows="$infoRows" />
                <a href="{{ $touristSite->maps_url }}" target="_blank" rel="noopener" class="mt-4 w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full bg-ab-teal text-white text-sm font-semibold no-underline">عرض الموقع في خرائط جوجل</a>
                <p class="mt-3 mb-0 text-center text-xs text-ab-muted">تُعرض الحقول عند توفر بيانات حقيقية فقط</p>
            </div>
        </div>
    </section>

    {{-- خدمات قريبة --}}
    @if ($nearbyServices->isNotEmpty())
        <section class="mt-14 md:mt-20 bg-ab-warm py-14 md:py-20">
            <div class="max-w-[1240px] mx-auto px-5">
                <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy mb-6">خدمات قريبة منك</h2>
                <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(320px,1fr))">
                    @foreach ($nearbyServices as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- مواقع أخرى قريبة --}}
    @if ($relatedSites->isNotEmpty())
        <section class="max-w-[1240px] mx-auto px-5 py-14 md:py-20">
            <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy mb-6">أماكن أخرى قريبة تستحق الزيارة</h2>
            <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(300px,1fr))">
                @foreach ($relatedSites as $related)
                    <x-site-card :site="$related" :directions="false" />
                @endforeach
            </div>
        </section>
    @endif

@endsection

@push('scripts')
<script>
document.querySelectorAll('.ab-thumb-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('gallery-main').src = btn.dataset.thumb;
        document.querySelectorAll('.ab-thumb-btn').forEach(function (b) {
            b.classList.toggle('border-ab-teal', b === btn);
            b.classList.toggle('border-transparent', b !== btn);
        });
    });
});

const shareBtn = document.getElementById('share-btn');
if (shareBtn) {
    shareBtn.addEventListener('click', function () {
        navigator.clipboard.writeText(shareBtn.dataset.url).then(function () {
            const label = document.getElementById('share-label');
            const original = label.textContent;
            label.textContent = 'تم نسخ الرابط';
            setTimeout(function () { label.textContent = original; }, 1800);
        });
    });
}
</script>
@endpush

@push('styles')
<style>
    @media (max-width: 760px) {
        .ab-gallery { grid-template-columns: 1fr !important; }
        .ab-thumbs { grid-template-columns: repeat(2, 1fr) !important; grid-template-rows: none !important; }
    }
</style>
@endpush
