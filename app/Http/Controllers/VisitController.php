<?php

namespace App\Http\Controllers;

use App\Models\VisitAggregate;
use App\Models\VisitLog;
use App\Services\VisitTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Cookie;

class VisitController extends Controller
{
    public function __construct(
        private readonly VisitTracker $visitTracker
    ) {
        //
    }

    /**
     * حفظ زيارة جديدة
     */
    public function saveVisit(Request $request)
    {
        try {
            $result = $this->visitTracker->track($request);

            Log::info('New visit recorded', [
                'id' => $result['log']->id,
                'country' => $result['log']->country,
                'city' => $result['log']->city,
                'path' => $result['log']->path,
                'ip_hash' => $result['log']->ip_hash,
                'session_id' => $result['log']->session_id,
                'is_unique' => $result['is_unique'],
            ]);

            $response = response()->json([
                'success' => true,
                'message' => 'Visit recorded successfully',
                'visit_id' => $result['log']->id,
                'is_unique' => $result['is_unique'],
            ]);

            if ($result['is_new_session']) {
                $cookie = new Cookie(
                    name: 'visit_session',
                    value: $result['session_id'],
                    expire: now()->addDays(30),
                    path: '/',
                    secure: false,
                    httpOnly: false,
                    raw: false,
                    sameSite: Cookie::SAMESITE_LAX
                );

                $response->headers->setCookie($cookie);
            }

            return $response;

        } catch (\Exception $e) {
            Log::error('Error recording visit', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error recording visit'
            ], 500);
        }
    }

    /**
     * الحصول على إحصائيات الزيارات
     */
    public function getStats()
    {
        try {
            $totalVisits = VisitLog::count();
            $visitsByCountry = VisitAggregate::selectRaw('country, SUM(visits_count) as count')
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            $visitsByCity = VisitAggregate::selectRaw('city, SUM(visits_count) as count')
                ->whereNotNull('city')
                ->groupBy('city')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            $recentVisits = VisitLog::latest('visited_at')
                ->limit(10)
                ->get(['id', 'country', 'city', 'path', 'visited_at', 'is_unique']);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_visits' => $totalVisits,
                    'visits_by_country' => $visitsByCountry,
                    'visits_by_city' => $visitsByCity,
                    'recent_visits' => $recentVisits
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting visit stats', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting visit stats'
            ], 500);
        }
    }

    /**
     * الحصول على إجمالي الزيارات
     */
    public function getTotalVisits()
    {
        try {
            $totalVisits = VisitAggregate::sum('visits_count');
            
            return response()->json([
                'success' => true,
                'total_visits' => $totalVisits
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'total_visits' => VisitAggregate::sum('visits_count')
            ]);
        }
    }
}
