<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Transportation;
use App\Models\TransportationSteps;
use App\Models\TransportationTips;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class TransportationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed transportations along with their steps and tips.
     */
    public function run(): void
    {
        foreach ($this->transportations() as $data) {
            $steps = $data['steps'] ?? [];
            $tips = $data['tips'] ?? [];
            unset($data['steps'], $data['tips']);

            /** @var Transportation $transportation */
            $transportation = Transportation::query()->updateOrCreate(
                ['title' => $data['title']],
                $data,
            );

            foreach ($steps as $step) {
                TransportationSteps::query()->updateOrCreate(
                    [
                        'transportation_id' => $transportation->id,
                        'description' => $step['description'],
                    ],
                    $step + ['transportation_id' => $transportation->id],
                );
            }

            foreach ($tips as $tip) {
                TransportationTips::query()->updateOrCreate(
                    [
                        'transportation_id' => $transportation->id,
                        'tip' => $tip['tip'],
                    ],
                    $tip + ['transportation_id' => $transportation->id],
                );
            }
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function transportations(): array
    {
        return [
            [
                'title' => 'Pontianak ke Temajuk via Darat',
                'description' => 'Perjalanan darat dari Pontianak menuju Desa Temajuk melalui Kabupaten Sambas dengan pemandangan pedesaan Kalimantan yang asri.',
                'image' => 'https://images.pexels.com/photos/1178448/pexels-photo-1178448.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'estimated_time' => '6 - 8 jam',
                'estimated_cost' => 'Rp 150.000 - Rp 300.000',
                'difficulty' => 'Mudah',
                'steps' => [
                    [
                        'description' => 'Dari Bandara Supadio Pontianak, naik taksi atau ojek ke Terminal Antar Provinsi Pontianak.',
                        'duration' => '30 - 45 menit',
                        'order' => 1,
                        'cost' => 'Rp 50.000 - Rp 100.000',
                        'vehicle' => 'Taksi / Ojek Online',
                    ],
                    [
                        'description' => 'Dari Terminal Pontianak, naik bus atau travel menuju Kota Sambas.',
                        'duration' => '3 - 4 jam',
                        'order' => 2,
                        'cost' => 'Rp 60.000 - Rp 80.000',
                        'vehicle' => 'Bus / Travel',
                    ],
                    [
                        'description' => 'Dari Kota Sambas, lanjutkan perjalanan dengan angkutan pedesaan atau ojek menuju Desa Temajuk.',
                        'duration' => '2 - 3 jam',
                        'order' => 3,
                        'cost' => 'Rp 50.000 - Rp 150.000',
                        'vehicle' => 'Angkutan Pedesaan / Ojek',
                    ],
                ],
                'tips' => [
                    [
                        'tip' => 'Berangkatlah pagi hari dari Pontianak agar tiba sebelum gelap',
                        'order' => 1,
                    ],
                    [
                        'tip' => 'Persiapkan bekal makanan dan minuman karena minim warung di perjalanan',
                        'order' => 2,
                    ],
                    [
                        'tip' => 'Periksa kondisi kendaraan dan isi bensin penuh sebelum berangkat',
                        'order' => 3,
                    ],
                    [
                        'tip' => 'Bawalah jaket dan jas hujan karena cuaca Kalimantan yang tidak menentu',
                        'order' => 4,
                    ],
                ],
            ],
            [
                'title' => 'Pontianak ke Temajuk via Udara ke Sambas',
                'description' => 'Perjalanan kombinasi udara dan darat dengan penerbangan menuju Bandara Tebelian Sambas, dilanjutkan perjalanan darat.',
                'image' => 'https://images.pexels.com/photos/2026324/pexels-photo-2026324.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'estimated_time' => '4 - 5 jam',
                'estimated_cost' => 'Rp 400.000 - Rp 700.000',
                'difficulty' => 'Mudah',
                'steps' => [
                    [
                        'description' => 'Penerbangan dari Jakarta atau kota besar lainnya menuju Bandara Tebelian Sambas.',
                        'duration' => '1,5 - 2 jam',
                        'order' => 1,
                        'cost' => 'Rp 300.000 - Rp 500.000',
                        'vehicle' => 'Pesawat',
                    ],
                    [
                        'description' => 'Dari Bandara Tebelian, naik taksi atau travel menuju pusat Kota Sambas.',
                        'duration' => '30 - 45 menit',
                        'order' => 2,
                        'cost' => 'Rp 50.000 - Rp 80.000',
                        'vehicle' => 'Taksi',
                    ],
                    [
                        'description' => 'Dari Kota Sambas, lanjutkan dengan ojek atau angkutan pedesaan menuju Desa Temajuk.',
                        'duration' => '2 - 3 jam',
                        'order' => 3,
                        'cost' => 'Rp 50.000 - Rp 150.000',
                        'vehicle' => 'Angkutan Pedesaan / Ojek',
                    ],
                ],
                'tips' => [
                    [
                        'tip' => 'Booking tiket pesawat jauh-jauh hari karena penerbangan terbatas',
                        'order' => 1,
                    ],
                    [
                        'tip' => 'Hubungi penginapan untuk layanan jemputan dari Kota Sambas',
                        'order' => 2,
                    ],
                    [
                        'tip' => 'Cek jadwal penerbangan karena bisa berubah sewaktu-waktu',
                        'order' => 3,
                    ],
                    [
                        'tip' => 'Simpan bukti pemesanan transportasi dengan baik',
                        'order' => 4,
                    ],
                ],
            ],
            [
                'title' => 'Kuching (Malaysia) ke Temajuk',
                'description' => 'Rute internasional dari Kuching, Sarawak menuju Temajuk melalui perbatasan Entikong dengan petualangan lintas negara.',
                'image' => 'https://images.pexels.com/photos/1309766/pexels-photo-1309766.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'estimated_time' => '8 - 10 jam',
                'estimated_cost' => 'Rp 300.000 - Rp 600.000',
                'difficulty' => 'Sedang',
                'steps' => [
                    [
                        'description' => 'Dari Kuching, naik bus antar negara menuju Pos Imigrasi Entikong di perbatasan Indonesia-Malaysia.',
                        'duration' => '3 - 4 jam',
                        'order' => 1,
                        'cost' => 'RM 30 - RM 50',
                        'vehicle' => 'Bus Internasional',
                    ],
                    [
                        'description' => 'Proses imigrasi di Pos Entikong (pastikan dokumen perjalanan lengkap).',
                        'duration' => '30 - 60 menit',
                        'order' => 2,
                        'cost' => 'Gratis',
                        'vehicle' => 'Jalan Kaki',
                    ],
                    [
                        'description' => 'Dari Entikong, naik bus atau travel menuju Kota Sambas.',
                        'duration' => '2 - 3 jam',
                        'order' => 3,
                        'cost' => 'Rp 50.000 - Rp 80.000',
                        'vehicle' => 'Bus / Travel',
                    ],
                    [
                        'description' => 'Dari Kota Sambas, lanjutkan dengan angkutan pedesaan atau ojek menuju Desa Temajuk.',
                        'duration' => '2 - 3 jam',
                        'order' => 4,
                        'cost' => 'Rp 50.000 - Rp 150.000',
                        'vehicle' => 'Angkutan Pedesaan / Ojek',
                    ],
                ],
                'tips' => [
                    [
                        'tip' => 'Siapkan paspor dan dokumen perjalanan yang masih berlaku',
                        'order' => 1,
                    ],
                    [
                        'tip' => 'Pastikan visa (jika diperlukan) sudah diurus sebelum keberangkatan',
                        'order' => 2,
                    ],
                    [
                        'tip' => 'Bawa mata uang Rupiah untuk keperluan di sisi Indonesia',
                        'order' => 3,
                    ],
                    [
                        'tip' => 'Proses imigrasi bisa memakan waktu, bersabarlah dan ikuti prosedur dengan baik',
                        'order' => 4,
                    ],
                    [
                        'tip' => 'Hubungi pihak penginapan sebelumnya untuk memastikan ketersediaan kamar',
                        'order' => 5,
                    ],
                ],
            ],
        ];
    }
}
