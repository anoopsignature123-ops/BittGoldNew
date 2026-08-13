<?php

namespace App\Http\Controllers;

use App\Models\User;

abstract class Controller
{
    protected function authenticatedUser(): ?User
    {
        // An administrator may open a member dashboard in preview mode. Use the
        // selected member only inside member controllers; admin controllers must
        // always continue to resolve the administrator who is logged in.
        $isMemberController = str_starts_with(static::class, 'App\\Http\\Controllers\\User\\');
        $userId = $isMemberController && session('auth.role') === 'admin'
            ? session('auth.preview_user_id', session('auth.user_id'))
            : session('auth.user_id');

        return $userId ? User::with('role')->find($userId) : null;
    }
}
