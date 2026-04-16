<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        
        $popular = Product::withCount('offers')->orderByDesc('offers_count')->take(4)->get();

        
        $bestSelling = Product::withCount('offers')->orderByDesc('offers_count')->skip(4)->take(8)->get();

        
        $consoleGames = Product::whereIn('platform_type', ['ps4', 'ps5', 'xbox', 'nintendo'])->take(4)->get();

        return view('home', compact('popular', 'bestSelling', 'consoleGames'));
    }
}
