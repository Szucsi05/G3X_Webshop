<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductOffer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected function validateApiToken(Request $request): bool
    {
        $apiToken = config('app.api_token') ?? env('API_TOKEN');

        if (! $apiToken) {
            return true; 
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
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $orders = $user->orders()
            ->with(['items.productOffer.product', 'items.productOffer.vendor', 'items.productOffer.platform'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders);
    }

    
    public function show(int $id): JsonResponse
    {
        $order = Order::with(['user', 'items.productOffer.product', 'items.productOffer.vendor', 'items.productOffer.platform'])
            ->findOrFail($id);

        
        if (Auth::check() && Auth::id() !== $order->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($order);
    }

    
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_offer_id' => 'required|exists:product_offers,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'billing_name' => 'required|string',
            'billing_email' => 'required|email',
            'billing_phone' => 'nullable|string',
            'billing_country' => 'required|string',
            'billing_city' => 'required|string',
            'billing_postal' => 'required|string',
            'billing_street' => 'required|string',
            'billing_company_name' => 'nullable|string',
            'billing_tax_id' => 'nullable|string',
            'account_type' => 'required|in:personal,company',
        ]);

        try {
            DB::beginTransaction();

            
            $order = Order::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'payment_method' => $validated['payment_method'],
                'total_amount' => 0, 
                'status' => 'pending',
                'currency' => 'USD',
                'billing_name' => $validated['billing_name'],
                'billing_email' => $validated['billing_email'],
                'billing_phone' => $validated['billing_phone'] ?? null,
                'billing_country' => $validated['billing_country'],
                'billing_city' => $validated['billing_city'],
                'billing_postal' => $validated['billing_postal'],
                'billing_street' => $validated['billing_street'],
                'billing_company_name' => $validated['billing_company_name'] ?? null,
                'billing_tax_id' => $validated['billing_tax_id'] ?? null,
                'account_type' => $validated['account_type'],
            ]);

            $totalPrice = 0;

            
            foreach ($validated['items'] as $itemData) {
                $offer = ProductOffer::findOrFail($itemData['product_offer_id']);

                
                if ($offer->stock < $itemData['quantity']) {
                    throw new \Exception('Nincs elegendő készlet: ' . $offer->product->name);
                }

                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_offer_id' => $offer->id,
                    'price_at_purchase' => $offer->price,
                    'quantity' => $itemData['quantity'],
                ]);

                $totalPrice += $offer->price * $itemData['quantity'];

                
                $offer->decrement('stock', $itemData['quantity']);
            }

            
            $order->update(['total_amount' => $totalPrice]);

            DB::commit();

            return response()->json(
                $order->load(['items.productOffer.product', 'items.productOffer.vendor']),
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    
    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,paid,processing,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json($order);
    }

    
    public function getItem(int $orderId, int $itemId): JsonResponse
    {
        $order = Order::findOrFail($orderId);
        $item = $order->items()->with(['productOffer.product', 'productOffer.vendor'])->findOrFail($itemId);

        
        if (Auth::check() && Auth::id() !== $order->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($item);
    }

    
    public function addLicenseKey(int $orderId, int $itemId, Request $request): JsonResponse
    {
        $order = Order::findOrFail($orderId);
        $item = $order->items()->findOrFail($itemId);

        $validated = $request->validate([
            'license_key' => 'required|string',
        ]);

        $item->update(['license_key' => $validated['license_key']]);

        return response()->json($item);
    }

    
    public function getAllOrders(Request $request): JsonResponse
    {
        if (! $this->validateApiToken($request)) {
            $this->abortUnauthorized();
        }

        $orders = Order::with([
            'user',
            'items.productOffer.product',
            'items.productOffer.vendor',
            'items.productOffer.platform'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return response()->json($orders);
    }
}

