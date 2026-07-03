<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\DestinationController;
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
        Route::get('/destinations', [DestinationController::class, 'adminIndex'])->name('destinations.index');
        Route::post('/destinations', [DestinationController::class, 'store'])->name('destinations.store');
        Route::put('/destinations/{destination:slug}', [DestinationController::class, 'update'])->name('destinations.update');
        Route::delete('/destinations/{destination:slug}', [DestinationController::class, 'destroy'])->name('destinations.destroy');
    });
});

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination:slug}', [DestinationController::class, 'show'])->name('destinations.show');
