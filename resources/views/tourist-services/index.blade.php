@extends('layouts.app')

@section('title', 'الخدمات السياحية')
@section('page-title', 'إدارة الخدمات السياحية')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">الخدمات السياحية</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">إدارة وعرض جميع الخدمات السياحية في النظام</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tourist-services.create-location') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">إضافة موقع خدمة جديد</a>
            <a href="{{ route('tourist-services.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">إضافة خدمة سريعة</a>
        </div>
    </div>

    @if ($touristServices->count() > 0)
        <div class="grid gap-4 mb-6" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
            <x-admin.stat-card label="إجمالي الخدمات السياحية" :value="$touristServices->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"></path></svg>
            </x-admin.stat-card>
            <x-admin.stat-card label="خدمات لها موقع إلكتروني" :value="$touristServices->where('website_url', '!=', null)->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20 15.3 15.3 0 0 1 0-20Z"></path></svg>
            </x-admin.stat-card>
            <x-admin.stat-card label="خدمات لها صور" :value="$touristServices->where('image_url', '!=', null)->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
            </x-admin.stat-card>
            <x-admin.stat-card label="خدمات أضيفت اليوم" :value="$touristServices->filter(fn($s) => $s->created_at && $s->created_at->isToday())->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
            </x-admin.stat-card>
        </div>

        <form method="GET" action="{{ route('tourist-services.index') }}" class="bg-white border border-ab-border rounded-[22px] p-5 mb-6 flex flex-col gap-4">
            <div class="grid gap-4" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
                <label class="flex flex-col gap-1.5">
                    <span class="text-xs font-semibold text-ab-body">البحث</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث في اسم الخدمة"
                        class="w-full border border-ab-border-2 rounded-full px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-xs font-semibold text-ab-body">المحافظة</span>
                    <select name="governorate_id" class="w-full border border-ab-border-2 rounded-full px-4 py-2.5 text-sm text-ab-navy">
                        <option value="">جميع المحافظات</option>
                        @foreach ($governorates as $governorate)
                            <option value="{{ $governorate->id }}" @selected(request('governorate_id') == $governorate->id)>{{ $governorate->name_ar }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-xs font-semibold text-ab-body">الولاية</span>
                    <select name="wilayat_id" class="w-full border border-ab-border-2 rounded-full px-4 py-2.5 text-sm text-ab-navy">
                        <option value="">جميع الولايات</option>
                        @foreach ($wilayats as $wilayat)
                            <option value="{{ $wilayat->id }}" @selected(request('wilayat_id') == $wilayat->id)>{{ $wilayat->name_ar }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-xs font-semibold text-ab-body">نوع الخدمة</span>
                    <select name="service_type_id" class="w-full border border-ab-border-2 rounded-full px-4 py-2.5 text-sm text-ab-navy">
                        <option value="">جميع الأنواع</option>
                        @foreach ($serviceTypes as $serviceType)
                            <option value="{{ $serviceType->id }}" @selected(request('service_type_id') == $serviceType->id)>{{ $serviceType->name_ar }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold">بحث</button>
                <a href="{{ route('tourist-services.index') }}" class="px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">مسح الفلاتر</a>
            </div>
        </form>

        <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(280px,1fr))">
            @foreach ($touristServices as $service)
                <div class="bg-white border border-ab-border rounded-[22px] overflow-hidden flex flex-col">
                    <div class="h-44 bg-ab-cool">
                        @if ($service->has_image)
                            <img src="{{ $service->image_url }}" alt="{{ $service->name_ar }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full grid place-items-center">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"></path></svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex flex-col gap-2.5 flex-1">
                        <h3 class="m-0 font-bold text-ab-navy">{{ $service->name_ar }}</h3>
                        <p class="m-0 text-sm text-ab-muted" dir="ltr">{{ $service->name_en }}</p>

                        <div class="flex flex-wrap gap-1.5">
                            @if ($service->serviceType)
                                <span class="px-2.5 py-1 rounded-full bg-ab-chip-bg text-ab-chip-text text-xs font-semibold">{{ $service->serviceType->name_ar }}</span>
                            @endif
                            @if ($service->governorate)
                                <span class="px-2.5 py-1 rounded-full bg-ab-cool text-ab-navy text-xs font-semibold">{{ $service->governorate->name_ar }}</span>
                            @endif
                            @if ($service->wilayat)
                                <span class="px-2.5 py-1 rounded-full bg-ab-cool text-ab-navy text-xs font-semibold">{{ $service->wilayat->name_ar }}</span>
                            @endif
                        </div>

                        <div class="mt-auto flex items-center justify-between pt-3 border-t border-ab-border">
                            <span class="text-xs text-ab-muted">{{ $service->created_at->format('Y-m-d') }}</span>
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('tourist-services.show', $service->id) }}" title="عرض التفاصيل"
                                    class="w-9 h-9 grid place-items-center rounded-full bg-ab-cool text-ab-navy no-underline">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                                <a href="{{ route('tourist-services.edit', $service->id) }}" title="تعديل"
                                    class="w-9 h-9 grid place-items-center rounded-full bg-amber-50 text-amber-600 no-underline">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
                                </a>
                                <form action="{{ route('tourist-services.destroy', $service->id) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة السياحية؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="حذف" class="w-9 h-9 grid place-items-center rounded-full bg-red-50 text-red-600">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <x-admin.empty-state title="لا توجد خدمات سياحية" body="لم يتم إضافة أي خدمات سياحية بعد. ابدأ بإضافة أول خدمة سياحية في النظام.">
            <x-slot:actions>
                <a href="{{ route('tourist-services.create') }}" class="px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">إضافة أول خدمة سياحية</a>
            </x-slot:actions>
        </x-admin.empty-state>
    @endif

@endsection
