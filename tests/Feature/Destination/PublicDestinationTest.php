<?php

declare(strict_types=1);

use App\Models\Destination;
use App\Models\DestinationGallery;
use Illuminate\Support\Facades\DB;

function publicDestinationRow(
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

describe('GET /api/destinations', function () {
    it('returns destinations using the frontend destination contract', function () {
        $destination = Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'Pantai eksotis dengan pasir putih.',
            'full_description' => 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.',
            'image' => 'https://example.test/pantai.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk',
            'open_hours' => '24 jam',
            'facilities' => ['Area Parkir', 'Toilet Umum'],
            'activities' => ['Berenang', 'Melihat Sunset'],
            'tips' => ['Bawalah sunblock'],
        ]);

        DestinationGallery::query()->create([
            'destination_id' => $destination->id,
            'image' => 'https://example.test/gallery.jpg',
            'sort_order' => 1,
        ]);

        $this->getJson('/api/destinations')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Berhasil.')
            ->assertJsonPath('data.0.id', 'pantai-temajuk')
            ->assertJsonPath('data.0.title', 'Pantai Temajuk')
            ->assertJsonPath('data.0.fullDescription', 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.')
            ->assertJsonPath('data.0.image', 'https://example.test/pantai.jpg')
            ->assertJsonPath('data.0.openHours', '24 jam')
            ->assertJsonPath('data.0.facilities.0', 'Area Parkir')
            ->assertJsonPath('data.0.activities.1', 'Melihat Sunset')
            ->assertJsonPath('data.0.tips.0', 'Bawalah sunblock')
            ->assertJsonPath('data.0.galleries.0.image', 'https://example.test/gallery.jpg')
            ->assertJsonMissingPath('data.0.slug')
            ->assertJsonMissingPath('data.0.created_at');
    });

    it('searches public destinations by title and description', function () {
        DB::table('destinations')->insert([
            publicDestinationRow(1, 'pantai-temajuk', 'Pantai Temajuk', 'Pantai eksotis di Temajuk.'),
            publicDestinationRow(2, 'bukit-maung', 'Bukit Maung', 'Pemandangan bukit dan hutan.'),
            publicDestinationRow(3, 'sunset-point', 'Sunset Point', 'Spot terbaik untuk melihat penyu bertelur.', 'Alam'),
        ]);

        $this->getJson('/api/destinations?search=penyu')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', 'sunset-point')
            ->assertJsonPath('meta.pagination.total', 1);
    });

    it('filters public destinations by category', function () {
        DB::table('destinations')->insert([
            publicDestinationRow(1, 'pantai-temajuk', 'Pantai Temajuk', 'Pantai eksotis di Temajuk.', 'Pantai'),
            publicDestinationRow(2, 'bukit-maung', 'Bukit Maung', 'Pemandangan bukit dan hutan.', 'Alam'),
            publicDestinationRow(3, 'hutan-mangrove', 'Hutan Mangrove', 'Ekosistem mangrove.', 'Alam'),
        ]);

        $this->getJson('/api/destinations?category=Alam')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', 'hutan-mangrove')
            ->assertJsonPath('data.1.id', 'bukit-maung')
            ->assertJsonPath('meta.filters.categories.0', 'Alam')
            ->assertJsonPath('meta.filters.categories.1', 'Pantai')
            ->assertJsonPath('meta.pagination.total', 2);
    });

    it('paginates public destinations with metadata', function () {
        $rows = [];

        for ($number = 1; $number <= 12; $number++) {
            $rows[] = publicDestinationRow(
                $number,
                'destinasi-'.$number,
                'Destinasi '.$number,
                'Deskripsi destinasi '.$number,
            );
        }

        DB::table('destinations')->insert($rows);

        $this->getJson('/api/destinations?page=2&per_page=5')
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('data.0.id', 'destinasi-7')
            ->assertJsonPath('meta.pagination.current_page', 2)
            ->assertJsonPath('meta.pagination.per_page', 5)
            ->assertJsonPath('meta.pagination.last_page', 3)
            ->assertJsonPath('meta.pagination.total', 12);
    });
});

describe('GET /api/destinations/{slug}', function () {
    it('returns one destination by slug', function () {
        Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'Pantai eksotis dengan pasir putih.',
            'full_description' => 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.',
            'image' => 'https://example.test/pantai.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk',
            'open_hours' => '24 jam',
            'facilities' => ['Area Parkir', 'Toilet Umum'],
            'activities' => ['Berenang', 'Melihat Sunset'],
            'tips' => ['Bawalah sunblock'],
        ]);

        $this->getJson('/api/destinations/pantai-temajuk')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', 'pantai-temajuk')
            ->assertJsonPath('data.title', 'Pantai Temajuk')
            ->assertJsonPath('data.fullDescription', 'Pantai Temajuk adalah pantai eksotis di ujung barat Indonesia.');
    });

    it('returns empty envelope for empty database', function () {
        $this->getJson('/api/destinations')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', [])
            ->assertJsonPath('meta.pagination.total', 0)
            ->assertJsonPath('meta.filters.categories', []);
    });

    it('returns 404 for non-existent slug', function () {
        $this->getJson('/api/destinations/tidak-ada')
            ->assertNotFound();
    });
});
