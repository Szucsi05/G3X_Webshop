<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    
    public function run(): void
    {
        $products = $this->getProducts();

        foreach ($products as $product) {
            Product::create($product);
        }
    }

    private function getProducts(): array
    {
        $gameCategory = Category::where('name', 'Játék')->first()?->id;
        $softCategory = Category::where('name', 'Szoftver')->first()?->id;
        $subCategory = Category::where('name', 'Előfizetés')->first()?->id;

        $products = [];

        
        $gameVariants = [
            'ac_valhalla' => [
                'pc' => ["Assassin's Creed Valhalla (PC)", 'ac_valhalla.jpg'],
                'ps5' => ["Assassin's Creed Valhalla (PS5)", 'ac_valhalla_ps5.jpg'],
                'xbox' => ["Assassin's Creed Valhalla (Xbox)", 'ac_valhalla_xbox.jpg'],
            ],
            'alien_colonial_marines' => [
                'pc' => ['Alien Colonial Marines', 'alien_colonial_marines.jpg'],
            ],
            'alien_dark_descent' => [
                'pc' => ['Alien: Dark Descent', 'alien_dark_descent.png'],
            ],
            'alien_isolation' => [
                'pc' => ['Alien: Isolation (PC)', 'alien_isolation.jpg'],
                'xbox' => ['Alien: Isolation (Xbox)', 'alien_isolation_xbox.jpg'],
            ],
            'amongus' => [
                'pc' => ['Among Us', 'amongus.jpg'],
            ],
            'arc_raiders' => [
                'pc' => ['Arc Raiders', 'arc_raiders.jpg'],
            ],
            'ark_survival_ascended' => [
                'pc' => ['ARK Survival Ascended', 'ark_survival_ascended.jpg'],
            ],
            'avp' => [
                'pc' => ['Alien vs Predator', 'avp.jpg'],
            ],
            'back_4_blood' => [
                'pc' => ['Back 4 Blood (PC)', 'back_4_blood.jpg'],
                'ps5' => ['Back 4 Blood (PS5)', 'back_4_blood_ps5.jpg'],
                'xbox' => ['Back 4 Blood (Xbox)', 'back_4_blood_xbox.jpg'],
            ],
            'batman_arkham' => [
                'pc' => ['Batman Arkham', 'batman_arkham.jpg'],
            ],
            'bf_1' => [
                'pc' => ['Battlefield 1 (PC)', 'bf_1.jpg'],
                'xbox' => ['Battlefield 1 (Xbox)', 'bf_1_xbox.jpg'],
            ],
            'bf_4' => [
                'pc' => ['Battlefield 4 (PC)', 'bf_4.jpg'],
                'xbox' => ['Battlefield 4 (Xbox)', 'bf_4_xbox.jpg'],
            ],
            'bf_5' => [
                'pc' => ['Battlefield 5 (PC)', 'bf_5.jpg'],
                'xbox' => ['Battlefield 5 (Xbox)', 'bf_5_xbox.jpg'],
            ],
            'bf_6' => [
                'pc' => ['Battlefield 6', 'bf_6.jpg'],
            ],
            'buckshot_roulette' => [
                'pc' => ['Buckshot Roulette', 'buckshot_roulette.jpg'],
            ],
            'civilization_6' => [
                'pc' => ['Civilization VI', 'civilization_6.jpg'],
            ],
            'cod_black_ops_6' => [
                'pc' => ['Call of Duty Black Ops 6', 'cod_black_ops_6.jpg'],
            ],
            'cod_black_ops_7' => [
                'pc' => ['Call of Duty Black Ops 7', 'cod_black_ops_7.png'],
            ],
            'cod_modern_warfare_2' => [
                'xbox' => ['Call of Duty Modern Warfare 2 (Xbox)', 'cod_modern_warfare_2_xbox.jpg'],
            ],
            'cod_ww2' => [
                'pc' => ['Call of Duty WW2', 'cod_ww2.jpg'],
            ],
            'cyberpunk' => [
                'pc' => ['Cyberpunk 2077 (PC)', 'cyberpunk.jpg'],
                'ps5' => ['Cyberpunk 2077 (PS5)', 'cyberpunk_ps5.jpg'],
                'xbox' => ['Cyberpunk 2077 (Xbox)', 'cyberpunk_xbox.jpg'],
            ],
            'dbd' => [
                'pc' => ['Dead by Daylight', 'dbd.jpg'],
            ],
            'diablo_3' => [
                'pc' => ['Diablo 3 (PC)', 'diablo_3.jpg'],
                'xbox' => ['Diablo 3 (Xbox)', 'diablo_3_xbox.jpg'],
            ],
            'diablo_4' => [
                'pc' => ['Diablo 4 (PC)', 'diablo_4.jpg'],
                'ps5' => ['Diablo 4 (PS5)', 'diablo_4_ps5.jpg'],
                'xbox' => ['Diablo 4 (Xbox)', 'diablo_4_xbox.jpg'],
            ],
            'dirt_rally' => [
                'pc' => ['Dirt Rally (PC)', 'dirt_rally.jpg'],
                'xbox' => ['Dirt Rally (Xbox)', 'dirt_rally_xbox.jpg'],
            ],
            'dirt_rally_2' => [
                'pc' => ['Dirt Rally 2 (PC)', 'dirt_rally_2.jpg'],
                'xbox' => ['Dirt Rally 2 (Xbox)', 'dirt_rally_2_xbox.jpg'],
            ],
            'elden_ring' => [
                'pc' => ['Elden Ring', 'elden_ring.jpg'],
            ],
            'fc_24' => [
                'pc' => ['FC 24 (PC)', 'fc_24.jpg'],
                'ps4' => ['FC 24 (PS4)', 'fc_24_ps4.jpg'],
                'ps5' => ['FC 24 (PS5)', 'fc_24_ps5.jpg'],
                'xbox' => ['FC 24 (Xbox)', 'fc_24_xbox.jpg'],
            ],
            'fc_25' => [
                'pc' => ['FC 25 (PC)', 'fc_25.jpg'],
                'ps5' => ['FC 25 (PS5)', 'fc_25_ps5.jpg'],
                'xbox' => ['FC 25 (Xbox)', 'fc_25_xbox.jpg'],
            ],
            'fc_26' => [
                'pc' => ['FC 26', 'fc_26.jpg'],
            ],
            'fifa_23' => [
                'pc' => ['FIFA 23 (PC)', 'fifa_23.jpg'],
                'ps5' => ['FIFA 23 (PS5)', 'fifa_23_ps5.jpg'],
                'xbox' => ['FIFA 23 (Xbox)', 'fifa_23_xbox.jpg'],
            ],
            'forest' => [
                'pc' => ['The Forest', 'forest.jpg'],
            ],
            'gow_ragnarök' => [
                'pc' => ['God of War Ragnarök', 'gow_ragnarök.jpg'],
            ],
            'green_hell' => [
                'pc' => ['Green Hell', 'green_hell.jpg'],
            ],
            'gta_v' => [
                'pc' => ['Grand Theft Auto V', 'gta_v.jpg'],
            ],
            'hogwarts_legacy' => [
                'pc' => ['Hogwarts Legacy', 'hogwarts_legacy.jpg'],
            ],
            'left_4_dead' => [
                'pc' => ['Left 4 Dead', 'left_4_dead.jpg'],
            ],
            'left_4_dead_2' => [
                'pc' => ['Left 4 Dead 2', 'left_4_dead_2.jpg'],
            ],
            'metro_exodus' => [
                'pc' => ['Metro Exodus (PC)', 'metro_exodus.jpg'],
                'ps5' => ['Metro Exodus (PS5)', 'metro_exodus_ps5.png'],
                'xbox' => ['Metro Exodus (Xbox)', 'metro_exodus_xbox.jpg'],
            ],
            'metro_redus' => [
                'xbox' => ['Metro Redux (Xbox)', 'metro_redus_xbox.jpg'],
            ],
            'metro_redux' => [
                'pc' => ['Metro Redux (PC)', 'metro_redux.jpg'],
            ],
            'minecraft' => [
                'pc' => ['Minecraft (PC)', 'minecraft.jpg'],
                'ps4' => ['Minecraft (PS4)', 'minecraft_ps4.jpg'],
                'xbox' => ['Minecraft (Xbox)', 'minecraft_xbox.jpg'],
            ],
            'mortal_kombat_11' => [
                'pc' => ['Mortal Kombat 11 (PC)', 'mortal_kombat_11.jpg'],
                'ps5' => ['Mortal Kombat 11 (PS5)', 'mortal_kombat_11_ps5.jpg'],
                'xbox' => ['Mortal Kombat 11 (Xbox)', 'mortal_kombat_11_xbox.jpg'],
            ],
            'mortal_kombat_x' => [
                'pc' => ['Mortal Kombat X (PC)', 'mortal_kombat_x.jpg'],
                'ps4' => ['Mortal Kombat X (PS4)', 'mortal_kombat_x_ps4.jpg'],
                'ps5' => ['Mortal Kombat X (PS5)', 'mortal_kombat_x_ps5.jpg'],
                'xbox' => ['Mortal Kombat X (Xbox)', 'mortal_kombat_x_xbox.jpg'],
            ],
            'nba_2k24' => [
                'pc' => ['NBA 2K24', 'nba_2k24.jpg'],
            ],
            'need_for_speed_heat' => [
                'pc' => ['Need for Speed Heat (PC)', 'need_for_speed_heat.jpg'],
                'xbox' => ['Need for Speed Heat (Xbox)', 'need_for_speed_heat_xbox.jpg'],
            ],
            'outlast_2' => [
                'pc' => ['Outlast 2', 'outlast_2.jpg'],
            ],
            'predator_hunting_grounds' => [
                'pc' => ['Predator Hunting Grounds', 'predator_hunting_grounds.jpg'],
            ],
            'rdr' => [
                'pc' => ['Red Dead Redemption (PC)', 'rdr.jpg'],
                'nintendo' => ['Red Dead Redemption (Nintendo)', 'rdr_nintendo.jpg'],
            ],
            'rdr_2' => [
                'pc' => ['Red Dead Redemption 2 (PC)', 'rdr_2.jpg'],
                'ps4' => ['Red Dead Redemption 2 (PS4)', 'rdr_2_ps4.jpg'],
                'xbox' => ['Red Dead Redemption 2 (Xbox)', 'rdr_2_xbox.jpg'],
            ],
            'ready_or_not' => [
                'pc' => ['Ready or Not', 'ready_or_not.jpg'],
            ],
            'resident_evil' => [
                'pc' => ['Resident Evil', 'resident_evil.jpg'],
            ],
            'resident_evil_2' => [
                'pc' => ['Resident Evil 2 (PC)', 'resident_evil_2.jpg'],
                'xbox' => ['Resident Evil 2 (Xbox)', 'resident_evil_2_xbox.jpg'],
            ],
            'resident_evil_3' => [
                'pc' => ['Resident Evil 3', 'resident_evil_3.jpg'],
            ],
            'resident_evil_4' => [
                'pc' => ['Resident Evil 4 (PC)', 'resident_evil_4.jpg'],
                'ps5' => ['Resident Evil 4 (PS5)', 'resident_evil_4_ps5.jpg'],
            ],
            'resident_evil_requiem' => [
                'pc' => ['Resident Evil Requiem', 'resident_evil_requiem.jpg'],
            ],
            'resident_evil_resistance' => [
                'ps4' => ['Resident Evil Resistance (PS4)', 'resident_evil_resistance_ps4.jpg'],
            ],
            'resident_evil_village' => [
                'pc' => ['Resident Evil Village (PC)', 'resident_evil_village.png'],
                'ps4' => ['Resident Evil Village (PS4)', 'resident_evil_village_ps4.jpg'],
                'xbox' => ['Resident Evil Village (Xbox)', 'resident_evil_village_xbox.jpg'],
            ],
            'silent_hill_2' => [
                'pc' => ['Silent Hill 2', 'silent_hill_2.jpg'],
            ],
            'sims_4' => [
                'pc' => ['The Sims 4', 'sims_4.jpg'],
            ],
            'spiderman' => [
                'ps5' => ['Spider-Man (PS5)', 'spiderman_ps5.jpg'],
            ],
            'spiderman_2' => [
                'ps5' => ['Spider-Man 2 (PS5)', 'spiderman_2_ps5.jpg'],
            ],
            'stardew_valley' => [
                'pc' => ['Stardew Valley', 'stardew_valley.jpg'],
            ],
            'the_last_of_us_part_1' => [
                'ps5' => ['The Last of Us Part I (PS5)', 'the_last_of_us_part_1_ps5.jpg'],
            ],
            'the_last_of_us_part_2' => [
                'ps5' => ['The Last of Us Part 2 (PS5)', 'the_last_of_us_part_2_ps5.jpg'],
            ],
            'witcher_3' => [
                'pc' => ['The Witcher 3: Wild Hunt', 'witcher_3.jpg'],
            ],
        ];

        
        foreach ($gameVariants as $baseKey => $variants) {
            foreach ($variants as $platform => $data) {
                [$name, $image] = $data;
                $products[] = [
                    'name' => $name,
                    'description' => 'Videó játék',
                    'category_id' => $gameCategory,
                    'image' => "images/{$image}",
                    'platform_type' => $platform,
                ];
            }
        }

        
        $softwareData = [
            ['Norton', 'Norton.jpg', 'Biztonságos vírusvédelmi szoftver'],
            ['Backup', 'backup.jpg', 'Automatikus adatmentő szoftver'],
            ['McAfee', 'mcafee.jpg', 'Vírusvédelmi szoftver'],
            ['Microsoft Office 365', 'microsoft_office_365.jpg', 'Teljes irodai szoftvercsomag'],
            ['YouCam', 'youcam.jpg', 'Professzionális fényképszerkesztő szoftver'],
        ];

        foreach ($softwareData as [$name, $image, $description]) {
            $products[] = [
                'name' => $name,
                'description' => $description,
                'category_id' => $softCategory,
                'image' => "images/{$image}",
                'platform_type' => 'pc',
            ];
        }

        
        $subscriptionData = [
            ['Netflix', 'netflix.jpg', 'Streaming szolgáltatás filmekhez és sorozatokhoz'],
            ['Spotify', 'spotify.jpg', 'Zenei streaming szolgáltatás'],
            ['HBO', 'hbo.jpg', 'Premium streaming szolgáltatás'],
            ['YouTube Premium', 'yt.jpg', 'Videó streaming szolgáltatás'],
        ];

        foreach ($subscriptionData as [$name, $image, $description]) {
            $products[] = [
                'name' => $name,
                'description' => $description,
                'category_id' => $subCategory,
                'image' => "images/{$image}",
                'platform_type' => 'pc',
            ];
        }

        return $products;
    }
}
