<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function show($category = null)
    {
        $categoryLabels = [
            'pc_games' => '🖥️ PC Játékok',
            'console_games' => '🎮 Konzol Játékok',
            'game_subscriptions' => '🎯 Játék Előfizetések',
            'software' => '💻 Szoftver',
        ];

        if ($category) {
            switch ($category) {
                case 'pc_games':
                    $products = Product::where('platform_type', 'pc')
                        ->whereHas('category', fn($q) => $q->where('name', 'Játék'))
                        ->get();
                    break;
                case 'console_games':
                    $products = Product::whereIn('platform_type', ['ps4', 'ps5', 'xbox', 'nintendo'])
                        ->whereHas('category', fn($q) => $q->where('name', 'Játék'))
                        ->get();
                    break;
                case 'game_subscriptions':
                    $products = Product::whereHas('category', fn($q) => $q->where('name', 'Előfizetés'))->get();
                    break;
                case 'software':
                    $products = Product::whereHas('category', fn($q) => $q->where('name', 'Szoftver'))->get();
                    break;
                default:
                    $products = Product::all();
                    break;
            }
            $categoryLabel = $categoryLabels[$category] ?? 'Összes Termék';
        } else {
            $products = Product::all();
            $category = null;
            $categoryLabel = 'Összes Termék';
        }

        return view('filter', compact('products', 'category', 'categoryLabel'));
    }
}
