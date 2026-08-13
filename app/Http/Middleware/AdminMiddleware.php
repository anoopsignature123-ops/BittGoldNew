<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('auth.user_id') || session('auth.role') !== 'admin') {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
