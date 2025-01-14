<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
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
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::get('users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
});

// Quản lý sản phẩm
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
});

// Quản lý hồ sơ cá nhân
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profiles/{id}', [ProfileController::class, 'show'])->name('profiles.show');
    Route::get('/profiles/{id}/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::put('/profiles/{id}', [ProfileController::class, 'update'])->name('profiles.update');
});

// Trang Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth routes
require __DIR__ . '/auth.php';
