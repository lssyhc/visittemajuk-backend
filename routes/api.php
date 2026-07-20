<?php

declare(strict_types=1);

use App\Http\Controllers\AccomodationController;
use App\Http\Controllers\AdditionalCulinaryController;
use App\Http\Controllers\AdditionalInformationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\CulinaryController;
use App\Http\Controllers\CulinaryGalleriesController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\TransportationController;
use App\Http\Controllers\TransportationStepsController;
use App\Http\Controllers\TransportationTipsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->as('auth.')->group(function () {
    Route::post('/login', LoginController::class)->middleware('throttle:5,1')->name('login');
    Route::post('/logout', LogoutController::class)->middleware('auth:sanctum')->name('logout');
    Route::post('/refresh', RefreshTokenController::class)->middleware(['auth:sanctum', 'ability:api:access'])->name('refresh');
});

Route::prefix('admin')->as('admin.')
    ->middleware(['auth:sanctum', 'ability:api:access'])
    ->group(function () {
        Route::get('/destinations', [DestinationController::class, 'adminIndex'])->name('destinations.index');
        Route::post('/destinations', [DestinationController::class, 'store'])->name('destinations.store');
        Route::put('/destinations/{destination:slug}', [DestinationController::class, 'update'])->name('destinations.update');
        Route::delete('/destinations/{destination:slug}', [DestinationController::class, 'destroy'])->name('destinations.destroy');

        Route::get('/accomodations', [AccomodationController::class, 'adminIndex'])->name('accomodations.index');
        Route::post('/accomodations', [AccomodationController::class, 'store'])->name('accomodations.store');
        Route::put('/accomodations/{accomodation:slug}', [AccomodationController::class, 'update'])->name('accomodations.update');
        Route::delete('/accomodations/{accomodation:slug}', [AccomodationController::class, 'destroy'])->name('accomodations.destroy');
    });

Route::middleware(['auth:sanctum', 'ability:api:access'])->group(function () {
    Route::get('/user', [UserController::class, 'show'])->name('user.show');

    Route::post('/culinaries', [CulinaryController::class, 'store']);
    Route::post('/culinaries/{culinary:id}', [CulinaryController::class, 'update']);
    Route::delete('/culinaries/{culinary:id}', [CulinaryController::class, 'destroy']);

    Route::post('/specialties', [SpecialtyController::class, 'store']);
    Route::put('/specialties/{specialty:id}', [SpecialtyController::class, 'update']);
    Route::delete('/specialties/{specialty:id}', [SpecialtyController::class, 'destroy']);

    Route::post('/culinaryGalleries', [CulinaryGalleriesController::class, 'store']);
    Route::delete('/culinaryGalleries/{culinaryGalleries:id}', [CulinaryGalleriesController::class, 'destroy']);

    Route::post('/transportations', [TransportationController::class, 'store']);
    Route::post('/transportations/{transportation:id}', [TransportationController::class, 'update']);
    Route::delete('/transportations/{transportation:id}', [TransportationController::class, 'destroy']);

    Route::post('/transportationSteps', [TransportationStepsController::class, 'store']);
    Route::put('/transportationSteps/{transportationSteps:id}', [TransportationStepsController::class, 'update']);
    Route::delete('/transportationSteps/{transportationSteps:id}', [TransportationStepsController::class, 'destroy']);

    Route::post('/transportationTips', [TransportationTipsController::class, 'store']);
    Route::put('/transportationTips/{transportationTips:id}', [TransportationTipsController::class, 'update']);
    Route::delete('/transportationTips/{transportationTips:id}', [TransportationTipsController::class, 'destroy']);

    Route::post('/additionalCulinaries', [AdditionalCulinaryController::class, 'store']);
    Route::post('/additionalCulinaries/{additionalCulinary:id}', [AdditionalCulinaryController::class, 'update']);
    Route::get('/additionalCulinaries/{additionalCulinary:id}', [AdditionalCulinaryController::class, 'show']);
    Route::delete('/additionalCulinaries/{additionalCulinary:id}', [AdditionalCulinaryController::class, 'destroy']);

    Route::post('/additionalInformation', [AdditionalInformationController::class, 'store']);
    Route::put('/additionalInformation/{additionalInformation:id}', [AdditionalInformationController::class, 'update']);
    Route::get('/additionalInformation/{additionalInformation:id}', [AdditionalInformationController::class, 'show']);
    Route::delete('/additionalInformation/{additionalInformation:id}', [AdditionalInformationController::class, 'destroy']);
});

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination:slug}', [DestinationController::class, 'show'])->name('destinations.show');
Route::get('/culinaries', [CulinaryController::class, 'index']);
Route::get('/culinaries/{culinary}', [CulinaryController::class, 'show']);
Route::get('/reviews', [ReviewController::class, 'index']);
Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/accomodations', [AccomodationController::class, 'index'])->name('accomodations.index');
Route::get('/accomodations/{accomodation:slug}', [AccomodationController::class, 'show'])->name('accomodations.show');
Route::get('/transportations', [TransportationController::class, 'index']);
Route::get('/transportations/{transportation:id}', [TransportationController::class, 'show']);
Route::get('/additionalCulinaries', [AdditionalCulinaryController::class, 'index']);
Route::get('/additionalInformation', [AdditionalInformationController::class, 'index']);
