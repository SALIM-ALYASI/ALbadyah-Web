@extends('layouts.app')

@section('title', 'تعديل الخدمة السياحية - ' . $touristService->name_ar)
@section('page-title', 'تعديل الخدمة السياحية')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">تعديل الخدمة السياحية</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">تعديل بيانات الخدمة السياحية: {{ $touristService->name_ar }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tourist-services.show', $touristService->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">عرض التفاصيل</a>
            <a href="{{ route('tourist-services.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">العودة للقائمة</a>
        </div>
    </div>

    <div class="grid gap-6" style="grid-template-columns:minmax(0,2fr) minmax(220px,1fr)">
        <div class="bg-white border border-ab-border rounded-[22px] p-6 sm:p-8">
            <form action="{{ route('tourist-services.update', $touristService->id) }}" method="POST" id="editForm" class="flex flex-col gap-5">
                @csrf
                @method('PUT')

                <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(220px,1fr))">
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm font-semibold text-ab-navy">الاسم بالعربية *</span>
                        <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $touristService->name_ar) }}" required
                            class="w-full border {{ $errors->has('name_ar') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                        @error('name_ar') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm font-semibold text-ab-navy">الاسم بالإنجليزية *</span>
                        <input type="text" name="name_en" value="{{ old('name_en', $touristService->name_en) }}" required dir="ltr"
                            class="w-full border {{ $errors->has('name_en') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                        @error('name_en') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm font-semibold text-ab-navy">نوع الخدمة</span>
                        <select name="service_type_id" class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                            <option value="">اختر نوع الخدمة</option>
                            @foreach ($serviceTypes as $serviceType)
                                <option value="{{ $serviceType->id }}" @selected(old('service_type_id', $touristService->service_type_id) == $serviceType->id)>{{ $serviceType->name_ar }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm font-semibold text-ab-navy">المحافظة</span>
                        <select name="governorate_id" class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                            <option value="">اختر المحافظة</option>
                            @foreach ($governorates as $governorate)
                                <option value="{{ $governorate->id }}" @selected(old('governorate_id', $touristService->governorate_id) == $governorate->id)>{{ $governorate->name_ar }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm font-semibold text-ab-navy">الولاية</span>
                        <select name="wilayat_id" class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                            <option value="">اختر الولاية</option>
                            @foreach ($wilayats as $wilayat)
                                <option value="{{ $wilayat->id }}" @selected(old('wilayat_id', $touristService->wilayat_id) == $wilayat->id)>{{ $wilayat->name_ar }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">رابط الموقع الرسمي</span>
                    <input type="url" name="website_url" value="{{ old('website_url', $touristService->website_url) }}" dir="ltr"
                        class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">رابط صورة الخدمة</span>
                    <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $touristService->image_url) }}" dir="ltr"
                        onchange="AdminUI.previewImageUrl(this, 'service-edit-preview')"
                        class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                    @error('image_url') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <div class="p-4 rounded-2xl border {{ $touristService->is_active ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200' }}">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $touristService->is_active)) class="w-5 h-5 rounded border-ab-border-2">
                        <span class="text-sm font-bold text-ab-navy">نشر الخدمة (تظهر بالموقع العام)</span>
                    </label>
                    <p class="m-0 mt-2 text-xs text-ab-body">
                        الحالة الحالية: {{ $touristService->is_active ? 'منشورة' : 'غير منشورة' }} —
                        {{ $touristService->verification_status === 'approved' ? 'معتمدة' : 'قيد المراجعة (' . $touristService->verification_status . ')' }}.
                        تفعيل هذا الخيار يعتمد الخدمة تلقائيًا وينشرها فورًا.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-ab-border">
                    <a href="{{ route('tourist-services.show', $touristService->id) }}" class="px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">إلغاء</a>
                    <button type="submit" id="submitBtn" class="px-6 py-2.5 rounded-full bg-amber-500 text-white text-sm font-semibold">حفظ التعديلات</button>
                </div>
            </form>
        </div>

        <div class="flex flex-col gap-4">
            <div class="bg-white border border-ab-border rounded-[22px] p-5">
                <h3 class="m-0 text-sm font-bold text-ab-navy mb-3">صورة الخدمة</h3>
                <div id="service-edit-preview" class="w-full aspect-square rounded-2xl overflow-hidden bg-ab-cool grid place-items-center">
                    @if ($touristService->has_image)
                        <img src="{{ $touristService->image_url }}" alt="{{ $touristService->name_ar }}" class="w-full h-full object-cover">
                    @else
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.getElementById('editForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = 'جاري الحفظ...';
        setTimeout(() => { btn.disabled = false; btn.textContent = 'حفظ التعديلات'; }, 3000);
    });
</script>
@endpush
