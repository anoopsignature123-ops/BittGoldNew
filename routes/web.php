<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

// Route::redirect('/', '/admin/dashboard');

Route::get('/', function () {
    return view('website.index');
})->name('website.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';