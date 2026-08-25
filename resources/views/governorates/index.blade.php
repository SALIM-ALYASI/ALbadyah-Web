@extends('layouts.app')

@section('title', 'المحافظات')
@section('page-title', 'إدارة المحافظات')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">المحافظات</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">إدارة وعرض جميع المحافظات في النظام</p>
        </div>
        <a href="{{ route('governorates.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"></path></svg>
            إضافة محافظة جديدة
        </a>
    </div>

    @if ($governorates->count() > 0)
        <div class="grid gap-4 mb-6" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
            <x-admin.stat-card label="إجمالي المحافظات" :value="$governorates->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"></path></svg>
            </x-admin.stat-card>
            <x-admin.stat-card label="محافظات لها موقع إلكتروني" :value="$governorates->where('website_url', '!=', null)->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20 15.3 15.3 0 0 1 0-20Z"></path></svg>
            </x-admin.stat-card>
            <x-admin.stat-card label="محافظات لها صور" :value="$governorates->where('image_url', '!=', null)->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
            </x-admin.stat-card>
            <x-admin.stat-card label="محافظات أضيفت اليوم" :value="$governorates->filter(fn($g) => $g->created_at && $g->created_at->isToday())->count()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
            </x-admin.stat-card>
        </div>

        <div class="bg-white border border-ab-border rounded-[22px] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[720px]">
                    <thead>
                        <tr class="bg-ab-navy text-white">
                            <th class="px-4 py-3 text-center font-semibold">#</th>
                            <th class="px-4 py-3 text-right font-semibold">الاسم بالعربية</th>
                            <th class="px-4 py-3 text-right font-semibold">الاسم بالإنجليزية</th>
                            <th class="px-4 py-3 text-center font-semibold">موقع الويب</th>
                            <th class="px-4 py-3 text-center font-semibold">الصورة</th>
                            <th class="px-4 py-3 text-center font-semibold">تاريخ الإنشاء</th>
                            <th class="px-4 py-3 text-center font-semibold">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($governorates as $index => $governorate)
                            <tr class="border-b border-ab-border last:border-0 hover:bg-ab-warm/60">
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-ab-cool text-ab-navy text-xs font-bold">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-4 py-3 font-bold text-ab-navy whitespace-nowrap">{{ $governorate->name_ar ?? 'غير محدد' }}</td>
                                <td class="px-4 py-3 text-ab-body whitespace-nowrap" dir="ltr">{{ $governorate->name_en ?? 'غير محدد' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if ($governorate->website_url)
                                        <a href="{{ $governorate->website_url }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-ab-border-2 text-ab-navy text-xs font-semibold no-underline whitespace-nowrap">
                                            زيارة الموقع
                                        </a>
                                    @else
                                        <span class="inline-flex px-3 py-1.5 rounded-full bg-ab-cool text-ab-muted text-xs">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="mx-auto w-12 h-12 rounded-xl overflow-hidden bg-ab-cool grid place-items-center">
                                        @if ($governorate->has_image)
                                            <img src="{{ $governorate->image_url }}" alt="{{ $governorate->name_ar }}" class="w-full h-full object-cover">
                                        @else
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <span class="block font-medium text-ab-navy">{{ $governorate->created_at->format('Y-m-d') }}</span>
                                    <span class="block text-xs text-ab-muted">{{ $governorate->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('governorates.show', $governorate->id) }}" title="عرض التفاصيل"
                                            class="w-9 h-9 grid place-items-center rounded-full bg-ab-cool text-ab-navy no-underline">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </a>
                                        <a href="{{ route('governorates.edit', $governorate->id) }}" title="تعديل"
                                            class="w-9 h-9 grid place-items-center rounded-full bg-amber-50 text-amber-600 no-underline">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
                                        </a>
                                        <form action="{{ route('governorates.destroy', $governorate->id) }}" method="POST"
                                            onsubmit="return confirm('هل أنت متأكد من حذف هذه المحافظة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="حذف" class="w-9 h-9 grid place-items-center rounded-full bg-red-50 text-red-600">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <x-admin.empty-state title="لا توجد محافظات" body="لم يتم إضافة أي محافظات بعد. ابدأ بإضافة أول محافظة في النظام.">
            <x-slot:actions>
                <a href="{{ route('governorates.create') }}" class="px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">إضافة أول محافظة</a>
            </x-slot:actions>
        </x-admin.empty-state>
    @endif

@endsection
