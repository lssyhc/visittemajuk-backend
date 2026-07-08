<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Accomodation;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

final class AccomodationSeeder extends Seeder
{
    /**
     * Seed the accommodations along with their room types.
     */
    public function run(): void
    {
        foreach ($this->accomodations() as $data) {
            $roomTypes = $data['room_types'];
            unset($data['room_types']);

            /** @var Accomodation $accomodation */
            $accomodation = Accomodation::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );

            foreach ($roomTypes as $roomType) {
                RoomType::query()->updateOrCreate(
                    [
                        'accommodation_id' => $accomodation->id,
                        'name'             => $roomType['name'],
                    ],
                    $roomType + ['accommodation_id' => $accomodation->id],
                );
            }
        }
    }

    /**
     * @return list<array{
     *     slug: string,
     *     title: string,
     *     description: string,
     *     full_description: string,
     *     image_url: string,
     *     category: string,
     *     min_price: string,
     *     max_price: string,
     *     location: string,
     *     contacs: string,
     *     site_url: string,
     *     facilities: list<string>,
     *     tips: list<string>,
     *     gallery: list<string>,
     *     room_types: list<array{name: string, description: string, capacity: int, price: float}>
     * }>
     */
    private function accomodations(): array
    {
        return [
            // 1 — Resort
            [
                'slug'             => 'temajuk-paradise-resort',
                'title'            => 'Temajuk Paradise Resort',
                'description'      => 'Resort tepi pantai dengan pemandangan laut langsung dan fasilitas lengkap untuk liburan keluarga maupun pasangan.',
                'full_description' => 'Temajuk Paradise Resort berdiri megah di tepi Pantai Temajuk dengan pemandangan Laut Natuna yang tiada duanya. Didesain dengan sentuhan arsitektur tropis modern, setiap kamar dan bungalow memiliki akses langsung ke pantai berpasir putih. Resort ini menyediakan berbagai fasilitas premium mulai dari kolam renang infinity yang menghadap laut, restoran seafood segar, hingga layanan spa tradisional Kalimantan. Cocok untuk bulan madu, liburan keluarga, maupun retreat bisnis. Staf kami yang ramah siap memastikan setiap tamu mendapatkan pengalaman menginap yang tak terlupakan di ujung barat Indonesia.',
                'image_url'        => 'https://images.pexels.com/photos/338504/pexels-photo-338504.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category'         => 'resort',
                'min_price'        => 'Rp 650.000',
                'max_price'        => 'Rp 1.800.000',
                'location'         => 'Jl. Pantai Temajuk No. 1, Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat',
                'contacs'          => '+62 812-3456-7890',
                'site_url'         => 'https://temajukparadiseresort.com',
                'facilities'       => [
                    'Kolam Renang Infinity',
                    'Restoran Seafood',
                    'Spa & Wellness',
                    'WiFi Gratis',
                    'Area Parkir Luas',
                    'Layanan Antar-Jemput',
                    'Penyewaan Alat Snorkeling',
                    'Bar Tepi Pantai',
                ],
                'tips'             => [
                    'Pesan minimal 3 hari sebelumnya karena resort sering penuh di musim liburan',
                    'Manfaatkan paket early check-in untuk lebih lama menikmati pantai privat',
                    'Coba menu sarapan seafood lokal yang disajikan langsung dari nelayan setempat',
                ],
                'gallery'          => [
                    'https://images.pexels.com/photos/261102/pexels-photo-261102.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/189296/pexels-photo-189296.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1457842/pexels-photo-1457842.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
                'room_types'       => [
                    [
                        'name'        => 'Kamar Deluxe Ocean View',
                        'description' => 'Kamar luas dengan balkon pribadi menghadap langsung ke laut, dilengkapi tempat tidur king size dan bathtub.',
                        'capacity'    => 2,
                        'price'       => 650000.00,
                    ],
                    [
                        'name'        => 'Suite Keluarga',
                        'description' => 'Suite dua kamar yang ideal untuk keluarga, dengan ruang tamu terpisah, dapur kecil, dan teras menghadap pantai.',
                        'capacity'    => 4,
                        'price'       => 1200000.00,
                    ],
                    [
                        'name'        => 'Villa Pantai Privat',
                        'description' => 'Villa mewah dengan akses langsung ke pantai privat, private pool, dan butler service 24 jam.',
                        'capacity'    => 2,
                        'price'       => 1800000.00,
                    ],
                ],
            ],

            // 2 — Wisma
            [
                'slug'             => 'wisma-nelayan-paloh',
                'title'            => 'Wisma Nelayan Paloh',
                'description'      => 'Penginapan sederhana namun nyaman milik warga lokal, cocok bagi traveler yang ingin merasakan kehidupan pesisir Temajuk.',
                'full_description' => 'Wisma Nelayan Paloh adalah penginapan milik keluarga nelayan lokal yang telah berdiri lebih dari satu dekade. Berlokasi tak jauh dari perkampungan nelayan, wisma ini menawarkan pengalaman autentik kehidupan masyarakat pesisir Kalimantan Barat. Kamar-kamarnya bersih, sederhana, dan ditata dengan sentuhan budaya Melayu yang hangat. Tamu dapat ikut serta dalam kegiatan melaut bersama nelayan di pagi hari, atau belajar memasak hidangan laut tradisional. Sarapan berupa nasi goreng ikan dan teh tarik sudah termasuk dalam tarif menginap.',
                'image_url'        => 'https://images.pexels.com/photos/1643383/pexels-photo-1643383.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category'         => 'wisma',
                'min_price'        => 'Rp 150.000',
                'max_price'        => 'Rp 300.000',
                'location'         => 'Jl. Nelayan Rt. 03, Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat',
                'contacs'          => '+62 813-9876-5432',
                'site_url'         => 'https://wismanelayan.id',
                'facilities'       => [
                    'Sarapan Termasuk',
                    'WiFi Gratis',
                    'Area Parkir',
                    'Dapur Bersama',
                    'Ruang Santai',
                    'Laundry',
                ],
                'tips'             => [
                    'Tanyakan kepada pemilik soal jadwal melaut gratis yang sering ditawarkan untuk tamu menginap',
                    'Bawa uang tunai karena belum tersedia mesin ATM di sekitar wisma',
                    'Kamar AC tersedia terbatas, sebaiknya pesan jauh-jauh hari',
                ],
                'gallery'          => [
                    'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1655329/pexels-photo-1655329.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
                'room_types'       => [
                    [
                        'name'        => 'Kamar Standar Kipas',
                        'description' => 'Kamar nyaman dengan kipas angin, tempat tidur double, dan kamar mandi dalam. Cocok untuk pasangan atau solo traveler.',
                        'capacity'    => 2,
                        'price'       => 150000.00,
                    ],
                    [
                        'name'        => 'Kamar AC Twin',
                        'description' => 'Kamar ber-AC dengan dua tempat tidur single, ideal untuk dua orang teman perjalanan.',
                        'capacity'    => 2,
                        'price'       => 250000.00,
                    ],
                    [
                        'name'        => 'Kamar Keluarga',
                        'description' => 'Kamar luas ber-AC dengan satu tempat tidur double dan dua tempat tidur single untuk keluarga kecil.',
                        'capacity'    => 4,
                        'price'       => 300000.00,
                    ],
                ],
            ],

            // 3 — Bungalow
            [
                'slug'             => 'bungalow-mangrove-temajuk',
                'title'            => 'Bungalow Mangrove Temajuk',
                'description'      => 'Bungalow kayu eksotis di tepi hutan mangrove dengan suasana alam yang tenang dan pemandangan sungai yang memukau.',
                'full_description' => 'Bungalow Mangrove Temajuk menawarkan pengalaman menginap yang benar-benar menyatu dengan alam. Setiap unit bungalow dibangun dari kayu ulin lokal dan berdiri di atas tiang-tiang di tepi sungai yang berbatasan langsung dengan hutan mangrove. Di pagi hari, tamu dapat menikmati kicauan burung langka dan menyaksikan aktivitas kepiting bakau dari teras masing-masing bungalow. Tersedia juga fasilitas kayak dan perahu untuk menjelajahi hutan mangrove secara mandiri. Bungalow ini sangat ideal bagi pecinta alam dan fotografer yang ingin mengabadikan keindahan ekosistem mangrove.',
                'image_url'        => 'https://images.pexels.com/photos/2476632/pexels-photo-2476632.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category'         => 'bungalow',
                'min_price'        => 'Rp 350.000',
                'max_price'        => 'Rp 700.000',
                'location'         => 'Kawasan Mangrove Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat',
                'contacs'          => '+62 821-5678-9012',
                'site_url'         => 'https://bungalowmangrovetemajuk.com',
                'facilities'       => [
                    'Teras Tepi Sungai',
                    'Kayak & Perahu Gratis',
                    'WiFi Terbatas',
                    'Sarapan Ringan',
                    'Area Parkir',
                    'Pemandu Wisata Mangrove',
                    'Hammock di Setiap Unit',
                ],
                'tips'             => [
                    'Gunakan losion antinyamuk karena area mangrove cukup banyak serangga di malam hari',
                    'Waktu terbaik mengamati burung adalah pukul 06.00–08.00 pagi',
                    'Bawa senter sendiri karena pencahayaan di malam hari cukup redup untuk menjaga suasana alami',
                ],
                'gallery'          => [
                    'https://images.pexels.com/photos/2583852/pexels-photo-2583852.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/3155666/pexels-photo-3155666.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1802255/pexels-photo-1802255.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
                'room_types'       => [
                    [
                        'name'        => 'Bungalow Standar',
                        'description' => 'Unit bungalow kayu dengan satu tempat tidur double, kipas angin, dan teras menghadap sungai.',
                        'capacity'    => 2,
                        'price'       => 350000.00,
                    ],
                    [
                        'name'        => 'Bungalow Loft',
                        'description' => 'Bungalow dua lantai dengan tempat tidur di lantai atas dan ruang santai di bawah, cocok untuk pasangan yang romantis.',
                        'capacity'    => 2,
                        'price'       => 500000.00,
                    ],
                    [
                        'name'        => 'Bungalow Keluarga Luas',
                        'description' => 'Unit bungalow besar dengan dua kamar tidur, ruang tamu, dan dapur kecil, menghadap hutan mangrove.',
                        'capacity'    => 5,
                        'price'       => 700000.00,
                    ],
                ],
            ],

            // 4 — Homestay
            [
                'slug'             => 'homestay-pak-amin-temajuk',
                'title'            => 'Homestay Pak Amin',
                'description'      => 'Homestay hangat dan bersahabat milik keluarga lokal, tinggal bersama tuan rumah dan nikmati masakan rumahan khas Melayu.',
                'full_description' => 'Homestay Pak Amin adalah rumah keluarga yang dibuka untuk umum dengan penuh keramahan khas masyarakat Melayu Sambas. Tinggal di sini bukan sekadar menginap, tetapi merupakan pengalaman budaya yang mendalam. Tamu akan disambut dengan teh dan kue-kue tradisional, diajak berbincang hangat dengan keluarga tuan rumah, dan disajikan masakan rumahan yang kaya rempah. Lokasi homestay berjarak hanya 500 meter dari Pantai Temajuk. Pak Amin juga dengan senang hati menjadi pemandu wisata informal yang mengenal setiap sudut Desa Temajuk.',
                'image_url'        => 'https://images.pexels.com/photos/1029599/pexels-photo-1029599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category'         => 'homestay',
                'min_price'        => 'Rp 100.000',
                'max_price'        => 'Rp 200.000',
                'location'         => 'Gg. Mawar No. 5, Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat',
                'contacs'          => '+62 853-2345-6789',
                'site_url'         => 'https://homestaypakamin.id',
                'facilities'       => [
                    'Makan 3x Sehari (Opsional)',
                    'Dapur Bersama',
                    'Ruang Keluarga',
                    'WiFi Gratis',
                    'Area Parkir',
                    'Sepeda Pinjaman',
                ],
                'tips'             => [
                    'Pesan paket makan untuk merasakan masakan khas Melayu yang autentik dengan harga terjangkau',
                    'Minta Pak Amin untuk mengantarkan ke spot sunrise terbaik di Temajuk',
                    'Cocok untuk solo traveler atau pasangan yang ingin pengalaman lokal yang hangat',
                ],
                'gallery'          => [
                    'https://images.pexels.com/photos/276724/pexels-photo-276724.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/2062426/pexels-photo-2062426.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1457847/pexels-photo-1457847.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
                'room_types'       => [
                    [
                        'name'        => 'Kamar Kipas Sederhana',
                        'description' => 'Kamar bersih dengan kipas angin, tempat tidur single, dan kamar mandi bersama di luar.',
                        'capacity'    => 1,
                        'price'       => 100000.00,
                    ],
                    [
                        'name'        => 'Kamar Double Kipas',
                        'description' => 'Kamar dengan tempat tidur double, kipas angin, dan kamar mandi dalam yang nyaman.',
                        'capacity'    => 2,
                        'price'       => 175000.00,
                    ],
                    [
                        'name'        => 'Kamar AC Double',
                        'description' => 'Kamar terbaik di homestay dengan AC, tempat tidur double besar, dan lemari pakaian.',
                        'capacity'    => 2,
                        'price'       => 200000.00,
                    ],
                ],
            ],

            // 5 — Villa
            [
                'slug'             => 'villa-cakrawala-temajuk',
                'title'            => 'Villa Cakrawala Temajuk',
                'description'      => 'Villa mewah di perbukitan dengan pemandangan panoramik laut Temajuk dan hutan tropis yang memanjakan mata.',
                'full_description' => 'Villa Cakrawala Temajuk berdiri di ketinggian perbukitan yang menghadap langsung ke Laut Natuna, menawarkan pemandangan 180 derajat yang tiada tara. Setiap villa berdiri sendiri dengan halaman privat, kolam renang pribadi, dan dapur lengkap yang siap untuk memasak sendiri maupun memesan layanan private chef. Desain interiornya memadukan kemewahan kontemporer dengan material alam lokal seperti kayu ulin dan batu alam Kalimantan. Tamu yang menginap akan merasakan privasi dan ketenangan yang sempurna, jauh dari kebisingan kota namun tetap dekat dengan destinasi wisata utama Temajuk.',
                'image_url'        => 'https://images.pexels.com/photos/32870/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category'         => 'villa',
                'min_price'        => 'Rp 1.200.000',
                'max_price'        => 'Rp 3.500.000',
                'location'         => 'Bukit Cakrawala, Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat',
                'contacs'          => '+62 878-9012-3456',
                'site_url'         => 'https://villacakrawala.com',
                'facilities'       => [
                    'Private Pool',
                    'Dapur Lengkap',
                    'Layanan Private Chef (Opsional)',
                    'WiFi Fiber Optic',
                    'Smart TV',
                    'Ruang Tamu Mewah',
                    'Teras Panoramik',
                    'Layanan Antar-Jemput Bandara',
                    'Concierge 24 Jam',
                ],
                'tips'             => [
                    'Nikmati sunset dari teras villa, pemandangannya adalah salah satu yang terbaik di Kalimantan Barat',
                    'Sewa villa selama minimal 2 malam untuk mendapatkan pengalaman yang lebih maksimal',
                    'Pesan layanan private chef minimal sehari sebelumnya untuk menu makan malam spesial',
                ],
                'gallery'          => [
                    'https://images.pexels.com/photos/53464/sheraton-palace-hotel-lobby-architecture-53464.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/2631746/pexels-photo-2631746.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/2029722/pexels-photo-2029722.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
                'room_types'       => [
                    [
                        'name'        => 'Villa One Bedroom',
                        'description' => 'Villa satu kamar tidur dengan private pool kecil, ruang tamu, dapur, dan teras panoramik menghadap laut.',
                        'capacity'    => 2,
                        'price'       => 1200000.00,
                    ],
                    [
                        'name'        => 'Villa Two Bedroom',
                        'description' => 'Villa dua kamar tidur dengan private pool besar, living room luas, dapur modern, dan dua teras yang menghadap ke laut dan hutan.',
                        'capacity'    => 4,
                        'price'       => 2200000.00,
                    ],
                    [
                        'name'        => 'Grand Villa Three Bedroom',
                        'description' => 'Villa terbesar dengan tiga kamar tidur en-suite, private pool jumbo, area BBQ, ruang bioskop mini, dan pemandangan 180 derajat.',
                        'capacity'    => 6,
                        'price'       => 3500000.00,
                    ],
                ],
            ],

            // 6 — Homestay (kedua)
            [
                'slug'             => 'homestay-ibu-sari-temajuk',
                'title'            => 'Homestay Ibu Sari',
                'description'      => 'Homestay nyaman di pusat desa Temajuk, dikelola ibu rumah tangga lokal dengan layanan personal yang tulus dan masakan rumahan yang lezat.',
                'full_description' => 'Homestay Ibu Sari adalah tempat peristirahatan favorit para backpacker dan peneliti yang mengunjungi Temajuk. Dikelola langsung oleh Ibu Sari beserta putrinya, homestay ini terkenal dengan kebersihan kamar, kehangatan pelayanan, dan kelezatan masakan Kalimantan Barat yang disajikan setiap hari. Porsi makan yang besar dengan lauk ikan segar langsung dari nelayan membuat banyak tamu menjadi pelanggan setia. Lokasinya strategis, dekat dengan warung, masjid, dan angkutan menuju destinasi wisata utama di Temajuk.',
                'image_url'        => 'https://images.pexels.com/photos/2029731/pexels-photo-2029731.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category'         => 'homestay',
                'min_price'        => 'Rp 90.000',
                'max_price'        => 'Rp 180.000',
                'location'         => 'Jl. Desa Rt. 01, Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat',
                'contacs'          => '+62 819-6543-2109',
                'site_url'         => 'https://homestaybusari.id',
                'facilities'       => [
                    'Makan Pagi & Malam',
                    'Dapur Bersama',
                    'Ruang Santai Ber-AC',
                    'WiFi Gratis',
                    'Toilet & Kamar Mandi Bersama',
                    'Loker Penyimpanan Barang',
                ],
                'tips'             => [
                    'Coba menu ikan bakar bumbu tempoyak khas Ibu Sari yang menjadi andalan tamu',
                    'Cocok untuk backpacker yang membutuhkan akomodasi bersih dengan budget terbatas',
                    'Informasikan waktu kepulangan lebih awal agar dapat dibuatkan bekal perjalanan',
                ],
                'gallery'          => [
                    'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1001965/pexels-photo-1001965.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/2119714/pexels-photo-2119714.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
                'room_types'       => [
                    [
                        'name'        => 'Kamar Dormitori',
                        'description' => 'Kamar dormitori dengan 4 tempat tidur bunk, loker pribadi, dan kipas angin. Ideal untuk backpacker solo.',
                        'capacity'    => 1,
                        'price'       => 90000.00,
                    ],
                    [
                        'name'        => 'Kamar Privat Kipas',
                        'description' => 'Kamar privat dengan tempat tidur double, kipas angin, dan kamar mandi bersama.',
                        'capacity'    => 2,
                        'price'       => 150000.00,
                    ],
                    [
                        'name'        => 'Kamar Privat AC',
                        'description' => 'Kamar privat paling nyaman dengan AC, tempat tidur double, dan kamar mandi dalam.',
                        'capacity'    => 2,
                        'price'       => 180000.00,
                    ],
                ],
            ],

            // 7 — Bungalow (kedua)
            [
                'slug'             => 'bungalow-sunrise-paloh',
                'title'            => 'Bungalow Sunrise Paloh',
                'description'      => 'Bungalow tepi pantai dengan orientasi menghadap timur, menawarkan pengalaman menikmati matahari terbit langsung dari tempat tidur.',
                'full_description' => 'Bungalow Sunrise Paloh dirancang khusus dengan orientasi menghadap ke arah timur, sehingga tamu dapat menikmati matahari terbit yang spektakuler langsung dari jendela kamar atau teras depan bungalow. Setiap unit dibangun dari bahan semi-permanen yang menyejukkan, terletak hanya 30 meter dari bibir pantai. Suara ombak menjadi teman tidur yang menenangkan setiap malam. Pengelola juga menyediakan paket wisata lokal termasuk trip ke Tugu Perbatasan Indonesia-Malaysia dan snorkeling di perairan dangkal Temajuk.',
                'image_url'        => 'https://images.pexels.com/photos/2373201/pexels-photo-2373201.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'category'         => 'bungalow',
                'min_price'        => 'Rp 280.000',
                'max_price'        => 'Rp 550.000',
                'location'         => 'Pantai Timur Temajuk, Desa Temajuk, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat',
                'contacs'          => '+62 822-3456-7890',
                'site_url'         => 'https://bungalowsunrisepaloh.com',
                'facilities'       => [
                    'Akses Langsung ke Pantai',
                    'Teras Tepi Pantai',
                    'Sarapan Termasuk',
                    'WiFi Gratis',
                    'Penyewaan Alat Snorkeling',
                    'Parkir Motor & Mobil',
                    'Paket Wisata Lokal',
                    'Area BBQ',
                ],
                'tips'             => [
                    'Bangun pukul 05.30 untuk menikmati matahari terbit yang memukau langsung dari teras',
                    'Paket snorkeling tersedia dengan harga terjangkau, daftarkan diri ke resepsionis sore hari sebelumnya',
                    'Bawa sunblock dan topi karena pantai langsung menghadap matahari tanpa naungan pepohonan',
                ],
                'gallery'          => [
                    'https://images.pexels.com/photos/1802255/pexels-photo-1802255.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/1450353/pexels-photo-1450353.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'https://images.pexels.com/photos/3155666/pexels-photo-3155666.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                ],
                'room_types'       => [
                    [
                        'name'        => 'Bungalow Pantai Standar',
                        'description' => 'Unit bungalow dengan satu tempat tidur double, kipas angin, dan teras menghadap pantai timur.',
                        'capacity'    => 2,
                        'price'       => 280000.00,
                    ],
                    [
                        'name'        => 'Bungalow Honeymoon',
                        'description' => 'Unit romantis dengan dekorasi bunga segar, bathtub outdoor pribadi, dan champagne welcome drink untuk pasangan.',
                        'capacity'    => 2,
                        'price'       => 450000.00,
                    ],
                    [
                        'name'        => 'Bungalow Keluarga Pantai',
                        'description' => 'Unit besar dengan dua kamar tidur, ruang tamu, dan teras lebar menghadap pantai untuk keluarga.',
                        'capacity'    => 5,
                        'price'       => 550000.00,
                    ],
                ],
            ],
        ];
    }
}
