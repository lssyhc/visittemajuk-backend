<?php

declare(strict_types=1);

use App\Http\Controllers\AccomodationController;
use App\Http\Controllers\AccomodationGalleriesController;
use App\Http\Controllers\AdditionalCulinaryController;
use App\Http\Controllers\AdditionalInformationController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\CulinaryController;
use App\Http\Controllers\CulinaryGalleriesController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\FooterSocialController;
use App\Http\Controllers\PhotographyTipController;
use App\Http\Controllers\PhotoSpotController;
use App\Http\Controllers\PhotoSpotGalleriesController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SiteSettingsController;
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
    Route::put('/password', ChangePasswordController::class)
        ->middleware(['auth:sanctum', 'ability:api:access', 'throttle:10,1'])
        ->name('password.update');
});

Route::prefix('admin')->as('admin.')
    ->middleware(['auth:sanctum', 'ability:api:access'])
    ->group(function () {
        Route::get('/destinations', [DestinationController::class, 'adminIndex'])->name('destinations.index');
        Route::get('/destinations/{destination:slug}', [DestinationController::class, 'adminShow'])->name('destinations.show');
        Route::post('/destinations', [DestinationController::class, 'store'])->name('destinations.store');
        Route::post('/destinations/{destination:slug}', [DestinationController::class, 'update'])->name('destinations.update');
        Route::delete('/destinations/{destination:slug}', [DestinationController::class, 'destroy'])->name('destinations.destroy');
        Route::post('/destinations/{destination:slug}/galleries', [DestinationController::class, 'addGalleryImage'])->name('destinations.galleries.store');
        Route::delete('/destinations/galleries/{gallery}', [DestinationController::class, 'removeGalleryImage'])->name('destinations.galleries.destroy');

        Route::get('/accomodations', [AccomodationController::class, 'adminIndex'])->name('accomodations.index');
        Route::post('/accomodations', [AccomodationController::class, 'store'])->name('accomodations.store');
        Route::post('/accomodations/{accomodation:slug}', [AccomodationController::class, 'update'])->name('accomodations.update');
        Route::delete('/accomodations/{accomodation:slug}', [AccomodationController::class, 'destroy'])->name('accomodations.destroy');

        Route::post('/accomodationGalleries', [AccomodationGalleriesController::class, 'store']);
        Route::delete('/accomodationGalleries/{accomodationGalleries:id}', [AccomodationGalleriesController::class, 'destroy']);

        Route::get('/photoSpots', [PhotoSpotController::class, 'adminIndex'])->name('photoSpots.index');
        Route::post('/photoSpots', [PhotoSpotController::class, 'store'])->name('photoSpots.store');
        Route::post('/photoSpots/{photoSpot}', [PhotoSpotController::class, 'update'])->name('photoSpots.update');
        Route::delete('/photoSpots/{photoSpot}', [PhotoSpotController::class, 'destroy'])->name('photoSpots.destroy');

        Route::post('/photoSpotGalleries', [PhotoSpotGalleriesController::class, 'store']);
        Route::delete('/photoSpotGalleries/{photoSpotGalleries:id}', [PhotoSpotGalleriesController::class, 'destroy']);

        Route::get('/footer/socials', [FooterSocialController::class, 'adminIndex'])->name('footer.socials.index');
        Route::post('/footer/socials', [FooterSocialController::class, 'store'])->name('footer.socials.store');
        Route::put('/footer/socials/{social}', [FooterSocialController::class, 'update'])->name('footer.socials.update');
        Route::delete('/footer/socials/{social}', [FooterSocialController::class, 'destroy'])->name('footer.socials.destroy');

        Route::put('/site/settings', [SiteSettingsController::class, 'update'])->name('site.settings.update');

        Route::get('/photography-tips', [PhotographyTipController::class, 'adminIndex'])->name('photography-tips.index');
        Route::post('/photography-tips', [PhotographyTipController::class, 'store'])->name('photography-tips.store');
        Route::put('/photography-tips/{tip}', [PhotographyTipController::class, 'update'])->name('photography-tips.update');
        Route::delete('/photography-tips/{tip}', [PhotographyTipController::class, 'destroy'])->name('photography-tips.destroy');
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

Route::get('/site/settings', [SiteSettingsController::class, 'show'])->name('site.settings.show');
Route::get('/footer/socials', [FooterSocialController::class, 'index'])->name('footer.socials.index');
Route::get('/photography-tips', [PhotographyTipController::class, 'index'])->name('photography-tips.index');

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
Route::get('/photoSpots', [PhotoSpotController::class, 'index']);
Route::get('/photoSpots/{photoSpot}', [PhotoSpotController::class, 'show']);
