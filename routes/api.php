<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\CulinaryController;
use App\Http\Controllers\CulinaryGalleriesController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->as('auth.')->group(function () {
    Route::post('/login', LoginController::class)->middleware('throttle:5,1')->name('login');
    Route::post('/logout', LogoutController::class)->middleware('auth:sanctum')->name('logout');
    Route::post('/refresh', RefreshTokenController::class)->middleware(['auth:sanctum', 'ability:api:access'])->name('refresh');
});

Route::middleware(['auth:sanctum', 'ability:api:access'])->group(function () {
    Route::get('/user', [UserController::class, 'show'])->name('user.show');

    Route::post('/culinary', [CulinaryController::class, 'store']);
    Route::post('/culinary/{id}', [CulinaryController::class, 'update']);
    Route::delete('/culinary/{id}', [CulinaryController::class, 'destroy']);

    Route::post('/specialty', [SpecialtyController::class, 'store']);
    Route::put('/specialty/{id}', [SpecialtyController::class, 'update']);
    Route::delete('/specialty/{id}', [SpecialtyController::class, 'destroy']);

    Route::post('/culinaryGalleries', [CulinaryGalleriesController::class, 'store']);
    Route::delete('/culinaryGalleries/{id}', [CulinaryGalleriesController::class, 'destroy']);

});

Route::get('/culinary', [CulinaryController::class, 'index']);
Route::get('/culinary/{id}', [CulinaryController::class, 'show']);
Route::get('/specialty/{id}', [SpecialtyController::class, 'show']);
Route::get('/culinaryGalleries/{id}', [CulinaryGalleriesController::class, 'show']);
Route::get('/review', [ReviewController::class, 'index']);
Route::post('/review', [ReviewController::class, 'store']);
Route::get('/destination', [DestinationController::class, 'index']);
