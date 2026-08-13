<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminGuestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('auth.user_id')) {
            return $next($request);
        }

        if (session('auth.role') === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (session('auth.role') === 'user') {
            return redirect()->route('user.dashboard');
        }

        return $next($request);
    }
}
