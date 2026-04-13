<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Most popular products: top 4 by number of offers
        $popular = Product::withCount('offers')->orderByDesc('offers_count')->take(4)->get();

        // Best selling products: next 8 by number of offers
        $bestSelling = Product::withCount('offers')->orderByDesc('offers_count')->skip(4)->take(8)->get();

        // Console products: PS4 / PS5 / Xbox / Nintendo
        $consoleGames = Product::whereIn('platform_type', ['ps4', 'ps5', 'xbox', 'nintendo'])->take(4)->get();

        return view('home', compact('popular', 'bestSelling', 'consoleGames'));
    }
}
