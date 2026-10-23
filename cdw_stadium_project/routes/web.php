<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\CourtController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\PartnershipController;
use App\Http\Controllers\Web\PlanController;
use App\Http\Controllers\Web\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
Route::get('/courts/search', [CourtController::class, 'search'])->name('courts.search');
Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
Route::get('/merch', [ProductController::class, 'index'])->name('products.index');
Route::get('/partnership', [PartnershipController::class, 'index'])->name('partnership.index');

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

    Route::get('/plans/{plan}/checkout', [PlanController::class, 'checkout'])->name('plans.checkout');
    Route::post('/plans/{plan}/subscribe', [PlanController::class, 'subscribe'])->name('plans.subscribe');

    Route::get('/merch/{product}/checkout', [ProductController::class, 'checkout'])->name('products.checkout');
    Route::post('/merch/{product}/purchase', [ProductController::class, 'purchase'])->name('products.purchase');

    Route::get('/partnership/checkout', [PartnershipController::class, 'checkout'])->name('partnership.checkout');
    Route::post('/partnership/process', [PartnershipController::class, 'process'])->name('partnership.process');

    // Màn hình hồ sơ cá nhân (Profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Static Pages
Route::get('/about-us', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('pages.privacy');
