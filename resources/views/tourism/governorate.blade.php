@extends('layouts.tourism')

@section('title', 'محافظة ' . $governorate->name_ar . ' - البادية')
@section('description', 'اكتشف ولايات ومواقع وخدمات محافظة ' . $governorate->name_ar . ' في سلطنة عُمان')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'الرئيسية', 'url' => route('tourism.index')],
        ['label' => 'المحافظات', 'url' => route('tourism.governorates')],
        ['label' => $governorate->name_ar],
    ]" />

    {{-- Hero --}}
    <section class="max-w-[1240px] mx-auto px-5 pt-4">
        <div class="relative overflow-hidden rounded-[34px] bg-ab-navy p-8 md:p-14" style="min-height:min(520px,70vh)">
            <span class="absolute -top-[80px] -left-[100px] w-[320px] h-[320px] rounded-full bg-white/5"></span>
            <div class="relative flex flex-col gap-5">
                <span class="inline-flex self-start items-center gap-2 bg-ab-sand/15 text-ab-sand text-[13px] font-semibold px-4 py-2 rounded-full">محافظة</span>
                <h1 class="m-0 text-white font-bold" style="font-size:clamp(40px,7vw,72px)">{{ $governorate->name_ar }}</h1>
                @if ($governorate->name_en)
                    <p class="m-0 text-sand text-lg" dir="ltr" style="color:rgba(230,197,143,.9)">{{ $governorate->name_en }}</p>
                @endif

                <div class="grid gap-1 pt-4 max-w-md border-t border-white/15" style="grid-template-columns:repeat(auto-fit, minmax(110px,1fr))">
                    @foreach ([['value' => $governorate->wilayats_count, 'label' => 'ولاية'], ['value' => $governorate->tourist_sites_count, 'label' => 'موقع سياحي'], ['value' => $governorate->tourist_services_count, 'label' => 'خدمة سياحية']] as $stat)
                        <div class="flex flex-col gap-1 pt-3">
                            <span class="text-[26px] font-bold text-ab-sand">{{ $stat['value'] }}</span>
                            <span class="text-[13px] text-white/70">{{ $stat['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-wrap items-center gap-3 mt-2">
                    <a href="#wilayats" class="px-6 py-3 rounded-full bg-ab-sand text-ab-navy text-sm font-semibold no-underline">استكشف ولايات المحافظة</a>
                    <a href="{{ $governorate->maps_url }}" target="_blank" rel="noopener" class="px-6 py-3 rounded-full border border-white/30 text-white text-sm font-semibold no-underline">عرض على الخريطة</a>
                </div>
            </div>
        </div>
    </section>

    {{-- الولايات --}}
    <section id="wilayats" class="max-w-[1240px] mx-auto px-5 pt-14 md:pt-20">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-6" data-listing>
            <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy">ولايات محافظة {{ $governorate->name_ar }}</h2>
            <div class="inline-flex items-center border border-ab-border rounded-full p-1 bg-white" data-wilayat-filter>
                <button type="button" data-filter="all" class="ab-filter-btn px-4 py-2 rounded-full text-sm font-semibold bg-ab-navy text-white">جميع الولايات ({{ $governorate->wilayats->count() }})</button>
                <button type="button" data-filter="content" class="ab-filter-btn px-4 py-2 rounded-full text-sm font-semibold text-ab-navy">بها مواقع وخدمات ({{ $governorate->wilayats->filter(fn($w) => $w->tourist_sites_count + $w->tourist_services_count > 0)->count() }})</button>
            </div>
        </div>

        <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(268px,1fr))">
            @foreach ($governorate->wilayats as $wilayat)
                <div data-wilayat-item data-has-content="{{ ($wilayat->tourist_sites_count + $wilayat->tourist_services_count) > 0 ? '1' : '0' }}">
                    <x-wilayat-card :wilayat="$wilayat" />
                </div>
            @endforeach
        </div>
    </section>

    {{-- المواقع السياحية --}}
    @if ($featuredSites->isNotEmpty())
        <section id="sites" class="max-w-[1240px] mx-auto px-5 pt-14 md:pt-20">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
                <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy">مواقع سياحية في {{ $governorate->name_ar }}</h2>
                <a href="{{ route('tourism.tourist-sites', ['governorate_id' => $governorate->id]) }}" class="text-sm font-semibold text-ab-teal no-underline">عرض كل المواقع ←</a>
            </div>
            <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(300px,1fr))">
                @foreach ($featuredSites as $site)
                    <x-site-card :site="$site" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- الخدمات السياحية --}}
    @if ($featuredServices->isNotEmpty())
        <section id="services" class="mt-14 md:mt-20 bg-ab-warm py-14 md:py-20">
            <div class="max-w-[1240px] mx-auto px-5">
                <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
                    <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy">خدمات سياحية في {{ $governorate->name_ar }}</h2>
                    <a href="{{ route('tourism.tourist-services', ['governorate_id' => $governorate->id]) }}" class="text-sm font-semibold text-ab-teal no-underline">عرض كل الخدمات ←</a>
                </div>
                <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(320px,1fr))">
                    @foreach ($featuredServices as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- خريطة / محافظة أخرى --}}
    <section class="max-w-[1240px] mx-auto px-5 py-14 md:py-20">
        <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(320px,1fr))">
            <div class="rounded-[30px] p-8 flex flex-col gap-4" style="background:#F8F5EF">
                <span class="w-[54px] h-[54px] rounded-2xl bg-white grid place-items-center border border-ab-border text-ab-teal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
                </span>
                <h3 class="m-0 text-xl font-bold text-ab-navy">{{ $governorate->name_ar }} على الخريطة</h3>
                <a href="{{ $governorate->maps_url }}" target="_blank" rel="noopener" class="self-start inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-ab-teal text-white text-sm font-semibold no-underline">عرض في خرائط جوجل</a>
            </div>
            <a href="{{ route('tourism.governorates') }}" class="rounded-[30px] p-8 bg-ab-navy flex flex-col gap-4 no-underline">
                <h3 class="m-0 text-xl font-bold text-white">محافظة أخرى؟</h3>
                <p class="m-0 text-white/70">تصفّح باقي محافظات سلطنة عُمان الإحدى عشرة.</p>
                <span class="self-start inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/12 text-white text-sm font-semibold">عرض جميع المحافظات</span>
            </a>
        </div>
    </section>

@endsection

@push('scripts')
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-wilayat-filter] .ab-filter-btn');
    if (!btn) return;
    const filter = btn.dataset.filter;
    btn.closest('[data-listing]').parentElement.querySelectorAll('[data-wilayat-item]').forEach(function (item) {
        item.classList.toggle('hidden', filter === 'content' && item.dataset.hasContent !== '1');
    });
    btn.parentElement.querySelectorAll('.ab-filter-btn').forEach(function (b) {
        const active = b === btn;
        b.classList.toggle('bg-ab-navy', active);
        b.classList.toggle('text-white', active);
        b.classList.toggle('text-ab-navy', !active);
    });
});
</script>
@endpush
