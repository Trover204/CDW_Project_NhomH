
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

use App\Http\Controllers\FavoriteController;

Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/courts/{court}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});
