@extends('layouts.app')

@section('title', 'مراجعة مرشح البوت')
@section('page-title', 'مراجعة مرشّح البوت')

@php($isPublished = $candidate->status === 'published')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">مراجعة المرشح #{{ $candidate->id }}</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">راجع جميع الحقول قبل الحفظ في الموقع.</p>
        </div>
        <a href="{{ route('map-candidates.index') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">العودة للقائمة</a>
    </div>

    @if ($errors->any())
        <div class="mb-6 flex flex-col gap-1 px-5 py-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm">
            <strong>لم يتم الحفظ:</strong>
            @foreach ($errors->all() as $error)<span>{{ $error }}</span>@endforeach
        </div>
    @endif

    @if ($isPublished)
        <div class="mb-6 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
            نُقل هذا المرشح إلى <strong>{{ $candidate->published_table }}</strong>
            برقم <strong>#{{ $candidate->published_id }}</strong> بتاريخ {{ optional($candidate->published_at)->format('Y-m-d H:i') }}.
        </div>
    @endif

    <form method="POST" action="{{ route('map-candidates.process', $candidate->id) }}" id="candidate-form">
        @csrf

        <div class="grid gap-6" style="grid-template-columns:minmax(0,2fr) minmax(260px,1fr)">
            <div class="flex flex-col gap-6">
                <div class="bg-white border border-ab-border rounded-[22px] p-6">
                    <h2 class="m-0 text-sm font-bold text-ab-navy mb-4">المعلومات الأساسية</h2>
                    <div class="grid gap-4" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">نوع السجل</span>
                            <select name="category" id="category" required @disabled($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy disabled:bg-ab-warm disabled:text-ab-muted">
                                <option value="site" @selected(old('category', $candidate->category) === 'site')>موقع سياحي</option>
                                <option value="service" @selected(old('category', $candidate->category) === 'service')>خدمة سياحية</option>
                            </select>
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">الاسم العربي</span>
                            <input name="name_ar" value="{{ old('name_ar', $candidate->name_ar) }}" required @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">الاسم الإنجليزي</span>
                            <input name="name_en" dir="ltr" value="{{ old('name_en', $candidate->name_en) }}" required @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">
                        </label>
                    </div>

                    <div class="grid gap-4 mt-4" style="grid-template-columns:repeat(auto-fit, minmax(220px,1fr))">
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">الوصف العربي</span>
                            <textarea name="description_ar" rows="6" @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">{{ old('description_ar', $candidate->description_ar) }}</textarea>
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">الوصف الإنجليزي</span>
                            <textarea name="description_en" rows="6" dir="ltr" @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">{{ old('description_en', $candidate->description_en) }}</textarea>
                        </label>
                    </div>

                    <div class="grid gap-4 mt-4" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">التصنيف الفرعي</span>
                            <input name="subtype" value="{{ old('subtype', $candidate->subtype) }}" @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">الهاتف</span>
                            <input name="phone" dir="ltr" value="{{ old('phone', $candidate->phone) }}" @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">الموقع الإلكتروني</span>
                            <input name="website" dir="ltr" value="{{ old('website', $candidate->website) }}" @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">ساعات العمل</span>
                            <input name="opening_hours" value="{{ old('opening_hours', $candidate->opening_hours) }}" @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">العنوان / المنطقة</span>
                            <input name="address_ar" value="{{ old('address_ar', $candidate->address_ar) }}" @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">رابط الصورة</span>
                            <input name="image_url" dir="ltr" value="{{ old('image_url', $candidate->image_url) }}" @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">
                        </label>
                    </div>
                </div>

                <div class="bg-white border border-ab-border rounded-[22px] p-6">
                    <h2 class="m-0 text-sm font-bold text-ab-navy mb-4">الموقع والتصنيف</h2>
                    <div class="grid gap-4" style="grid-template-columns:repeat(auto-fit, minmax(200px,1fr))">
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">المحافظة</span>
                            <select name="governorate_id" id="governorate_id" @disabled($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy disabled:bg-ab-warm">
                                <option value="">تُحدد تلقائيًا من الولاية</option>
                                @foreach ($governorates as $governorate)
                                    <option value="{{ $governorate->id }}" @selected((string) old('governorate_id', $candidate->governorate_id) === (string) $governorate->id)>{{ $governorate->name_ar }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">الولاية</span>
                            <select name="wilayat_id" id="wilayat_id" required @disabled($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy disabled:bg-ab-warm">
                                <option value="">اختر الولاية</option>
                                @foreach ($wilayats as $wilayat)
                                    <option value="{{ $wilayat->id }}" data-governorate="{{ $wilayat->governorate_id }}" @selected((string) old('wilayat_id', $candidate->wilayat_id) === (string) $wilayat->id)>{{ $wilayat->name_ar }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="site-field flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">تصنيف الموقع</span>
                            <select name="tourist_site_category_id" @disabled($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy disabled:bg-ab-warm">
                                <option value="">غير محدد</option>
                                @foreach ($siteCategories as $category)
                                    <option value="{{ $category->id }}" @selected((string) old('tourist_site_category_id', $candidate->tourist_site_category_id) === (string) $category->id)>{{ $category->name_ar }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="service-field flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">نوع الخدمة</span>
                            <select name="service_type_id" @disabled($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy disabled:bg-ab-warm">
                                <option value="">غير محدد</option>
                                @foreach ($serviceTypes as $type)
                                    <option value="{{ $type->id }}" @selected((string) old('service_type_id', $candidate->service_type_id) === (string) $type->id)>{{ $type->name_ar }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">خط العرض</span>
                            <input type="number" step="0.0000001" name="latitude" dir="ltr" value="{{ old('latitude', $candidate->latitude) }}" required @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-ab-body">خط الطول</span>
                            <input type="number" step="0.0000001" name="longitude" dir="ltr" value="{{ old('longitude', $candidate->longitude) }}" required @readonly($isPublished)
                                class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy read-only:bg-ab-warm">
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div class="bg-white border border-ab-border rounded-[22px] p-6">
                    <h2 class="m-0 text-sm font-bold text-ab-navy mb-4">المعاينة والثقة</h2>
                    @if ($candidate->image_url && !$candidate->image_is_placeholder)
                        <img src="{{ $candidate->image_url }}" alt="معاينة" class="w-full max-h-[220px] object-cover rounded-2xl mb-4">
                    @else
                        <div class="h-44 rounded-2xl bg-ab-cool grid place-items-center mb-4">
                            <div class="text-center">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
                                <span class="text-xs text-ab-muted">لا توجد صورة موثوقة</span>
                            </div>
                        </div>
                    @endif
                    <div class="flex flex-col gap-2 text-sm">
                        <div class="flex items-center justify-between"><span class="text-ab-body">درجة الثقة</span><strong class="text-ab-navy">{{ $candidate->overall_confidence !== null ? round($candidate->overall_confidence * 100) . '%' : '—' }}</strong></div>
                        <div class="flex items-center justify-between"><span class="text-ab-body">الحالة</span><strong class="text-ab-navy">{{ $candidate->status }}</strong></div>
                        <div class="flex items-center justify-between"><span class="text-ab-body">OSM</span><code class="text-xs text-ab-navy">{{ $candidate->osm_type }}/{{ $candidate->osm_id }}</code></div>
                    </div>
                    @if ($candidate->latitude !== null && $candidate->longitude !== null)
                        <a target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query={{ $candidate->latitude }},{{ $candidate->longitude }}"
                            class="mt-4 w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-ab-teal text-white text-sm font-semibold no-underline">فتح في خرائط جوجل</a>
                    @endif
                </div>

                <div class="bg-white border border-ab-border rounded-[22px] p-6">
                    <h2 class="m-0 text-sm font-bold text-ab-navy mb-4">المصادر</h2>
                    <div class="flex flex-col gap-2 max-h-[420px] overflow-y-auto">
                        @forelse ($candidate->sources ?: [] as $source)
                            <div class="border border-ab-border rounded-xl p-3 text-sm">
                                <strong class="text-ab-navy">{{ $source['type'] ?? 'مصدر' }}</strong>
                                @if (isset($source['field']))<span class="text-ab-muted"> — {{ $source['field'] }}</span>@endif
                                @if (isset($source['confidence']))<span class="ms-2 px-2 py-0.5 rounded-full bg-ab-cool text-ab-navy text-xs">{{ round($source['confidence'] * 100) }}%</span>@endif
                                @if (!empty($source['url']))
                                    <div class="mt-1"><a href="{{ $source['url'] }}" target="_blank" rel="noopener" class="text-xs text-ab-teal break-all underline">{{ $source['url'] }}</a></div>
                                @endif
                            </div>
                        @empty
                            <p class="m-0 text-sm text-ab-muted">لا توجد مصادر مسجلة.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @unless ($isPublished)
            <div class="sticky bottom-3 z-10 mt-6 bg-white border border-ab-border rounded-[22px] p-5 shadow-[0_14px_36px_rgba(36,59,68,0.16)] flex flex-wrap items-center justify-between gap-3">
                <button type="submit" name="action" value="save"
                    class="px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold">حفظ التعديلات فقط</button>
                <div class="flex flex-wrap gap-2">
                    <button type="submit" name="action" value="save_review"
                        class="px-5 py-2.5 rounded-full bg-amber-500 text-white text-sm font-semibold">حفظ في الموقع للمراجعة</button>
                    <button type="submit" name="action" value="publish" onclick="return confirm('سيظهر هذا السجل للعامة مباشرة. هل راجعت الاسم والوصف والموقع والمصادر؟')"
                        class="px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold">حفظ ونشر للعامة</button>
                </div>
            </div>
        @endunless
    </form>

    @unless ($isPublished)
        <form method="POST" action="{{ route('map-candidates.reject', $candidate->id) }}" onsubmit="return confirm('هل تريد رفض هذا المرشح؟')"
            class="mt-6 bg-white border border-red-200 rounded-[22px] p-5 flex flex-wrap items-end gap-3">
            @csrf
            <label class="flex-1 min-w-[220px] flex flex-col gap-1.5">
                <span class="text-xs font-semibold text-ab-body">سبب الرفض (اختياري)</span>
                <input name="rejected_reason" placeholder="مثال: مكرر أو ليس معلمًا سياحيًا"
                    class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
            </label>
            <button class="px-5 py-2.5 rounded-full bg-red-50 text-red-600 text-sm font-semibold">رفض المرشح</button>
        </form>
    @endunless

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const category = document.getElementById('category');
        const governorate = document.getElementById('governorate_id');
        const wilayat = document.getElementById('wilayat_id');

        function toggleCategoryFields() {
            document.querySelectorAll('.site-field').forEach(el => el.style.display = category.value === 'site' ? '' : 'none');
            document.querySelectorAll('.service-field').forEach(el => el.style.display = category.value === 'service' ? '' : 'none');
        }
        function filterWilayats() {
            const selected = governorate.value;
            Array.from(wilayat.options).forEach(option => {
                if (!option.value) return;
                option.hidden = selected && option.dataset.governorate !== selected;
            });
        }
        if (category && !category.disabled) category.addEventListener('change', toggleCategoryFields);
        if (governorate && !governorate.disabled) governorate.addEventListener('change', filterWilayats);
        toggleCategoryFields();
        filterWilayats();
    });
</script>
@endpush
