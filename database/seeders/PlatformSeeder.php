<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    
    public function run(): void
    {
        $platforms = [
            ['name' => 'PC', 'description' => 'Personal Computer / Windows'],
            ['name' => 'PlayStation 5', 'description' => 'Sony PlayStation 5'],
            ['name' => 'PlayStation 4', 'description' => 'Sony PlayStation 4'],
            ['name' => 'Xbox Series X/S', 'description' => 'Microsoft Xbox Series X és S'],
            ['name' => 'Xbox One', 'description' => 'Microsoft Xbox One'],
            ['name' => 'Nintendo Switch', 'description' => 'Nintendo Switch console'],
            ['name' => 'Steam', 'description' => 'Steam platform'],
            ['name' => 'Epic Games Store', 'description' => 'Epic Games Store platform'],
            ['name' => 'GOG', 'description' => 'GOG.com platform'],
            ['name' => 'Uplay', 'description' => 'Ubisoft Uplay platform'],
        ];

        foreach ($platforms as $platform) {
            Platform::create($platform);
        }
    }
}
