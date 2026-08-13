<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $isMemberSession = session('auth.user_id') && session('auth.role') === 'user';
        $previewUserId = session('auth.preview_user_id');
        $isAdminPreview = session('auth.user_id')
            && session('auth.role') === 'admin'
            && $previewUserId
            && User::whereKey($previewUserId)->whereHas('role', fn ($query) => $query->where('slug', 'user'))->exists();

        if (! $isMemberSession && ! $isAdminPreview) {
            return redirect()->route('user.login');
        }

        return $next($request);
    }
}
