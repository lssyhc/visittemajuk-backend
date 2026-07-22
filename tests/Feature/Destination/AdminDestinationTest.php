<?php

declare(strict_types=1);

use App\Models\Destination;
use App\Models\DestinationGallery;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

function adminDestinationRow(
    int $id,
    string $slug,
    string $title,
    string $description,
    string $category = 'Pantai',
): array {
    return [
        'id' => $id,
        'slug' => $slug,
        'title' => $title,
        'description' => $description,
        'full_description' => $description.' Deskripsi lengkap.',
        'image' => 'https://example.test/'.$slug.'.jpg',
        'category' => $category,
        'price' => 'Rp 10.000',
        'location' => 'Desa Temajuk',
        'open_hours' => '24 jam',
        'facilities' => json_encode(['Area Parkir'], JSON_THROW_ON_ERROR),
        'activities' => json_encode(['Berenang'], JSON_THROW_ON_ERROR),
        'tips' => json_encode(['Bawalah sunblock'], JSON_THROW_ON_ERROR),
        'created_at' => now(),
        'updated_at' => now(),
    ];
}

function adminDestinationToken(): string
{
    $user = User::factory()->create();

    return $user->createToken('api-token', ['api:access'])->plainTextToken;
}

describe('GET /api/admin/destinations', function () {
    it('searches admin destinations by title only', function () {
        DB::table('destinations')->insert([
            adminDestinationRow(1, 'pantai-temajuk', 'Pantai Temajuk', 'Pantai eksotis di Temajuk.'),
            adminDestinationRow(2, 'bukit-maung', 'Bukit Maung', 'Penyu disebut di deskripsi saja.', 'Alam'),
            adminDestinationRow(3, 'penyu-point', 'Penyu Point', 'Spot konservasi.', 'Alam'),
        ]);

        $this->withHeader('Authorization', 'Bearer '.adminDestinationToken())
            ->getJson('/api/admin/destinations?search=penyu')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', 'penyu-point')
            ->assertJsonPath('meta.pagination.total', 1);
    });

    it('filters admin destinations by category', function () {
        DB::table('destinations')->insert([
            adminDestinationRow(1, 'pantai-temajuk', 'Pantai Temajuk', 'Pantai eksotis di Temajuk.', 'Pantai'),
            adminDestinationRow(2, 'bukit-maung', 'Bukit Maung', 'Pemandangan bukit dan hutan.', 'Alam'),
            adminDestinationRow(3, 'hutan-mangrove', 'Hutan Mangrove', 'Ekosistem mangrove.', 'Alam'),
        ]);

        $this->withHeader('Authorization', 'Bearer '.adminDestinationToken())
            ->getJson('/api/admin/destinations?category=Alam')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', 'hutan-mangrove')
            ->assertJsonPath('data.1.id', 'bukit-maung')
            ->assertJsonPath('meta.filters.categories.0', 'Alam')
            ->assertJsonPath('meta.filters.categories.1', 'Pantai')
            ->assertJsonPath('meta.pagination.total', 2);
    });

    it('paginates admin destinations with metadata', function () {
        $rows = [];

        for ($number = 1; $number <= 12; $number++) {
            $rows[] = adminDestinationRow(
                $number,
                'destinasi-'.$number,
                'Destinasi '.$number,
                'Deskripsi destinasi '.$number,
            );
        }

        DB::table('destinations')->insert($rows);

        $this->withHeader('Authorization', 'Bearer '.adminDestinationToken())
            ->getJson('/api/admin/destinations?page=3&per_page=4')
            ->assertOk()
            ->assertJsonCount(4, 'data')
            ->assertJsonPath('data.0.id', 'destinasi-4')
            ->assertJsonPath('meta.pagination.current_page', 3)
            ->assertJsonPath('meta.pagination.per_page', 4)
            ->assertJsonPath('meta.pagination.last_page', 3)
            ->assertJsonPath('meta.pagination.total', 12);
    });

    it('rejects unauthenticated admin destination listing', function () {
        $this->getJson('/api/admin/destinations')
            ->assertUnauthorized()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Anda belum terautentikasi.');
    });
});

