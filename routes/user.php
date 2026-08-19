<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\IncomeController;
use App\Http\Controllers\User\GlobalSearchController;
use App\Http\Controllers\User\InvestmentController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\RankController;
use App\Http\Controllers\User\SupportController;
use App\Http\Controllers\User\TeamController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\WithdrawalController;
use App\Http\Middleware\UserGuestMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;

/* Member panel routes: /user/... */
Route::prefix('user')->name('user.')->group(function () {
    // Registration confirmation is intentionally accessible after submit even if
    // another panel session still exists in the browser.
    Route::get('/registration-success', [UserAuthController::class, 'registrationSuccess'])->name('registration.success');

    Route::middleware([UserGuestMiddleware::class])->group(function () {
        Route::controller(UserAuthController::class)->group(function () {
            Route::get('/login', 'showLogin')->name('login');
            Route::post('/login', 'login')->name('login.submit');
            Route::post('/send-otp', 'sendOtp')->name('send.otp');
            Route::get('/verify-otp', 'showOtpVerify')->name('otp.verify');
            Route::post('/resend-otp', 'resendOtp')->name('otp.resend');
            Route::post('/verify-otp', 'verifyOtp')->name('otp.submit');
            Route::get('/forgot-password', 'showForgotPassword')->name('password.request');
            Route::post('/forgot-password', 'sendResetLink')->name('password.email');
            Route::get('/reset-password/{token}', 'showResetPassword')->name('password.reset');
            Route::post('/reset-password', 'resetPassword')->name('password.update');
            Route::get('/register', 'showRegister')->name('register');
            Route::get('/sponsor-lookup', 'sponsorLookup')->name('sponsor.lookup');
            Route::post('/register', 'register')->name('register.submit');
        });
    });

    Route::middleware([UserMiddleware::class])->group(function () {
        Route::controller(UserAuthController::class)->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });

        Route::controller(\App\Http\Controllers\User\UserKycController::class)->group(function () {
            Route::get('/kyc', 'index')->name('kyc.index');
            Route::post('/kyc', 'store')->name('kyc.store');
        });

        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'profile')->name('profile');
            Route::put('/profile/update', 'updateProfile')->name('profile.update');
            Route::put('/profile/password', 'updatePassword')->name('profile.password');
        });

        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/search', [GlobalSearchController::class, 'index'])->name('search');
        Route::controller(DepositController::class)->group(function () {
            Route::get('/deposit', 'index')->name('deposit.index');
            Route::post('/deposit', 'store')->name('deposit.store');
        });
        Route::controller(InvestmentController::class)->group(function () {
            Route::get('/investment', 'index')->name('investment.index');
            Route::post('/investment', 'store')->name('investment.store');
        });

        Route::controller(IncomeController::class)->group(function () {
            Route::get('/incomes', 'index')->name('income.index');
        });

        Route::controller(RankController::class)->group(function () {
            Route::get('/rank-report', 'index')->name('rank.index');
        });
        Route::controller(TeamController::class)->group(function () {
            Route::get('/my-team', 'index')->name('team.index');
        });
        Route::controller(TransactionController::class)->group(function () {
            Route::get('/transactions', 'index')->name('transaction.index');
        });

        Route::controller(WithdrawalController::class)->group(function () {
            Route::get('/withdrawals', 'index')->name('withdrawal.index');
            Route::post('/withdrawals', 'store')->name('withdrawal.store');
        });
        Route::get('/team/tree/{user?}', [TeamController::class, 'tree'])->name('team.tree');

        Route::name('supports.')->controller(SupportController::class)->group(function () {
            Route::get('/support', 'index')->name('index');
            Route::get('/support/create', 'create')->name('create');
            Route::post('/support/store', 'store')->name('store');
            Route::get('/support/{ticket_id}', 'show')->name('show');
            Route::post('/support/{ticket_id}/reply', 'reply')->name('reply');
        });

    });
});
