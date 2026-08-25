@extends('layouts.app')

@section('title', 'إضافة محافظة جديدة')
@section('page-title', 'إضافة محافظة جديدة')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">إضافة محافظة جديدة</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">أدخل بيانات المحافظة الجديدة في النموذج أدناه</p>
        </div>
        <a href="{{ route('governorates.index') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7M19 12H5"></path></svg>
            العودة للقائمة
        </a>
    </div>

    <div class="max-w-2xl mx-auto bg-white border border-ab-border rounded-[22px] p-6 sm:p-8">
        <form action="{{ route('governorates.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
            @csrf

            <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(220px,1fr))">
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الاسم بالعربية *</span>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}" placeholder="أدخل اسم المحافظة بالعربية" required
                        class="w-full border {{ $errors->has('name_ar') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                    @error('name_ar') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">الاسم بالإنجليزية *</span>
                    <input type="text" name="name_en" value="{{ old('name_en') }}" placeholder="Governorate name in English" required dir="ltr"
                        class="w-full border {{ $errors->has('name_en') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                    @error('name_en') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-semibold text-ab-navy">رابط الموقع الرسمي</span>
                <input type="url" name="website_url" value="{{ old('website_url') }}" placeholder="https://example.com" dir="ltr"
                    class="w-full border border-ab-border-2 rounded-2xl px-4 py-3 text-ab-navy focus:outline-none focus:border-ab-teal">
                @error('website_url') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </label>

            <x-admin.image-upload name="image" url-name="image_url" label="صورة المحافظة (اختياري)" />

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-ab-border">
                <a href="{{ route('governorates.index') }}" class="px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">إلغاء</a>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold">حفظ المحافظة</button>
            </div>
        </form>
    </div>

@endsection
