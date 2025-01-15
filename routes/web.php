<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
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
    Route::resource('users', UserController::class);
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
    Route::resource('profile', ProfileController::class)->except(['edit', 'update']);
    Route::get('profile/{profile}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/{profile}', [ProfileController::class, 'update'])->name('profile.update');
});

// Trang Home
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Password reset routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Auth routes
require __DIR__ . '/auth.php';  