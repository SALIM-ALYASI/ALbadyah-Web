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
        if (session('admin_access')) {
            return redirect()->route('governorates.index');
        }
        
        return view('admin.login');
    }

    /**
     * تسجيل دخول الإدمن
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // بيانات الإدمن - كلمة المرور من متغير البيئة
        $adminCredentials = [
            'username' => 'admin',
            'password' => env('ADMIN_SECRET', 'admin123'), // كلمة المرور من متغير البيئة
        ];

        if ($request->username === $adminCredentials['username'] && 
            $request->password === $adminCredentials['password']) {
            
            // تعيين جلسة الإدمن
            session(['admin_access' => true]);
            
            return redirect()->route('governorates.index')
                ->with('success', 'مرحباً بك في لوحة التحكم');
        }

        return back()->withErrors([
            'credentials' => 'اسم المستخدم أو كلمة المرور غير صحيحة'
        ]);
    }

    /**
     * تسجيل خروج الإدمن
     */
    public function logout()
    {
        session()->forget('admin_access');
        
        return redirect()->route('admin.login')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
