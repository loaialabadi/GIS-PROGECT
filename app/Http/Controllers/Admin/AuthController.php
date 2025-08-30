<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ✅ عرض صفحة تسجيل الدخول
    public function showLogin()
    {
        return view('auth.login');
    }

    // ✅ تسجيل الدخول
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    // نجرب نجيب المستخدم الأول
    $user = User::where('email', $credentials['email'])->first();

    if ($user && !$user->is_active) {
        return back()->withErrors([
            'email' => 'تم تعطيل حسابك، يرجى التواصل مع الإدارة.',
        ]);
    }

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'بيانات الدخول غير صحيحة.',
    ]);
}

    // ✅ عرض صفحة التسجيل
    public function showRegister()
    {
        return view('auth.register');
    }

    // ✅ تنفيذ التسجيل
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // مهم تشفير الباسورد
            'role' => 'user', // أي مستخدم جديد هيكون role = user
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // ✅ تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
