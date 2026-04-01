<?php

namespace Database\Seeders;

use App\Models\ProductOffer;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $vendors = Vendor::all();
        $platforms = Platform::all()->pluck('id')->toArray();

        // Minden termékhez több ajánlatot adunk (legalább 3 eladóval)
        foreach ($products as $product) {
            $basePrices = [rand(1500, 5000), rand(1600, 5500), rand(1700, 6000)];
            $vendorIds = [1, 2, 3];

            foreach ($vendorIds as $index => $vendorId) {
                $platformId = $platforms[array_rand($platforms)];

                ProductOffer::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'vendor_id' => $vendorId,
                        'platform_id' => $platformId,
                    ],
                    [
                        'price' => $basePrices[$index],
                        'stock' => rand(5, 100),
                        'delivery_type' => 'key',
                        'region' => 'EU',
                        'status' => 'active',
                    ]
                );
            }
        }

        // Rockstar Games - Grand Theft Auto V és RDR2
        $gtav = Product::where('name', 'Grand Theft Auto V')->first();
        $rdr2 = Product::where('name', 'Red Dead Redemption 2')->first();

        if ($gtav) {
            ProductOffer::updateOrCreate([
                'product_id' => $gtav->id,
                'vendor_id' => 4, // Rockstar Games
                'platform_id' => Platform::where('name', 'PC')->first()->id,
            ],[
                'price' => 2999,
                'stock' => 999,
                'delivery_type' => 'key',
                'region' => 'GLOBAL',
                'status' => 'active',
            ]);
        }

        if ($rdr2) {
            ProductOffer::updateOrCreate([
                'product_id' => $rdr2->id,
                'vendor_id' => 4, // Rockstar Games
                'platform_id' => Platform::where('name', 'PC')->first()->id,
            ],[
                'price' => 4999,
                'stock' => 999,
                'delivery_type' => 'key',
                'region' => 'GLOBAL',
                'status' => 'active',
            ]);
        }

        // Mojang - Minecraft
        $minecraft = Product::where('name', 'Minecraft')->first();
        if ($minecraft) {
            ProductOffer::updateOrCreate([
                'product_id' => $minecraft->id,
                'vendor_id' => 7, // Mojang Studios
                'platform_id' => Platform::where('name', 'PC')->first()->id,
            ],[
                'price' => 4999,
                'stock' => 999,
                'delivery_type' => 'account',
                'region' => 'GLOBAL',
                'status' => 'active',
            ]);
        }

        // CD Projekt - The Witcher 3 és Cyberpunk 2077
        $witcher = Product::where('name', 'The Witcher 3: Wild Hunt')->first();
        $cyberpunk = Product::where('name', 'Cyberpunk 2077')->first();

        if ($witcher) {
            ProductOffer::updateOrCreate([
                'product_id' => $witcher->id,
                'vendor_id' => 6, // CD Projekt RED
                'platform_id' => Platform::where('name', 'PC')->first()->id,
            ],[
                'price' => 1999,
                'stock' => 999,
                'delivery_type' => 'key',
                'region' => 'GLOBAL',
                'status' => 'active',
            ]);
        }

        if ($cyberpunk) {
            ProductOffer::updateOrCreate([
                'product_id' => $cyberpunk->id,
                'vendor_id' => 6, // CD Projekt RED
                'platform_id' => Platform::where('name', 'PC')->first()->id,
            ],[
                'price' => 3999,
                'stock' => 999,
                'delivery_type' => 'key',
                'region' => 'GLOBAL',
                'status' => 'active',
            ]);
        }

        // Epic Games - Fortnite (free)
        $fortnite = Product::where('name', 'Fortnite')->first();
        if ($fortnite) {
            ProductOffer::updateOrCreate([
                'product_id' => $fortnite->id,
                'vendor_id' => 8, // Epic Games Store
                'platform_id' => Platform::where('name', 'Epic Games Store')->first()->id,
            ],[
                'price' => 0,
                'stock' => 999,
                'delivery_type' => 'account',
                'region' => 'GLOBAL',
                'status' => 'active',
            ]);
        }
    }
}
