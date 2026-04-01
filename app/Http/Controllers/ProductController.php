<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product', compact('product'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if ($query) {
            $products = Product::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('platform', 'like', "%{$query}%")
                ->orWhere('genre', 'like', "%{$query}%")
                ->get();
        } else {
            $products = Product::all();
        }

        return view('search', compact('products', 'query'));
    }
}
