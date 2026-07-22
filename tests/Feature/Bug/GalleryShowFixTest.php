<?php

declare(strict_types=1);

use App\Http\Controllers\AccomodationGalleriesController;
use App\Http\Controllers\PhotoSpotGalleriesController;
use App\Models\Accomodation;
use App\Models\AccomodationGalleries;
use App\Models\PhotoSpot;
use App\Models\PhotoSpotGalleries;

describe('Bug B4: PhotoSpotGalleriesController::show fix', function () {
    it('controller returns galleries filtered by photo_spot_id, not by gallery id', function () {
        $photoSpot = PhotoSpot::query()->create([
            'slug' => 'spot-1',
            'title' => 'Spot 1',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'photospots/spot-1.jpg',
            'category' => 'Alam',
            'bestHour' => '06.00 - 18.00',
            'location' => 'Temajuk',
            'nearestAttraction' => ['Pantai'],
            'tips' => ['Datang saat golden hour'],
        ]);

        $spot2 = PhotoSpot::query()->create([
            'slug' => 'spot-2',
            'title' => 'Spot 2',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'photospots/spot-2.jpg',
            'category' => 'Alam',
            'bestHour' => '06.00 - 18.00',
            'location' => 'Temajuk',
            'nearestAttraction' => ['Pantai'],
            'tips' => ['Datang saat golden hour'],
        ]);

        PhotoSpotGalleries::query()->create(['photo_spot_id' => $photoSpot->id, 'image' => 'ps/gallery-1.jpg']);
        PhotoSpotGalleries::query()->create(['photo_spot_id' => $photoSpot->id, 'image' => 'ps/gallery-2.jpg']);
        PhotoSpotGalleries::query()->create(['photo_spot_id' => $spot2->id, 'image' => 'ps/other.jpg']);

        // Invoke controller directly to bypass route registration
        $controller = app(PhotoSpotGalleriesController::class);
        $firstGallery = PhotoSpotGalleries::query()->where('photo_spot_id', $photoSpot->id)->first();

        $response = $controller->show($firstGallery);
        expect($response->getStatusCode())->toBe(200);

        $payload = json_decode($response->getContent(), true);
        expect(count($payload['data']))->toBe(2);
    });
});

describe('Bug B5: AccomodationGalleriesController::show fix', function () {
    it('controller returns galleries filtered by accomodation_id, not by gallery id', function () {
        $accomodation = Accomodation::query()->create([
            'slug' => 'resort-a',
            'title' => 'Resort A',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'accomodations/resort-a.jpg',
            'category' => 'resort',
            'min_price' => 100000,
            'max_price' => 200000,
            'location' => 'Temajuk',
            'contacs' => '08123',
            'facilities' => [],
        ]);

        $accomodation2 = Accomodation::query()->create([
            'slug' => 'resort-b',
            'title' => 'Resort B',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'accomodations/resort-b.jpg',
            'category' => 'resort',
            'min_price' => 100000,
            'max_price' => 200000,
            'location' => 'Temajuk',
            'contacs' => '08123',
            'facilities' => [],
        ]);

        AccomodationGalleries::query()->create(['accomodation_id' => $accomodation->id, 'image' => 'acc/g1.jpg']);
        AccomodationGalleries::query()->create(['accomodation_id' => $accomodation->id, 'image' => 'acc/g2.jpg']);
        AccomodationGalleries::query()->create(['accomodation_id' => $accomodation2->id, 'image' => 'acc/other.jpg']);

        $controller = app(AccomodationGalleriesController::class);
        $firstGallery = AccomodationGalleries::query()->where('accomodation_id', $accomodation->id)->first();

        $response = $controller->show($firstGallery);
        expect($response->getStatusCode())->toBe(200);

        $payload = json_decode($response->getContent(), true);
        expect(count($payload['data']))->toBe(2);
    });
});
