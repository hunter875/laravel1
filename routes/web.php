<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// Trang chủ
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Quản lý người dùng
Route::resource('users', UserController::class)->middleware('auth');
Route::get('users/create', [ProfileController::class, 'create'])->name('users.create');
Route::post('users', [ProfileController::class, 'store'])->name('users.store');

// Quản lý hồ sơ cá nhân
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes
require __DIR__.'/auth.php';

// Trang Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/users/search', [UserController::class, 'search'])->name('users.search');