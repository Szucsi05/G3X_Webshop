<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOffer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductOfferController extends Controller
{
    
    public function index(Request $request): JsonResponse
    {
        $query = ProductOffer::with(['product', 'vendor', 'platform']);

        
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        
        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $request->input('vendor_id'));
        }

        
        if ($request->has('platform_id')) {
            $query->where('platform_id', $request->input('platform_id'));
        }

        
        if ($request->has('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        
        if ($request->input('available', false)) {
            $query->available();
        }

        
        $sort_by = $request->input('sort_by', 'price');
        $sort_order = $request->input('sort_order', 'asc');

        if (in_array($sort_by, ['price', 'stock', 'created_at'])) {
            $query->orderBy($sort_by, $sort_order);
        }

        $offers = $query->paginate($request->input('per_page', 15));

        return response()->json($offers);
    }

    
    public function show(int $id): JsonResponse
    {
        $offer = ProductOffer::with(['product', 'vendor', 'platform'])->findOrFail($id);

        return response()->json($offer);
    }

    
    public function byProduct(int $productId, Request $request): JsonResponse
    {
        $product = Product::findOrFail($productId);
        $query = $product->offers()->with(['vendor', 'platform']);

        
        $sort_by = $request->input('sort_by', 'price');
        $sort_order = $request->input('sort_order', 'asc');

        if (in_array($sort_by, ['price', 'stock'])) {
            $query->orderBy($sort_by, $sort_order);
        }

        $offers = $query->get();

        return response()->json([
            'product' => $product,
            'offers' => $offers,
            'count' => $offers->count(),
            'lowest_price' => $offers->min('price'),
            'highest_price' => $offers->max('price'),
        ]);
    }

    
    public function byVendor(int $vendorId): JsonResponse
    {
        $offers = ProductOffer::where('vendor_id', $vendorId)
            ->with(['product', 'platform'])
            ->paginate(15);

        return response()->json($offers);
    }

    
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'vendor_id' => 'required|exists:vendors,id',
            'platform_id' => 'required|exists:platforms,id',
            'price' => 'required|numeric|min:0',
            'region' => 'nullable|string',
            'delivery_type' => 'required|in:key,account,gift,physical',
            'status' => 'in:active,inactive,out_of_stock',
        ]);

        try {
            $offer = ProductOffer::create($validated);
            return response()->json($offer->load(['product', 'vendor', 'platform']), 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An offer already exists for this product-vendor-platform combination.',
            ], 422);
        }
    }

    
    public function update(int $id, Request $request): JsonResponse
    {
        $offer = ProductOffer::findOrFail($id);

        $validated = $request->validate([
            'price' => 'numeric|min:0',
            'region' => 'nullable|string',
        ]);

        $offer->update($validated);

        return response()->json($offer->load(['product', 'vendor', 'platform']));
    }

    
    public function destroy(int $id): JsonResponse
    {
        $offer = ProductOffer::findOrFail($id);
        $offer->delete();

        return response()->json(['message' => 'Offer deleted successfully.'], 204);
    }
}
