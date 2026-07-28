<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

final class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'key' => 'home.hero',
                'value' => [
                    'title' => 'Jelajahi Keindahan Wisata Temajuk',
                    'subtitle' => 'Destinasi wisata tersembunyi di Kalimantan Barat Indonesia',
                    'image' => null,
                    'button_text' => 'Jelajahi Sekarang',
                    'button_link' => '/destinasi',
                ],
            ],
            [
                'key' => 'home.intro',
                'value' => [
                    'title' => 'Selamat Datang di Temajuk',
                    'body' => 'Temajuk adalah surga tersembunyi yang terletak di ujung barat Indonesia, tepatnya di Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat. Dengan pantai pasir putih yang membentang sepanjang 6 km, hutan mangrove yang asri, dan berbagai destinasi wisata menarik lainnya, Temajuk menawarkan pengalaman wisata yang tak terlupakan.',
                    'image' => null,
                ],
            ],
            [
                'key' => 'home.section_titles',
                'value' => [
                    'features' => 'Kenapa Harus Mengunjungi Temajuk?',
                    'destinations' => 'Destinasi Populer',
                    'accommodations' => 'Akomodasi Terbaik',
                    'photo_spots' => 'Spot Foto Instagramable',
                    'testimonials' => 'Ulasan Pengunjung',
                    'transport_cta' => 'Butuh Panduan Transportasi?',
                    'newsletter' => 'Dapatkan Informasi Terbaru',
                ],
            ],
            [
                'key' => 'home.features',
                'value' => [
                    ['title' => 'Destinasi Eksotis', 'body' => 'Temajuk menawarkan destinasi wisata eksotis dengan keindahan alam yang masih terjaga.', 'icon' => 'Map'],
                    ['title' => 'Lokasi Unik', 'body' => 'Terletak di perbatasan Indonesia-Malaysia, Anda bisa berfoto dengan satu kaki di dua negara!', 'icon' => 'MapPin'],
                    ['title' => 'Kuliner Lezat', 'body' => 'Nikmati hidangan seafood segar dan kuliner khas Kalimantan Barat yang lezat.', 'icon' => 'Utensils'],
                    ['title' => 'Spot Foto Menarik', 'body' => 'Temukan berbagai spot foto instagramable untuk mengabadikan momen liburan Anda.', 'icon' => 'Camera'],
                ],
            ],
            [
                'key' => 'home.transport_cta',
                'value' => [
                    'title' => 'Butuh Panduan Transportasi?',
                    'body' => 'Kami menyediakan informasi lengkap tentang cara mencapai Temajuk dan transportasi lokal untuk menjelajahi berbagai destinasi wisata. Mulai dari rute, estimasi biaya, hingga tips perjalanan yang akan membantu liburan Anda berjalan lancar.',
                    'button_text' => 'Panduan Transportasi',
                    'button_link' => '/transportasi',
                    'image' => 'https://images.pexels.com/photos/2199293/pexels-photo-2199293.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'key' => 'footer.brand',
                'value' => [
                    'tagline' => 'Jelajahi keindahan alam Temajuk, destinasi wisata eksotis di ujung barat Indonesia.',
                    'brand_text' => 'Visit Temajuk',
                ],
            ],
            [
                'key' => 'footer.contact',
                'value' => [
                    'address' => 'Temajuk, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat, Indonesia',
                    'phone' => '+62 8123 4567 890',
                    'email' => 'info@visittemajuk.id',
                ],
            ],
        ];

        foreach ($defaults as $row) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $row['key']],
                $row,
            );
        }
    }
}
