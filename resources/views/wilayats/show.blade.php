@extends('layouts.app')

@section('title', 'تفاصيل الولاية - ' . $wilayat->name_ar)
@section('page-title', 'تفاصيل الولاية')

@section('content')

    <div class="relative overflow-hidden rounded-[24px] bg-ab-navy p-6 sm:p-8 mb-6">
        <span class="absolute -top-12 -left-12 w-40 h-40 rounded-full bg-white/5"></span>
        <div class="relative flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="m-0 text-2xl sm:text-3xl font-bold text-white">{{ $wilayat->name_ar }}</h1>
                <p class="m-0 mt-1 text-white/80" dir="ltr">{{ $wilayat->name_en }}</p>
                <p class="m-0 mt-1 text-sm text-white/60">{{ $wilayat->governorate->name_ar ?? 'غير محدد' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('wilayats.edit', $wilayat->id) }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-ab-sand text-ab-navy text-sm font-semibold no-underline">تعديل</a>
                <a href="{{ route('wilayats.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/12 text-white text-sm font-semibold no-underline">العودة للقائمة</a>
            </div>
        </div>
    </div>

    <div class="grid gap-6" style="grid-template-columns:minmax(0,2fr) minmax(240px,1fr)">
        <div class="bg-white border border-ab-border rounded-[22px] p-6 sm:p-8 flex flex-col gap-5">
            <div class="grid gap-4" style="grid-template-columns:repeat(auto-fit, minmax(200px,1fr))">
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الاسم بالعربية</span>
                    <span class="block font-bold text-ab-navy">{{ $wilayat->name_ar ?? 'غير محدد' }}</span>
                </div>
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الاسم بالإنجليزية</span>
                    <span class="block font-bold text-ab-navy" dir="ltr">{{ $wilayat->name_en ?? 'غير محدد' }}</span>
                </div>
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">المحافظة</span>
                    @if ($wilayat->governorate)
                        <a href="{{ route('governorates.show', $wilayat->governorate->id) }}" class="text-sm font-semibold text-ab-navy underline">{{ $wilayat->governorate->name_ar }}</a>
                    @else
                        <span class="text-sm text-ab-muted">غير محدد</span>
                    @endif
                </div>
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الموقع الرسمي</span>
                    @if ($wilayat->website_url)
                        <a href="{{ $wilayat->website_url }}" target="_blank" rel="noopener" class="text-sm font-semibold text-ab-navy underline">زيارة الموقع</a>
                    @else
                        <span class="text-sm text-ab-muted">غير محدد</span>
                    @endif
                </div>
            </div>

            <div>
                <span class="block text-xs font-semibold text-ab-teal mb-2">صورة الولاية</span>
                @if ($wilayat->has_image)
                    <img src="{{ $wilayat->image_url }}" alt="{{ $wilayat->name_ar }}" class="w-full max-h-96 object-cover rounded-2xl">
                @else
                    <div class="w-full min-h-[220px] rounded-2xl bg-ab-cool grid place-items-center">
                        <div class="text-center">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
                            <p class="m-0 text-sm text-ab-muted">لا توجد صورة متاحة</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-ab-border">
                <span class="text-xs text-ab-muted">آخر تحديث: {{ $wilayat->updated_at->format('Y-m-d H:i:s') }}</span>
                <form action="{{ route('wilayats.destroy', $wilayat->id) }}" method="POST"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذه الولاية؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-red-50 text-red-600 text-sm font-semibold">حذف الولاية</button>
                </form>
            </div>
        </div>

        <div class="bg-white border border-ab-border rounded-[22px] p-6 flex flex-col gap-4 h-fit">
            <h3 class="m-0 text-sm font-bold text-ab-navy">معلومات إضافية</h3>
            <div class="flex flex-col gap-3 text-sm">
                <div class="flex items-center justify-between"><span class="text-ab-body">معرّف الولاية</span><span class="font-bold text-ab-navy">#{{ $wilayat->id }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">المحافظة</span><span class="font-semibold text-ab-navy">{{ $wilayat->governorate->name_ar ?? 'غير محدد' }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">تاريخ الإنشاء</span><span class="font-semibold text-ab-navy">{{ $wilayat->created_at->diffForHumans() }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">آخر تحديث</span><span class="font-semibold text-ab-navy">{{ $wilayat->updated_at->diffForHumans() }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">الموقع الإلكتروني</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $wilayat->website_url ? 'bg-emerald-50 text-emerald-700' : 'bg-ab-cool text-ab-muted' }}">{{ $wilayat->website_url ? 'متوفر' : 'غير متوفر' }}</span>
                </div>
                <div class="flex items-center justify-between"><span class="text-ab-body">الصورة</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $wilayat->image_url ? 'bg-emerald-50 text-emerald-700' : 'bg-ab-cool text-ab-muted' }}">{{ $wilayat->image_url ? 'متوفرة' : 'غير متوفرة' }}</span>
                </div>
            </div>
        </div>
    </div>

@endsection
