<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * توثيق مستقل تمامًا لبوت البادية (تلجرام + OSM)، منفصل كليًا عن Sanctum
 * وعن admin.auth. يعتمد فقط على BADYAH_BOT_API_TOKEN. البوت المستقل هو
 * الوحيد الذي يعرف هذا التوكن — لا علاقة له بحساب أي مستخدم أو أدمن.
 */
class BadyahBotApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedToken = config('services.badyah_bot_api.token');

        if (!$expectedToken) {
            abort(500, 'BADYAH_BOT_API_TOKEN غير مُعرّف في إعدادات هذا المشروع.');
        }

        $providedToken = $request->bearerToken();

        if (!$providedToken || !hash_equals($expectedToken, $providedToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: invalid or missing Badyah bot API token.',
            ], 401);
        }

        return $next($request);
    }
}
