<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaveVisit;

class VisitStatsController extends Controller
{
    /**
     * عرض صفحة إحصائيات الزيارات
     */
    public function index()
    {
        $totalVisits = SaveVisit::count();
        
        $visitsByCountry = SaveVisit::selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->get();

        $visitsByCity = SaveVisit::selectRaw('city, COUNT(*) as count')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->limit(20)
            ->get();

        $recentVisits = SaveVisit::latest()
            ->limit(20)
            ->get();

        $visitsToday = SaveVisit::whereDate('created_at', today())->count();
        $visitsThisWeek = SaveVisit::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $visitsThisMonth = SaveVisit::whereMonth('created_at', now()->month)->count();

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
