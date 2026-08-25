<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - البادية</title>

    <link rel="icon" href="{{ asset('images/loogo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/loogo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/loogo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-ab-warm text-ab-navy antialiased">

    {{-- شريط تنقّل جانبي --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="AdminUI.closeSidebar()"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 right-0 z-50 w-72 bg-ab-navy flex flex-col transition-transform duration-300 translate-x-full lg:translate-x-0">
        <div class="flex items-center gap-3 px-6 py-6 border-b border-white/10">
            <span class="w-11 h-11 rounded-2xl bg-ab-sand/15 grid place-items-center text-ab-sand text-lg font-bold shrink-0">ب</span>
            <div class="flex flex-col leading-tight min-w-0">
                <span class="text-lg font-bold text-white">البادية</span>
                <span class="text-xs text-white/60">لوحة التحكم</span>
            </div>
            <button type="button" onclick="AdminUI.closeSidebar()" aria-label="إغلاق القائمة"
                class="ms-auto lg:hidden w-9 h-9 rounded-xl grid place-items-center text-white/70 hover:bg-white/10 shrink-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-4 flex flex-col gap-1">
            @php
                $navItems = [
                    ['route' => 'governorates.index', 'match' => 'governorates.*', 'label' => 'المحافظات',
                        'icon' => '<path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"></path>'],
                    ['route' => 'wilayats.index', 'match' => 'wilayats.*', 'label' => 'الولايات',
                        'icon' => '<path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle>'],
                    ['route' => 'tourist-sitesController.index', 'match' => 'tourist-sitesController.*', 'label' => 'المواقع السياحية',
                        'icon' => '<path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle>'],
                    ['route' => 'tourist-services.index', 'match' => 'tourist-services.*', 'label' => 'الخدمات السياحية',
                        'icon' => '<path d="M4 7h16M4 12h16M4 17h10"></path>'],
                    ['route' => 'map-candidates.index', 'match' => 'map-candidates.*', 'label' => 'مراجعة مرشّحات البوت',
                        'icon' => '<path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle>'],
                    ['route' => 'data-viewer.index', 'match' => 'data-viewer.*', 'label' => 'عرض جميع البيانات',
                        'icon' => '<ellipse cx="12" cy="5" rx="8" ry="3"></ellipse><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"></path>'],
                    ['route' => 'visit-stats.index', 'match' => 'visit-stats.*', 'label' => 'إحصائيات الزيارات',
                        'icon' => '<path d="M3 3v18h18M7 16v-4m5 4V8m5 8v-7"></path>'],
                ];
            @endphp
            @foreach ($navItems as $item)
                @php $isActive = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium no-underline transition {{ $isActive ? 'bg-white/12 text-white font-semibold' : 'text-white/70 hover:bg-white/8 hover:text-white' }}">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">{!! $item['icon'] !!}</svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="px-4 py-4 border-t border-white/10">
            <a href="{{ route('tourism.index') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium text-white/60 hover:bg-white/8 hover:text-white no-underline">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><path d="M15 3h6v6M10 14 21 3"></path></svg>
                عرض الموقع العام
            </a>
        </div>
    </aside>

    {{-- المحتوى الرئيسي --}}
    <div class="lg:me-72 min-h-screen flex flex-col">
        <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-ab-border">
            <div class="flex items-center gap-4 px-4 sm:px-6 py-4">
                <button type="button" onclick="AdminUI.openSidebar()" aria-label="فتح القائمة"
                    class="lg:hidden w-11 h-11 shrink-0 rounded-2xl border border-ab-border grid place-items-center text-ab-navy">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"></path></svg>
                </button>
                <h1 class="text-lg sm:text-xl font-bold text-ab-navy truncate">@yield('page-title', 'لوحة التحكم')</h1>

                <form action="{{ route('admin.logout') }}" method="POST" class="ms-auto shrink-0">
                    @csrf
                    <button type="submit" onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full border border-ab-border-2 text-ab-body text-sm font-semibold hover:border-red-300 hover:text-red-600">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><path d="M16 17l5-5-5-5M21 12H9"></path></svg>
                        <span class="hidden sm:inline">تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 max-w-[1400px] w-full mx-auto">
            <div id="admin-alerts" class="flex flex-col gap-3 mb-6">
                @if (session('success'))
                    <div class="admin-alert flex items-start gap-3 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="admin-alert flex items-start gap-3 px-5 py-4 rounded-2xl bg-red-50 border border-red-200 text-red-800">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="9"></circle><path d="M12 8v5M12 16h.01"></path></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>

    <script>
        window.AdminUI = (function () {
            function openSidebar() {
                document.getElementById('sidebar').classList.remove('translate-x-full');
                document.getElementById('sidebar-overlay').classList.remove('hidden');
            }
            function closeSidebar() {
                document.getElementById('sidebar').classList.add('translate-x-full');
                document.getElementById('sidebar-overlay').classList.add('hidden');
            }
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) closeSidebar();
            });

            function setPreview(previewId, src) {
                const box = document.getElementById(previewId);
                if (!box) return;
                box.innerHTML = src
                    ? '<img src="' + src + '" class="w-full h-full object-cover" alt="">'
                    : box.dataset.placeholder || '';
            }

            function previewImageFile(input, previewId) {
                const file = input.files && input.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function (e) { setPreview(previewId, e.target.result); };
                reader.readAsDataURL(file);
            }

            function previewImageUrl(input, previewId) {
                const url = input.value.trim();
                if (!url) { setPreview(previewId, null); return; }
                const test = new Image();
                test.onload = function () { setPreview(previewId, url); };
                test.onerror = function () {};
                test.src = url;
            }

            function openModal(id) {
                // نتحكم بالظهور عبر style.display لا صنف hidden، لأن ترتيب توليد
                // Tailwind لأصناف hidden مقابل grid/flex غير مضمون عند تواجدهما
                // معًا بنفس العنصر (قد يفوز flex/grid على hidden حسب ترتيب البناء).
                const modal = document.getElementById(id);
                if (modal) modal.style.display = 'grid';
            }
            function closeModal(id) {
                const modal = document.getElementById(id);
                if (modal) modal.style.display = 'none';
            }

            function showTab(groupId, tabKey) {
                const group = document.getElementById(groupId);
                if (!group) return;
                group.querySelectorAll('[data-tab-panel]').forEach(function (panel) {
                    panel.classList.toggle('hidden', panel.dataset.tabPanel !== tabKey);
                });
                group.querySelectorAll('[data-tab-btn]').forEach(function (btn) {
                    const active = btn.dataset.tabBtn === tabKey;
                    btn.classList.toggle('bg-ab-navy', active);
                    btn.classList.toggle('text-white', active);
                    btn.classList.toggle('text-ab-body', !active);
                });
            }

            return {
                openSidebar, closeSidebar,
                previewImageFile, previewImageUrl,
                openModal, closeModal,
                showTab,
            };
        })();

        setTimeout(function () {
            document.querySelectorAll('.admin-alert').forEach(function (el) {
                el.style.transition = 'opacity .4s ease';
                el.style.opacity = '0';
                setTimeout(function () { el.remove(); }, 400);
            });
        }, 5000);
    </script>
    @stack('scripts')
</body>
</html>
