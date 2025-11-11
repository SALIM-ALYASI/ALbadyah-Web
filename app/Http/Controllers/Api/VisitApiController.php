<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitAggregate;
use App\Models\VisitLog;
use App\Services\VisitTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Cookie;

class VisitApiController extends Controller
{
    public function __construct(
        private readonly VisitTracker $visitTracker
    ) {
        //
    }

    /**
     * تسجيل زيارة جديدة
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ip_address' => 'nullable|ip',
                'user_agent' => 'nullable|string|max:500',
                'page_url' => 'nullable|string|max:255',
                'referrer' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
                'session_id' => 'nullable|uuid',
            ]);

            $result = $this->visitTracker->track($request, [
                'session_id' => $validated['session_id'] ?? null,
                'ip' => $validated['ip_address'] ?? $request->ip(),
                'user_agent' => $validated['user_agent'] ?? $request->userAgent(),
                'country' => $validated['country'] ?? null,
                'city' => $validated['city'] ?? null,
                'path' => $validated['page_url'] ?? $request->path(),
                'referer' => $validated['referrer'] ?? $request->headers->get('referer'),
            ]);

            $response = response()->json([
                'success' => true,
                'message' => 'تم تسجيل الزيارة بنجاح',
                'data' => [
                    'id' => $result['log']->id,
                    'session_id' => $result['session_id'],
                    'is_unique' => $result['is_unique'],
                    'visited_at' => $result['log']->visited_at,
                ]
            ], 201);

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
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تسجيل الزيارة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إحصائيات الزيارات العامة
     */
    public function stats(Request $request)
    {
        try {
            $period = max((int) $request->get('period', 30), 1);
            $startDate = Carbon::now()->subDays($period);

            $stats = [
                'total_visits' => VisitLog::count(),
                'period_visits' => VisitLog::where('visited_at', '>=', $startDate)->count(),
                'unique_visitors' => VisitLog::distinct('fingerprint')->count('fingerprint'),
                'period_unique_visitors' => VisitLog::where('visited_at', '>=', $startDate)
                    ->distinct('fingerprint')->count('fingerprint'),
            ];

            // إحصائيات حسب البلدان
            $countryStats = VisitAggregate::selectRaw('country, SUM(visits_count) as visits')
                ->whereNotNull('country')
                ->where('date', '>=', $startDate->toDateString())
                ->groupBy('country')
                ->orderByDesc('visits')
                ->limit(10)
                ->get();

            // إحصائيات حسب الصفحات
            $pageStats = VisitAggregate::selectRaw('path, SUM(visits_count) as visits')
                ->whereNotNull('path')
                ->where('date', '>=', $startDate->toDateString())
                ->groupBy('path')
                ->orderByDesc('visits')
                ->limit(10)
                ->get();

            // إحصائيات يومية للفترة المحددة
            $dailyStats = VisitAggregate::selectRaw('date, SUM(visits_count) as visits, SUM(unique_visits_count) as unique_visitors')
                ->where('date', '>=', $startDate->toDateString())
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'overview' => $stats,
                    'top_countries' => $countryStats,
                    'top_pages' => $pageStats,
                    'daily_stats' => $dailyStats,
                    'period' => $period . ' days',
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => now()->format('Y-m-d'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب الإحصائيات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إجمالي الزيارات
     */
    public function total()
    {
        try {
            $total = VisitLog::count();
            $unique = VisitLog::distinct('fingerprint')->count('fingerprint');

            return response()->json([
                'success' => true,
                'data' => [
                    'total_visits' => $total,
                    'unique_visitors' => $unique,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب إجمالي الزيارات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إحصائيات مفصلة للإدمن
     */
    public function adminStats(Request $request)
    {
        try {
            $period = max((int) $request->get('period', 30), 1);
            $startDate = Carbon::now()->subDays($period);

            // إحصائيات شاملة
            $overview = [
                'total_visits' => VisitLog::count(),
                'period_visits' => VisitLog::where('visited_at', '>=', $startDate)->count(),
                'unique_visitors' => VisitLog::distinct('fingerprint')->count('fingerprint'),
                'period_unique_visitors' => VisitLog::where('visited_at', '>=', $startDate)
                    ->distinct('fingerprint')->count('fingerprint'),
                'avg_visits_per_day' => VisitAggregate::where('date', '>=', $startDate->toDateString())
                    ->selectRaw('SUM(visits_count) / ? as avg_visits', [$period])
                    ->value('avg_visits'),
            ];

            // إحصائيات حسب الساعة
            $hourlyStats = VisitLog::selectRaw('HOUR(visited_at) as hour, COUNT(*) as visits')
                ->where('visited_at', '>=', $startDate)
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            // إحصائيات حسب اليوم في الأسبوع
            $weeklyStats = VisitLog::selectRaw('DAYOFWEEK(visited_at) as day_of_week, COUNT(*) as visits')
                ->where('visited_at', '>=', $startDate)
                ->groupBy('day_of_week')
                ->orderBy('day_of_week')
                ->get();

            // إحصائيات حسب الشهر
            $monthlyStats = VisitLog::selectRaw('MONTH(visited_at) as month, YEAR(visited_at) as year, COUNT(*) as visits')
                ->where('visited_at', '>=', $startDate)
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'overview' => $overview,
                    'hourly_stats' => $hourlyStats,
                    'weekly_stats' => $weeklyStats,
                    'monthly_stats' => $monthlyStats,
                    'period' => $period . ' days',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب الإحصائيات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تصدير بيانات الزيارات
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'json'); // json, csv
            $period = max((int) $request->get('period', '30'), 1);
            $startDate = Carbon::now()->subDays($period);

            $visits = VisitLog::where('visited_at', '>=', $startDate)
                ->orderBy('visited_at', 'desc')
                ->get();

            if ($format === 'csv') {
                $csvData = "ID,Country,City,Path,Referrer,Is Unique,Visited At\n";
                
                foreach ($visits as $visit) {
                    $csvData .= sprintf(
                        "%d,%s,%s,%s,%s,%s,%s\n",
                        $visit->id,
                        $visit->country ?? '',
                        $visit->city ?? '',
                        $visit->path ?? '',
                        $visit->referer ?? '',
                        $visit->is_unique ? 'yes' : 'no',
                        $visit->visited_at?->format('Y-m-d H:i:s')
                    );
                }

                return response($csvData)
                    ->header('Content-Type', 'text/csv')
                    ->header('Content-Disposition', 'attachment; filename="visits_export.csv"');
            }

            return response()->json([
                'success' => true,
                'data' => $visits,
                'total' => $visits->count(),
                'period' => $period . ' days',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تصدير البيانات',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
