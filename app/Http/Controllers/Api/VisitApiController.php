<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitApiController extends Controller
{
    /**
     * تسجيل زيارة جديدة
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ip_address' => 'required|ip',
                'user_agent' => 'nullable|string|max:500',
                'page_url' => 'nullable|string|max:500',
                'referrer' => 'nullable|string|max:500',
                'country' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
            ]);

            $visit = Visit::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الزيارة بنجاح',
                'data' => [
                    'id' => $visit->id,
                    'ip_address' => $visit->ip_address,
                    'created_at' => $visit->created_at,
                ]
            ], 201);
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
            $period = $request->get('period', '30'); // days
            $startDate = now()->subDays($period);

            $stats = [
                'total_visits' => Visit::count(),
                'period_visits' => Visit::where('created_at', '>=', $startDate)->count(),
                'unique_visitors' => Visit::distinct('ip_address')->count(),
                'period_unique_visitors' => Visit::where('created_at', '>=', $startDate)
                    ->distinct('ip_address')->count(),
            ];

            // إحصائيات حسب البلدان
            $countryStats = Visit::select('country', DB::raw('count(*) as visits'))
                ->whereNotNull('country')
                ->where('created_at', '>=', $startDate)
                ->groupBy('country')
                ->orderByDesc('visits')
                ->limit(10)
                ->get();

            // إحصائيات حسب الصفحات
            $pageStats = Visit::select('page_url', DB::raw('count(*) as visits'))
                ->whereNotNull('page_url')
                ->where('created_at', '>=', $startDate)
                ->groupBy('page_url')
                ->orderByDesc('visits')
                ->limit(10)
                ->get();

            // إحصائيات يومية للفترة المحددة
            $dailyStats = Visit::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as visits'),
                    DB::raw('count(DISTINCT ip_address) as unique_visitors')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy(DB::raw('DATE(created_at)'))
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
            $total = Visit::count();
            $unique = Visit::distinct('ip_address')->count();

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
            $period = $request->get('period', '30');
            $startDate = now()->subDays($period);

            // إحصائيات شاملة
            $overview = [
                'total_visits' => Visit::count(),
                'period_visits' => Visit::where('created_at', '>=', $startDate)->count(),
                'unique_visitors' => Visit::distinct('ip_address')->count(),
                'period_unique_visitors' => Visit::where('created_at', '>=', $startDate)
                    ->distinct('ip_address')->count(),
                'avg_visits_per_day' => Visit::where('created_at', '>=', $startDate)
                    ->selectRaw('COUNT(*) / ? as avg_visits', [$period])
                    ->value('avg_visits'),
            ];

            // إحصائيات حسب الساعة
            $hourlyStats = Visit::select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('count(*) as visits')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy(DB::raw('HOUR(created_at)'))
                ->orderBy('hour')
                ->get();

            // إحصائيات حسب اليوم في الأسبوع
            $weeklyStats = Visit::select(
                    DB::raw('DAYOFWEEK(created_at) as day_of_week'),
                    DB::raw('count(*) as visits')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy(DB::raw('DAYOFWEEK(created_at)'))
                ->orderBy('day_of_week')
                ->get();

            // إحصائيات حسب الشهر
            $monthlyStats = Visit::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('count(*) as visits')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'))
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
            $period = $request->get('period', '30');
            $startDate = now()->subDays($period);

            $visits = Visit::where('created_at', '>=', $startDate)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($format === 'csv') {
                $csvData = "ID,IP Address,Country,City,Page URL,Referrer,User Agent,Created At\n";
                
                foreach ($visits as $visit) {
                    $csvData .= sprintf(
                        "%d,%s,%s,%s,%s,%s,%s,%s\n",
                        $visit->id,
                        $visit->ip_address,
                        $visit->country ?? '',
                        $visit->city ?? '',
                        $visit->page_url ?? '',
                        $visit->referrer ?? '',
                        str_replace(',', ';', $visit->user_agent ?? ''),
                        $visit->created_at->format('Y-m-d H:i:s')
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
