<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaveVisit;
use Illuminate\Support\Facades\Log;

class VisitController extends Controller
{
    /**
     * حفظ زيارة جديدة
     */
    public function saveVisit(Request $request)
    {
        try {
            // التحقق من وجود session لتجنب العد المكرر
            if (session()->has('visit_recorded_' . $request->ip())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit already recorded for this session'
                ]);
            }

            // جلب معلومات الموقع من الطلب
            $country = $request->input('country', 'Unknown');
            $city = $request->input('city', 'Unknown');
            
            // حفظ الزيارة
            $visit = SaveVisit::create([
                'country' => $country,
                'city' => $city
            ]);

            // تسجيل session لتجنب العد المكرر
            session(['visit_recorded_' . $request->ip() => true]);

            // تسجيل في اللوج
            Log::info('New visit recorded', [
                'id' => $visit->id,
                'country' => $country,
                'city' => $city,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Visit recorded successfully',
                'visit_id' => $visit->id
            ]);

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
            $totalVisits = SaveVisit::count();
            
            $visitsByCountry = SaveVisit::selectRaw('country, COUNT(*) as count')
                ->groupBy('country')
                ->orderBy('count', 'desc')
                ->get();

            $visitsByCity = SaveVisit::selectRaw('city, COUNT(*) as count')
                ->groupBy('city')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            $recentVisits = SaveVisit::latest()
                ->limit(10)
                ->get();

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
            $totalVisits = SaveVisit::count();
            
            return response()->json([
                'success' => true,
                'total_visits' => $totalVisits
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'total_visits' => 0
            ]);
        }
    }
}
