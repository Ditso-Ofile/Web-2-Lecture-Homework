
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\MessageController;

// Mainpage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Introduction
Route::get('introduction', function () {
    return view('introduction');
});

// Database
Route::get('database', [DatabaseController::class, 'index'])->name('database.index');

Route::get('database/towns/{town}', [DatabaseController::class, 'show'])->name('database.town');

// Pages requiring login
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('user', function () {
        return view('user');
    })->name('user');

    Route::get('admin', 'App\Http\Controllers\AdminController@adminPage');

    // Messages
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
