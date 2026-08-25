@extends('layouts.app')

@section('title', 'تعديل الموقع السياحي - ' . $touristSite->name_ar)
@section('page-title', 'تعديل الموقع السياحي')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">تعديل الموقع السياحي</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">تعديل بيانات الموقع السياحي: {{ $touristSite->name_ar }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tourist-sitesController.show', $touristSite->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">عرض التفاصيل</a>
            <a href="{{ route('tourist-sitesController.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">العودة للقائمة</a>
        </div>
    </div>

    <div class="grid gap-6" style="grid-template-columns:minmax(0,2fr) minmax(240px,1fr)">
        <div class="bg-white border border-ab-border rounded-[22px] p-6 sm:p-8">
            {{-- ملاحظة: الرابط الصحيح لمسار التحديث هو tourist-sitesController.update
                 (اسم المورد المسجّل بـroutes/web.php) --}}
            <form action="{{ route('tourist-sitesController.update', $touristSite->id) }}" method="POST" id="editForm" class="flex flex-col gap-5">
                @csrf
                @method('PUT')

                <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(220px,1fr))">
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm font-semibold text-ab-navy">الاسم بالعربية *</span>
                        <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $touristSite->name_ar) }}" required
                            class="w-full border {{ $errors->has('name_ar') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                        @error('name_ar') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm font-semibold text-ab-navy">الاسم بالإنجليزية *</span>
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en', $touristSite->name_en) }}" required dir="ltr"
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
                                <option value="{{ $governorate->id }}" @selected(old('governorate_id', $touristSite->governorate_id) == $governorate->id)>{{ $governorate->name_ar }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm font-semibold text-ab-navy">الولاية</span>
                        <select name="wilayat_id" class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                            <option value="">اختر الولاية</option>
                            @foreach ($wilayats as $wilayat)
                                <option value="{{ $wilayat->id }}" @selected(old('wilayat_id', $touristSite->wilayat_id) == $wilayat->id)>{{ $wilayat->name_ar }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الموقع الجغرافي</span>
                    <input type="text" name="location" value="{{ old('location', $touristSite->location) }}"
                        class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">رابط الموقع الرسمي</span>
                    <input type="url" name="website_url" value="{{ old('website_url', $touristSite->website_url) }}" dir="ltr"
                        class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الوصف بالعربية *</span>
                    <textarea id="description_ar" name="description_ar" rows="4" required
                        class="w-full border {{ $errors->has('description_ar') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">{{ old('description_ar', $touristSite->description_ar) }}</textarea>
                    @error('description_ar') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الوصف بالإنجليزية *</span>
                    <textarea id="description_en" name="description_en" rows="4" required dir="ltr"
                        class="w-full border {{ $errors->has('description_en') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">{{ old('description_en', $touristSite->description_en) }}</textarea>
                    @error('description_en') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <div class="p-4 rounded-2xl border {{ $touristSite->is_active ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200' }}">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $touristSite->is_active)) class="w-5 h-5 rounded border-ab-border-2">
                        <span class="text-sm font-bold text-ab-navy">نشر الموقع (يظهر بالموقع العام)</span>
                    </label>
                    <p class="m-0 mt-2 text-xs text-ab-body">
                        الحالة الحالية: {{ $touristSite->is_active ? 'منشور' : 'غير منشور' }} —
                        {{ $touristSite->verification_status === 'approved' ? 'معتمد' : 'قيد المراجعة (' . $touristSite->verification_status . ')' }}.
                        تفعيل هذا الخيار يعتمد الموقع تلقائيًا وينشره فورًا.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-ab-border">
                    <a href="{{ route('tourist-sitesController.show', $touristSite->id) }}" class="px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">إلغاء</a>
                    <button type="submit" id="submitBtn" class="px-6 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold">حفظ التعديلات</button>
                </div>
            </form>
        </div>

        <div class="bg-white border border-ab-border rounded-[22px] p-6 h-fit flex flex-col gap-4">
            <h3 class="m-0 text-sm font-bold text-ab-navy">إدارة الصور</h3>
            @if ($touristSite->images->count() > 0)
                <div class="flex items-center justify-between">
                    <span class="text-xs text-ab-muted">الصور الحالية ({{ $touristSite->images->count() }})</span>
                    <a href="{{ route('tourist-sitesController.show', $touristSite->id) }}" class="text-xs font-semibold text-ab-navy underline">إدارة الصور</a>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($touristSite->images->take(4) as $image)
                        <img src="{{ $image->image_url }}" alt="{{ $touristSite->name_ar }}" class="w-full h-16 object-cover rounded-xl">
                    @endforeach
                </div>
                @if ($touristSite->images->count() > 4)
                    <span class="text-xs text-ab-muted">و {{ $touristSite->images->count() - 4 }} صورة أخرى...</span>
                @endif
            @else
                <div class="text-center py-4">
                    <p class="m-0 text-sm text-ab-muted mb-3">لا توجد صور للموقع السياحي</p>
                    <a href="{{ route('tourist-sitesController.show', $touristSite->id) }}" class="inline-flex px-4 py-2 rounded-full bg-ab-navy text-white text-xs font-semibold no-underline">إضافة صور</a>
                </div>
            @endif
            <div class="flex items-start gap-2 bg-sky-50 border border-sky-200 rounded-2xl p-3 text-sky-800 text-xs">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="9"></circle><path d="M12 8v5M12 16h.01"></path></svg>
                <span>يمكنك إدارة الصور من صفحة عرض الموقع السياحي</span>
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