describe('POST /api/admin/destinations', function () {
    it('creates a destination with an uploaded image file', function () {
        Storage::fake('public');

        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/admin/destinations', [
                'title' => 'Pantai Temajuk',
                'description' => 'Pantai eksotis dengan pasir putih.',
                'fullDescription' => 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.',
                'image' => UploadedFile::fake()->image('pantai.jpg'),
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk',
                'openHours' => '24 jam',
                'facilities' => ['Area Parkir', 'Toilet Umum'],
                'activities' => ['Berenang', 'Melihat Sunset'],
                'tips' => ['Bawalah sunblock'],
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Destinasi berhasil dibuat.')
            ->assertJsonPath('data.id', 'pantai-temajuk')
            ->assertJsonPath('data.facilities.1', 'Toilet Umum');

        Storage::disk('public')->assertExists($response->json('data.image'));
    });

    it('rejects destination creation without an image', function () {
        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access'])->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/destinations', [
                'title' => 'Pantai No Image',
                'description' => 'Tanpa gambar.',
                'fullDescription' => 'Tanpa gambar seharusnya ditolak.',
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk',
                'openHours' => '24 jam',
                'facilities' => [],
                'activities' => [],
                'tips' => [],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    });

    it('rejects non-image file upload', function () {
        Storage::fake('public');

        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access'])->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/admin/destinations', [
                'title' => 'Pantai Teks',
                'description' => 'Gambar bukan gambar.',
                'fullDescription' => 'File yang dikirim bukan gambar.',
                'image' => UploadedFile::fake()->create('not-image.pdf', 100, 'application/pdf'),
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk',
                'openHours' => '24 jam',
                'facilities' => [],
                'activities' => [],
                'tips' => [],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    });

    it('rejects a destination title that would duplicate an existing slug', function () {
        DB::table('destinations')->insert([
            adminDestinationRow(1, 'pantai-temajuk', 'Pantai Temajuk', 'Pantai eksotis di Temajuk.'),
        ]);

        Storage::fake('public');
        $token = adminDestinationToken();

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/admin/destinations', [
                'title' => 'Pantai  Temajuk',
                'description' => 'Pantai lain dengan judul yang menghasilkan slug sama.',
                'fullDescription' => 'Pantai lain dengan judul yang menghasilkan slug sama.',
                'image' => UploadedFile::fake()->image('pantai-dup.jpg'),
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk',
                'openHours' => '24 jam',
                'facilities' => [],
                'activities' => [],
                'tips' => [],
            ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Data yang diberikan tidak valid.')
            ->assertJsonPath('errors.title.0', 'Nama destinasi sudah digunakan.');
    });
});

describe('POST /api/admin/destinations/{slug} (update)', function () {
    it('updates a destination without changing its frontend id', function () {
        Storage::fake('public');

        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access'])->plainTextToken;

        $destination = Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'Pantai eksotis dengan pasir putih.',
            'full_description' => 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.',
            'image' => 'destinations/old-pantai.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk',
            'open_hours' => '24 jam',
            'facilities' => ['Area Parkir'],
            'activities' => ['Berenang'],
            'tips' => ['Bawalah sunblock'],
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/admin/destinations/pantai-temajuk', [
                '_method' => 'POST',
                'title' => 'Pantai Temajuk Baru',
                'description' => 'Deskripsi baru.',
                'fullDescription' => 'Deskripsi lengkap baru untuk Pantai Temajuk.',
                'category' => 'Pantai',
                'price' => 'Rp 12.000',
                'location' => 'Desa Temajuk Baru',
                'openHours' => '06.00 - 18.00 WIB',
                'facilities' => ['Area Parkir', 'Warung Makan'],
                'activities' => ['Berenang', 'Snorkeling'],
                'tips' => ['Datang pagi hari'],
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Destinasi berhasil diperbarui.')
            ->assertJsonPath('data.id', 'pantai-temajuk')
            ->assertJsonPath('data.title', 'Pantai Temajuk Baru')
            ->assertJsonPath('data.openHours', '06.00 - 18.00 WIB')
            ->assertJsonPath('data.facilities.1', 'Warung Makan');

        // Original image preserved (no new upload)
        expect($destination->fresh()->image)->toBe('destinations/old-pantai.jpg');
    });

    it('replaces image and deletes old file when new file uploaded', function () {
        Storage::fake('public');
        // seed an existing image file
        Storage::disk('public')->put('destinations/old.jpg', 'old-content');

        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access'])->plainTextToken;

        Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'destinations/old.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk',
            'open_hours' => '24 jam',
            'facilities' => [],
            'activities' => [],
            'tips' => [],
        ]);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/admin/destinations/pantai-temajuk', [
                '_method' => 'POST',
                'title' => 'Pantai Temajuk',
                'description' => 'desc',
                'fullDescription' => 'full',
                'image' => UploadedFile::fake()->image('new.jpg'),
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk',
                'openHours' => '24 jam',
                'facilities' => [],
                'activities' => [],
                'tips' => [],
            ])
            ->assertOk();

        Storage::disk('public')->assertMissing('destinations/old.jpg');
    });
});

