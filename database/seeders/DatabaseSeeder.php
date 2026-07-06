<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['username' => 'admin'],
            [
                'role' => 'admin',
                'password' => Hash::make('temajuk2024'),
            ],
        );

        Destination::create([
            'title' => 'Pantai Temajuk',
            'description' => 'Pantai eksotis dengan pasir putih dan air jernih yang membentang sepanjang 6 km di ujung barat Indonesia.',
            'full_description' => 'Pantai Temajuk adalah pantai eksotis yang terletak di ujung barat Indonesia. Dengan hamparan pasir putih yang membentang sepanjang 6 km dan air laut yang jernih, pantai ini menawarkan pemandangan yang memukau. Pantai Temajuk relatif masih sepi pengunjung sehingga sangat cocok bagi Anda yang mencari tempat wisata yang tenang dan jauh dari keramaian. Anda dapat menikmati keindahan sunset yang menakjubkan, berenang di perairan yang jernih, atau sekadar bersantai di tepi pantai sambil menikmati kelapa muda.',
            'image' => 'https://images.pexels.com/photos/1450353/pexels-photo-1450353.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat',
            'open_hours' => '24 jam (terbaik dikunjungi pagi atau sore hari)',
        ]);

        Destination::create([
            'title' => 'Tugu Perbatasan Indonesia-Malaysia',
            'description' => 'Monumen perbatasan yang menandai wilayah Indonesia dan Malaysia di ujung barat Pulau Kalimantan.',
            'full_description' => 'Tugu Perbatasan Indonesia-Malaysia adalah monumen yang terletak tepat di garis perbatasan antara Indonesia dan Malaysia di ujung barat Pulau Kalimantan. Tugu ini menjadi saksi bisu perjalanan sejarah kedua negara dan merupakan simbol kedaulatan negara. Pengunjung dapat berfoto dengan latar belakang tugu sambil menginjak dua negara sekaligus. Tugu ini dikelilingi oleh hutan tropis yang masih asri dan menawarkan pengalaman wisata yang unik dan berbeda.',
            'image' => 'https=>//images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            'category' => 'Monumen',
            'price' => 'Rp 5.000',
            'location' => 'Perbatasan Indonesia-Malaysia, Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
            'open_hours' => '08.00 - 17.00 WIB',
        ]);
    }
}
