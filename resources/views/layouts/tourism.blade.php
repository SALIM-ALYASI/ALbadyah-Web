<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'اكتشف جمال سلطنة عُمان - مواقع سياحية رائعة وخدمات متميزة')">
    <meta name="keywords" content="عُمان، سياحة، مواقع سياحية، خدمات سياحية، محافظات، ولايات">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'البادية - السياحة في عُمان')</title>

    <link rel="icon" href="{{ asset('images/loogo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/loogo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/loogo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-white text-ab-navy antialiased">

    {{-- الهيدر --}}
    <header class="sticky top-0 z-40 bg-white/92 backdrop-blur-md border-b border-ab-border">
        <div class="max-w-[1240px] mx-auto px-5 py-3.5 flex items-center gap-6">
            <a href="{{ route('tourism.index') }}" class="flex items-center gap-3 shrink-0 no-underline">
                <span class="w-[46px] h-[46px] rounded-2xl bg-ab-navy grid place-items-center text-ab-sand text-[22px] font-bold leading-none">ب</span>
                <span class="flex flex-col leading-tight">
                    <span class="text-xl font-bold text-ab-navy">البادية</span>
                    <span class="text-xs font-normal text-ab-teal">السياحة في عُمان</span>
                </span>
            </a>

            <nav class="ab-desk-nav items-center gap-1.5 ms-auto">
                <a href="{{ route('tourism.index') }}"
                    class="px-4 py-2.5 rounded-full text-[15px] no-underline text-ab-navy {{ request()->routeIs('tourism.index') ? 'font-semibold bg-ab-cool' : 'font-medium' }}">الرئيسية</a>
                <a href="{{ route('tourism.governorates') }}"
                    class="px-4 py-2.5 rounded-full text-[15px] no-underline text-ab-navy {{ request()->routeIs('tourism.governorates*') || request()->routeIs('tourism.governorate') || request()->routeIs('tourism.wilayats*') || request()->routeIs('tourism.wilayat') ? 'font-semibold bg-ab-cool' : 'font-medium' }}">استكشف عُمان</a>
                <a href="{{ route('tourism.tourist-sites') }}"
                    class="px-4 py-2.5 rounded-full text-[15px] no-underline text-ab-navy {{ request()->routeIs('tourism.tourist-sites*') || request()->routeIs('tourism.tourist-site') ? 'font-semibold bg-ab-cool' : 'font-medium' }}">المواقع السياحية</a>
                <a href="{{ route('tourism.tourist-services') }}"
                    class="px-4 py-2.5 rounded-full text-[15px] no-underline text-ab-navy {{ request()->routeIs('tourism.tourist-services*') || request()->routeIs('tourism.tourist-service') ? 'font-semibold bg-ab-cool' : 'font-medium' }}">الخدمات</a>
                <a href="{{ route('tourism.about') }}"
                    class="px-4 py-2.5 rounded-full text-[15px] no-underline text-ab-navy {{ request()->routeIs('tourism.about') ? 'font-semibold bg-ab-cool' : 'font-medium' }}">عن البادية</a>
                <a href="{{ route('tourism.search') }}"
                    class="ms-2.5 inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-ab-navy text-white text-[15px] font-semibold no-underline">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
                    بحث
                </a>
            </nav>

            <a href="{{ route('tourism.search') }}" aria-label="بحث"
                class="ab-burger ms-auto w-[46px] h-[46px] border border-ab-border rounded-2xl bg-white grid place-items-center text-ab-navy">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
            </a>
        </div>
    </header>

    {{-- المحتوى --}}
    <main>
        @yield('content')
    </main>

    {{-- الفوتر --}}
    <footer class="ab-pad-dock pt-[clamp(40px,6vw,72px)] px-5">
        <div class="max-w-[1240px] mx-auto grid gap-9" style="grid-template-columns: repeat(auto-fit, minmax(230px, 1fr))">
            <div class="flex flex-col gap-4 max-w-[360px]">
                <span class="flex items-center gap-3">
                    <span class="w-[46px] h-[46px] rounded-2xl bg-ab-navy grid place-items-center text-ab-sand text-[22px] font-bold">ب</span>
                    <span class="flex flex-col leading-tight">
                        <span class="text-[19px] font-bold text-ab-navy">البادية</span>
                        <span class="text-xs text-ab-teal">السياحة في عُمان</span>
                    </span>
                </span>
                <p class="m-0 text-[14.5px] font-light leading-relaxed text-ab-body">اكتشف جمال سلطنة عُمان — تجربة سياحية عبر تراث عُمان العريق وطبيعتها الساحرة.</p>
                <div class="flex gap-2.5">
                    <a href="https://x.com/alyasi_mbrmj?s=21" target="_blank" rel="noopener" aria-label="X" class="w-[42px] h-[42px] border border-ab-border rounded-full grid place-items-center text-[#3C5760] no-underline">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3H21l-6.5 7.4L21.6 21h-5.9l-4.2-5.6L6.6 21H4.4l6.8-7.7L4 3h6l3.9 5.2L18.9 3Z"></path></svg>
                    </a>
                    <a href="https://www.instagram.com/alyasi_mbrmj" target="_blank" rel="noopener" aria-label="Instagram" class="w-[42px] h-[42px] border border-ab-border rounded-full grid place-items-center text-[#3C5760] no-underline">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><rect x="3.5" y="3.5" width="17" height="17" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17" cy="7" r="1.1" fill="currentColor"></circle></svg>
                    </a>
                    <a href="https://wa.me/96871568883" target="_blank" rel="noopener" aria-label="WhatsApp" class="w-[42px] h-[42px] border border-ab-border rounded-full grid place-items-center text-[#3C5760] no-underline">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20.5 11.6a8.5 8.5 0 0 1-12.6 7.4L3.5 20.5l1.6-4.3A8.5 8.5 0 1 1 20.5 11.6Z"></path><path d="M8.8 9.2c0 3 2.2 5.2 5.2 5.2l1.2-1.4-1.9-.9-1 .8a4.4 4.4 0 0 1-2-2l.8-1-.9-1.9-1.4 1.2Z"></path></svg>
                    </a>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <span class="text-[15px] font-bold text-ab-navy">روابط سريعة</span>
                <a href="{{ route('tourism.index') }}" class="text-[14.5px] text-ab-body no-underline">الرئيسية</a>
                <a href="{{ route('tourism.governorates') }}" class="text-[14.5px] text-ab-body no-underline">المحافظات</a>
                <a href="{{ route('tourism.tourist-sites') }}" class="text-[14.5px] text-ab-body no-underline">المواقع السياحية</a>
                <a href="{{ route('tourism.tourist-services') }}" class="text-[14.5px] text-ab-body no-underline">الخدمات السياحية</a>
                <a href="{{ route('tourism.search') }}" class="text-[14.5px] text-ab-body no-underline">البحث</a>
            </div>

            <div class="flex flex-col gap-3">
                <span class="text-[15px] font-bold text-ab-navy">معلومات الاتصال</span>
                <a href="https://maps.app.goo.gl/VMs4iu4cCwCa5Q4o7" target="_blank" rel="noopener" class="text-[14.5px] text-ab-body no-underline">الياسي للبرمجيات</a>
                <a href="mailto:alyasiforchargers@gmail.com" class="text-[14.5px] text-ab-body no-underline" dir="ltr">alyasiforchargers@gmail.com</a>
                <a href="https://wa.me/96871568883" target="_blank" rel="noopener" class="text-[14.5px] text-ab-body no-underline" dir="ltr">+968 7156 8883</a>
            </div>

            <div class="flex flex-col gap-3 max-w-[320px]">
                <span class="text-[15px] font-bold text-ab-navy">اشترك في النشرة الإخبارية</span>
                <span class="text-sm font-light text-ab-body leading-relaxed">احصل على آخر الأخبار والعروض السياحية</span>
                <span class="flex items-center gap-2 bg-ab-warm border border-ab-border rounded-full p-1.5">
                    <input type="email" placeholder="بريدك الإلكتروني" class="flex-1 min-w-0 border-0 bg-transparent outline-none text-sm text-ab-navy px-3.5 py-2.5" />
                    <button type="button" class="shrink-0 px-5 py-2.5 border-0 rounded-full bg-ab-navy text-white text-sm font-semibold cursor-pointer">اشتراك</button>
                </span>
            </div>
        </div>

        <div class="max-w-[1240px] mx-auto mt-9 pt-[22px] border-t border-ab-border flex flex-wrap gap-3.5 justify-between text-[13.5px] text-ab-muted-2">
            <span>© {{ date('Y') }} البادية. جميع الحقوق محفوظة.</span>
            <span class="flex gap-5">
                <a href="#" class="text-ab-muted-2 no-underline">سياسة الخصوصية</a>
                <a href="#" class="text-ab-muted-2 no-underline">شروط الاستخدام</a>
            </span>
        </div>
    </footer>

    {{-- شريط التنقل السفلي للجوال --}}
    <nav class="ab-dock fixed inset-x-3.5 bottom-3.5 z-50 grid-cols-4 gap-1 bg-white/95 backdrop-blur-lg border border-ab-border rounded-[26px] p-2 shadow-[0_14px_36px_rgba(36,59,68,0.16)]" aria-label="التنقل السريع">
        @php
            $dockActive = request()->routeIs('tourism.index') ? 'home'
                : ((request()->routeIs('tourism.governorates*') || request()->routeIs('tourism.governorate') || request()->routeIs('tourism.wilayats*') || request()->routeIs('tourism.wilayat')) ? 'explore'
                : (request()->routeIs('tourism.search*') ? 'search'
                : ((request()->routeIs('tourism.tourist-services*') || request()->routeIs('tourism.tourist-service')) ? 'services' : null)));
        @endphp
        <a href="{{ route('tourism.index') }}" class="flex flex-col items-center gap-1 py-2.5 px-1 rounded-[18px] no-underline min-h-14 text-[11.5px] {{ $dockActive === 'home' ? 'bg-ab-cool text-ab-navy font-semibold' : 'text-ab-body font-medium' }}">
            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="m4 11 8-7 8 7v8a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19v-8Z"></path></svg>
            الرئيسية
        </a>
        <a href="{{ route('tourism.governorates') }}" class="flex flex-col items-center gap-1 py-2.5 px-1 rounded-[18px] no-underline min-h-14 text-[11.5px] {{ $dockActive === 'explore' ? 'bg-ab-cool text-ab-navy font-semibold' : 'text-ab-body font-medium' }}">
            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
            استكشف
        </a>
        <a href="{{ route('tourism.search') }}" class="flex flex-col items-center gap-1 py-2.5 px-1 rounded-[18px] no-underline min-h-14 text-[11.5px] {{ $dockActive === 'search' ? 'bg-ab-cool text-ab-navy font-semibold' : 'text-ab-body font-medium' }}">
            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
            بحث
        </a>
        <a href="{{ route('tourism.tourist-services') }}" class="flex flex-col items-center gap-1 py-2.5 px-1 rounded-[18px] no-underline min-h-14 text-[11.5px] {{ $dockActive === 'services' ? 'bg-ab-cool text-ab-navy font-semibold' : 'text-ab-body font-medium' }}">
            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"></path></svg>
            الخدمات
        </a>
    </nav>

    @stack('scripts')

    {{-- تتبّع الزيارات --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!sessionStorage.getItem('visit_recorded')) {
                trackVisit();
            }
        });

        function trackVisit() {
            fetch('https://ipapi.co/json/')
                .then(response => response.json())
                .then(data => sendVisit(data.country_name || 'Unknown', data.city || 'Unknown'))
                .catch(() => sendVisit('Unknown', 'Unknown'));
        }

        function sendVisit(country, city) {
            fetch('/save-visit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ country, city })
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        sessionStorage.setItem('visit_recorded', 'true');
                    }
                })
                .catch(error => console.error('Error recording visit:', error));
        }
    </script>

</body>

</html>
