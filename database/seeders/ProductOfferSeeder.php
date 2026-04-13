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

        // Platform mapping: platform_type => Platform name
        $platformMap = [
            'pc' => 'PC',
            'ps4' => 'PlayStation 4',
            'ps5' => 'PlayStation 5',
            'xbox' => 'Xbox Series X/S',
            'nintendo' => 'Nintendo Switch',
        ];

        // Mindegyik termékhez 2-4 ajánlatot adunk diferentes eladóktól
        foreach ($products as $product) {
            // Platform ID meghatározása a product.platform_type alapján
            $platformName = $platformMap[$product->platform_type] ?? 'PC';
            $platform = Platform::where('name', $platformName)->first();

            if (!$platform) {
                // Ha nem találjuk, PC-re alapértelmezünk
                $platform = Platform::where('name', 'PC')->first();
            }

            // 2-4 random eladót választunk
            $vendorCount = rand(2, 4);
            $selectedVendors = $vendors->random(min($vendorCount, $vendors->count()));

            $basePrice = $this->getBasePrice($product);

            foreach ($selectedVendors as $index => $vendor) {
                // Ár variáció - ±10%
                $priceVariation = $basePrice * (0.9 + rand(0, 20) / 100);
                $price = round($priceVariation / 100) * 100; // Kerekített ár

                ProductOffer::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'vendor_id' => $vendor->id,
                        'platform_id' => $platform->id,
                    ],
                    [
                        'price' => $price,
                        'delivery_type' => 'key',
                        'region' => ['EU', 'US', 'GLOBAL'][rand(0, 2)],
                        'status' => 'active',
                    ]
                );
            }
        }
    }

    private function getBasePrice(Product $product): float
    {
        // Alapár kategória és típus alapján
        if ($product->category->name === 'Játék') {
            // Játékok: 1500-4999 Ft
            return rand(1500, 4999);
        } elseif ($product->category->name === 'Szoftver') {
            // Szoftverek: 2000-8000 Ft
            return rand(2000, 8000);
        } else {
            // Előfizetések: 999-2999 Ft
            return rand(999, 2999);
        }
    }
}
