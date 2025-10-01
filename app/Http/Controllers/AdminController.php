<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول للإدمن
     */
    public function showLogin()
    {
        // إذا كان مسجل دخول بالفعل، إعادة توجيه للوحة التحكم
        if (session('admin_access') && session('admin_secret') === env('ADMIN_SECRET')) {
            return redirect()->route('governorates.index');
        }
        
        return view('admin.login');
    }

    /**
     * تسجيل دخول الإدمن - متغير واحد فقط مع حماية إضافية
     */
    public function login(Request $request)
    {
        // حماية ضد محاولات التلاعب - Rate Limiting
        $ip = $request->ip();
        $attempts = session("login_attempts_{$ip}", 0);
        $lastAttempt = session("last_attempt_{$ip}", 0);
        
        // منع محاولات متتالية سريعة (أقل من 3 ثواني)
        if (time() - $lastAttempt < 3) {
            return back()->withErrors([
                'credentials' => 'محاولة دخول سريعة جداً، انتظر قليلاً'
            ]);
        }
        
        // منع أكثر من 5 محاولات في 10 دقائق
        if ($attempts >= 5 && (time() - session("first_attempt_{$ip}", time())) < 600) {
            return back()->withErrors([
                'credentials' => 'تم تجاوز عدد المحاولات المسموح، حاول مرة أخرى بعد 10 دقائق'
            ]);
        }
        
        // تحديث عداد المحاولات
        if ($attempts == 0) {
            session(["first_attempt_{$ip}" => time()]);
        }
        session([
            "login_attempts_{$ip}" => $attempts + 1,
            "last_attempt_{$ip}" => time()
        ]);

        $request->validate([
            'admin_key' => 'required|string|min:6',
        ]);

        // متغير واحد فقط للدخول - ADMIN_SECRET
        $adminSecret = env('ADMIN_SECRET', 'admin123');
        
        // التحقق من صحة المفتاح مع تشفير إضافي
        $inputKey = $request->admin_key;
        
        if (hash('sha256', $inputKey) === hash('sha256', $adminSecret)) {
            // مسح عداد المحاولات عند نجاح الدخول
            session()->forget([
                "login_attempts_{$ip}",
                "last_attempt_{$ip}",
                "first_attempt_{$ip}"
            ]);
            
            // تعيين جلسة الإدمن مع ADMIN_SECRET
            session([
                'admin_access' => true,
                'admin_secret' => env('ADMIN_SECRET'),
                'admin_login_time' => time(),
                'admin_ip' => $ip,
                'admin_session_id' => uniqid('admin_', true)
            ]);
            
            return redirect()->route('governorates.index')
                ->with('success', 'مرحباً بك في لوحة التحكم');
        }

        return back()->withErrors([
            'credentials' => 'مفتاح الدخول غير صحيح'
        ]);
    }

    /**
     * تسجيل خروج الإدمن
     */
    public function logout()
    {
        // حذف جميع بيانات الجلسة الإدارية
        session()->forget([
            'admin_access', 
            'admin_secret', 
            'admin_login_time', 
            'admin_username',
            'admin_ip',
            'admin_session_id'
        ]);
        
        return redirect()->route('admin.login')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
