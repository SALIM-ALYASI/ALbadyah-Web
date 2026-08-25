@extends('layouts.app')

@section('title', 'عرض جميع البيانات')
@section('page-title', 'عرض جميع البيانات')

@php
    $tabs = [
        ['key' => 'governorates', 'label' => 'المحافظات', 'count' => $governorates->count()],
        ['key' => 'wilayats', 'label' => 'الولايات', 'count' => $wilayats->count()],
        ['key' => 'tourist-sites', 'label' => 'المواقع السياحية', 'count' => $touristSites->count()],
        ['key' => 'tourist-services', 'label' => 'الخدمات السياحية', 'count' => $touristServices->count()],
        ['key' => 'service-types', 'label' => 'أنواع الخدمات', 'count' => $serviceTypes->count()],
        ['key' => 'images', 'label' => 'الصور', 'count' => $touristImages->count()],
    ];
@endphp

@section('content')

    <div class="mb-6">
        <h1 class="m-0 text-2xl font-bold text-ab-navy">عرض جميع البيانات</h1>
        <p class="m-0 mt-1 text-sm text-ab-body">نظرة شاملة على كل جداول الموقع للمراجعة السريعة.</p>
    </div>

    <div class="grid gap-4 mb-6" style="grid-template-columns:repeat(auto-fit, minmax(150px,1fr))">
        <x-admin.stat-card label="المحافظات" :value="$stats['total_governorates']">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"></path></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="الولايات" :value="$stats['total_wilayats']">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="المواقع السياحية" :value="$stats['total_tourist_sites']">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="الخدمات السياحية" :value="$stats['total_tourist_services']">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"></path></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="أنواع الخدمات" :value="$stats['total_service_types']">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m20.6 12.3-8.3 8.3a2 2 0 0 1-2.8 0L3 14.1V3h11.1l6.5 6.5a2 2 0 0 1 0 2.8Z"></path><circle cx="7.5" cy="7.5" r="1.2"></circle></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="الصور" :value="$stats['total_images']">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
        </x-admin.stat-card>
    </div>

    <div id="data-tabs" class="bg-white border border-ab-border rounded-[22px] overflow-hidden">
        <div class="flex flex-wrap gap-2 p-4 border-b border-ab-border overflow-x-auto">
            @foreach ($tabs as $index => $tab)
                <button type="button" data-tab-btn="{{ $tab['key'] }}" onclick="AdminUI.showTab('data-tabs', '{{ $tab['key'] }}')"
                    class="px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition {{ $index === 0 ? 'bg-ab-navy text-white' : 'text-ab-body hover:bg-ab-warm' }}">
                    {{ $tab['label'] }} ({{ $tab['count'] }})
                </button>
            @endforeach
        </div>

        <div class="p-5">
            {{-- المحافظات --}}
            <div data-tab-panel="governorates" class="overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">
                    <thead>
                        <tr class="bg-ab-navy text-white">
                            <th class="px-3 py-3 text-center font-semibold">#</th>
                            <th class="px-3 py-3 text-right font-semibold">الاسم بالعربية</th>
                            <th class="px-3 py-3 text-right font-semibold" dir="ltr">الاسم بالإنجليزية</th>
                            <th class="px-3 py-3 text-center font-semibold">موقع الويب</th>
                            <th class="px-3 py-3 text-center font-semibold">الولايات</th>
                            <th class="px-3 py-3 text-center font-semibold">المواقع</th>
                            <th class="px-3 py-3 text-center font-semibold">الخدمات</th>
                            <th class="px-3 py-3 text-center font-semibold">الصورة</th>
                            <th class="px-3 py-3 text-center font-semibold">تاريخ الإنشاء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($governorates as $governorate)
                            <tr class="border-b border-ab-border last:border-0 hover:bg-ab-warm/60">
                                <td class="px-3 py-3 text-center text-ab-muted">{{ $loop->iteration }}</td>
                                <td class="px-3 py-3 font-semibold text-ab-navy">{{ $governorate->name_ar }}</td>
                                <td class="px-3 py-3 text-ab-body" dir="ltr">{{ $governorate->name_en }}</td>
                                <td class="px-3 py-3 text-center">
                                    @if ($governorate->website_url)
                                        <a href="{{ $governorate->website_url }}" target="_blank" rel="noopener" class="text-ab-teal text-xs font-semibold underline">زيارة الموقع</a>
                                    @else
                                        <span class="text-ab-muted text-xs">لا يوجد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center"><span class="px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">{{ $governorate->wilayats_count }}</span></td>
                                <td class="px-3 py-3 text-center"><span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">{{ $governorate->tourist_sites_count }}</span></td>
                                <td class="px-3 py-3 text-center"><span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold">{{ $governorate->tourist_services_count }}</span></td>
                                <td class="px-3 py-3 text-center">
                                    @if ($governorate->image_url)
                                        <img src="{{ $governorate->image_url }}" alt="{{ $governorate->name_ar }}" class="w-10 h-10 rounded-lg object-cover mx-auto border border-ab-border">
                                    @else
                                        <span class="text-ab-muted text-xs">لا توجد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center whitespace-nowrap text-xs text-ab-muted">{{ $governorate->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-3 py-8 text-center text-ab-muted">لا توجد محافظات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- الولايات --}}
            <div data-tab-panel="wilayats" class="hidden overflow-x-auto">
                <table class="w-full text-sm min-w-[800px]">
                    <thead>
                        <tr class="bg-ab-navy text-white">
                            <th class="px-3 py-3 text-center font-semibold">#</th>
                            <th class="px-3 py-3 text-right font-semibold">الاسم بالعربية</th>
                            <th class="px-3 py-3 text-right font-semibold" dir="ltr">الاسم بالإنجليزية</th>
                            <th class="px-3 py-3 text-center font-semibold">المحافظة</th>
                            <th class="px-3 py-3 text-center font-semibold">موقع الويب</th>
                            <th class="px-3 py-3 text-center font-semibold">الصورة</th>
                            <th class="px-3 py-3 text-center font-semibold">تاريخ الإنشاء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wilayats as $wilayat)
                            <tr class="border-b border-ab-border last:border-0 hover:bg-ab-warm/60">
                                <td class="px-3 py-3 text-center text-ab-muted">{{ $loop->iteration }}</td>
                                <td class="px-3 py-3 font-semibold text-ab-navy">{{ $wilayat->name_ar }}</td>
                                <td class="px-3 py-3 text-ab-body" dir="ltr">{{ $wilayat->name_en }}</td>
                                <td class="px-3 py-3 text-center">
                                    @if ($wilayat->governorate)
                                        <span class="px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">{{ $wilayat->governorate->name_ar }}</span>
                                    @else
                                        <span class="text-ab-muted text-xs">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if ($wilayat->website_url)
                                        <a href="{{ $wilayat->website_url }}" target="_blank" rel="noopener" class="text-ab-teal text-xs font-semibold underline">زيارة الموقع</a>
                                    @else
                                        <span class="text-ab-muted text-xs">لا يوجد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if ($wilayat->image_url)
                                        <img src="{{ $wilayat->image_url }}" alt="{{ $wilayat->name_ar }}" class="w-10 h-10 rounded-lg object-cover mx-auto border border-ab-border">
                                    @else
                                        <span class="text-ab-muted text-xs">لا توجد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center whitespace-nowrap text-xs text-ab-muted">{{ $wilayat->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-3 py-8 text-center text-ab-muted">لا توجد ولايات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- المواقع السياحية --}}
            <div data-tab-panel="tourist-sites" class="hidden overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">
                    <thead>
                        <tr class="bg-ab-navy text-white">
                            <th class="px-3 py-3 text-center font-semibold">#</th>
                            <th class="px-3 py-3 text-right font-semibold">الاسم بالعربية</th>
                            <th class="px-3 py-3 text-right font-semibold" dir="ltr">الاسم بالإنجليزية</th>
                            <th class="px-3 py-3 text-center font-semibold">الموقع</th>
                            <th class="px-3 py-3 text-center font-semibold">المحافظة</th>
                            <th class="px-3 py-3 text-center font-semibold">الولاية</th>
                            <th class="px-3 py-3 text-center font-semibold">عدد الصور</th>
                            <th class="px-3 py-3 text-center font-semibold">موقع الويب</th>
                            <th class="px-3 py-3 text-center font-semibold">تاريخ الإنشاء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($touristSites as $site)
                            <tr class="border-b border-ab-border last:border-0 hover:bg-ab-warm/60">
                                <td class="px-3 py-3 text-center text-ab-muted">{{ $loop->iteration }}</td>
                                <td class="px-3 py-3 font-semibold text-ab-navy">{{ $site->name_ar }}</td>
                                <td class="px-3 py-3 text-ab-body" dir="ltr">{{ $site->name_en }}</td>
                                <td class="px-3 py-3 text-center text-ab-body">{{ $site->location ?? 'غير محدد' }}</td>
                                <td class="px-3 py-3 text-center">
                                    @if ($site->governorate)
                                        <span class="px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">{{ $site->governorate->name_ar }}</span>
                                    @else
                                        <span class="text-ab-muted text-xs">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if ($site->wilayat)
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">{{ $site->wilayat->name_ar }}</span>
                                    @else
                                        <span class="text-ab-muted text-xs">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center"><span class="px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">{{ $site->images->count() }}</span></td>
                                <td class="px-3 py-3 text-center">
                                    @if ($site->website_url)
                                        <a href="{{ $site->website_url }}" target="_blank" rel="noopener" class="text-ab-teal text-xs font-semibold underline">زيارة الموقع</a>
                                    @else
                                        <span class="text-ab-muted text-xs">لا يوجد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center whitespace-nowrap text-xs text-ab-muted">{{ $site->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-3 py-8 text-center text-ab-muted">لا توجد مواقع سياحية</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- الخدمات السياحية --}}
            <div data-tab-panel="tourist-services" class="hidden overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">
                    <thead>
                        <tr class="bg-ab-navy text-white">
                            <th class="px-3 py-3 text-center font-semibold">#</th>
                            <th class="px-3 py-3 text-right font-semibold">الاسم بالعربية</th>
                            <th class="px-3 py-3 text-right font-semibold" dir="ltr">الاسم بالإنجليزية</th>
                            <th class="px-3 py-3 text-center font-semibold">نوع الخدمة</th>
                            <th class="px-3 py-3 text-center font-semibold">المحافظة</th>
                            <th class="px-3 py-3 text-center font-semibold">الولاية</th>
                            <th class="px-3 py-3 text-center font-semibold">موقع الويب</th>
                            <th class="px-3 py-3 text-center font-semibold">الصورة</th>
                            <th class="px-3 py-3 text-center font-semibold">تاريخ الإنشاء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($touristServices as $service)
                            <tr class="border-b border-ab-border last:border-0 hover:bg-ab-warm/60">
                                <td class="px-3 py-3 text-center text-ab-muted">{{ $loop->iteration }}</td>
                                <td class="px-3 py-3 font-semibold text-ab-navy">{{ $service->name_ar }}</td>
                                <td class="px-3 py-3 text-ab-body" dir="ltr">{{ $service->name_en }}</td>
                                <td class="px-3 py-3 text-center">
                                    @if ($service->serviceType)
                                        <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold">{{ $service->serviceType->name_ar }}</span>
                                    @else
                                        <span class="text-ab-muted text-xs">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if ($service->governorate)
                                        <span class="px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">{{ $service->governorate->name_ar }}</span>
                                    @else
                                        <span class="text-ab-muted text-xs">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if ($service->wilayat)
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">{{ $service->wilayat->name_ar }}</span>
                                    @else
                                        <span class="text-ab-muted text-xs">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if ($service->website_url)
                                        <a href="{{ $service->website_url }}" target="_blank" rel="noopener" class="text-ab-teal text-xs font-semibold underline">زيارة الموقع</a>
                                    @else
                                        <span class="text-ab-muted text-xs">لا يوجد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if ($service->image_url)
                                        <img src="{{ $service->image_url }}" alt="{{ $service->name_ar }}" class="w-10 h-10 rounded-lg object-cover mx-auto border border-ab-border">
                                    @else
                                        <span class="text-ab-muted text-xs">لا توجد</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center whitespace-nowrap text-xs text-ab-muted">{{ $service->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-3 py-8 text-center text-ab-muted">لا توجد خدمات سياحية</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- أنواع الخدمات --}}
            <div data-tab-panel="service-types" class="hidden overflow-x-auto">
                <table class="w-full text-sm min-w-[520px]">
                    <thead>
                        <tr class="bg-ab-navy text-white">
                            <th class="px-3 py-3 text-center font-semibold">#</th>
                            <th class="px-3 py-3 text-right font-semibold">الاسم بالعربية</th>
                            <th class="px-3 py-3 text-right font-semibold" dir="ltr">الاسم بالإنجليزية</th>
                            <th class="px-3 py-3 text-center font-semibold">عدد الخدمات</th>
                            <th class="px-3 py-3 text-center font-semibold">تاريخ الإنشاء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($serviceTypes as $type)
                            <tr class="border-b border-ab-border last:border-0 hover:bg-ab-warm/60">
                                <td class="px-3 py-3 text-center text-ab-muted">{{ $loop->iteration }}</td>
                                <td class="px-3 py-3 font-semibold text-ab-navy">{{ $type->name_ar }}</td>
                                <td class="px-3 py-3 text-ab-body" dir="ltr">{{ $type->name_en }}</td>
                                <td class="px-3 py-3 text-center"><span class="px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">{{ $type->tourist_services_count }}</span></td>
                                <td class="px-3 py-3 text-center whitespace-nowrap text-xs text-ab-muted">{{ $type->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-3 py-8 text-center text-ab-muted">لا توجد أنواع خدمات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- الصور --}}
            <div data-tab-panel="images" class="hidden">
                @if ($touristImages->count())
                    <div class="grid gap-4" style="grid-template-columns:repeat(auto-fill, minmax(180px,1fr))">
                        @foreach ($touristImages as $image)
                            <div class="border border-ab-border rounded-2xl overflow-hidden">
                                <img src="{{ $image->image_url }}" alt="صورة" class="w-full h-36 object-cover">
                                <div class="p-3">
                                    <p class="m-0 text-sm font-semibold text-ab-navy truncate">{{ $image->touristSite->name_ar ?? 'صورة غير مرتبطة' }}</p>
                                    <p class="m-0 mt-1 text-xs text-ab-muted">{{ $image->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-admin.empty-state title="لا توجد صور" body="لم تتم إضافة أي صور بعد." />
                @endif
            </div>
        </div>
    </div>

@endsection
