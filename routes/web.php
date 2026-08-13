<?php

use Illuminate\Support\Facades\Route;

// Route::redirect('/', '/admin/dashboard');

Route::get('/', function () {
    return view('website.index');
})->name('website.index');

require __DIR__.'/admin.php';
require __DIR__.'/user.php';