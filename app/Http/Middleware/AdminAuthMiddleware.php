<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من وجود ADMIN_SECRET في متغيرات البيئة
        $adminSecret = env('ADMIN_SECRET');
        
        if (!$adminSecret) {
            abort(500, 'Admin secret not configured. Please set ADMIN_SECRET in .env file.');
        }
        
        // التحقق من وجود جلسة إدارية صحيحة
        if (!session('admin_access') || session('admin_secret') !== $adminSecret) {
            // حفظ الـ URL الحالي للعودة إليه بعد تسجيل الدخول
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('admin.login')->with('error', 'يجب تسجيل الدخول للوصول للوحة التحكم');
        }
        
        // التحقق من IP - حماية إضافية ضد سرقة الجلسة
        $sessionIp = session('admin_ip');
        $currentIp = $request->ip();
        if ($sessionIp && $sessionIp !== $currentIp) {
            session()->forget([
                'admin_access', 
                'admin_secret', 
                'admin_login_time', 
                'admin_ip',
                'admin_session_id'
            ]);
            return redirect()->route('admin.login')->with('error', 'تم اكتشاف تغيير في عنوان IP، يرجى تسجيل الدخول مرة أخرى');
        }
        
        // التحقق من انتهاء صلاحية الجلسة (اختياري)
        $sessionTimeout = session('admin_login_time');
        if ($sessionTimeout && (time() - $sessionTimeout) > 7200) { // ساعتان
            session()->forget([
                'admin_access', 
                'admin_secret', 
                'admin_login_time', 
                'admin_ip',
                'admin_session_id'
            ]);
            return redirect()->route('admin.login')->with('error', 'انتهت صلاحية الجلسة، يرجى تسجيل الدخول مرة أخرى');
        }
        
        return $next($request);
    }
}
