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
    
    public function run(): void
    {
        $products = Product::all();
        $vendors = Vendor::all();

        
        $platformMap = [
            'pc' => 'PC',
            'ps4' => 'PlayStation 4',
            'ps5' => 'PlayStation 5',
            'xbox' => 'Xbox Series X/S',
            'nintendo' => 'Nintendo Switch',
        ];

        
        foreach ($products as $product) {
            
            $platformName = $platformMap[$product->platform_type] ?? 'PC';
            $platform = Platform::where('name', $platformName)->first();

            if (!$platform) {
                
                $platform = Platform::where('name', 'PC')->first();
            }

            
            $vendorCount = rand(2, 4);
            $selectedVendors = $vendors->random(min($vendorCount, $vendors->count()));

            $basePrice = $this->getBasePrice($product);

            foreach ($selectedVendors as $index => $vendor) {
                
                $priceVariation = $basePrice * (0.9 + rand(0, 20) / 100);
                $price = round($priceVariation / 100) * 100; 

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
        
        if ($product->category->name === 'Játék') {
            
            return rand(1500, 4999);
        } elseif ($product->category->name === 'Szoftver') {
            
            return rand(2000, 8000);
        } else {
            
            return rand(999, 2999);
        }
    }
}
