@extends('layouts.app')

@section('title', 'إضافة موقع سياحي جديد')
@section('page-title', 'إضافة موقع سياحي جديد')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">إضافة موقع سياحي جديد</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">أدخل بيانات الموقع السياحي الجديد في النموذج أدناه</p>
        </div>
        <a href="{{ route('tourist-sitesController.index') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7M19 12H5"></path></svg>
            العودة للقائمة
        </a>
    </div>

    <div class="grid gap-6" style="grid-template-columns:minmax(0,2fr) minmax(240px,1fr)">
        <div class="bg-white border border-ab-border rounded-[22px] p-6 sm:p-8">
            <form action="{{ route('tourist-sitesController.store') }}" method="POST" enctype="multipart/form-data" id="touristSiteForm" class="flex flex-col gap-5">
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
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en') }}" required dir="ltr"
                            class="w-full border {{ $errors->has('name_en') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                        @error('name_en') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(220px,1fr))">
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
                    <span class="text-sm font-semibold text-ab-navy">الموقع الجغرافي</span>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="أدخل الموقع الجغرافي التفصيلي"
                        class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">رابط الموقع الرسمي</span>
                    <input type="url" name="website_url" value="{{ old('website_url') }}" placeholder="https://example.com" dir="ltr"
                        class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الوصف بالعربية *</span>
                    <textarea id="description_ar" name="description_ar" rows="4" required
                        class="w-full border {{ $errors->has('description_ar') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">{{ old('description_ar') }}</textarea>
                    @error('description_ar') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الوصف بالإنجليزية *</span>
                    <textarea id="description_en" name="description_en" rows="4" required dir="ltr"
                        class="w-full border {{ $errors->has('description_en') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">{{ old('description_en') }}</textarea>
                    @error('description_en') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الصورة المميزة (اختياري)</span>
                    <input type="file" name="featured_image" accept="image/*"
                        class="text-sm text-ab-body file:me-3 file:px-4 file:py-2 file:rounded-full file:border-0 file:bg-ab-cool file:text-ab-navy file:font-semibold w-full border border-ab-border-2 rounded-2xl p-1.5">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الصور الإضافية (اختياري، يمكن اختيار أكثر من صورة)</span>
                    <input type="file" name="images[]" accept="image/*" multiple
                        class="text-sm text-ab-body file:me-3 file:px-4 file:py-2 file:rounded-full file:border-0 file:bg-ab-cool file:text-ab-navy file:font-semibold w-full border border-ab-border-2 rounded-2xl p-1.5">
                </label>

                <label class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="w-5 h-5 rounded border-ab-border-2 text-ab-navy focus:ring-ab-teal">
                    <span class="text-sm font-semibold text-ab-navy">تفعيل الموقع السياحي (سيظهر للزوار)</span>
                </label>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-ab-border">
                    <a href="{{ route('tourist-sitesController.index') }}" class="px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">إلغاء</a>
                    <button type="submit" id="submitBtn" class="px-6 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold">حفظ الموقع السياحي</button>
                </div>
            </form>
        </div>

        <div class="bg-white border border-ab-border rounded-[22px] p-6 h-fit flex flex-col gap-4">
            <div class="flex items-start gap-3 bg-sky-50 border border-sky-200 rounded-2xl p-4 text-sky-800 text-sm">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01"></path><circle cx="12" cy="12" r="9"></circle></svg>
                <span><strong>نصيحة:</strong> بعد حفظ الموقع السياحي، يمكنك إضافة الصور من صفحة عرض الموقع.</span>
            </div>
            <div>
                <h4 class="text-sm font-bold text-ab-navy mb-2">الخطوات التالية</h4>
                <ol class="list-decimal list-inside text-sm text-ab-body flex flex-col gap-1">
                    <li>احفظ بيانات الموقع الأساسية</li>
                    <li>انتقل لصفحة عرض الموقع</li>
                    <li>أضف الصور من قسم إدارة الصور</li>
                </ol>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.getElementById('touristSiteForm').addEventListener('submit', function (e) {
        const required = ['name_ar', 'name_en', 'description_ar', 'description_en'];
        const missing = required.some(id => !document.getElementById(id).value.trim());
        if (missing) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
            return;
        }
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = 'جاري الحفظ...';
        setTimeout(() => { btn.disabled = false; btn.textContent = 'حفظ الموقع السياحي'; }, 10000);
    });
</script>
@endpush
