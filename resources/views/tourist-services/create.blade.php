@extends('layouts.app')

@section('title', 'إضافة خدمة سياحية سريعة')
@section('page-title', 'إضافة خدمة سياحية سريعة')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">إضافة خدمة سياحية سريعة</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">أدخل بيانات الخدمة السياحية في النموذج أدناه</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tourist-services.create-location') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">إضافة موقع خدمة جديد</a>
            <a href="{{ route('tourist-services.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">العودة للقائمة</a>
        </div>
    </div>

    <div class="max-w-2xl mx-auto bg-white border border-ab-border rounded-[22px] p-6 sm:p-8">
        <form action="{{ route('tourist-services.store') }}" method="POST" id="serviceForm" enctype="multipart/form-data" class="flex flex-col gap-5">
            @csrf

            <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(220px,1fr))">
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الاسم بالعربية *</span>
                    <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required
                        class="w-full border {{ $errors->has('name_ar') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                    @error('name_ar') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الاسم بالإنجليزية *</span>
                    <input type="text" name="name_en" value="{{ old('name_en') }}" required dir="ltr"
                        class="w-full border {{ $errors->has('name_en') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                    @error('name_en') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">نوع الخدمة (اختياري)</span>
                    <select name="service_type_id" class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                        <option value="">اختر نوع الخدمة</option>
                        @foreach ($serviceTypes as $serviceType)
                            <option value="{{ $serviceType->id }}" @selected(old('service_type_id') == $serviceType->id)>{{ $serviceType->name_ar }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">المحافظة</span>
                    <select name="governorate_id" class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                        <option value="">اختر المحافظة</option>
                        @foreach ($governorates as $governorate)
                            <option value="{{ $governorate->id }}" @selected(old('governorate_id') == $governorate->id)>{{ $governorate->name_ar }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الولاية</span>
                    <select name="wilayat_id" class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                        <option value="">اختر الولاية</option>
                        @foreach ($wilayats as $wilayat)
                            <option value="{{ $wilayat->id }}" @selected(old('wilayat_id') == $wilayat->id)>{{ $wilayat->name_ar }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-semibold text-ab-navy">رابط الموقع الرسمي</span>
                <input type="url" name="website_url" value="{{ old('website_url') }}" placeholder="https://example.com" dir="ltr"
                    class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                @error('website_url') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </label>

            <x-admin.image-upload name="image_file" url-name="image_url" label="صورة الخدمة (اختياري)" />

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-ab-border">
                <a href="{{ route('tourist-services.index') }}" class="px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">إلغاء</a>
                <button type="submit" id="submitBtn" class="px-6 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold">حفظ الخدمة السياحية</button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
    document.getElementById('serviceForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = 'جاري الحفظ...';
        setTimeout(() => { btn.disabled = false; btn.textContent = 'حفظ الخدمة السياحية'; }, 3000);
    });
</script>
@endpush
