<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FooterSocial;
use Illuminate\Database\Seeder;

final class FooterSocialSeeder extends Seeder
{
    public function run(): void
    {
        $socials = [
            ['platform' => 'instagram', 'label' => 'Instagram', 'url' => 'https://instagram.com/visittemajuk', 'order' => 1],
            ['platform' => 'facebook', 'label' => 'Facebook', 'url' => 'https://facebook.com/visittemajuk', 'order' => 2],
            ['platform' => 'tiktok', 'label' => 'TikTok', 'url' => 'https://tiktok.com/@visittemajuk', 'order' => 3],
        ];

        foreach ($socials as $social) {
            FooterSocial::query()->updateOrCreate(
                ['platform' => $social['platform'], 'url' => $social['url']],
                $social,
            );
        }
    }
}
