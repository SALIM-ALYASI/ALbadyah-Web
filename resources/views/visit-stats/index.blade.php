@extends('layouts.app')

@section('title', 'إحصائيات الزيارات')
@section('page-title', 'إحصائيات الزيارات')

@section('content')

    <div class="mb-6">
        <h1 class="m-0 text-2xl font-bold text-ab-navy">إحصائيات الزيارات</h1>
        <p class="m-0 mt-1 text-sm text-ab-body">تتبع زيارات الموقع والإحصائيات التفصيلية.</p>
    </div>

    <div class="grid gap-4 mb-6" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
        <x-admin.stat-card label="إجمالي الزيارات" :value="number_format($totalVisits)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="زيارات اليوم" :value="number_format($visitsToday)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="3"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="زيارات هذا الأسبوع" :value="number_format($visitsThisWeek)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="3"></rect><path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01"></path></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="زيارات هذا الشهر" :value="number_format($visitsThisMonth)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="3"></rect><path d="M16 2v4M8 2v4M3 10h18"></path><path d="M8 14h8v4H8z"></path></svg>
        </x-admin.stat-card>
    </div>

    <div class="grid gap-6 mb-6" style="grid-template-columns:repeat(auto-fit, minmax(320px,1fr))">
        <div class="bg-white border border-ab-border rounded-[22px] p-6">
            <h2 class="m-0 text-sm font-bold text-ab-navy mb-4">الزيارات حسب الدولة</h2>
            @if ($visitsByCountry->count())
                <div class="flex flex-col gap-3">
                    @foreach ($visitsByCountry as $visit)
                        @php($pct = $totalVisits > 0 ? round(($visit->count / $totalVisits) * 100, 1) : 0)
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-ab-navy font-medium">{{ $visit->country }}</span>
                                <span class="text-ab-muted">{{ number_format($visit->count) }} · {{ $pct }}%</span>
                            </div>
                            <div class="h-2 rounded-full bg-ab-cool overflow-hidden">
                                <div class="h-full rounded-full bg-ab-teal" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="m-0 text-sm text-ab-muted">لا توجد بيانات زيارات متاحة</p>
            @endif
        </div>

        <div class="bg-white border border-ab-border rounded-[22px] p-6">
            <h2 class="m-0 text-sm font-bold text-ab-navy mb-4">الزيارات حسب المدينة</h2>
            @if ($visitsByCity->count())
                <div class="flex flex-col gap-3">
                    @foreach ($visitsByCity as $visit)
                        @php($pct = $totalVisits > 0 ? round(($visit->count / $totalVisits) * 100, 1) : 0)
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-ab-navy font-medium">{{ $visit->city }}</span>
                                <span class="text-ab-muted">{{ number_format($visit->count) }} · {{ $pct }}%</span>
                            </div>
                            <div class="h-2 rounded-full bg-ab-cool overflow-hidden">
                                <div class="h-full rounded-full bg-emerald-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="m-0 text-sm text-ab-muted">لا توجد بيانات زيارات متاحة</p>
            @endif
        </div>
    </div>

    <div class="bg-white border border-ab-border rounded-[22px] overflow-hidden">
        <div class="p-6 pb-0">
            <h2 class="m-0 text-sm font-bold text-ab-navy">الزيارات الأخيرة</h2>
        </div>
        @if ($recentVisits->count())
            <div class="overflow-x-auto p-6">
                <table class="w-full text-sm min-w-[500px]">
                    <thead>
                        <tr class="bg-ab-navy text-white">
                            <th class="px-3 py-3 text-center font-semibold rounded-r-xl">#</th>
                            <th class="px-3 py-3 text-center font-semibold">الدولة</th>
                            <th class="px-3 py-3 text-center font-semibold">المدينة</th>
                            <th class="px-3 py-3 text-center font-semibold">تاريخ الزيارة</th>
                            <th class="px-3 py-3 text-center font-semibold rounded-l-xl">وقت الزيارة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentVisits as $index => $visit)
                            <tr class="border-b border-ab-border last:border-0 hover:bg-ab-warm/60">
                                <td class="px-3 py-3 text-center text-ab-muted">{{ $index + 1 }}</td>
                                <td class="px-3 py-3 text-center text-ab-navy font-medium">{{ $visit->country }}</td>
                                <td class="px-3 py-3 text-center text-ab-body">{{ $visit->city }}</td>
                                <td class="px-3 py-3 text-center text-ab-body">{{ $visit->created_at->format('Y-m-d') }}</td>
                                <td class="px-3 py-3 text-center text-ab-muted" dir="ltr">{{ $visit->created_at->format('H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-10">
                <x-admin.empty-state title="لا توجد زيارات مسجلة بعد" body="ستظهر بيانات الزوار هنا فور بدء تتبع الزيارات." />
            </div>
        @endif
    </div>

@endsection
