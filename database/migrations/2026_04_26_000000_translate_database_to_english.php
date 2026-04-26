<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Translate categories
        DB::table('categories')->update([
            'name' => DB::raw("CASE name
                WHEN 'Játék' THEN 'Games'
                WHEN 'Szoftver' THEN 'Software'
                WHEN 'Előfizetés' THEN 'Subscription'
                ELSE name
            END"),
            'description' => DB::raw("CASE description
                WHEN 'Videó játékok' THEN 'Video games'
                WHEN 'Asztali alkalmazások' THEN 'Desktop applications'
                WHEN 'Előfizetéses szolgáltatások' THEN 'Subscription services'
                ELSE description
            END")
        ]);

        // Translate product descriptions
        DB::table('products')->update([
            'description' => DB::raw("CASE description
                WHEN 'Videó játék' THEN 'Video game'
                WHEN 'Biztonságos vírusvédelmi szoftver' THEN 'Secure antivirus software'
                WHEN 'Automatikus adatmentő szoftver' THEN 'Automatic backup software'
                WHEN 'Vírusvédelmi szoftver' THEN 'Antivirus software'
                WHEN 'Teljes irodai szoftvercsomag' THEN 'Complete office software package'
                WHEN 'Professzionális fényképszerkesztő szoftver' THEN 'Professional photo editing software'
                WHEN 'Streaming szolgáltatás filmekhez és sorozatokhoz' THEN 'Streaming service for movies and series'
                WHEN 'Zenei streaming szolgáltatás' THEN 'Music streaming service'
                WHEN 'Premium streaming szolgáltatás' THEN 'Premium streaming service'
                WHEN 'Videó streaming szolgáltatás' THEN 'Video streaming service'
                ELSE description
            END")
        ]);

        // Translate vendor descriptions
        DB::table('vendors')->update([
            'description' => DB::raw("CASE description
                WHEN 'Megbízható digitális játék eladó' THEN 'Reliable digital game seller'
                WHEN 'Kiváló árakkal és gyors szállítással' THEN 'Excellent prices and fast delivery'
                WHEN 'Szoftver és játék szupermarket' THEN 'Software and game supermarket'
                WHEN 'Rockstar Games hivatalos értékesítés' THEN 'Rockstar Games official sales'
                WHEN 'Electronic Arts hivatalos értékesítés' THEN 'Electronic Arts official sales'
                WHEN 'CD Projekt RED hivatalos értékesítés' THEN 'CD Projekt RED official sales'
                WHEN 'Minecraft hivatalos értékesítés' THEN 'Minecraft official sales'
                WHEN 'Epic Games játéka és alkalmazásai' THEN 'Epic Games games and applications'
                ELSE description
            END")
        ]);
    }

    public function down(): void
    {
        // Reverse translations back to Hungarian
        DB::table('categories')->update([
            'name' => DB::raw("CASE name
                WHEN 'Games' THEN 'Játék'
                WHEN 'Software' THEN 'Szoftver'
                WHEN 'Subscription' THEN 'Előfizetés'
                ELSE name
            END"),
            'description' => DB::raw("CASE description
                WHEN 'Video games' THEN 'Videó játékok'
                WHEN 'Desktop applications' THEN 'Asztali alkalmazások'
                WHEN 'Subscription services' THEN 'Előfizetéses szolgáltatások'
                ELSE description
            END")
        ]);

        DB::table('products')->update([
            'description' => DB::raw("CASE description
                WHEN 'Video game' THEN 'Videó játék'
                WHEN 'Secure antivirus software' THEN 'Biztonságos vírusvédelmi szoftver'
                WHEN 'Automatic backup software' THEN 'Automatikus adatmentő szoftver'
                WHEN 'Antivirus software' THEN 'Vírusvédelmi szoftver'
                WHEN 'Complete office software package' THEN 'Teljes irodai szoftvercsomag'
                WHEN 'Professional photo editing software' THEN 'Professzionális fényképszerkesztő szoftver'
                WHEN 'Streaming service for movies and series' THEN 'Streaming szolgáltatás filmekhez és sorozatokhoz'
                WHEN 'Music streaming service' THEN 'Zenei streaming szolgáltatás'
                WHEN 'Premium streaming service' THEN 'Premium streaming szolgáltatás'
                WHEN 'Video streaming service' THEN 'Videó streaming szolgáltatás'
                ELSE description
            END")
        ]);

        DB::table('vendors')->update([
            'description' => DB::raw("CASE description
                WHEN 'Reliable digital game seller' THEN 'Megbízható digitális játék eladó'
                WHEN 'Excellent prices and fast delivery' THEN 'Kiváló árakkal és gyors szállítással'
                WHEN 'Software and game supermarket' THEN 'Szoftver és játék szupermarket'
                WHEN 'Rockstar Games official sales' THEN 'Rockstar Games hivatalos értékesítés'
                WHEN 'Electronic Arts official sales' THEN 'Electronic Arts hivatalos értékesítés'
                WHEN 'CD Projekt RED official sales' THEN 'CD Projekt RED hivatalos értékesítés'
                WHEN 'Minecraft official sales' THEN 'Minecraft hivatalos értékesítés'
                WHEN 'Epic Games games and applications' THEN 'Epic Games játéka és alkalmazásai'
                ELSE description
            END")
        ]);
    }
};
