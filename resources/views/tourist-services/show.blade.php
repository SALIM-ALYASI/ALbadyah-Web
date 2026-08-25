@extends('layouts.app')

@section('title', 'تفاصيل الخدمة السياحية - ' . $touristService->name_ar)
@section('page-title', 'تفاصيل الخدمة السياحية')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">تفاصيل الخدمة السياحية</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">عرض جميع معلومات الخدمة السياحية</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tourist-services.add-services', $touristService->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold no-underline">إضافة خدمات</a>
            <a href="{{ route('tourist-services.edit', $touristService->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-ab-sand text-ab-navy text-sm font-semibold no-underline">تعديل</a>
            <a href="{{ route('tourist-services.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">العودة للقائمة</a>
        </div>
    </div>

    <div class="grid gap-6" style="grid-template-columns:minmax(0,2fr) minmax(240px,1fr)">
        <div class="bg-white border border-ab-border rounded-[22px] p-6 sm:p-8 flex flex-col gap-4">
            <h2 class="m-0 text-lg font-bold text-ab-navy">{{ $touristService->name_ar ?? 'غير محدد' }}</h2>

            <div class="grid gap-4" style="grid-template-columns:repeat(auto-fit, minmax(200px,1fr))">
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الاسم بالعربية</span>
                    <span class="block font-bold text-ab-navy">{{ $touristService->name_ar ?? 'غير محدد' }}</span>
                </div>
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الاسم بالإنجليزية</span>
                    <span class="block font-bold text-ab-navy" dir="ltr">{{ $touristService->name_en ?? 'غير محدد' }}</span>
                </div>
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">نوع الخدمة</span>
                    <span class="text-sm text-ab-navy">{{ $touristService->serviceType?->name_ar ?? 'غير محدد' }}</span>
                </div>
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">المحافظة</span>
                    @if ($touristService->governorate)
                        <a href="{{ route('governorates.show', $touristService->governorate->id) }}" class="text-sm font-semibold text-ab-navy underline">{{ $touristService->governorate->name_ar }}</a>
                    @else
                        <span class="text-sm text-ab-muted">غير محدد</span>
                    @endif
                </div>
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الولاية</span>
                    @if ($touristService->wilayat)
                        <a href="{{ route('wilayats.show', $touristService->wilayat->id) }}" class="text-sm font-semibold text-ab-navy underline">{{ $touristService->wilayat->name_ar }}</a>
                    @else
                        <span class="text-sm text-ab-muted">غير محدد</span>
                    @endif
                </div>
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الموقع الرسمي</span>
                    @if ($touristService->website_url)
                        <a href="{{ $touristService->website_url }}" target="_blank" rel="noopener" class="text-sm font-semibold text-ab-navy underline">زيارة الموقع</a>
                    @else
                        <span class="text-sm text-ab-muted">غير محدد</span>
                    @endif
                </div>
            </div>

            @if ($touristService->has_image)
                <div>
                    <span class="block text-xs font-semibold text-ab-teal mb-2">صورة الخدمة</span>
                    <img src="{{ $touristService->image_url }}" alt="{{ $touristService->name_ar }}" class="w-full max-h-96 object-cover rounded-2xl">
                </div>
            @endif

            <div class="flex items-center justify-between pt-4 border-t border-ab-border">
                <span class="text-xs text-ab-muted">آخر تحديث: {{ $touristService->updated_at->format('Y-m-d H:i:s') }}</span>
                <form action="{{ route('tourist-services.destroy', $touristService->id) }}" method="POST"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة السياحية؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-red-50 text-red-600 text-sm font-semibold">حذف الخدمة السياحية</button>
                </form>
            </div>
        </div>

        <div class="bg-white border border-ab-border rounded-[22px] p-6 flex flex-col gap-4 h-fit">
            <h3 class="m-0 text-sm font-bold text-ab-navy">معلومات إضافية</h3>
            <div class="flex flex-col gap-3 text-sm">
                <div class="flex items-center justify-between"><span class="text-ab-body">معرّف الخدمة</span><span class="font-bold text-ab-navy">#{{ $touristService->id }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">نوع الخدمة</span><span class="font-semibold text-ab-navy">{{ $touristService->serviceType?->name_ar ?? 'غير محدد' }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">تاريخ الإنشاء</span><span class="font-semibold text-ab-navy">{{ $touristService->created_at->diffForHumans() }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">آخر تحديث</span><span class="font-semibold text-ab-navy">{{ $touristService->updated_at->diffForHumans() }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">الموقع الإلكتروني</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $touristService->website_url ? 'bg-emerald-50 text-emerald-700' : 'bg-ab-cool text-ab-muted' }}">{{ $touristService->website_url ? 'متوفر' : 'غير متوفر' }}</span>
                </div>
                <div class="flex items-center justify-between"><span class="text-ab-body">الصورة</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $touristService->image_url ? 'bg-emerald-50 text-emerald-700' : 'bg-ab-cool text-ab-muted' }}">{{ $touristService->image_url ? 'متوفرة' : 'غير متوفرة' }}</span>
                </div>
            </div>
        </div>
    </div>

@endsection
