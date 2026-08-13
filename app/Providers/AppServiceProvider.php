<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer(['admin.*', 'user.*'], function ($view): void {
            $user = null;
            $userId = session('auth.user_id');
            $previewMode = str_starts_with($view->getName(), 'user.')
                && session('auth.role') === 'admin'
                && session('auth.preview_user_id');

            if ($previewMode) {
                $userId = session('auth.preview_user_id');
            }

            if ($userId) {
                $user = User::with('role')->find($userId);
            }

            if (! array_key_exists('user', $view->getData())) {
                $view->with('user', $user);
            }

            if (! array_key_exists('headerUser', $view->getData())) {
                $view->with('headerUser', $user);
            }

            if ($previewMode && ! array_key_exists('previewMode', $view->getData())) {
                $view->with('previewMode', true);
            }
        });
    }
}
