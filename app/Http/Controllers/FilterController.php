<?php

namespace App\Http\Controllers;

use App\Models\Product;

class FilterController extends Controller
{
    public function show($category = null)
    {
        $categoryLabels = [
            'pc_games' => 'PC Games',
            'console_games' => 'Console Games',
            'game_subscriptions' => 'Subscriptions',
            'software' => 'Software',
        ];

        $productsQuery = Product::query()
            ->with('category')
            ->withCount('offers')
            ->withMin('offers', 'price')
            ->orderBy('id');

        if ($category) {
            switch ($category) {
                case 'pc_games':
                    $productsQuery
                        ->where('platform_type', 'pc')
                        ->whereHas('category', fn($q) => $q->where('name', 'Játék'));
                    break;
                case 'console_games':
                    $productsQuery
                        ->whereIn('platform_type', ['ps4', 'ps5', 'xbox', 'nintendo'])
                        ->whereHas('category', fn($q) => $q->where('name', 'Játék'));
                    break;
                case 'game_subscriptions':
                    $productsQuery->whereHas('category', fn($q) => $q->where('name', 'Előfizetés'));
                    break;
                case 'software':
                    $productsQuery->whereHas('category', fn($q) => $q->where('name', 'Szoftver'));
                    break;
                default:
                    break;
            }
            $categoryLabel = $categoryLabels[$category] ?? 'All Games';
        } else {
            $category = null;
            $categoryLabel = 'All Games';
        }

        $products = $productsQuery->paginate(28)->withQueryString();

        return view('filter', compact('products', 'category', 'categoryLabel'));
    }
}
