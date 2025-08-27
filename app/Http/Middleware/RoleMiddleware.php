<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // لو مش لوج ان
        if (!$user) {
            abort(403, 'غير مصرح لك');
        }

        // admin يدخل أي حاجة
        if ($user->role === 'admin') {
            return $next($request);
        }

        // لو الدور مش موجود في اللستة
        if (!in_array($user->role, $roles)) {
            abort(403, 'غير مصرح لك');
        }

        return $next($request);
    }
}
