<?php

namespace App\Http\Controllers;

use App\Models\VisitAggregate;
use App\Models\VisitLog;

class VisitStatsController extends Controller
{
    /**
     * عرض صفحة إحصائيات الزيارات
     */
    public function index()
    {
        $totalVisits = VisitAggregate::sum('visits_count');

        $visitsByCountry = VisitAggregate::selectRaw('country, SUM(visits_count) as count')
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('count')
            ->get();

        $visitsByCity = VisitAggregate::selectRaw('city, SUM(visits_count) as count')
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        $recentVisits = VisitLog::latest('visited_at')
            ->limit(20)
            ->get();

        $visitsToday = VisitAggregate::where('date', today())->sum('visits_count');
        $visitsThisWeek = VisitAggregate::whereBetween('date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])
            ->sum('visits_count');
        $visitsThisMonth = VisitAggregate::whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('visits_count');

        return view('visit-stats.index', compact(
            'totalVisits',
            'visitsByCountry',
            'visitsByCity',
            'recentVisits',
            'visitsToday',
            'visitsThisWeek',
            'visitsThisMonth'
        ));
    }
}
