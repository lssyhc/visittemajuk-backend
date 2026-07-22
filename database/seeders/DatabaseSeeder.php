<?php

declare(strict_types=1);

namespace Database\Seeders;

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

        $this->call(DestinationSeeder::class);
        $this->call(AccomodationSeeder::class);
        $this->call(SiteSettingsSeeder::class);
        $this->call(FooterSocialSeeder::class);
    }
}
