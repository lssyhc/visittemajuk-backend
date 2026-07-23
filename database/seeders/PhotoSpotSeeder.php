<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PhotoSpot;
use App\Models\PhotoSpotGalleries;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class PhotoSpotSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed photo spots along with their galleries.
     */
    public function run(): void
    {
        foreach ($this->photoSpots() as $data) {
            $galleries = $data['galleries'] ?? [];
            unset($data['galleries']);

            /** @var PhotoSpot $photoSpot */
            $photoSpot = PhotoSpot::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );

            foreach ($galleries as $imagePath) {
                PhotoSpotGalleries::query()->updateOrCreate(
                    [
                        'photo_spot_id' => $photoSpot->id,
                        'image' => $imagePath,
                    ],
                    ['photo_spot_id' => $photoSpot->id, 'image' => $imagePath],
                );
            }
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function photoSpots(): array
    {
        return [
            [
                'slug' => 'sunset-pantai-temajuk',
                'title' => 'Sunset di Pantai Temajuk',
                'description' => 'Spot fotografi sunset terbaik di Temajuk dengan siluet perahu nelayan dan langit berwarna jingga yang memukau.',
                'full_description' => 'Pantai Temajuk menawarkan salah satu spot fotografi sunset paling spektakuler di Kalimantan Barat. Ketika matahari mulai turun ke horizon Laut Natuna, langit berubah menjadi kanvas raksasa dengan gradasi warna jingga, merah muda, dan ungu yang luar biasa. Siluet perahu nelayan yang berlabuh di kejauhan menambah dimensi artistik pada setiap foto. Waktu terbaik untuk memotret adalah 30 menit sebelum hingga 15 menit setelah matahari terbenam, ketika warna langit berada pada puncak intensitasnya.',
                'image' => 'https://images.pexels.com/photos/189349/pexels-photo-189349.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Pantai',
                'bestHour' => '17.00 - 18.30 WIB',
                'location' => 'Pantai Temajuk, Desa Temajuk, Kecamatan Paloh',
                'location_map' => null,
                'tips' => ['Gunakan tripod untuk foto long exposure', 'Coba angle rendah dari garis air untuk refleksi', 'Bawa filter ND untuk efek silky water'],
                'nearestAttraction' => ['Pantai Temajuk', 'Sunset Point Temajuk'],
                'galleries' => [
                    'https://images.pexels.com/photos/189349/pexels-photo-189349.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/462024/pexels-photo-462024.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1450353/pexels-photo-1450353.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'hutan-mangrove-golden-hour',
                'title' => 'Hutan Mangrove di Golden Hour',
                'description' => 'Spot fotografi hutan mangrove saat golden hour dengan akar-akar pohon yang terpantul di air tenang.',
                'full_description' => 'Hutan Mangrove Temajuk berubah menjadi dunia ajaib fotografi saat golden hour tiba. Cahaya keemasan yang menembus celah-celah dedaunan menciptakan pola cahaya dan bayangan yang dramatis di antara akar-akar pohon bakau. Air yang tenang di saluran mangrove berfungsi sebagai cermin alami yang memantulkan keindahan hutan secara sempurna. Posisi fotografi terbaik adalah di atas jalur kayu yang melintasi hutan, di mana Anda bisa mendapatkan perspektif yang unik dari ketinggian sedang.',
                'image' => 'https://images.pexels.com/photos/2583852/pexels-photo-2583852.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Hutan',
                'bestHour' => '06.00 - 07.30 & 16.00 - 17.30 WIB',
                'location' => 'Kawasan Mangrove Desa Temajuk, Kecamatan Paloh',
                'location_map' => null,
                'tips' => ['Bawa lensa wide angle untuk landscape', 'Gunakan polarizing filter untuk mengurangi pantulan', 'Datang saat air pasang untuk refleksi terbaik', 'Gunakan obat anti nyamuk'],
                'nearestAttraction' => ['Hutan Mangrove', 'Jalur Susur Mangrove'],
                'galleries' => [
                    'https://images.pexels.com/photos/2583852/pexels-photo-2583852.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/165505/pexels-photo-165505.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/5232048/pexels-photo-5232048.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'tugu-perbatasan-sunrise',
                'title' => 'Tugu Perbatasan Saat Sunrise',
                'description' => 'Spot fotografi ikonik Tugu Perbatasan Indonesia-Malaysia dengan latar belakang matahari terbit dan hutan tropis.',
                'full_description' => 'Tugu Perbatasan Indonesia-Malaysia menjadi objek fotografi yang sangat ikonik saat matahari terbit. Cahaya pagi yang lembut menerangi monumen perbatasan dengan latar belakang hutan tropis Kalimantan yang masih perawan. Kontras antara bangunan tugu yang megah dan alam yang liar menciptakan narasi visual yang kuat tentang persatuan negara dan keindahan alam. Posisi fotografi terbaik adalah dari arah timur, di mana matahari terbit tepat di belakang tugu, menciptakan efek siluet yang dramatis.',
                'image' => 'https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Monumen',
                'bestHour' => '05.30 - 07.00 WIB',
                'location' => 'Tugu Perbatasan, Desa Temajuk, Kecamatan Paloh',
                'location_map' => null,
                'tips' => ['Datang sebelum matahari terbit untuk setup', 'Gunakan lensa tele untuk isolasi tugu', 'Sertakan elemen manusia untuk skala', 'Bawa senter untuk trekking pagi buta'],
                'nearestAttraction' => ['Tugu Perbatasan Indonesia-Malaysia', 'Landmark Perbatasan RI-Malaysia'],
                'galleries' => [
                    'https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/2559941/pexels-photo-2559941.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/372098/pexels-photo-372098.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
        ];
    }
}
