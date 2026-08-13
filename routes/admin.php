<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\GlobalSearchController;
use App\Http\Controllers\Admin\InvestmentController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RankController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Middleware\AdminGuestMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Support;
use Illuminate\Support\Facades\Route;

/* Admin panel routes: /admin/... */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware([AdminGuestMiddleware::class])->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::controller(AdminAuthController::class)->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });

        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/search', [GlobalSearchController::class, 'index'])->name('search');
        Route::post('/users/preview/exit', [AdminUserController::class, 'exitPreview'])->name('users.preview.exit');
        Route::resource('users', AdminUserController::class)->except(['show']);

        Route::controller(AdminUserController::class)->group(function () {
            // Updated with optional parameter {user?} to fix sidebar tree link error

            Route::get('/users/{user}/view', 'view')->name('users.view');
            Route::get('/users/{user?}/tree', 'tree')->name('users.tree');

            Route::get('/users/{user}/dashboard-preview', 'previewDashboard')->name('users.preview');
            Route::post('/users/{user}/proxy/investment', 'proxyInvestment')->name('users.proxy.investment');
            Route::post('/users/{user}/proxy/withdrawal', 'proxyWithdrawal')->name('users.proxy.withdrawal');
            Route::post('/users/{user}/proxy/deposit', 'proxyDeposit')->name('users.proxy.deposit');
            Route::get('/users/{user}/team-report', 'teamReport')->name('users.team');

            Route::get('/users/{user}/wallet-adjust', 'adjustWallet')->name('users.wallet.adjust');
            Route::post('/users/{user}/wallet-adjust', 'updateWallet')->name('users.wallet.update');

            Route::get('/direct-fund-deposit', 'directDepositIndex')->name('direct.deposit.index');
            Route::post('/direct-fund-deposit/{user}', 'storeDirectDeposit')->name('direct.deposit.store');
        });

        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'profile')->name('profile');
            Route::put('/profile/update', 'updateProfile')->name('profile.update');
            Route::put('/profile/password', 'updatePassword')->name('profile.password');
        });
        Route::controller(DepositController::class)->group(function () {
            Route::get('/deposits', 'index')->name('deposits.index');
            Route::post('/deposits/{id}/approve', 'approve')->name('deposits.approve');
            Route::post('/deposits/{id}/reject', 'reject')->name('deposits.reject');
        });

        Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');
        Route::get('/commissions', [IncomeController::class, 'index'])->name('commissions.index');
        Route::get('/ranks-report', [RankController::class, 'index'])->name('ranks.index');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

        Route::controller(WithdrawalController::class)->group(function () {
            Route::get('/withdrawals', 'index')->name('withdrawals.index');
            Route::post('/withdrawals/{id}/approve', 'approve')->name('withdrawals.approve');
            Route::post('/withdrawals/{id}/reject', 'reject')->name('withdrawals.reject');
        });

        Route::name('supports.')->controller(SupportController::class)->group(function () {
            Route::get('/supports', 'index')->name('index');
            Route::get('/supports/{ticket_id}', 'show')->name('show');
            Route::post('/supports/{ticket_id}/reply', 'reply')->name('reply');
            Route::post('/supports/{ticket_id}/status', 'updateStatus')->name('status');
        });

    });
});
