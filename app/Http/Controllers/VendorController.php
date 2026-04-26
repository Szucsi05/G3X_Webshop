<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VendorController extends Controller
{
    
    public function index(Request $request): JsonResponse
    {
        $query = Vendor::query();

        
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        
        $sort_by = $request->input('sort_by', 'rating');
        $sort_order = $request->input('sort_order', 'desc');

        if (in_array($sort_by, ['name', 'rating', 'created_at'])) {
            $query->orderBy($sort_by, $sort_order);
        }

        $vendors = $query->paginate($request->input('per_page', 15));

        return response()->json($vendors);
    }

    
    public function show(int $id): JsonResponse
    {
        $vendor = Vendor::with('productOffers')->findOrFail($id);

        return response()->json($vendor);
    }

    
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'description' => 'nullable|string',
            'website' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'status' => 'in:active,inactive,suspended',
        ]);

        $validated['status'] = $validated['status'] ?? 'active';
        $validated['rating'] = 5.0; 

        $vendor = Vendor::create($validated);

        return response()->json($vendor, 201);
    }

    
    public function update(int $id, Request $request): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:vendors,email,' . $id,
            'description' => 'nullable|string',
            'rating' => 'sometimes|numeric|min:0|max:5',
            'website' => 'nullable|url',
            'logo_url' => 'nullable|url',
            'status' => 'in:active,inactive,suspended',
        ]);

        $vendor->update($validated);

        return response()->json($vendor);
    }

    
    public function destroy(int $id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return response()->json(['message' => 'Vendor deleted successfully.'], 204);
    }
}
