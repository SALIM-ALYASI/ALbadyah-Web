@extends('layouts.app')

@section('title', 'المواقع السياحية')
@section('page-title', 'إدارة المواقع السياحية')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">المواقع السياحية</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">إدارة وعرض جميع المواقع السياحية في النظام</p>
        </div>
        <a href="{{ route('tourist-sitesController.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"></path></svg>
            إضافة موقع سياحي جديد
        </a>
    </div>

    @if ($touristSites->count() > 0)
        <div class="grid gap-4 mb-6" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
            <x-admin.stat-card label="إجمالي المواقع السياحية" :value="$touristSites->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
            </x-admin.stat-card>
            <x-admin.stat-card label="مواقع لها موقع إلكتروني" :value="$touristSites->where('website_url', '!=', null)->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20 15.3 15.3 0 0 1 0-20Z"></path></svg>
            </x-admin.stat-card>
            <x-admin.stat-card label="مواقع لها صور" :value="$touristSites->filter(fn($s) => $s->images->count() > 0)->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
            </x-admin.stat-card>
            <x-admin.stat-card label="مواقع أضيفت اليوم" :value="$touristSites->filter(fn($s) => $s->created_at && $s->created_at->isToday())->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
            </x-admin.stat-card>
        </div>

        <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(280px,1fr))">
            @foreach ($touristSites as $site)
                <div class="bg-white border border-ab-border rounded-[22px] overflow-hidden flex flex-col">
                    <div class="relative h-44 bg-ab-cool">
                        @if ($site->images->count() > 0)
                            <img src="{{ $site->images->first()->image_url }}" alt="{{ $site->name_ar }}" class="w-full h-full object-cover">
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-ab-navy/86 text-white text-xs font-semibold">{{ $site->images->count() }} صورة</span>
                        @else
                            <div class="w-full h-full grid place-items-center">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex flex-col gap-2.5 flex-1">
                        <h3 class="m-0 font-bold text-ab-navy">{{ $site->name_ar }}</h3>
                        <p class="m-0 text-sm text-ab-body leading-relaxed">{{ Str::limit($site->description_ar, 100) }}</p>

                        <div class="flex flex-wrap gap-1.5">
                            @if ($site->governorate)
                                <span class="px-2.5 py-1 rounded-full bg-ab-chip-bg text-ab-chip-text text-xs font-semibold">{{ $site->governorate->name_ar }}</span>
                            @endif
                            @if ($site->wilayat)
                                <span class="px-2.5 py-1 rounded-full bg-ab-cool text-ab-navy text-xs font-semibold">{{ $site->wilayat->name_ar }}</span>
                            @endif
                        </div>

                        <div class="mt-auto flex items-center justify-between pt-3 border-t border-ab-border">
                            <span class="text-xs text-ab-muted">{{ $site->created_at->format('Y-m-d') }}</span>
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('tourist-sitesController.show', $site->id) }}" title="عرض التفاصيل"
                                    class="w-9 h-9 grid place-items-center rounded-full bg-ab-cool text-ab-navy no-underline">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                                <a href="{{ route('tourist-sitesController.edit', $site->id) }}" title="تعديل"
                                    class="w-9 h-9 grid place-items-center rounded-full bg-amber-50 text-amber-600 no-underline">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
                                </a>
                                <form action="{{ route('tourist-sitesController.destroy', $site->id) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع السياحي؟ سيتم حذف جميع الصور المرتبطة به أيضاً.')">
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
        <x-admin.empty-state title="لا توجد مواقع سياحية" body="لم يتم إضافة أي مواقع سياحية بعد. ابدأ بإضافة أول موقع سياحي في النظام.">
            <x-slot:actions>
                <a href="{{ route('tourist-sitesController.create') }}" class="px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">إضافة أول موقع سياحي</a>
            </x-slot:actions>
        </x-admin.empty-state>
    @endif

@endsection
