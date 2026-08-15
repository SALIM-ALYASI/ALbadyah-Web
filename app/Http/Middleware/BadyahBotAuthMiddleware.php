<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * توثيق مستقل تمامًا لمحرك/بوت البادية الذكي (n8n أو أي محرك جلب بيانات).
 *
 * لا علاقة له بـ Sanctum admin tokens ولا بأي نظام توثيق من مشاريع أخرى.
 * يعتمد فقط على BADYAH_BOT_TOKEN المعرّف في .env الخاص بهذا المشروع.
 */
class BadyahBotAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedToken = config('services.badyah_bot.token');

        if (!$expectedToken) {
            abort(500, 'BADYAH_BOT_TOKEN غير مُعرّف في إعدادات هذا المشروع.');
        }

        $providedToken = $request->bearerToken();

        if (!$providedToken || !hash_equals($expectedToken, $providedToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: invalid or missing Badyah bot token.',
            ], 401);
        }

        return $next($request);
    }
}
