@extends('layouts.tourism')

@section('title', 'البادية - السياحة في عُمان')
@section('description', 'اكتشف جمال سلطنة عُمان - مواقع سياحية رائعة وخدمات متميزة عبر ١١ محافظة و٦٣ ولاية')

@section('content')

    {{-- Hero --}}
    <section class="max-w-[1240px] mx-auto px-5 pt-6">
        <div class="relative rounded-[34px] overflow-hidden" style="min-height:min(620px,78vh)">
            <img src="{{ asset('images/albadyah.jpg') }}" alt="عُمان" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(36,59,68,.95) 8%, rgba(36,59,68,.72) 45%, rgba(36,59,68,.35) 100%)"></div>

            <div class="relative z-10 flex flex-col items-center text-center gap-6 px-6 py-16 md:py-20">
                <span class="inline-flex items-center gap-2 bg-white/12 border border-white/25 text-ab-sand text-[13px] font-semibold px-4 py-2 rounded-full">
                    سلطنة عُمان · {{ $stats['total_governorates'] }} محافظة · {{ $stats['total_wilayats'] }} ولاية
                </span>

                <h1 class="m-0 text-white font-bold" style="font-size:clamp(40px,7vw,76px)">اكتشف عُمان</h1>
                <p class="m-0 max-w-2xl text-white/85 text-lg leading-relaxed">من الجبال الشامخة إلى السواحل الذهبية، من الصحراء الذهبية إلى الواحات الخضراء — رحلة عبر تراث عُمان العريق وطبيعتها الساحرة.</p>

                {{-- شريط البحث --}}
                <div class="relative w-full max-w-xl" id="hero-search">
                    <form class="ab-search flex items-center gap-2 bg-white rounded-full p-2 shadow-[0_18px_44px_rgba(20,35,41,.28)]" style="flex-wrap:wrap" onsubmit="event.preventDefault(); window.location.href='{{ route('tourism.search') }}?query=' + encodeURIComponent(document.getElementById('hero-search-input').value);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8A9B9E" stroke-width="2.5" stroke-linecap="round" class="shrink-0 mr-2"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
                        <input id="hero-search-input" type="text" autocomplete="off" placeholder="ابحث عن مكان أو خدمة..." class="ab-search-field flex-1 min-w-0 border-0 outline-none bg-transparent text-sm text-ab-navy px-1 py-2.5">
                        <button type="submit" class="ab-search-cta inline-flex items-center gap-2 px-5 py-3 rounded-full bg-ab-teal text-white text-sm font-semibold shrink-0">استكشف عُمان</button>
                    </form>
                    <div id="hero-search-results" class="hidden absolute z-20 mt-3 w-full text-right bg-white rounded-[26px] shadow-[0_24px_60px_rgba(20,35,41,.3)] p-3 overflow-y-auto" style="max-height:min(58vh,460px)"></div>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-2">
                    @foreach (['مطرح', 'قلعة', 'فندق في مسقط', 'المتحف الوطني'] as $chip)
                        <button type="button" class="ab-search-chip px-4 py-2 rounded-full bg-white/12 border border-white/25 text-white text-[13px]" data-query="{{ $chip }}">{{ $chip }}</button>
                    @endforeach
                </div>

                <div class="grid gap-1 pt-4 w-full max-w-xl border-t border-white/15" style="grid-template-columns:repeat(auto-fit, minmax(110px,1fr))">
                    @foreach ([['value' => $stats['total_governorates'], 'label' => 'محافظة'], ['value' => $stats['total_wilayats'], 'label' => 'ولاية'], ['value' => $stats['total_tourist_sites'], 'label' => 'موقع سياحي'], ['value' => $stats['total_tourist_services'], 'label' => 'خدمة سياحية']] as $stat)
                        <div class="flex flex-col items-center gap-1 pt-3">
                            <span class="text-[28px] font-bold text-ab-sand">{{ $stat['value'] }}</span>
                            <span class="text-[13px] text-white/70">{{ $stat['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- مسارَان --}}
    <section class="max-w-[1240px] mx-auto px-5 pt-14 md:pt-20">
        <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(320px,1fr))">
            <a href="{{ route('tourism.governorates') }}" class="relative overflow-hidden rounded-[30px] bg-ab-navy p-8 no-underline">
                <span class="absolute -top-[60px] -left-[110px] w-[300px] h-[300px] rounded-full bg-white/5"></span>
                <div class="relative flex flex-col gap-4">
                    <span class="w-[62px] h-[62px] rounded-2xl bg-ab-sand/20 grid place-items-center text-ab-sand">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
                    </span>
                    <span class="text-ab-sand text-[13px] font-semibold">ابدأ من هنا</span>
                    <h2 class="m-0 text-white font-bold" style="font-size:clamp(28px,4vw,40px)">أين تريد أن تذهب؟</h2>
                    <p class="m-0 text-white/75 leading-relaxed">تصفّح محافظات عُمان الإحدى عشرة وولاياتها الثلاث والستين.</p>
                    <span class="inline-flex items-center gap-2 self-start px-5 py-2.5 rounded-full bg-white/12 text-white text-sm font-semibold">
                        تصفّح المحافظات
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                    </span>
                </div>
            </a>

            <a href="{{ route('tourism.tourist-services') }}" class="relative overflow-hidden rounded-[30px] p-8 no-underline" style="background:#F3E7D0">
                <span class="absolute -top-[60px] -left-[110px] w-[300px] h-[300px] rounded-full bg-white/25"></span>
                <div class="relative flex flex-col gap-4">
                    <span class="w-[62px] h-[62px] rounded-2xl bg-white/60 grid place-items-center text-ab-chip-text">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"></path></svg>
                    </span>
                    <span class="text-ab-chip-text text-[13px] font-semibold">خدمات سياحية</span>
                    <h2 class="m-0 text-ab-navy font-bold" style="font-size:clamp(28px,4vw,40px)">ماذا تحتاج؟</h2>
                    <p class="m-0 text-ab-navy/70 leading-relaxed">فنادق وأسواق ومطاعم ومرافق قريبة من وجهتك.</p>
                    <span class="inline-flex items-center gap-2 self-start px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold">
                        تصفّح الخدمات
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                    </span>
                </div>
            </a>
        </div>
    </section>

    {{-- المحافظات --}}
    <section id="governorates" class="max-w-[1240px] mx-auto px-5 pt-14 md:pt-20">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
            <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy">محافظات عُمان</h2>
            <a href="{{ route('tourism.governorates') }}" class="text-sm font-semibold text-ab-teal no-underline">عرض جميع المحافظات ←</a>
        </div>

        <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(268px,1fr))">
            @foreach ($governorates as $governorate)
                <x-governorate-card :governorate="$governorate" />
            @endforeach
        </div>
    </section>

    {{-- أنواع الخدمات --}}
    @if ($serviceTypes->isNotEmpty())
        <section id="needs" class="mt-14 md:mt-20 bg-ab-warm py-14 md:py-20">
            <div class="max-w-[1240px] mx-auto px-5">
                <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy mb-6">ماذا تحتاج في رحلتك؟</h2>
                <div class="grid gap-4" style="grid-template-columns:repeat(auto-fit, minmax(240px,1fr))">
                    @foreach ($serviceTypes as $type)
                        <a href="{{ route('tourism.tourist-services', ['service_type_id' => $type->id]) }}"
                            class="flex items-center gap-4 bg-white border border-ab-border rounded-[22px] p-4 no-underline transition hover:border-ab-teal">
                            <span class="w-[54px] h-[54px] shrink-0 rounded-2xl bg-ab-cool grid place-items-center text-ab-teal text-lg font-bold">{{ mb_substr($type->name_ar, 0, 1) }}</span>
                            <span class="flex flex-col">
                                <span class="font-bold text-ab-navy">{{ $type->name_ar }}</span>
                                <span class="text-[13px] text-ab-muted">{{ $type->tourist_services_count }} خدمة</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- المواقع السياحية --}}
    @if ($featuredSites->isNotEmpty())
        <section id="sites" class="max-w-[1240px] mx-auto px-5 pt-14 md:pt-20">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
                <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy">مواقع سياحية مميزة</h2>
                <a href="{{ route('tourism.tourist-sites') }}" class="text-sm font-semibold text-ab-teal no-underline">عرض كل المواقع ←</a>
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
        <section id="services" class="max-w-[1240px] mx-auto px-5 pt-14 md:pt-20">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
                <h2 class="m-0 text-2xl md:text-3xl font-bold text-ab-navy">خدمات سياحية مقترحة</h2>
                <a href="{{ route('tourism.tourist-services') }}" class="text-sm font-semibold text-ab-teal no-underline">عرض كل الخدمات ←</a>
            </div>
            <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(320px,1fr))">
                @foreach ($featuredServices as $service)
                    <x-service-card :service="$service" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- بانر من نحن --}}
    <section class="max-w-[1240px] mx-auto px-5 py-14 md:py-20">
        <div class="relative overflow-hidden rounded-[30px] bg-ab-navy p-10 md:p-14 text-center">
            <span class="absolute -bottom-[80px] -right-[100px] w-[320px] h-[320px] rounded-full bg-white/5"></span>
            <div class="relative flex flex-col items-center gap-4">
                <h2 class="m-0 text-white font-bold" style="font-size:clamp(28px,4vw,42px)">جاهز لاستكشاف عُمان؟</h2>
                <p class="m-0 max-w-xl text-white/75 leading-relaxed">ابدأ رحلتك من المواقع السياحية أو دع البادية يدلّك على الخدمات القريبة منك.</p>
                <div class="flex flex-wrap items-center justify-center gap-3 mt-2">
                    <a href="{{ route('tourism.tourist-sites') }}" class="px-6 py-3 rounded-full bg-ab-sand text-ab-navy text-sm font-semibold no-underline">المواقع السياحية</a>
                    <a href="{{ route('tourism.tourist-services') }}" class="px-6 py-3 rounded-full border border-white/30 text-white text-sm font-semibold no-underline">الخدمات السياحية</a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
