<?php

use App\Http\Controllers\Admin\SportTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::resource('sport-types', SportTypeController::class)->except('show');