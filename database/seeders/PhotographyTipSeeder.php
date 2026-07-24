<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PhotographyTip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class PhotographyTipSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed photography tips.
     */
    public function run(): void
    {
        foreach ($this->tips() as $data) {
            PhotographyTip::query()->updateOrCreate(
                ['title' => $data['title']],
                $data,
            );
        }
    }

    /**
     * @return list<array{title: string, description: string, image: ?string, order: int}>
     */
    private function tips(): array
    {
        return [
            [
                'title' => 'Waktu Terbaik untuk Fotografi Landscape',
                'description' => 'Golden hour (30 menit setelah matahari terbit dan sebelum matahari terbenam) adalah waktu terbaik untuk fotografi landscape di Temajuk. Cahaya yang hangat dan lembut menciptakan bayangan panjang dan warna yang dramatis. Blue hour (sebelum matahari terbit dan setelah terbenam) cocok untuk foto dengan nuansa biru yang tenang. Hindari memotret di tengah hari karena cahaya yang terlalu keras menciptakan bayangan yang tidak diinginkan.',
                'image' => 'https://images.pexels.com/photos/1631677/pexels-photo-1631677.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'order' => 1,
            ],
            [
                'title' => 'Teknik Memotret Air Terjun',
                'description' => 'Untuk mendapatkan efek silky water pada air terjun Coras, gunakan shutter speed lambat (1/4 - 1 detik) dengan tripod yang stabil. Filter ND sangat membantu untuk mengurangi cahaya berlebih. Coba berbagai komposisi: wide angle untuk keseluruhan air terjun, atau zoom untuk detail percikan air di bebatuan. Waktu terbaik adalah saat cuaca mendung untuk pencahayaan yang merata.',
                'image' => 'https://images.pexels.com/photos/358457/pexels-photo-358457.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'order' => 2,
            ],
            [
                'title' => 'Fotografi Makro di Hutan Mangrove',
                'description' => 'Hutan mangrove Temajuk adalah surga fotografi makro. Kepiting bakau, serangga, dan tekstur akar pohon menjadi objek yang menarik. Gunakan lensa makro atau lensa dengan kemampuan close-up yang baik. Aperture lebar (f/2.8 - f/5.6) untuk isolasi subjek, atau aperture sempit (f/8 - f/11) untuk detail yang tajam. Tripod mini sangat membantu untuk stabilitas di jalur kayu.',
                'image' => 'https://images.pexels.com/photos/1108572/pexels-photo-1108572.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'order' => 3,
            ],
            [
                'title' => 'Melindungi Peralatan di Lingkungan Tropis',
                'description' => 'Kelembapan tinggi dan garam laut di Temajuk bisa merusak peralatan fotografi. Gunakan dry box atau kantong silika gel untuk menjaga kelembapan kamera. Bersihkan lensa setelah terkena cipratan air laut. Bawa kain microfiber dan blower untuk pembersihan di lapangan. Saat berpindah dari ruangan ber-AC ke lingkungan luar, tunggu beberapa menit sebelum membuka lensa untuk mencegah kondensasi.',
                'image' => 'https://images.pexels.com/photos/1787220/pexels-photo-1787220.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'order' => 4,
            ],
        ];
    }
}
