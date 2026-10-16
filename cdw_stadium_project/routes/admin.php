<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CourtController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SportTypeController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('facilities', FacilityController::class);
Route::resource('sport-types', SportTypeController::class);
Route::resource('courts', CourtController::class);
Route::resource('comments', CommentController::class);
Route::resource('plans', PlanController::class);
Route::resource('subscriptions', SubscriptionController::class);
Route::resource('users', UserController::class);
Route::resource('bookings', BookingController::class);
Route::resource('payments', PaymentController::class);
Route::resource('news', NewsController::class);
