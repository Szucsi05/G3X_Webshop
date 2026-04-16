<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    
    public function run(): void
    {
        $categories = [
            ['name' => 'Játék', 'description' => 'Videó játékok'],
            ['name' => 'Szoftver', 'description' => 'Asztali alkalmazások'],
            ['name' => 'Előfizetés', 'description' => 'Előfizetéses szolgáltatások'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