describe('DELETE /api/admin/destinations/{slug}', function () {
    it('deletes a destination by frontend id', function () {
        Storage::fake('public');
        Storage::disk('public')->put('destinations/pantai.jpg', 'content');

        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access'])->plainTextToken;

        Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'destinations/pantai.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk',
            'open_hours' => '24 jam',
            'facilities' => [],
            'activities' => [],
            'tips' => [],
        ]);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/destinations/pantai-temajuk')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Destinasi berhasil dihapus.');

        Storage::disk('public')->assertMissing('destinations/pantai.jpg');
        $this->assertDatabaseMissing('destinations', ['slug' => 'pantai-temajuk']);
    });
});

describe('POST /api/admin/destinations/{slug}/galleries', function () {
    it('adds a gallery image to an existing destination', function () {
        Storage::fake('public');

        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access'])->plainTextToken;

        $destination = Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'destinations/main.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk',
            'open_hours' => '24 jam',
            'facilities' => [],
            'activities' => [],
            'tips' => [],
        ]);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/admin/destinations/pantai-temajuk/galleries', [
                'image' => UploadedFile::fake()->image('gallery.jpg'),
                'sort_order' => 2,
            ])
            ->assertCreated()
            ->assertJsonPath('data.sortOrder', 2);

        Storage::disk('public')->assertExists(DestinationGallery::query()->first()->image);
    });
});

describe('GET /api/admin/destinations/{slug} (adminShow)', function () {
    it('returns a single destination with galleries', function () {
        $destination = Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'desc',
            'full_description' => 'full desc',
            'image' => 'destinations/main.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk',
            'open_hours' => '24 jam',
            'facilities' => [],
            'activities' => [],
            'tips' => [],
        ]);

        DestinationGallery::query()->create([
            'destination_id' => $destination->id,
            'image' => 'destinations/galleries/g1.jpg',
            'sort_order' => 1,
        ]);

        $this->withHeader('Authorization', 'Bearer '.adminDestinationToken())
            ->getJson('/api/admin/destinations/pantai-temajuk')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', 'pantai-temajuk')
            ->assertJsonPath('data.title', 'Pantai Temajuk')
            ->assertJsonPath('data.galleries.0.image', 'destinations/galleries/g1.jpg');
    });

    it('returns 404 for non-existent slug', function () {
        $this->withHeader('Authorization', 'Bearer '.adminDestinationToken())
            ->getJson('/api/admin/destinations/tidak-ada')
            ->assertNotFound();
    });

    it('rejects unauthenticated request', function () {
        $this->getJson('/api/admin/destinations/pantai-temajuk')
            ->assertUnauthorized();
    });
});

describe('DELETE /api/admin/destinations/galleries/{gallery} (removeGalleryImage)', function () {
    it('removes gallery image and deletes file from storage', function () {
        Storage::fake('public');
        Storage::disk('public')->put('destinations/galleries/g1.jpg', 'content');

        $destination = Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'destinations/main.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk',
            'open_hours' => '24 jam',
            'facilities' => [],
            'activities' => [],
            'tips' => [],
        ]);

        $gallery = DestinationGallery::query()->create([
            'destination_id' => $destination->id,
            'image' => 'destinations/galleries/g1.jpg',
            'sort_order' => 1,
        ]);

        $this->withHeader('Authorization', 'Bearer '.adminDestinationToken())
            ->deleteJson("/api/admin/destinations/galleries/{$gallery->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Galeri destinasi berhasil dihapus.');

        $this->assertDatabaseMissing('destination_galleries', ['id' => $gallery->id]);
        Storage::disk('public')->assertMissing('destinations/galleries/g1.jpg');
    });

    it('rejects unauthenticated request', function () {
        $this->deleteJson('/api/admin/destinations/galleries/1')
            ->assertUnauthorized();
    });
});
