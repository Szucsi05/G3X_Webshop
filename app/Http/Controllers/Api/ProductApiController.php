<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductApiController extends Controller
{
    protected function validateApiToken(Request $request): bool
    {
        $apiToken = config('app.api_token') ?? env('API_TOKEN');

        if (! $apiToken) {
            return true; // If not configured, allow (for development).
        }

        $token = $request->header('X-API-TOKEN') ?: $request->query('api_token');

        return is_string($token) && hash_equals($apiToken, $token);
    }

    protected function abortUnauthorized(): void
    {
        abort(response()->json(['message' => 'Unauthorized'], 401));
    }

    public function index(Request $request): JsonResponse
    {
        if (! $this->validateApiToken($request)) {
            $this->abortUnauthorized();
        }

        $products = Product::orderBy('id', 'desc')->get();

        return response()->json($products);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        if (! $this->validateApiToken($request)) {
            $this->abortUnauthorized();
        }

        $product = Product::findOrFail($id);

        return response()->json($product);
    }

    public function store(Request $request): JsonResponse
    {
        if (! $this->validateApiToken($request)) {
            $this->abortUnauthorized();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'platform' => ['nullable', 'string', 'max:255'],
            'genre' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'release_year' => ['nullable', 'integer'],
        ]);

        // NOTE: Prices are now managed through ProductOffers, not directly on Products
        $product = Product::create($data);

        return response()->json($product, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (! $this->validateApiToken($request)) {
            $this->abortUnauthorized();
        }

        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'platform' => ['nullable', 'string', 'max:255'],
            'genre' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'release_year' => ['nullable', 'integer'],
        ]);

        // NOTE: Prices are managed through ProductOffers, not directly on Products
        $product->update($data);

        return response()->json($product);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        if (! $this->validateApiToken($request)) {
            $this->abortUnauthorized();
        }

        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
