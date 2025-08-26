<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        $user = Auth::user();
        if (!$user || ($role && $user->role !== $role)) {
            abort(403, 'غير مصرح لك');
        }
        return $next($request);
    }
}
