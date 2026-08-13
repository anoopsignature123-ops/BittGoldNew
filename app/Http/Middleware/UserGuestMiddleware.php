<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserGuestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('auth.user_id')) {
            return $next($request);
        }

        if (session('auth.role') === 'user') {
            return redirect()->route('user.dashboard');
        }

        if (session('auth.role') === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
