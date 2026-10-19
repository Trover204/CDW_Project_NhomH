<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\CourtController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\PlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/courts/{court}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/courts/{court}/book', [BookingController::class, 'store'])->name('bookings.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/courts/{court}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::post('/plans/{plan}/subscribe', [PlanController::class, 'subscribe'])->name('plans.subscribe');

    // Màn hình hồ sơ cá nhân (Profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
