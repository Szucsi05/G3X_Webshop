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

        $productsQuery = Product::query()
            ->with('category')
            ->withCount('offers')
            ->withMin('offers', 'price')
            ->orderBy('id');

        if ($query) {
            $productsQuery->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ;
            });
        }

        $products = $productsQuery->paginate(20)->withQueryString();

        return view('search', compact('products', 'query'));
    }
}
