<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

describe('POST /api/admin/accomodationGalleries', function () {
    it('accepts the accommodation slug returned by the resource and stores the gallery', function () {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $token = $user->createToken('api-token', ['api:access']);

        DB::table('accomodations')->insert([
            'id' => 1,
            'slug' => 'resort-baru',
            'title' => 'Resort Baru',
            'description' => 'Deskripsi resort baru.',
            'full_description' => 'Deskripsi lengkap resort baru.',
            'image' => 'accomodations/resort-baru.jpg',
            'category' => 'resort',
            'min_price' => 100000,
            'max_price' => 250000,
            'location' => 'Temajuk',
            'location_map' => null,
            'contacs' => '08123456789',
            'site_url' => null,
            'facilities' => json_encode([], JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->post('/api/admin/accomodationGalleries', [
                'image' => UploadedFile::fake()->image('gallery.jpg'),
                'accomodation_id' => 'resort-baru',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.accomodation_id', 1)
            ->assertJsonPath('message', 'Galeri akomodasi berhasil dibuat.');

        Storage::disk('public')->assertExists($response->json('data.image'));
    });
});
