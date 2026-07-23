<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AdditionalInformation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class AdditionalInformationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed additional information entries.
     */
    public function run(): void
    {
        foreach ($this->entries() as $data) {
            AdditionalInformation::query()->updateOrCreate(
                ['title' => $data['title']],
                $data,
            );
        }
    }

    /**
     * @return list<array{title: string, description: string, type: string}>
     */
    private function entries(): array
    {
        return [
            [
                'title' => 'Cuaca dan Iklim di Temajuk',
                'description' => 'Temajuk memiliki iklim tropis dengan suhu rata-rata 26-32°C sepanjang tahun. Musim kemarau terbaik untuk berkunjung adalah April hingga Oktober. Musim hujan dari November hingga Maret membawa hujan deras dan angin kencang. Selalu bawa jas hujan dan sunblock.',
                'type' => 'cuaca',
            ],
            [
                'title' => 'Jaringan Komunikasi dan Internet',
                'description' => 'Sinyal telepon seluler di Temajuk terbatas, terutama di area pantai dan hutan. Beberapa penginapan menyediakan WiFi namun kecepatannya tidak stabil. Disarankan untuk mengunduh peta offline dan informasi penting sebelum berangkat.',
                'type' => 'komunikasi',
            ],
            [
                'title' => 'Kesehatan dan Keamanan',
                'description' => 'Fasilitas kesehatan di Temajuk terbatas pada posyandu dan klinik kecil. Bawa obat-obatan pribadi yang cukup, termasuk obat anti-malaria, anti-nyamuk, dan P3K dasar. Area ini relatif aman, namun tetap waspada terhadap barang berharga.',
                'type' => 'kesehatan',
            ],
            [
                'title' => 'Mata Uang dan Pembayaran',
                'description' => 'Tidak ada ATM di Desa Temajuk. Bawalah uang tunai dalam jumlah cukup dari Kota Sambas atau Pontianak. Beberapa penginapan menerima transfer bank, namun sebagian besar transaksi dilakukan secara tunai.',
                'type' => 'keuangan',
            ],
            [
                'title' => 'Etika dan Adat Istiadat Lokal',
                'description' => 'Masyarakat Temajuk mayoritas beragama Islam dengan budaya Melayu yang santun. Hormati adat istiadat setempat, berpakaian sopan terutama saat mengunjungi tempat ibadah, dan minta izin sebelum memotret warga lokal. Gunakan tangan kanan saat memberi atau menerima sesuatu.',
                'type' => 'budaya',
            ],
        ];
    }
}