(function () {
    const input = document.getElementById('hero-search-input');
    const results = document.getElementById('hero-search-results');
    const suggestUrl = @json(route('tourism.search.suggest'));
    let debounce;

    function iconFor() {
        return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#789A9A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>';
    }

    function render(groups) {
        if (!groups.length) {
            results.innerHTML = '<p class="text-center text-sm text-ab-muted py-6 m-0">لا توجد نتائج مطابقة</p>';
            results.classList.remove('hidden');
            return;
        }
        results.innerHTML = groups.map(function (group) {
            const rows = group.items.map(function (item) {
                const avatar = item.image
                    ? '<img src="' + item.image + '" class="w-11 h-11 rounded-2xl object-cover" alt="">'
                    : '<span class="w-11 h-11 rounded-2xl bg-ab-cool grid place-items-center">' + iconFor() + '</span>';
                return '<a href="' + item.url + '" class="flex items-center gap-3 p-2 rounded-2xl no-underline hover:bg-ab-cool">' +
                    avatar +
                    '<span class="flex flex-col min-w-0">' +
                        '<span class="text-sm font-semibold text-ab-navy truncate">' + item.name + '</span>' +
                        (item.meta ? '<span class="text-xs text-ab-muted truncate">' + item.meta + '</span>' : '') +
                    '</span>' +
                '</a>';
            }).join('');
            return '<div class="mb-2"><p class="px-2 text-xs font-semibold text-ab-muted mb-1">' + group.title + ' · ' + group.items.length + '</p>' + rows + '</div>';
        }).join('');
        results.classList.remove('hidden');
    }

    input.addEventListener('input', function () {
        clearTimeout(debounce);
        const value = input.value.trim();
        if (!value) {
            results.classList.add('hidden');
            return;
        }
        debounce = setTimeout(function () {
            fetch(suggestUrl + '?query=' + encodeURIComponent(value))
                .then(function (r) { return r.json(); })
                .then(function (data) { render(data.groups || []); })
                .catch(function () {});
        }, 250);
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#hero-search')) {
            results.classList.add('hidden');
        }
    });

    document.querySelectorAll('.ab-search-chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            input.value = chip.dataset.query;
            input.dispatchEvent(new Event('input'));
            input.focus();
        });
    });
})();
</script>
@endpush
