<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Featured products (first 8)
        $featured = Product::take(8)->get();
        return view('home', compact('featured'));
    }
}
