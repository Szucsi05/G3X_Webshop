<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = DB::table('products')->select('id','name','image','platform_type','category_id')->get();

foreach($products as $p){
    $newPlatformType = 'pc'; // default
    
    if($p->category_id == 1){ // játék
        if(strpos($p->image, '_xbox') !== false){
            $newPlatformType = 'xbox';
        } elseif(strpos($p->image, '_ps5') !== false || strpos($p->image, '_ps4') !== false){
            $newPlatformType = 'playstation';
        } elseif(strpos($p->image, '_switch') !== false){
            $newPlatformType = 'switch';
        } else {
            $newPlatformType = 'pc';
        }
    } elseif($p->category_id == 2 || $p->category_id == 3){ // szoftver vagy előfizetés
        $newPlatformType = 'pc';
    }
    
    if($p->platform_type != $newPlatformType){
        DB::table('products')->where('id', $p->id)->update(['platform_type' => $newPlatformType]);
        echo 'Updated ' . $p->name . ' from ' . ($p->platform_type ?? 'null') . ' to ' . $newPlatformType . PHP_EOL;
    }
}

echo 'Platform types updated. Now fixing offers...' . PHP_EOL;

// Platform mappings
$platformMaps = [
    'pc' => [1,7,8,9,10], // PC, Steam, Epic, GOG, Uplay
    'xbox' => [4,5], // Xbox Series X/S, Xbox One
    'playstation' => [2,3], // PS5, PS4
    'switch' => [6], // Nintendo Switch
    'szoftver' => [1], // PC for software
    'other' => [1,2,3,4,5,6,7,8,9,10] // all
];

$products = DB::table('products')->select('id','name','platform_type')->get();

foreach($products as $p){
    $validPlatforms = $platformMaps[$p->platform_type] ?? [1];
    $offers = DB::table('product_offers')->where('product_id', $p->id)->get();
    
    // Delete invalid offers
    foreach($offers as $o){
        if(!in_array($o->platform_id, $validPlatforms)){
            DB::table('product_offers')->where('id', $o->id)->delete();
            echo 'Deleted invalid offer id ' . $o->id . ' for ' . $p->name . PHP_EOL;
        }
    }
    
    // Limit to 4 offers
    $remainingOffers = DB::table('product_offers')->where('product_id', $p->id)->orderBy('price')->get();
    if($remainingOffers->count() > 4){
        $toDelete = $remainingOffers->skip(4);
        foreach($toDelete as $o){
            DB::table('product_offers')->where('id', $o->id)->delete();
            echo 'Deleted extra offer id ' . $o->id . ' for ' . $p->name . PHP_EOL;
        }
    }
    
    // If no offers, add some
    $finalOffers = DB::table('product_offers')->where('product_id', $p->id)->count();
    if($finalOffers == 0){
        $vendors = [1,2,3];
        $prices = [rand(1500,3000), rand(1600,3200), rand(1700,3500)];
        foreach($vendors as $i => $vid){
            $pid = $validPlatforms[array_rand($validPlatforms)];
            DB::table('product_offers')->insert([
                'product_id' => $p->id,
                'vendor_id' => $vid,
                'platform_id' => $pid,
                'price' => $prices[$i],
                'stock' => rand(5,100),
                'delivery_type' => 'key',
                'region' => 'EU',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        echo 'Added 3 offers for ' . $p->name . PHP_EOL;
    }
}

echo 'Done' . PHP_EOL;