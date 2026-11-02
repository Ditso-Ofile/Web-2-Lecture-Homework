<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GraphController;

// Mainpage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Introduction
Route::get('introduction', function () {
    return view('introduction');
});

// Database
Route::get('database', [DatabaseController::class, 'index'])->name('database.index');

Route::get('database/towns/{town}', [DatabaseController::class, 'show'])->name('database.town');

// Contact (TASK 6) - guests can send messages too
Route::get('contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('contact', [ContactController::class, 'store'])->name('contact.store');

// Graph (TASK 8)
Route::get('graph', [GraphController::class, 'index'])->name('graph.index');

// Pages requiring login
Route::middleware(['auth', 'verified'])->group(function () {

    // After login/registration the starter kit goes to /dashboard -> send users to our Mainpage
    Route::get('dashboard', function () {
        return redirect()->route('home');
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
