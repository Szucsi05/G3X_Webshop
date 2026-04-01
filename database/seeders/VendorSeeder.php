<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'GameShop Pro',
                'email' => 'support@gameshop.com',
                'description' => 'Megbízható digitális játék eladó',
                'rating' => 4.8,
                'website' => 'https://gameshop.com',
                'status' => 'active',
            ],
            [
                'name' => 'Digital Games Hub',
                'email' => 'info@digitalgames.com',
                'description' => 'Kiváló árakkal és gyors szállítással',
                'rating' => 4.6,
                'website' => 'https://digitalgames.com',
                'status' => 'active',
            ],
            [
                'name' => 'TechStore Online',
                'email' => 'customer@techstore.com',
                'description' => 'Szoftver és játék szupermarket',
                'rating' => 4.7,
                'website' => 'https://techstore.com',
                'status' => 'active',
            ],
            [
                'name' => 'Rockstar Games Official',
                'email' => 'support@rockstargames.com',
                'description' => 'Rockstar Games hivatalos értékesítés',
                'rating' => 4.9,
                'website' => 'https://store.rockstargames.com',
                'status' => 'active',
            ],
            [
                'name' => 'EA Games Store',
                'email' => 'support@eagames.com',
                'description' => 'Electronic Arts hivatalos értékesítés',
                'rating' => 4.5,
                'website' => 'https://store.eagames.com',
                'status' => 'active',
            ],
            [
                'name' => 'CD Projekt RED',
                'email' => 'support@cdprojektred.com',
                'description' => 'CD Projekt RED hivatalos értékesítés',
                'rating' => 4.8,
                'website' => 'https://store.cdprojektred.com',
                'status' => 'active',
            ],
            [
                'name' => 'Mojang Studios',
                'email' => 'support@mojang.com',
                'description' => 'Minecraft hivatalos értékesítés',
                'rating' => 4.9,
                'website' => 'https://minecraft.net',
                'status' => 'active',
            ],
            [
                'name' => 'Epic Games Store',
                'email' => 'support@epicgames.com',
                'description' => 'Epic Games játéka és alkalmazásai',
                'rating' => 4.7,
                'website' => 'https://epicgamesstore.com',
                'status' => 'active',
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}
