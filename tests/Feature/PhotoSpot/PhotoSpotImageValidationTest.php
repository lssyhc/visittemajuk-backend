<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('PhotoSpot image validation', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('api-token', ['api:access'])->plainTextToken;
    });

    it('accepts valid jpg image for create', function () {
        Storage::fake('public');

        $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->post('/api/admin/photoSpots', [
                'title' => 'Sunset Point',
                'description' => 'Nice spot.',
                'full_description' => 'Full description.',
                'image' => UploadedFile::fake()->image('spot.jpg'),
                'category' => 'Pantai',
                'bestHour' => '17:00',
                'location' => 'Temajuk',
                'nearestAttraction' => ['Pantai Temajuk'],
            ])
            ->assertCreated();
    });

    it('rejects png image for create', function () {
        Storage::fake('public');

        $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->post('/api/admin/photoSpots', [
                'title' => 'Bad Spot',
                'description' => 'Desc.',
                'full_description' => 'Full.',
                'image' => UploadedFile::fake()->create('bad.png', 100, 'image/png'),
                'category' => 'Pantai',
                'bestHour' => '17:00',
                'location' => 'Temajuk',
                'nearestAttraction' => ['Pantai'],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    });

    it('rejects oversized image (>1MB) for create', function () {
        Storage::fake('public');

        $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->post('/api/admin/photoSpots', [
                'title' => 'Large Spot',
                'description' => 'Desc.',
                'full_description' => 'Full.',
                'image' => UploadedFile::fake()->image('big.jpg')->size(1025),
                'category' => 'Pantai',
                'bestHour' => '17:00',
                'location' => 'Temajuk',
                'nearestAttraction' => ['Pantai'],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    });

    it('rejects non-image file for create', function () {
        Storage::fake('public');

        $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->post('/api/admin/photoSpots', [
                'title' => 'PDF Spot',
                'description' => 'Desc.',
                'full_description' => 'Full.',
                'image' => UploadedFile::fake()->create('file.pdf', 100, 'application/pdf'),
                'category' => 'Pantai',
                'bestHour' => '17:00',
                'location' => 'Temajuk',
                'nearestAttraction' => ['Pantai'],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    });
});
