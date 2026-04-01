<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = $this->getProducts();

        foreach ($products as $product) {
            Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }

    private function getProducts(): array
    {
        $gameCategory = Category::where('name', 'Játék')->first()?->id;
        $softCategory = Category::where('name', 'Szoftver')->first()?->id;
        $subCategory = Category::where('name', 'Előfizetés')->first()?->id;

        return [
            ['name' => 'Call of Duty WW2', 'description' => 'Tapasztald meg a második világháború ikonikus csatáit ebben az epikus first-person shooter játékban. Játszd el a híres hadműveleteket és alakítsd a történelem menetét.', 'category_id' => $gameCategory, 'image' => 'images/cod_ww2.jpg', 'platform_type' => 'pc'],
            ['name' => 'Dead by Daylight', 'description' => 'Egy multiplayer horror játék, ahol túlélők próbálnak megszökni egy gyilkos elől. Játszd a túlélő vagy a gyilkos szerepét izgalmas, feszült mérkőzésekben.', 'category_id' => $gameCategory, 'image' => 'images/dbd.jpg', 'platform_type' => 'xbox'],
            ['name' => 'The Last of Us Part I', 'description' => 'Joel és Ellie története egy poszt-apokaliptikus világban. Ez az érzelmes, akciódús játék a túlélésről és az emberi kapcsolatokról szól.', 'category_id' => $gameCategory, 'image' => 'images/lou_part1.jpg', 'platform_type' => 'playstation'],
            ['name' => 'Battlefield 6', 'description' => 'A Battlefield sorozat legújabb része hatalmas, pusztító csatákkal. Tapasztald meg a modern háború káoszát nagy létszámú multiplayer módokban.', 'category_id' => $gameCategory, 'image' => null, 'platform_type' => 'pc'],
            ['name' => 'FC 26', 'description' => 'A FIFA sorozat új kiadása, ahol irányíthatod kedvenc csapataidat vagy létrehozhatsz saját játékost. Élvezd a valósághű focit 1 hónapos előfizetéssel.', 'category_id' => $subCategory, 'image' => 'images/fc_26.jpg', 'platform_type' => 'pc'],
            ['name' => 'Red Dead Redemption 2', 'description' => 'Arthur Morgan kalandjai az amerikai vadnyugaton. Ez az open-world játék a bűnözés, a túlélés és a moralitás témáit járja körül.', 'category_id' => $gameCategory, 'image' => 'images/rdr_2.jpg', 'platform_type' => 'xbox'],
            ['name' => 'YouCam', 'description' => 'Professzionális fényképszerkesztő szoftver. Szépítsd ki képeidet, távolítsd el a háttért és alkosd meg a tökéletes selfie-t.', 'category_id' => $softCategory, 'image' => 'images/youcam.jpg', 'platform_type' => 'other'],
            ['name' => 'McAfee', 'description' => 'Biztonságos vírusvédelmi szoftver. Védje számítógépét a vírusoktól, malware-tól és online fenyegetésektől 1 évig.', 'category_id' => $softCategory, 'image' => 'images/mcafee.jpg', 'platform_type' => 'other'],
            ['name' => 'Backup', 'description' => 'Automatikus adatmentő szoftver. Biztosítsd adataidat a veszteség ellen és tárold őket biztonságosan a felhőben.', 'category_id' => $softCategory, 'image' => 'images/backup.jpg', 'platform_type' => 'other'],
            ['name' => 'Grand Theft Auto V', 'description' => 'Los Santos városában játszódó open-world akció játék. Lopj autót, kövess el bűncselekményeket és élvezd a szabadságot.', 'category_id' => $gameCategory, 'platform_type' => 'xbox'],
            ['name' => 'Minecraft', 'description' => 'Kreatív sandbox játék, ahol építhetsz, bányászolhatsz és túlélhetsz egy blokkos világban. Végtelen lehetőségek.', 'category_id' => $gameCategory, 'platform_type' => 'pc'],
            ['name' => 'The Witcher 3: Wild Hunt', 'description' => 'Geralt of Rivia kalandjai egy fantasy világban. Vadászd a szörnyeket, hozd meg a nehéz döntéseket.', 'category_id' => $gameCategory, 'platform_type' => 'pc'],
            ['name' => 'Cyberpunk 2077', 'description' => 'Night City-ben játszódó RPG, ahol a jövőben élsz. Válassz utadat a bűnözők, vállalatok és hackerek világában.', 'category_id' => $gameCategory, 'platform_type' => 'pc'],
            ['name' => 'Among Us', 'description' => 'Multiplayer játék, ahol űrhajósok között vannak imposterok. Találd ki ki a csaló és mentsd meg a hajót.', 'category_id' => $gameCategory, 'platform_type' => 'pc'],
            ['name' => 'Apex Legends', 'description' => 'Battle Royale játék, ahol legendák harcolnak. Válassz karaktert és használd egyedi képességeiket.', 'category_id' => $gameCategory, 'platform_type' => 'pc'],
            ['name' => 'The Sims 4', 'description' => 'Életszimulátor játék, ahol irányítod a simsek életét. Építs házat, neveld az állatokat és építs karriert.', 'category_id' => $gameCategory, 'platform_type' => 'pc'],
            ['name' => 'Stardew Valley', 'description' => 'Farm szimulátor játék. Műveld a földet, neveld az állatokat és építs kapcsolatokat.', 'category_id' => $gameCategory, 'platform_type' => 'pc'],
            ['name' => 'Civilization VI', 'description' => 'Stratégiai játék, ahol civilizációt építesz az ókortól a modern korig.', 'category_id' => $gameCategory, 'platform_type' => 'pc'],
            ['name' => 'FIFA 23', 'description' => 'Foci szimulátor játék. Játszd a mérkőzéseket, irányítsd a csapatokat.', 'category_id' => $gameCategory, 'platform_type' => 'pc'],
            ['name' => 'NBA 2K24', 'description' => 'Kosárlabda szimulátor. Játszd a NBA mérkőzéseket, építs csapatot.', 'category_id' => $gameCategory, 'platform_type' => 'pc'],
            ['name' => 'AC Valhalla', 'description' => 'Viking kalandok a Assassins Creed sorozatban.', 'category_id' => $gameCategory, 'image' => 'images/ac_valhalla.jpg', 'platform_type' => 'pc'],
            ['name' => 'Alien Isolation', 'description' => 'Horror játék az Alien univerzumban.', 'category_id' => $gameCategory, 'image' => 'images/alien_isolation.jpg', 'platform_type' => 'pc'],
            ['name' => 'Alien Dark Descent', 'description' => 'Stratégiai horror játék.', 'category_id' => $gameCategory, 'image' => 'images/alien_dark_descent.png', 'platform_type' => 'pc'],
            ['name' => 'Among Us', 'description' => 'Multiplayer játék, ahol űrhajósok között vannak imposterok.', 'category_id' => $gameCategory, 'image' => 'images/amoungus.jpg', 'platform_type' => 'pc'],
            ['name' => 'Arc Raiders', 'description' => 'Akció játék.', 'category_id' => $gameCategory, 'image' => 'images/arc_raiders.jpg', 'platform_type' => 'pc'],
            ['name' => 'ARK Survival Ascended', 'description' => 'Túlélő játék dinoszauruszokkal.', 'category_id' => $gameCategory, 'image' => 'images/ark_survival_ascended.jpg', 'platform_type' => 'pc'],
            ['name' => 'Back 4 Blood', 'description' => 'Co-op shooter játék.', 'category_id' => $gameCategory, 'image' => 'images/back_4_blood.jpg', 'platform_type' => 'pc'],
            ['name' => 'Batman Arkham', 'description' => 'Batman akció játék.', 'category_id' => $gameCategory, 'image' => 'images/batman_arkham.jpg', 'platform_type' => 'pc'],
            ['name' => 'Battlefield 1', 'description' => 'First World War shooter.', 'category_id' => $gameCategory, 'image' => 'images/bf_1.jpg', 'platform_type' => 'pc'],
            ['name' => 'Buckshot Roulette', 'description' => 'Feszült játék.', 'category_id' => $gameCategory, 'image' => 'images/buckshot_roulette.jpg', 'platform_type' => 'pc'],
            ['name' => 'Call of Duty Black Ops 6', 'description' => 'Modern Warfare shooter.', 'category_id' => $gameCategory, 'image' => 'images/cod_black_ops_6.jpg', 'platform_type' => 'pc'],
            ['name' => 'Call of Duty Black Ops 7', 'description' => 'Shooter játék.', 'category_id' => $gameCategory, 'image' => 'images/cod_black_ops_7.png', 'platform_type' => 'pc'],
            ['name' => 'Call of Duty Modern Warfare 2', 'description' => 'Taktikai shooter.', 'category_id' => $gameCategory, 'image' => 'images/cod_modern_warfare_2.jpg', 'platform_type' => 'pc'],
            ['name' => 'Diablo 4', 'description' => 'RPG játék.', 'category_id' => $gameCategory, 'image' => 'images/diablo_4_xbox.jpg', 'platform_type' => 'xbox'],
            ['name' => 'Dirt Rally', 'description' => 'Rally játék.', 'category_id' => $gameCategory, 'image' => 'images/dirt_rally.jpg', 'platform_type' => 'pc'],
            ['name' => 'Dirt Rally 2', 'description' => 'Rally játék.', 'category_id' => $gameCategory, 'image' => 'images/dirt_rally_2.jpg', 'platform_type' => 'pc'],
            ['name' => 'Elden Ring', 'description' => 'Open world RPG.', 'category_id' => $gameCategory, 'image' => 'images/elden_ring.jpg', 'platform_type' => 'pc'],
            ['name' => 'GOD of War Ragnarök', 'description' => 'Akció játék.', 'category_id' => $gameCategory, 'image' => 'images/gow_ragnarök.jpg', 'platform_type' => 'playstation'],
            ['name' => 'Green Hell', 'description' => 'Túlélő játék.', 'category_id' => $gameCategory, 'image' => 'images/green_hell.jpg', 'platform_type' => 'pc'],
            ['name' => 'Hogwarts Legacy', 'description' => 'Harry Potter játék.', 'category_id' => $gameCategory, 'image' => 'images/hogwarts_legacy.jpg', 'platform_type' => 'pc'],
            ['name' => 'Left 4 Dead', 'description' => 'Co-op shooter.', 'category_id' => $gameCategory, 'image' => 'images/left_4_dead.jpg', 'platform_type' => 'pc'],
            ['name' => 'Left 4 Dead 2', 'description' => 'Co-op shooter.', 'category_id' => $gameCategory, 'image' => 'images/left_4_dead_2.jpg', 'platform_type' => 'pc'],
            ['name' => 'Metro Exodus', 'description' => 'Post-apocalyptic shooter.', 'category_id' => $gameCategory, 'image' => 'images/metro_exodus.jpg', 'platform_type' => 'pc'],
            ['name' => 'Metro Redux', 'description' => 'Shooter játék.', 'category_id' => $gameCategory, 'image' => 'images/metro_redux.jpg', 'platform_type' => 'pc'],
            ['name' => 'Minecraft Xbox', 'description' => 'Sandbox játék.', 'category_id' => $gameCategory, 'image' => 'images/minecraft_xbox.jpg', 'platform_type' => 'xbox'],
            ['name' => 'Need for Speed Heat', 'description' => 'Racing játék.', 'category_id' => $gameCategory, 'image' => 'images/need_for_speed_heat.jpg', 'platform_type' => 'pc'],
            ['name' => 'Outlast 2', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/outlast_2.jpg', 'platform_type' => 'pc'],
            ['name' => 'Ready or Not', 'description' => 'Taktikai játék.', 'category_id' => $gameCategory, 'image' => 'images/ready_or_not.jpg', 'platform_type' => 'pc'],
            ['name' => 'Red Dead Redemption', 'description' => 'Western játék.', 'category_id' => $gameCategory, 'image' => 'images/red_dead_redemtion.jpg', 'platform_type' => 'pc'],
            ['name' => 'Resident Evil', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/resident_evil.jpg', 'platform_type' => 'pc'],
            ['name' => 'Resident Evil 2', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/resident_evil_2.jpg', 'platform_type' => 'pc'],
            ['name' => 'Resident Evil 2 Xbox', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/resident_evil_2_xbox.jpg', 'platform_type' => 'xbox'],
            ['name' => 'Resident Evil 4', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/resident_evil_4.jpg', 'platform_type' => 'pc'],
            ['name' => 'Resident Evil 4 PS5', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/resident_evil_4_ps5.jpg', 'platform_type' => 'playstation'],
            ['name' => 'Resident Evil Requiem', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/resident_evil_requiem.jpg', 'platform_type' => 'pc'],
            ['name' => 'Resident Evil Resistance PS4', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/resident_evil_resistance_ps4.jpg', 'platform_type' => 'playstation'],
            ['name' => 'Resident Evil Village', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/resident_evil_village.png', 'platform_type' => 'pc'],
            ['name' => 'Resident Evil Village PS5', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/resident_evil_village_ps5.jpg', 'platform_type' => 'playstation'],
            ['name' => 'Silent Hill 2', 'description' => 'Horror játék.', 'category_id' => $gameCategory, 'image' => 'images/silent_hill_2.jpg', 'platform_type' => 'pc'],
            ['name' => 'Spiderman', 'description' => 'Akció játék.', 'category_id' => $gameCategory, 'image' => 'images/spiderman.jpg', 'platform_type' => 'playstation'],
            ['name' => 'Spiderman 2', 'description' => 'Akció játék.', 'category_id' => $gameCategory, 'image' => 'images/spiderman_2.jpg', 'platform_type' => 'playstation'],
            ['name' => 'The Last of Us Part 2', 'description' => 'Akció játék.', 'category_id' => $gameCategory, 'image' => 'images/the_last_of_us_part_2.jpg', 'platform_type' => 'playstation'],
            ['name' => 'The Last of Us Part 2 PS5', 'description' => 'Akció játék.', 'category_id' => $gameCategory, 'image' => 'images/the_last_of_us_part_2_ps5.jpg', 'platform_type' => 'playstation'],
            ['name' => 'Microsoft Office 365', 'description' => 'Teljes irodai szoftvercsomag előfizetéssel.', 'category_id' => $softCategory, 'image' => 'images/microsoft_office_365.jpg', 'platform_type' => 'szoftver'],
            ['name' => 'Norton', 'description' => 'Biztonságos vírusvédelmi szoftver.', 'category_id' => $softCategory, 'image' => 'images/Norton.jpg', 'platform_type' => 'szoftver'],
            ['name' => 'Netflix', 'description' => 'Streaming szolgáltatás filmekhez és sorozatokhoz.', 'category_id' => $subCategory, 'image' => 'images/netflix.jpg', 'platform_type' => 'pc'],
            ['name' => 'Spotify', 'description' => 'Zenei streaming szolgáltatás.', 'category_id' => $subCategory, 'image' => 'images/spotify.jpg', 'platform_type' => 'pc'],
            ['name' => 'YouTube', 'description' => 'Videó streaming szolgáltatás.', 'category_id' => $subCategory, 'image' => 'images/yt.jpg', 'platform_type' => 'pc'],
        ];
    }
}
