<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () { return Inertia::render('welcome'); })->name('home');

Route::get('introduction', function () { return view('introduction'); });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () { return Inertia::render('dashboard'); })->name('dashboard');
    Route::get('user', function () { return view('user'); })->name('user');
    Route::get('admin', 'App\Http\Controllers\AdminController@adminPage');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';