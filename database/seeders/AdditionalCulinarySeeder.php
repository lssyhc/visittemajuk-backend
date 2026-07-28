<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AdditionalCulinary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class AdditionalCulinarySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed additional culinary entries.
     */
    public function run(): void
    {
        foreach ($this->entries() as $data) {
            AdditionalCulinary::query()->updateOrCreate(
                ['title' => $data['title']],
                $data,
            );
        }
    }

    /**
     * @return list<array{title: string, description: string, image: string}>
     */
    private function entries(): array
    {
        return [
            [
                'title' => 'Pasar Pagi Tradisional',
                'description' => 'Pasar pagi yang menjual berbagai jajanan tradisional Melayu Sambas, buah-buahan segar, dan hasil kebun warga lokal. Kunjungi sebelum pukul 09.00 untuk mendapatkan pilihan terbaik.',
                'image' => 'https://images.pexels.com/photos/2252597/pexels-photo-2252597.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            ],
            [
                'title' => 'Jajanan Khas Temajuk',
                'description' => 'Koleksi kue dan jajanan tradisional khas Temajuk yang dijual oleh warga lokal, termasuk lempuk durian, kue cucur, dan berbagai kuih Melayu yang lezat dengan harga sangat terjangkau.',
                'image' => 'https://images.pexels.com/photos/4553117/pexels-photo-4553117.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            ],
            [
                'title' => 'Minuman Segar Kelapa Muda Temajuk',
                'description' => 'Kelapa muda segar langsung dipetik dari pohon di sekitar pantai Temajuk. Tersedia di hampir setiap warung tepi pantai, nikmati air kelapa muda yang manis dan menyegarkan di bawah terik matahari.',
                'image' => 'https://images.pexels.com/photos/1470510/pexels-photo-1470510.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            ],
        ];
    }
}
