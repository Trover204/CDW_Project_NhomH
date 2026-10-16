<?php

use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CourtController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\SportTypeController;
use App\Http\Controllers\Admin\PlanController;
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
