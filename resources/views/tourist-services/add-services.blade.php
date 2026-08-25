@extends('layouts.app')

@section('title', 'إضافة خدمات للموقع')
@section('page-title', 'إضافة خدمات للموقع')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">إضافة خدمات للموقع</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">أضف خدمات متعددة للموقع: {{ $location->name_ar }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tourist-services.show', $location->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">عرض الموقع</a>
            <a href="{{ route('tourist-services.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">العودة للقائمة</a>
        </div>
    </div>

    <div class="bg-ab-navy rounded-[22px] p-6 mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="m-0 text-white font-bold">{{ $location->name_ar }}</h2>
            <p class="m-0 mt-1 text-white/70 text-sm" dir="ltr">{{ $location->name_en }}</p>
            <div class="flex flex-wrap gap-3 mt-2 text-xs text-white/60">
                @if ($location->governorate)<span>{{ $location->governorate->name_ar }}</span>@endif
                @if ($location->wilayat)<span>{{ $location->wilayat->name_ar }}</span>@endif
                @if ($location->serviceType)<span>{{ $location->serviceType->name_ar }}</span>@endif
            </div>
        </div>
        <div class="w-24 h-20 rounded-xl overflow-hidden bg-white/10 shrink-0">
            @if ($location->has_location_image)
                <img src="{{ $location->location_image_url }}" alt="{{ $location->name_ar }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full grid place-items-center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white border border-ab-border rounded-[22px] p-6 sm:p-8">
        <form action="{{ route('tourist-services.store-services', $location->id) }}" method="POST" enctype="multipart/form-data" id="servicesForm">
            @csrf

            <div id="services-container" class="flex flex-col gap-4">
                <div class="service-item border border-ab-border rounded-2xl p-5" data-service-index="0">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="m-0 text-sm font-bold text-ab-navy">الخدمة رقم <span class="service-number">1</span></h3>
                        <button type="button" class="remove-service inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 text-red-600 text-xs font-semibold" style="display:none">حذف الخدمة</button>
                    </div>

                    <div class="grid gap-4 mb-4" style="grid-template-columns:repeat(auto-fit, minmax(200px,1fr))">
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">اسم الخدمة بالعربية *</span>
                            <input type="text" name="services[0][name_ar]" required
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">اسم الخدمة بالإنجليزية *</span>
                            <input type="text" name="services[0][name_en]" required dir="ltr"
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
                        </label>
                    </div>

                    <div class="grid gap-4 mb-4" style="grid-template-columns:repeat(auto-fit, minmax(200px,1fr))">
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">نوع الخدمة (اختياري)</span>
                            <select name="services[0][service_type_id]" class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy">
                                <option value="">اختر نوع الخدمة</option>
                                @foreach ($serviceTypes as $serviceType)
                                    <option value="{{ $serviceType->id }}">{{ $serviceType->name_ar }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">رابط الموقع الرسمي</span>
                            <input type="url" name="services[0][website_url]" dir="ltr"
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
                        </label>
                    </div>

                    <x-admin.image-upload name="services[0][image_file]" url-name="services[0][image_url]" label="صورة الخدمة (اختياري)" />
                </div>
            </div>

            <div class="text-center my-6">
                <button type="button" id="addServiceBtn"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"></path></svg>
                    إضافة خدمة أخرى
                </button>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-ab-border">
                <a href="{{ route('tourist-services.show', $location->id) }}" class="px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">إلغاء</a>
                <button type="submit" id="submitBtn" class="px-6 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold">حفظ الخدمات</button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
    let serviceCount = 1;

    const serviceTypeOptions = `<option value="">اختر نوع الخدمة</option>` +
        @json($serviceTypes->map(fn($t) => ['id' => $t->id, 'name' => $t->name_ar]))
            .map(t => `<option value="${t.id}">${t.name}</option>`).join('');

    function serviceBlockHtml(index) {
        const uid = 'preview-services-' + index + '-' + Math.random().toString(36).slice(2, 8);
        return `
            <div class="service-item border border-ab-border rounded-2xl p-5" data-service-index="${index}">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="m-0 text-sm font-bold text-ab-navy">الخدمة رقم <span class="service-number">${index + 1}</span></h3>
                    <button type="button" class="remove-service inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 text-red-600 text-xs font-semibold">حذف الخدمة</button>
                </div>

                <div class="grid gap-4 mb-4" style="grid-template-columns:repeat(auto-fit, minmax(200px,1fr))">
                    <label class="flex flex-col gap-1.5">
                        <span class="text-xs font-semibold text-ab-body">اسم الخدمة بالعربية *</span>
                        <input type="text" name="services[${index}][name_ar]" required
                            class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-xs font-semibold text-ab-body">اسم الخدمة بالإنجليزية *</span>
                        <input type="text" name="services[${index}][name_en]" required dir="ltr"
                            class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
                    </label>
                </div>

                <div class="grid gap-4 mb-4" style="grid-template-columns:repeat(auto-fit, minmax(200px,1fr))">
                    <label class="flex flex-col gap-1.5">
                        <span class="text-xs font-semibold text-ab-body">نوع الخدمة (اختياري)</span>
                        <select name="services[${index}][service_type_id]" class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy">${serviceTypeOptions}</select>
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-xs font-semibold text-ab-body">رابط الموقع الرسمي</span>
                        <input type="url" name="services[${index}][website_url]" dir="ltr"
                            class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
                    </label>
                </div>

                <div class="flex flex-col gap-3">
                    <span class="text-sm font-semibold text-ab-navy">صورة الخدمة (اختياري)</span>
                    <div class="flex flex-col sm:flex-row gap-4 items-start">
                        <div id="${uid}" data-placeholder='<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" style="margin:auto"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>'
                            class="w-28 h-28 shrink-0 rounded-2xl overflow-hidden bg-ab-cool border border-ab-border grid place-items-center">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
                        </div>
                        <div class="flex-1 flex flex-col gap-3 w-full min-w-0">
                            <label class="flex flex-col gap-1.5">
                                <span class="text-xs text-ab-body">رفع من الجهاز</span>
                                <input type="file" name="services[${index}][image_file]" accept="image/*" onchange="AdminUI.previewImageFile(this, '${uid}')"
                                    class="text-sm text-ab-body file:me-3 file:px-4 file:py-2 file:rounded-full file:border-0 file:bg-ab-cool file:text-ab-navy file:font-semibold w-full border border-ab-border-2 rounded-2xl p-1.5">
                            </label>
                            <label class="flex flex-col gap-1.5">
                                <span class="text-xs text-ab-body">أو رابط صورة مباشر</span>
                                <input type="url" name="services[${index}][image_url]" placeholder="https://..." oninput="AdminUI.previewImageUrl(this, '${uid}')"
                                    class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    document.getElementById('addServiceBtn').addEventListener('click', function () {
        document.getElementById('services-container').insertAdjacentHTML('beforeend', serviceBlockHtml(serviceCount));
        serviceCount++;
        document.querySelectorAll('.remove-service').forEach(btn => { btn.style.display = 'inline-flex'; });
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-service');
        if (!btn) return;
        btn.closest('.service-item').remove();
        document.querySelectorAll('.service-item .service-number').forEach((el, i) => { el.textContent = i + 1; });
        const remaining = document.querySelectorAll('.service-item');
        if (remaining.length === 1) {
            remaining[0].querySelector('.remove-service').style.display = 'none';
        }
    });

    document.getElementById('servicesForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = 'جاري الحفظ...';
        setTimeout(() => { btn.disabled = false; btn.textContent = 'حفظ الخدمات'; }, 3000);
    });

    document.querySelector('input[name="services[0][name_ar]"]').focus();
</script>
@endpush
