<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\DestinationGallery;
use Illuminate\Database\Seeder;

final class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        Destination::query()
            ->whereIn('slug', [
                'kampung-nelayan-temajuk',
                'pasar-kecil-temajuk',
            ])
            ->delete();

        foreach ($this->destinations() as $data) {
            $galleries = $data['galleries'] ?? [];
            unset($data['galleries']);

            /** @var Destination $destination */
            $destination = Destination::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );

            // Reset and re-seed galleries
            DestinationGallery::query()->where('destination_id', $destination->id)->delete();
            foreach ($galleries as $index => $imagePath) {
                DestinationGallery::query()->create([
                    'destination_id' => $destination->id,
                    'image' => $imagePath,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function destinations(): array
    {
        return [
            [
                'slug' => 'pantai-temajuk',
                'title' => 'Pantai Temajuk',
                'description' => 'Pantai eksotis dengan pasir putih dan air jernih yang membentang sepanjang 6 km di ujung barat Indonesia.',
                'full_description' => 'Pantai Temajuk adalah pantai eksotis yang terletak di ujung barat Indonesia. Dengan hamparan pasir putih yang membentang sepanjang 6 km dan air laut yang jernih, pantai ini menawarkan pemandangan yang memukau. Pantai Temajuk relatif masih sepi pengunjung sehingga sangat cocok bagi Anda yang mencari tempat wisata yang tenang dan jauh dari keramaian. Anda dapat menikmati keindahan sunset yang menakjubkan, berenang di perairan yang jernih, atau sekadar bersantai di tepi pantai sambil menikmati kelapa muda.',
                'image' => 'https://images.pexels.com/photos/1450353/pexels-photo-1450353.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat',
                'open_hours' => '24 jam (terbaik dikunjungi pagi atau sore hari)',
                'facilities' => ['Area Parkir', 'Toilet Umum', 'Warung Makan', 'Penyewaan Perahu'],
                'activities' => ['Berenang', 'Melihat Sunset', 'Berkemah', 'Snorkeling', 'Memancing'],
                'tips' => [
                    'Bawalah perlengkapan seperti sunblock, topi, dan kacamata untuk melindungi diri dari sinar matahari',
                    'Jika ingin bermalam, sebaiknya memesan penginapan terlebih dahulu karena ketersediaan terbatas',
                    'Kunjungi pada hari kerja untuk menghindari keramaian',
                ],
                'galleries' => [
                    'https://images.pexels.com/photos/1921336/pexels-photo-1921336.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1619317/pexels-photo-1619317.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1295036/pexels-photo-1295036.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'tugu-perbatasan',
                'title' => 'Tugu Perbatasan Indonesia-Malaysia',
                'description' => 'Monumen perbatasan yang menandai wilayah Indonesia dan Malaysia di ujung barat Pulau Kalimantan.',
                'full_description' => 'Tugu Perbatasan Indonesia-Malaysia adalah monumen yang terletak tepat di garis perbatasan antara Indonesia dan Malaysia di ujung barat Pulau Kalimantan. Tugu ini menjadi saksi bisu perjalanan sejarah kedua negara dan merupakan simbol kedaulatan negara. Pengunjung dapat berfoto dengan latar belakang tugu sambil menginjak dua negara sekaligus. Tugu ini dikelilingi oleh hutan tropis yang masih asri dan menawarkan pengalaman wisata yang unik dan berbeda.',
                'image' => 'https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Monumen',
                'price' => 'Rp 5.000',
                'location' => 'Perbatasan Indonesia-Malaysia, Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '08.00 - 17.00 WIB',
                'facilities' => ['Area Parkir', 'Toilet Umum', 'Pos Penjagaan'],
                'activities' => ['Berfoto', 'Melihat Pemandangan', 'Trekking'],
                'tips' => [
                    'Bawalah identitas diri (KTP/SIM/Paspor) saat berkunjung',
                    'Patuhi aturan dan jangan melewati batas negara tanpa izin',
                    'Bawalah air minum yang cukup karena perjalanan menuju tugu cukup melelahkan',
                ],
                'galleries' => [
                    'https://images.pexels.com/photos/2559941/pexels-photo-2559941.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/2471970/pexels-photo-2471970.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1576937/pexels-photo-1576937.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'hutan-mangrove',
                'title' => 'Hutan Mangrove Temajuk',
                'description' => 'Ekosistem mangrove yang menjadi habitat berbagai flora dan fauna serta menawarkan jalur susur mangrove.',
                'full_description' => 'Hutan Mangrove Temajuk adalah kawasan hutan bakau yang terletak di pesisir pantai Temajuk. Ekosistem mangrove ini menjadi rumah bagi berbagai flora dan fauna, termasuk burung-burung langka dan kepiting bakau. Pengunjung dapat menjelajahi hutan mangrove melalui jalur susur kayu yang telah disediakan. Pemandangan akar-akar pohon mangrove yang menjulang dari air adalah pemandangan yang menarik untuk diabadikan. Selain nilai estetikanya, hutan mangrove juga berperan penting dalam melindungi pesisir dari abrasi dan menjaga ekosistem laut.',
                'image' => 'https://images.pexels.com/photos/2583852/pexels-photo-2583852.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Alam',
                'price' => 'Rp 15.000',
                'location' => 'Pesisir Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '07.00 - 18.00 WIB',
                'facilities' => ['Jalur Susur Mangrove', 'Toilet Umum', 'Pos Informasi', 'Area Parkir'],
                'activities' => ['Tracking Mangrove', 'Fotografi', 'Pengamatan Burung', 'Edukasi Lingkungan'],
                'tips' => [
                    'Kenakan pakaian yang nyaman dan sepatu yang sesuai untuk tracking',
                    'Bawalah obat anti nyamuk',
                    'Jangan membuang sampah sembarangan untuk menjaga kelestarian ekosistem',
                ],
                'galleries' => [
                    'https://images.pexels.com/photos/2583852/pexels-photo-2583852.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/5232048/pexels-photo-5232048.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/14199312/pexels-photo-14199312.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'bukit-maung',
                'title' => 'Bukit Maung',
                'description' => 'Bukit dengan pemandangan spektakuler Laut Natuna dan bentangan hutan tropis yang luas.',
                'full_description' => 'Bukit Maung adalah salah satu destinasi wisata alam yang menawarkan pemandangan spektakuler di Temajuk. Dari puncak bukit, pengunjung dapat menikmati panorama Laut Natuna yang membentang luas serta hamparan hutan tropis yang mengelilingi kawasan Temajuk. Tracking menuju puncak bukit membutuhkan waktu sekitar 1-2 jam, namun keindahan pemandangan di puncak akan membayar semua usaha Anda. Bukit Maung adalah tempat ideal untuk melihat matahari terbit dan menikmati udara segar pegunungan.',
                'image' => 'https://images.pexels.com/photos/1770809/pexels-photo-1770809.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Alam',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '06.00 - 18.00 WIB',
                'facilities' => ['Jalur Pendakian', 'Pos Istirahat', 'Area Camping'],
                'activities' => ['Trekking', 'Camping', 'Fotografi', 'Melihat Sunrise'],
                'tips' => [
                    'Bawalah air minum dan bekal yang cukup',
                    'Kenakan sepatu trekking dan pakaian yang nyaman',
                    'Untuk melihat sunrise, sebaiknya mendaki pada malam hari dan bermalam di puncak',
                ],
                'galleries' => [
                    'https://images.pexels.com/photos/1666012/pexels-photo-1666012.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1624438/pexels-photo-1624438.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/2224956/pexels-photo-2224956.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'teluk-atong',
                'title' => 'Teluk Atong',
                'description' => 'Pantai ini dikenal karena atong adalah orang pertama yang mendirikan penginapan di kawasan ini.',
                'full_description' => 'Pantai ini dikenal karena atong adalah orang pertama yang mendirikan penginapan di kawasan ini, daerah yang dulunya paling ujung dan dekat dengan hutan lindung Tanjung Datuk. Sekarang semakin ramai karena jalur menuju atong bahari sudah menjadi jalur utama wisata Desa Temajuk. Karakteristik pantai ini sama dengan pantai Camar Bulan, namun perbedaannya adalah ketika surut batu karang akan terhampar luas didepan pantai dan ketika air pasang kita bisa melakukan snorkeling disekitar pantai ini.',
                'image' => 'https://jadesta.com/imgpost/35189.jpg',
                'category' => 'Teluk',
                'price' => 'Rp 15.000',
                'location' => 'Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '08.00 - 17.00 WIB',
                'facilities' => ['Area Parkir', 'Toilet Umum', 'Gazebo', 'Warung Makan'],
                'activities' => ['Berenang', 'Fotografi', 'Piknik', 'Bersantai'],
                'tips' => [
                    'Bawalah baju ganti jika berencana berenang',
                    'Waspada terhadap kedalaman danau di beberapa bagian',
                    'Jangan membuang sampah sembarangan untuk menjaga kebersihan pantai',
                ],
                'galleries' => [
                    'https://images.pexels.com/photos/2159538/pexels-photo-2159538.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1903702/pexels-photo-1903702.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1586298/pexels-photo-1586298.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'air-terjun-coras',
                'title' => 'Air Terjun Carocok Antu Soreh (Coras)',
                'description' => 'Air terjun bertingkat yang berada di tengah hutan belantara dengan air yang jernih dan sejuk.',
                'full_description' => 'Air Terjun Carocok Antu Soreh atau yang biasa disingkat Coras adalah air terjun bertingkat yang terletak di tengah hutan belantara Temajuk. Air terjun ini memiliki beberapa tingkatan dengan kolam-kolam alami yang dapat digunakan untuk berendam. Air yang jernih dan sejuk serta suara gemericik air yang menenangkan menciptakan atmosfer yang sangat menyegarkan. Perjalanan menuju air terjun ini melalui jalur trekking yang cukup menantang, namun keindahan alam sepanjang perjalanan dan keindahan air terjun akan membuat segala usaha terbayarkan.',
                'image' => 'https://images.pexels.com/photos/358457/pexels-photo-358457.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Air Terjun',
                'price' => 'Rp 20.000',
                'location' => 'Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '08.00 - 16.00 WIB',
                'facilities' => ['Jalur Trekking', 'Area Istirahat', 'Toilet Umum'],
                'activities' => ['Trekking', 'Berendam', 'Fotografi', 'Menikmati Alam'],
                'tips' => [
                    'Kenakan sepatu yang sesuai untuk trekking',
                    'Bawalah air minum yang cukup',
                    'Datanglah pagi hari untuk menghindari hujan sore yang biasa terjadi di kawasan hutan',
                ],
                'galleries' => [
                    'https://images.pexels.com/photos/358457/pexels-photo-358457.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/460621/pexels-photo-460621.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1650227/pexels-photo-1650227.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'pantai-camar-bulan',
                'title' => 'Pantai Camar Bulan',
                'description' => 'Pantai luas dengan pasir cerah dan ombak tenang yang cocok untuk bersantai.',
                'full_description' => 'Pantai Camar Bulan menawarkan hamparan pasir yang luas dengan suasana pesisir yang tenang. Area ini cocok untuk berjalan santai, menikmati angin laut, dan melihat aktivitas nelayan setempat. Wisatawan dapat datang pada pagi atau sore hari untuk mendapatkan cahaya terbaik dan cuaca yang lebih nyaman.',
                'image' => 'https://images.pexels.com/photos/457882/pexels-photo-457882.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '06.00 - 18.00 WIB',
                'facilities' => ['Area Parkir', 'Warung Makan', 'Gazebo'],
                'activities' => ['Bersantai', 'Fotografi', 'Jalan Pantai'],
                'tips' => ['Datang sore hari untuk menikmati angin laut', 'Bawa air minum dan alas duduk'],
                'galleries' => [
                    'https://images.pexels.com/photos/533881/pexels-photo-533881.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/994605/pexels-photo-994605.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'sunset-point-temajuk',
                'title' => 'Sunset Point Temajuk',
                'description' => 'Titik terbaik untuk menikmati matahari terbenam di kawasan pesisir Temajuk.',
                'full_description' => 'Sunset Point Temajuk menjadi salah satu tempat favorit untuk menikmati warna langit sore di pesisir. Lokasinya mudah dijangkau dari area pantai utama dan cocok untuk wisatawan yang ingin berfoto atau sekadar duduk santai menunggu matahari turun.',
                'image' => 'https://images.pexels.com/photos/189349/pexels-photo-189349.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Alam',
                'price' => 'Gratis',
                'location' => 'Pesisir Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '16.00 - 18.30 WIB',
                'facilities' => ['Area Duduk', 'Spot Foto'],
                'activities' => ['Melihat Sunset', 'Fotografi', 'Piknik'],
                'tips' => ['Datang sebelum pukul 17.00 WIB', 'Periksa cuaca agar pemandangan lebih jelas'],
                'galleries' => [
                    'https://images.pexels.com/photos/462024/pexels-photo-462024.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/247431/pexels-photo-247431.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'landmark-perbatasan-ri-malaysia',
                'title' => 'Landmark Perbatasan RI-Malaysia',
                'description' => 'Area foto perbatasan yang menampilkan identitas Temajuk sebagai desa ujung negeri.',
                'full_description' => 'Landmark Perbatasan RI-Malaysia menjadi penanda visual yang sering dikunjungi wisatawan. Tempat ini cocok untuk memahami posisi strategis Temajuk sekaligus berfoto dengan latar perbatasan negara.',
                'image' => 'https://images.pexels.com/photos/15286/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Monumen',
                'price' => 'Gratis',
                'location' => 'Kawasan perbatasan Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '08.00 - 17.00 WIB',
                'facilities' => ['Area Parkir', 'Spot Foto'],
                'activities' => ['Berfoto', 'Wisata Edukasi', 'Melihat Pemandangan'],
                'tips' => ['Bawa identitas diri', 'Ikuti arahan petugas setempat'],
                'galleries' => [
                    'https://images.pexels.com/photos/372098/pexels-photo-372098.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/210243/pexels-photo-210243.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'muara-sungai-temajuk',
                'title' => 'Muara Sungai Temajuk',
                'description' => 'Pertemuan aliran sungai dan laut yang menawarkan pemandangan pesisir yang berbeda.',
                'full_description' => 'Muara Sungai Temajuk menyajikan lanskap pertemuan air sungai dan laut. Area ini menarik untuk pengamatan aktivitas nelayan, fotografi alam, dan menikmati suasana pesisir yang lebih tenang dibandingkan area pantai utama.',
                'image' => 'https://images.pexels.com/photos/1647962/pexels-photo-1647962.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Alam',
                'price' => 'Gratis',
                'location' => 'Muara Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '06.00 - 17.00 WIB',
                'facilities' => ['Area Parkir Sederhana', 'Warung Lokal'],
                'activities' => ['Fotografi', 'Mengamati Nelayan', 'Menikmati Pemandangan'],
                'tips' => ['Gunakan alas kaki yang nyaman', 'Waspadai area licin saat air pasang'],
                'galleries' => [
                    'https://images.pexels.com/photos/1001682/pexels-photo-1001682.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1001435/pexels-photo-1001435.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'jalur-susur-mangrove',
                'title' => 'Jalur Susur Mangrove',
                'description' => 'Jalur eksplorasi mangrove untuk wisata edukasi ekosistem pesisir.',
                'full_description' => 'Jalur Susur Mangrove memberi pengalaman berjalan di area bakau sambil mengenal fungsi ekosistem pesisir. Jalur ini cocok untuk wisata edukasi, fotografi akar mangrove, dan pengamatan burung pada waktu tertentu.',
                'image' => 'https://images.pexels.com/photos/165505/pexels-photo-165505.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Alam',
                'price' => 'Rp 10.000',
                'location' => 'Kawasan mangrove Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '07.00 - 17.00 WIB',
                'facilities' => ['Jalur Kayu', 'Papan Informasi', 'Area Parkir'],
                'activities' => ['Edukasi Lingkungan', 'Fotografi', 'Pengamatan Burung'],
                'tips' => ['Bawa obat anti nyamuk', 'Jangan merusak tanaman mangrove'],
                'galleries' => [
                    'https://images.pexels.com/photos/2070485/pexels-photo-2070485.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1591373/pexels-photo-1591373.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'tanjung-datok-view',
                'title' => 'Tanjung Datok View',
                'description' => 'Titik pandang ke arah kawasan Tanjung Datok dan hamparan laut sekitar Temajuk.',
                'full_description' => 'Tanjung Datok View menawarkan pemandangan luas ke arah laut dan kawasan hutan sekitar. Tempat ini cocok untuk wisatawan yang menyukai lanskap alam terbuka dan ingin menikmati suasana perbatasan dari ketinggian.',
                'image' => 'https://images.pexels.com/photos/2335126/pexels-photo-2335126.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Alam',
                'price' => 'Rp 10.000',
                'location' => 'Arah Tanjung Datok, Desa Temajuk, Kecamatan Paloh',
                'open_hours' => '06.00 - 17.00 WIB',
                'facilities' => ['Area Pandang', 'Tempat Istirahat'],
                'activities' => ['Fotografi', 'Trekking Ringan', 'Melihat Pemandangan'],
                'tips' => ['Gunakan kendaraan yang sesuai kondisi jalan', 'Datang saat cuaca cerah'],
                'galleries' => [
                    'https://images.pexels.com/photos/414171/pexels-photo-414171.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/618833/pexels-photo-618833.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'pantai-batu-nenek',
                'title' => 'Pantai Batu Nenek',
                'description' => 'Pantai berbatu dengan karakter pesisir yang menarik untuk fotografi dan eksplorasi ringan.',
                'full_description' => 'Pantai Batu Nenek memiliki batuan pesisir yang membuat tampilannya berbeda dari pantai berpasir biasa. Saat air surut, wisatawan dapat melihat tekstur batu dan area sekitar yang cocok untuk fotografi alam.',
                'image' => 'https://images.pexels.com/photos/1295036/pexels-photo-1295036.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Pantai',
                'price' => 'Rp 10.000',
                'location' => 'Pesisir Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas',
                'open_hours' => '07.00 - 17.00 WIB',
                'facilities' => ['Area Parkir', 'Warung Lokal'],
                'activities' => ['Fotografi', 'Jalan Pantai', 'Menikmati Pemandangan'],
                'tips' => ['Perhatikan kondisi pasang surut', 'Gunakan sandal atau sepatu yang tidak licin'],
                'galleries' => [
                    'https://images.pexels.com/photos/1295036/pexels-photo-1295036.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1903702/pexels-photo-1903702.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'teluk-melano',
                'title' => 'Teluk Melano',
                'description' => 'Teluk tenang dengan suasana pesisir yang cocok untuk piknik dan menikmati laut.',
                'full_description' => 'Teluk Melano menghadirkan suasana teluk yang lebih teduh dan tenang. Lokasi ini cocok untuk wisata keluarga kecil, piknik sederhana, serta menikmati panorama laut tanpa aktivitas yang terlalu padat.',
                'image' => 'https://images.pexels.com/photos/248797/pexels-photo-248797.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Teluk',
                'price' => 'Rp 12.000',
                'location' => 'Kawasan pesisir Desa Temajuk, Kecamatan Paloh',
                'open_hours' => '08.00 - 17.00 WIB',
                'facilities' => ['Gazebo', 'Warung Makan', 'Area Parkir'],
                'activities' => ['Piknik', 'Bersantai', 'Fotografi'],
                'tips' => ['Bawa alas duduk tambahan', 'Jaga kebersihan area teluk'],
                'galleries' => [
                    'https://images.pexels.com/photos/533923/pexels-photo-533923.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1032650/pexels-photo-1032650.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'air-terjun-rimba-temajuk',
                'title' => 'Air Terjun Rimba Temajuk',
                'description' => 'Air terjun kecil di area rimba yang cocok untuk wisata alam dan trekking singkat.',
                'full_description' => 'Air Terjun Rimba Temajuk berada di jalur alam yang memerlukan perjalanan singkat melewati area hijau. Air terjun ini cocok untuk pengunjung yang menyukai suasana rimba dan ingin menikmati air segar di tengah perjalanan.',
                'image' => 'https://images.pexels.com/photos/699558/pexels-photo-699558.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Air Terjun',
                'price' => 'Rp 15.000',
                'location' => 'Area rimba Desa Temajuk, Kecamatan Paloh',
                'open_hours' => '08.00 - 15.00 WIB',
                'facilities' => ['Jalur Trekking', 'Area Istirahat'],
                'activities' => ['Trekking', 'Fotografi', 'Menikmati Alam'],
                'tips' => ['Gunakan sepatu trekking', 'Datang bersama pemandu lokal jika belum mengenal jalur'],
                'galleries' => [
                    'https://images.pexels.com/photos/460621/pexels-photo-460621.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/545968/pexels-photo-545968.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
            [
                'slug' => 'bukit-senja-temajuk',
                'title' => 'Bukit Senja Temajuk',
                'description' => 'Bukit rendah untuk menikmati panorama sore dan garis pantai Temajuk.',
                'full_description' => 'Bukit Senja Temajuk adalah titik pandang ringan yang dapat dikunjungi tanpa pendakian berat. Dari area ini, wisatawan dapat melihat garis pantai dan menikmati suasana sore dengan udara yang lebih segar.',
                'image' => 'https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category' => 'Alam',
                'price' => 'Rp 8.000',
                'location' => 'Perbukitan Desa Temajuk, Kecamatan Paloh',
                'open_hours' => '15.00 - 18.00 WIB',
                'facilities' => ['Area Pandang', 'Tempat Duduk'],
                'activities' => ['Melihat Sunset', 'Fotografi', 'Trekking Ringan'],
                'tips' => ['Datang saat sore hari', 'Bawa senter kecil jika pulang menjelang gelap'],
                'galleries' => [
                    'https://images.pexels.com/photos/1643113/pexels-photo-1643113.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/2098428/pexels-photo-2098428.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
            ],
        ];
    }
}
