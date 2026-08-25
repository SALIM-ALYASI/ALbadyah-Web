@extends('layouts.app')

@section('title', 'مراجعة بيانات بوت البادية')
@section('page-title', 'مراجعة مرشّحات البوت')

@php
    $statusLabels = [
        'pending_review' => ['بانتظار المراجعة', 'bg-sky-50 text-sky-700'],
        'needs_enrichment' => ['يحتاج إثراء', 'bg-amber-50 text-amber-700'],
        'approved_draft' => ['مسودة معتمدة', 'bg-ab-chip-bg text-ab-chip-text'],
        'deferred' => ['مؤجل', 'bg-ab-cool text-ab-muted'],
        'rejected' => ['مرفوض', 'bg-red-50 text-red-700'],
        'published' => ['محفوظ في الموقع', 'bg-emerald-50 text-emerald-700'],
    ];
@endphp

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">مراجعة بيانات بوت البادية</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">راجع البيانات وعدّلها ثم احفظها في جداول الموقع أو انشرها للعامة.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">لوحة التحكم</a>
    </div>

    <div class="grid gap-4 mb-6" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
        <x-admin.stat-card label="بانتظار العمل" :value="$stats['waiting']">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 3"></path></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="تحتاج إثراء" :value="$stats['needs_enrichment']">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"></path><circle cx="12" cy="12" r="4"></circle></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="محفوظة في الموقع" :value="$stats['published']">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="مرفوضة" :value="$stats['rejected']">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="m15 9-6 6M9 9l6 6"></path></svg>
        </x-admin.stat-card>
    </div>

    <form method="GET" action="{{ route('map-candidates.index') }}" class="bg-white border border-ab-border rounded-[22px] p-5 mb-6 flex flex-wrap items-end gap-4">
        <label class="flex flex-col gap-1.5 flex-[2] min-w-[200px]">
            <span class="text-xs font-semibold text-ab-body">بحث</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="الاسم العربي، الإنجليزي أو رقم OSM"
                class="w-full border border-ab-border-2 rounded-full px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
        </label>
        <label class="flex flex-col gap-1.5 flex-1 min-w-[160px]">
            <span class="text-xs font-semibold text-ab-body">الحالة</span>
            <select name="status" class="w-full border border-ab-border-2 rounded-full px-4 py-2.5 text-sm text-ab-navy">
                <option value="">كل الحالات</option>
                @foreach ($statusLabels as $value => $meta)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $meta[0] }}</option>
                @endforeach
            </select>
        </label>
        <label class="flex flex-col gap-1.5 flex-1 min-w-[140px]">
            <span class="text-xs font-semibold text-ab-body">النوع</span>
            <select name="category" class="w-full border border-ab-border-2 rounded-full px-4 py-2.5 text-sm text-ab-navy">
                <option value="">الكل</option>
                <option value="site" @selected(request('category') === 'site')>موقع سياحي</option>
                <option value="service" @selected(request('category') === 'service')>خدمة سياحية</option>
            </select>
        </label>
        <button type="submit" class="px-6 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold">تصفية</button>
    </form>

    <div class="bg-white border border-ab-border rounded-[22px] overflow-hidden">
        @if ($candidates->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">
                    <thead>
                        <tr class="bg-ab-navy text-white">
                            <th class="px-4 py-3 text-center font-semibold">#</th>
                            <th class="px-4 py-3 text-right font-semibold">المعلم أو الخدمة</th>
                            <th class="px-4 py-3 text-center font-semibold">النوع</th>
                            <th class="px-4 py-3 text-center font-semibold">الولاية</th>
                            <th class="px-4 py-3 text-center font-semibold">الثقة</th>
                            <th class="px-4 py-3 text-center font-semibold">الحالة</th>
                            <th class="px-4 py-3 text-center font-semibold">تاريخ الوصول</th>
                            <th class="px-4 py-3 text-center font-semibold">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidates as $candidate)
                            @php($status = $statusLabels[$candidate->status] ?? [$candidate->status, 'bg-ab-cool text-ab-muted'])
                            <tr class="border-b border-ab-border last:border-0 hover:bg-ab-warm/60">
                                <td class="px-4 py-3 text-center text-ab-muted">{{ $candidate->id }}</td>
                                <td class="px-4 py-3" style="min-width:230px">
                                    <strong class="block text-ab-navy">{{ $candidate->name_ar ?: 'بدون اسم عربي' }}</strong>
                                    <span class="block text-xs text-ab-muted" dir="ltr">{{ $candidate->name_en ?: '—' }}</span>
                                    @if ($candidate->osm_type && $candidate->osm_id)
                                        <span class="block text-xs text-ab-muted">OSM: {{ $candidate->osm_type }}/{{ $candidate->osm_id }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $candidate->category === 'site' ? 'bg-emerald-50 text-emerald-700' : 'bg-sky-50 text-sky-700' }}">{{ $candidate->category === 'site' ? 'موقع' : 'خدمة' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap text-ab-body">{{ optional($candidate->wilayat)->name_ar ?: '—' }}</td>
                                <td class="px-4 py-3 text-center text-ab-body">{{ $candidate->overall_confidence !== null ? round($candidate->overall_confidence * 100) . '%' : '—' }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $status[1] }}">{{ $status[0] }}</span>
                                    @if ($candidate->published_id)
                                        <span class="block text-xs text-ab-muted mt-1">#{{ $candidate->published_id }} في {{ $candidate->published_table }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap text-xs text-ab-muted">{{ optional($candidate->created_at)->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <a href="{{ route('map-candidates.edit', $candidate->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-ab-cool text-ab-navy text-xs font-semibold no-underline">
                                        {{ $candidate->status === 'published' ? 'عرض' : 'مراجعة' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-5">{{ $candidates->links() }}</div>
        @else
            <div class="p-10">
                <x-admin.empty-state title="لا توجد بيانات مطابقة" body="بعد إرسال البوت لمرشح جديد سيظهر هنا تلقائيًا." />
            </div>
        @endif
    </div>

@endsection
