<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Culinary;
use App\Models\CulinaryGalleries;
use App\Models\Specialty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class CulinarySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed culinaries along with their specialties and galleries.
     */
    public function run(): void
    {
        foreach ($this->culinaries() as $data) {
            $specialties = $data['specialties'] ?? [];
            $galleries = $data['galleries'] ?? [];
            unset($data['specialties'], $data['galleries']);

            /** @var Culinary $culinary */
            $culinary = Culinary::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );

            foreach ($specialties as $menu) {
                Specialty::query()->updateOrCreate(
                    [
                        'culinary_id' => $culinary->id,
                        'menu' => $menu['menu'],
                    ],
                    $menu + ['culinary_id' => $culinary->id],
                );
            }

            foreach ($galleries as $imagePath) {
                CulinaryGalleries::query()->updateOrCreate(
                    [
                        'culinary_id' => $culinary->id,
                        'image' => $imagePath['image'],
                    ],
                    $imagePath + ['culinary_id' => $culinary->id],
                );
            }
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function culinaries(): array
    {
        return [
            [
                'slug' => 'rumah-makan-pantai-temajuk',
                'title' => 'Rumah Makan Pantai Temajuk',
                'description' => 'Rumah makan tepi pantai yang menyajikan hidangan laut segar hasil tangkapan nelayan lokal setiap hari.',
                'full_description' => 'Rumah Makan Pantai Temajuk berdiri di tepi hamparan pasir putih Pantai Temajuk, menawarkan pengalaman bersantap seafood yang tak tertandingi. Setiap hidangan diolah dari ikan, udang, dan kepiting segar yang baru ditangkap oleh nelayan lokal di pagi hari. Suasana santai dengan angin laut yang sepoi-sepoi dan pemandangan matahari terbenam yang memukau menjadikan setiap makan malam di sini momen yang tak terlupakan. Masakan khas Melayu Sambas dengan bumbu rempah pilihan menjadi daya tarik utama yang membuat wisatawan selalu kembali.',
                'image' => 'https://images.pexels.com/photos/67468/pexels-photo-67468.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Seafood',
                'price' => 'Rp 25.000 - Rp 85.000',
                'location' => 'Jl. Pantai Temajuk, Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'location_map' => null,
                'open_hours' => '08.00 - 21.00 WIB',
                'contact' => '+62 812-3456-7890',
                'specialties' => [
                    [
                        'menu' => 'Ikan Bakar Sambal Dabu-Dabu',
                        'order' => 1,
                    ],
                    [
                        'menu' => 'Udang Galah Bumbu Rujak',
                        'order' => 2,
                    ],
                    [
                        'menu' => 'Kepiting Saus Padang',
                        'order' => 3,
                    ],
                    [
                        'menu' => 'Sup Ikan Kuah Asam',
                        'order' => 4,
                    ],
                    [
                        'menu' => 'Nasi Goreng Seafood Spesial',
                        'order' => 5,
                    ],
                ],
                'galleries' => [
                    [
                        'image' => 'https://images.pexels.com/photos/323682/pexels-photo-323682.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                        'order' => 1,
                    ],
                    [
                        'image' => 'https://images.pexels.com/photos/262959/pexels-photo-262959.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                        'order' => 2,
                    ],
                    [
                        'image' => 'https://images.pexels.com/photos/725997/pexels-photo-725997.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                        'order' => 3,
                    ],
                ],
            ],
            [
                'slug' => 'warung-bujang-temajuk',
                'title' => 'Warung Bujang Temajuk',
                'description' => 'Warung legendaris milik warga lokal yang terkenal dengan masakan rumahan khas Melayu Sambas yang autentik.',
                'full_description' => 'Warung Bujang Temajuk adalah warung sederhana yang telah berdiri sejak tahun 1990-an, dikelola oleh Pak Bujang dan keluarganya. Warung ini menjadi destinasi kuliner wajib bagi setiap pengunjung Temajuk berkat cita rasa masakan rumahan Melayu Sambas yang tak berubah sejak generasi pertama. Menu andalannya adalah nasi campur Melayu dengan berbagai lauk pauk tradisional yang disajikan dalam porsi besar dan harga yang sangat terjangkau. Suasana warung yang hangat dan ramah membuat pengunjung sering berlama-lama sambil bercerita dengan tuan rumah.',
                'image' => 'https://images.pexels.com/photos/2814828/pexels-photo-2814828.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Lokal',
                'price' => 'Rp 15.000 - Rp 45.000',
                'location' => 'Jl. Utama Desa Temajuk Rt. 02, Kecamatan Paloh, Kabupaten Sambas',
                'location_map' => null,
                'open_hours' => '06.00 - 20.00 WIB',
                'contact' => '+62 813-9876-5432',
                'specialties' => [
                    [
                        'menu' => 'Nasi Campur Melayu',
                        'order' => 1,
                    ],
                    [
                        'menu' => 'Ayam Masak Kuning',
                        'order' => 2,
                    ],
                    [
                        'menu' => 'Sayur Pakis Santan',
                        'order' => 3,
                    ],
                    [
                        'menu' => 'Ikan Asin Jambal Roti',
                        'order' => 4,
                    ],
                    [
                        'menu' => 'Kue Basah Tradisional',
                        'order' => 5,
                    ],
                ],
                'galleries' => [
                    [
                        'image' => 'https://images.pexels.com/photos/1640777/pexels-photo-1640777.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                        'order' => 1,
                    ],
                    [
                        'image' => 'https://images.pexels.com/photos/5907597/pexels-photo-5907597.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                        'order' => 2,
                    ],
                ],
            ],
            [
                'slug' => 'kedai-kopi-ujung-barat',
                'title' => 'Kedai Kopi Ujung Barat',
                'description' => 'Kedai kopi kecil dengan cita rasa kopi Kalimantan yang khas, cocok untuk bersantai sambil menikmati pemandangan.',
                'full_description' => 'Kedai Kopi Ujung Barat adalah surga bagi pecinta kopi yang mengunjungi Temajuk. Didirikan oleh seorang mantan barista dari Pontianak, kedai ini menyajikan kopi robusta lokal Kalimantan Barat yang diroasting secara manual dalam batch kecil. Selain kopi hitam klasik, tersedia pula berbagai olahan kopi kekinian dan minuman tradisional seperti kopi susu aren dan teh tarik. Suasana kedai yang cozy dengan dekorasi kayu dan bambu sangat cocok untuk melepas penat setelah seharian menjelajahi destinasi wisata Temajuk.',
                'image' => 'https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Kafe',
                'price' => 'Rp 10.000 - Rp 28.000',
                'location' => 'Jl. Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'location_map' => null,
                'open_hours' => '07.00 - 22.00 WIB',
                'contact' => '+62 821-5678-9012',
                'specialties' => [
                    [
                        'menu' => 'Kopi Robusta Temajuk',
                        'order' => 1,
                    ],
                    [
                        'menu' => 'Kopi Susu Aren',
                        'order' => 2,
                    ],
                    [
                        'menu' => 'Es Kopi Kelapa Muda',
                        'order' => 3,
                    ],
                    [
                        'menu' => 'Pisang Goreng Crispy',
                        'order' => 4,
                    ],
                    [
                        'menu' => 'Roti Panggang Mentega Kaya',
                        'order' => 5,
                    ],
                ],
                'galleries' => [
                    [
                        'image' => 'https://images.pexels.com/photos/1855214/pexels-photo-1855214.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                        'order' => 1,
                    ],
                    [
                        'image' => 'https://images.pexels.com/photos/2074130/pexels-photo-2074130.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                        'order' => 2,
                    ],
                ],
            ],
            [
                'slug' => 'seafood-panggang-pak-karim',
                'title' => 'Seafood Panggang Pak Karim',
                'description' => 'Spot seafood bakar terbuka di tepi pantai dengan ikan segar yang dibakar di atas arang kelapa.',
                'full_description' => 'Seafood Panggang Pak Karim adalah destinasi kuliner malam yang populer di kalangan wisatawan. Setiap sore menjelang malam, Pak Karim dan timnya menyiapkan panggangan arang kelapa di tepi pantai untuk mengolah ikan, cumi, dan udang yang baru ditangkap nelayan. Proses pembakaran yang perlahan dengan arang kelapa menciptakan aroma dan rasa yang khas. Sambal dabu-dabu segar dan lalapan menjadi pelengkap yang sempurna. Menikmati seafood panggang sambil duduk di tikar pantai dengan suara ombak dan bintang di atas kepala adalah pengalaman yang wajib dirasakan.',
                'image' => 'https://images.pexels.com/photos/2313682/pexels-photo-2313682.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Seafood',
                'price' => 'Rp 30.000 - Rp 100.000',
                'location' => 'Pantai Temajuk, Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'location_map' => null,
                'open_hours' => '16.00 - 22.00 WIB',
                'contact' => '+62 853-2345-6789',
                'specialties' => [
                    [
                        'menu' => 'Ikan Tongkol Bakar Arang',
                        'order' => 1,
                    ],
                    [
                        'menu' => 'Cumi Bakar Madu',
                        'order' => 2,
                    ],
                    [
                        'menu' => 'Udang Bakar Bumbu Rujak',
                        'order' => 3,
                    ],
                    [
                        'menu' => 'Ikan Kakap Merah Panggang',
                        'order' => 4,
                    ],
                    [
                        'menu' => 'Kerang Rebus Sambal Kecap',
                        'order' => 5,
                    ],
                ],
                'galleries' => [
                    [
                        'image' => 'https://images.pexels.com/photos/8951506/pexels-photo-8951506.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                        'order' => 1,
                    ],
                    [
                        'image' => 'https://images.pexels.com/photos/1267320/pexels-photo-1267320.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                        'order' => 2,
                    ],
                ],
            ],
        ];
    }
}
