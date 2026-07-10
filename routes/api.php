<?php

declare(strict_types=1);

use App\Http\Controllers\AccomodationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->as('auth.')->group(function () {
    Route::post('/login', LoginController::class)->middleware('throttle:5,1')->name('login');
    Route::post('/logout', LogoutController::class)->middleware('auth:sanctum')->name('logout');
    Route::post('/refresh', RefreshTokenController::class)->middleware(['auth:sanctum', 'ability:api:access'])->name('refresh');
});

Route::middleware(['auth:sanctum', 'ability:api:access'])->group(function () {
    Route::get('/user', [UserController::class, 'show'])->name('user.show');

    Route::prefix('admin')->as('admin.')->group(function () {
        Route::get('/accomodations', [AccomodationController::class, 'adminIndex'])->name('accomodations.index');
        Route::post('/accomodations', [AccomodationController::class, 'store'])->name('accomodations.store');
        Route::put('/accomodations/{accomodation:slug}', [AccomodationController::class, 'update'])->name('accomodations.update');
        Route::delete('/accomodations/{accomodation:slug}', [AccomodationController::class, 'destroy'])->name('accomodations.destroy');
    });
});

Route::get('/accomodations', [AccomodationController::class, 'index'])->name('accomodations.index');
Route::get('/accomodations/{accomodation:slug}', [AccomodationController::class, 'show'])->name('accomodations.show');
