{{--
    فلتر مشترك لصفحات القوائم (المواقع/الخدمات).
    المتغيرات المتوقعة: $action, $selects (name/label/options/selected), $countLabel, $isFiltered, $showViewToggle
--}}
@php
    $showViewToggle = $showViewToggle ?? true;
@endphp

<form action="{{ $action }}" method="GET" class="bg-ab-warm border border-ab-border rounded-[30px] p-5 md:p-6 flex flex-col gap-5">
    <div class="relative">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8A9B9E" stroke-width="2.5" stroke-linecap="round" class="absolute top-1/2 -translate-y-1/2 right-4 pointer-events-none"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث بالاسم..."
            class="w-full bg-white border border-ab-border rounded-full pr-12 pl-5 text-sm text-ab-navy placeholder:text-ab-muted" style="min-height:48px">
    </div>

    <div class="grid gap-3.5" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))">
        @foreach ($selects as $select)
            <label class="flex flex-col gap-1.5">
                <span class="text-[13px] font-semibold text-ab-body">{{ $select['label'] }}</span>
                <select name="{{ $select['name'] }}" onchange="this.form.submit()"
                    class="bg-white border border-ab-border rounded-full px-4 text-sm text-ab-navy" style="min-height:44px">
                    <option value="">الكل</option>
                    @foreach ($select['options'] as $id => $label)
                        <option value="{{ $id }}" @selected((string) $select['selected'] === (string) $id)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
        @endforeach
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="text-sm text-ab-body">{{ $countLabel }}</span>
            @if ($isFiltered)
                <a href="{{ $action }}" class="text-sm font-semibold text-ab-navy underline">إزالة الفلاتر</a>
            @endif
        </div>

        @if ($showViewToggle)
            <div data-view-toggle class="inline-flex items-center border border-ab-border rounded-full p-1 bg-white">
                <button type="button" data-view="cards" class="ab-view-btn px-4 py-2 rounded-full text-sm font-semibold bg-ab-navy text-white">بطاقات</button>
                <button type="button" data-view="map" class="ab-view-btn px-4 py-2 rounded-full text-sm font-semibold text-ab-navy">خريطة</button>
            </div>
        @endif
    </div>
</form>

@once
    @push('scripts')
        <script>
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('[data-view-toggle] button');
                if (!btn) return;
                const root = btn.closest('[data-listing]');
                if (!root) return;
                const view = btn.dataset.view;
                root.querySelectorAll('[data-view-panel]').forEach(function (panel) {
                    panel.classList.toggle('hidden', panel.dataset.viewPanel !== view);
                });
                root.querySelectorAll('[data-view-toggle] .ab-view-btn').forEach(function (b) {
                    const active = b === btn;
                    b.classList.toggle('bg-ab-navy', active);
                    b.classList.toggle('text-white', active);
                    b.classList.toggle('text-ab-navy', !active);
                });
            });
        </script>
    @endpush
@endonce
