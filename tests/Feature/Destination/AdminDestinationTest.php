<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        'image_url' => 'https://example.test/'.$slug.'.jpg',
        'category' => $category,
        'price' => 'Rp 10.000',
        'location' => 'Desa Temajuk',
        'open_hours' => '24 jam',
        'facilities' => json_encode(['Area Parkir'], JSON_THROW_ON_ERROR),
        'activities' => json_encode(['Berenang'], JSON_THROW_ON_ERROR),
        'tips' => json_encode(['Bawalah sunblock'], JSON_THROW_ON_ERROR),
        'gallery' => json_encode(['https://example.test/'.$slug.'-gallery.jpg'], JSON_THROW_ON_ERROR),
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
            ->assertJsonPath('data.0.id', 'bukit-maung')
            ->assertJsonPath('data.1.id', 'hutan-mangrove')
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
            ->assertJsonPath('data.0.id', 'destinasi-9')
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
    it('creates a destination from the admin form payload', function () {
        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access']);

        $response = $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/admin/destinations', [
                'title' => 'Pantai Temajuk',
                'description' => 'Pantai eksotis dengan pasir putih.',
                'fullDescription' => 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.',
                'imageUrl' => 'https://example.test/pantai.jpg',
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk',
                'openHours' => '24 jam',
                'facilities' => ['Area Parkir', 'Toilet Umum'],
                'activities' => ['Berenang', 'Melihat Sunset'],
                'tips' => ['Bawalah sunblock'],
                'gallery' => ['https://example.test/gallery.jpg'],
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Destinasi berhasil dibuat.')
            ->assertJsonPath('data.id', 'pantai-temajuk')
            ->assertJsonPath('data.fullDescription', 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.')
            ->assertJsonPath('data.imageUrl', 'https://example.test/pantai.jpg')
            ->assertJsonPath('data.openHours', '24 jam')
            ->assertJsonPath('data.facilities.1', 'Toilet Umum');

        $this->assertDatabaseHas('destinations', [
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'full_description' => 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.',
            'image_url' => 'https://example.test/pantai.jpg',
            'open_hours' => '24 jam',
        ]);
    });

    it('allows empty list fields from the admin form', function () {
        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access']);

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/admin/destinations', [
                'title' => 'Pantai Baru',
                'description' => 'Pantai baru.',
                'fullDescription' => 'Pantai baru untuk dikunjungi.',
                'imageUrl' => 'https://example.test/pantai-baru.jpg',
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk',
                'openHours' => '24 jam',
                'facilities' => [],
                'activities' => [],
                'tips' => [],
                'gallery' => [],
            ])
            ->assertCreated()
            ->assertJsonPath('data.facilities', [])
            ->assertJsonPath('data.activities', [])
            ->assertJsonPath('data.tips', [])
            ->assertJsonPath('data.gallery', []);
    });

    it('rejects relative image paths from the admin form', function () {
        $this->withHeader('Authorization', 'Bearer '.adminDestinationToken())
            ->postJson('/api/admin/destinations', [
                'title' => 'Pantai Relative',
                'description' => 'Pantai dengan gambar relative.',
                'fullDescription' => 'Pantai dengan gambar relative yang harus ditolak.',
                'imageUrl' => 'images/batu-nenek-aluwi.jpg',
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk',
                'openHours' => '24 jam',
                'facilities' => [],
                'activities' => [],
                'tips' => [],
                'gallery' => ['images/batu-nenek-aluwi.jpg'],
            ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Data yang diberikan tidak valid.')
            ->assertJsonValidationErrors(['imageUrl', 'gallery.0'])
            ->assertJsonFragment(['URL gambar utama harus berupa URL yang valid.'])
            ->assertJsonFragment(['URL galeri harus berupa URL yang valid.']);
    });

    it('rejects a destination title that would duplicate an existing slug', function () {
        DB::table('destinations')->insert([
            adminDestinationRow(1, 'pantai-temajuk', 'Pantai Temajuk', 'Pantai eksotis di Temajuk.'),
        ]);

        $this->withHeader('Authorization', 'Bearer '.adminDestinationToken())
            ->postJson('/api/admin/destinations', [
                'title' => 'Pantai  Temajuk',
                'description' => 'Pantai lain dengan judul yang menghasilkan slug sama.',
                'fullDescription' => 'Pantai lain dengan judul yang menghasilkan slug sama.',
                'imageUrl' => 'https://example.test/pantai-duplikat.jpg',
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk',
                'openHours' => '24 jam',
                'facilities' => [],
                'activities' => [],
                'tips' => [],
                'gallery' => [],
            ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Data yang diberikan tidak valid.')
            ->assertJsonPath('errors.title.0', 'Nama destinasi sudah digunakan.');
    });
});

describe('PUT /api/admin/destinations/{id}', function () {
    it('updates a destination without changing its frontend id', function () {
        DB::table('destinations')->insert([
            'id' => 1,
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'Pantai eksotis dengan pasir putih.',
            'full_description' => 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.',
            'image_url' => 'https://example.test/pantai.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk',
            'open_hours' => '24 jam',
            'facilities' => json_encode(['Area Parkir'], JSON_THROW_ON_ERROR),
            'activities' => json_encode(['Berenang'], JSON_THROW_ON_ERROR),
            'tips' => json_encode(['Bawalah sunblock'], JSON_THROW_ON_ERROR),
            'gallery' => json_encode(['https://example.test/gallery.jpg'], JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access']);

        $response = $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->putJson('/api/admin/destinations/pantai-temajuk', [
                'title' => 'Pantai Temajuk Baru',
                'description' => 'Deskripsi baru.',
                'fullDescription' => 'Deskripsi lengkap baru untuk Pantai Temajuk.',
                'imageUrl' => 'https://example.test/pantai-baru.jpg',
                'category' => 'Pantai',
                'price' => 'Rp 12.000',
                'location' => 'Desa Temajuk Baru',
                'openHours' => '06.00 - 18.00 WIB',
                'facilities' => ['Area Parkir', 'Warung Makan'],
                'activities' => ['Berenang', 'Snorkeling'],
                'tips' => ['Datang pagi hari'],
                'gallery' => ['https://example.test/gallery-baru.jpg'],
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Destinasi berhasil diperbarui.')
            ->assertJsonPath('data.id', 'pantai-temajuk')
            ->assertJsonPath('data.title', 'Pantai Temajuk Baru')
            ->assertJsonPath('data.openHours', '06.00 - 18.00 WIB')
            ->assertJsonPath('data.facilities.1', 'Warung Makan');

        $this->assertDatabaseHas('destinations', [
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk Baru',
            'image_url' => 'https://example.test/pantai-baru.jpg',
            'open_hours' => '06.00 - 18.00 WIB',
        ]);
    });
});

describe('DELETE /api/admin/destinations/{id}', function () {
    it('deletes a destination by frontend id', function () {
        DB::table('destinations')->insert([
            'id' => 1,
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'Pantai eksotis dengan pasir putih.',
            'full_description' => 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.',
            'image_url' => 'https://example.test/pantai.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk',
            'open_hours' => '24 jam',
            'facilities' => json_encode(['Area Parkir'], JSON_THROW_ON_ERROR),
            'activities' => json_encode(['Berenang'], JSON_THROW_ON_ERROR),
            'tips' => json_encode(['Bawalah sunblock'], JSON_THROW_ON_ERROR),
            'gallery' => json_encode(['https://example.test/gallery.jpg'], JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access']);

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->deleteJson('/api/admin/destinations/pantai-temajuk')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Destinasi berhasil dihapus.')
            ->assertJsonPath('data', null);

        $this->assertDatabaseMissing('destinations', [
            'slug' => 'pantai-temajuk',
        ]);
    });
});
