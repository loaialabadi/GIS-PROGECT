<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ✅ عرض كل المستخدمين
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // ✅ عرض صفحة إنشاء مستخدم جديد
    public function create()
    {
        return view('user.create');
    }

    // ✅ حفظ المستخدم الجديد
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:admin,customer_service,reviewer,data_entry',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'تم إضافة المستخدم بنجاح');
    }

    // ✅ عرض صفحة التعديل
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // ✅ تحديث بيانات المستخدم
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,customer_service,reviewer,data_entry',
        ]);

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => $request->password 
                            ? Hash::make($request->password) 
                            : $user->password,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'تم تحديث بيانات المستخدم');
    }

    // ✅ حذف مستخدم
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم');
    }
}
