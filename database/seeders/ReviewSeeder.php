<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class ReviewSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed reviews for existing destinations.
     */
    public function run(): void
    {
        foreach ($this->reviews() as $data) {
            $destination = Destination::query()
                ->where('slug', $data['destination_slug'])
                ->first();

            if (! $destination) {
                continue;
            }

            Review::query()->updateOrCreate(
                [
                    'destination_id' => $destination->id,
                    'name' => $data['name'],
                ],
                [
                    'destination_id' => $destination->id,
                    'name' => $data['name'],
                    'text' => $data['text'],
                    'rating' => $data['rating'],
                ],
            );
        }
    }

    /**
     * @return list<array{destination_slug: string, name: string, text: string, rating: int}>
     */
    private function reviews(): array
    {
        return [
            // Pantai Temajuk reviews
            [
                'destination_slug' => 'pantai-temajuk',
                'name' => 'Rina Wijaya',
                'text' => 'Pantainya sangat indah dan masih sangat alami! Pasir putihnya lembut, air lautnya jernih banget. Sunset di sini bikin saya speechless. Pastikan bawa kamera yang bagus, setiap sudut Instagramable!',
                'rating' => 5,
            ],
            [
                'destination_slug' => 'pantai-temajuk',
                'name' => 'Dedi Kurniawan',
                'text' => 'Pantai yang sangat tenang dan jauh dari keramaian. Cocok banget buat healing dan melepas penat. Sayang akses jalannya masih kurang bagus, tapi terbayar lunas dengan keindahan pantainya.',
                'rating' => 4,
            ],
            [
                'destination_slug' => 'pantai-temajuk',
                'name' => 'Siti Aminah',
                'text' => 'Subhanallah, pantai ini luar biasa. Rasanya seperti punya pantai pribadi. Nelayan-nelayan di sini juga ramah-ramah. Recommended banget buat yang cari ketenangan!',
                'rating' => 5,
            ],

            // Tugu Perbatasan reviews
            [
                'destination_slug' => 'tugu-perbatasan',
                'name' => 'Ahmad Faisal',
                'text' => 'Pengalaman yang luar biasa bisa berdiri di ujung barat Indonesia. Tugu perbatasannya megah dan terawat. Perjalanannya melelahkan tapi worth it banget. Jangan lupa bawa identitas diri!',
                'rating' => 5,
            ],
            [
                'destination_slug' => 'tugu-perbatasan',
                'name' => 'Lina Marlina',
                'text' => 'Tempat bersejarah yang wajib dikunjungi setidaknya sekali seumur hidup. Bisa foto dengan latar dua negara sekaligus. Pemandangan hutannya juga masih sangat asri.',
                'rating' => 4,
            ],

            // Hutan Mangrove reviews
            [
                'destination_slug' => 'hutan-mangrove',
                'name' => 'Budi Santoso',
                'text' => 'Hutan mangrove yang sangat terjaga kelestariannya. Jalur susurnya rapi dan aman. Saya beruntung bisa melihat burung-burung langka saat pagi hari. Edukasi lingkungan yang sangat baik.',
                'rating' => 5,
            ],
            [
                'destination_slug' => 'hutan-mangrove',
                'name' => 'Maya Putri',
                'text' => 'Spot yang menenangkan untuk berjalan-jalan sambil menikmati udara segar. Akar-akar mangrovenya unik banget untuk difoto. Jangan lupa bawa obat anti nyamuk ya!',
                'rating' => 4,
            ],

            // Bukit Maung reviews
            [
                'destination_slug' => 'bukit-maung',
                'name' => 'Rizky Pratama',
                'text' => 'Sunrise dari puncak Bukit Maung adalah salah satu yang terbaik yang pernah saya lihat. Trekkingnya cukup menantang tapi terbayar dengan pemandangan 360 derajat yang luar biasa!',
                'rating' => 5,
            ],

            // Air Terjun Coras reviews
            [
                'destination_slug' => 'air-terjun-coras',
                'name' => 'Dewi Lestari',
                'text' => 'Air terjun bertingkat yang sangat eksotis. Airnya jernih dan sejuk, cocok untuk berendam. Jalur trekkingnya cukup berat, jadi pastikan pakai sepatu yang tepat. Tempat ini hidden gem banget!',
                'rating' => 5,
            ],
            [
                'destination_slug' => 'air-terjun-coras',
                'name' => 'Hendra Gunawan',
                'text' => 'Perjalanan ke air terjun cukup melelahkan tapi sangat sebanding. Air terjunnya indah dan suasananya sangat alami. Bawa bekal air minum yang cukup karena tidak ada warung di sekitar.',
                'rating' => 4,
            ],

            // Pantai Camar Bulan reviews
            [
                'destination_slug' => 'pantai-camar-bulan',
                'name' => 'Fitri Handayani',
                'text' => 'Pantai yang luas dan bersih. Cocok untuk jalan-jalan santai sore hari. Suasananya sangat tenang dan damai. Angin sepoi-sepoi bikin betah berlama-lama di sini.',
                'rating' => 4,
            ],

            // Teluk Atong reviews
            [
                'destination_slug' => 'teluk-atong',
                'name' => 'Arif Setiawan',
                'text' => 'Teluk yang cantik dengan karakter pantai yang berbeda. Saat surut bisa melihat batu karang yang luas, saat pasang bisa snorkeling. Pemandangan under water-nya memuaskan!',
                'rating' => 5,
            ],

            // Sunset Point Temajuk reviews
            [
                'destination_slug' => 'sunset-point-temajuk',
                'name' => 'Nina Suryani',
                'text' => 'Titik paling sempurna untuk menikmati senja di Temajuk. Warna langit sorenya dramatis banget. Datang sekitar jam 5 sore untuk mendapatkan posisi terbaik.',
                'rating' => 5,
            ],

            // Bukit Senja Temajuk reviews
            [
                'destination_slug' => 'bukit-senja-temajuk',
                'name' => 'Tono Sugiarto',
                'text' => 'Bukit yang mudah diakses tanpa pendakian berat. View ke arah pantai sangat bagus saat sore hari. Cocok untuk semua kalangan termasuk keluarga dengan anak kecil.',
                'rating' => 4,
            ],
        ];
    }
}
