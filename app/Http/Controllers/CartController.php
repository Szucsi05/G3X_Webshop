<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\ProductOffer;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $offer = ProductOffer::with(['product', 'vendor'])->find($id);
        if (!$offer) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Offer not found.']);
            }
            return redirect()->back()->with('error', 'Offer not found.');
        }

        $cart = session()->get('cart', []);
        $cartKey = 'offer_' . $id; 

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                'offer_id' => $offer->id,
                'product_id' => $offer->product->id,
                'name' => $offer->product->name,
                'price' => (float) $offer->price,
                'seller' => $offer->vendor->name,
                'image' => $offer->product->image ?? 'images/default-product.svg',
                'quantity' => 1,
                'platform' => $offer->platform->name,
            ];
        }

        session()->put('cart', $cart);
        
        
        if (Auth::check()) {
            Auth::user()->update(['cart_data' => $cart]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Product added to cart.', 'cart_count' => array_sum(array_column($cart, 'quantity'))]);
        }
        return redirect()->back()->with('success', 'Product added to cart.');
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        
        if (Auth::check()) {
            Auth::user()->update(['cart_data' => $cart]);
        }
        
        if ($request->expectsJson()) {
            $total = array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));
            return response()->json(['success' => true, 'message' => 'Product removed from cart.', 'cart_count' => array_sum(array_column($cart, 'quantity')), 'total' => $total]);
        }
        return redirect()->back()->with('success', 'Product removed from cart.');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        
        if (Auth::check()) {
            Auth::user()->update(['cart_data' => $cart]);
        }
        
        if ($request->expectsJson()) {
            $total = array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));
            return response()->json(['success' => true, 'message' => 'Quantity updated.', 'cart_count' => array_sum(array_column($cart, 'quantity')), 'total' => $total]);
        }
        return redirect()->back()->with('success', 'Quantity updated.');
    }

    public function checkout(Request $request)
    {
        
        if ($request->isMethod('get')) {
            return redirect()->route('checkout.payment');
        }

        $request->validate([
            'payment_method' => 'required|string|in:card,paypal,google_pay,apple_pay',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'Your cart is empty.');
        }

        
        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        
        $checkoutDetails = session()->get('checkout_details', []);

        return view('checkout', [
            'cart' => $cart,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'checkoutDetails' => $checkoutDetails
        ]);
    }

    public function showDetails()
    {
        
        if (!Auth::check()) {
            
            return redirect()->route('login', ['from_checkout' => true])->with('info', 'Please log in or register to continue to checkout.');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'Your cart is empty.');
        }

        
        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        return view('checkout-details', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function storeDetails(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:personal,company',
        ]);

        
        if ($request->account_type === 'personal') {
            $validated += $request->validate([
                'billing_name_personal' => 'required|string',
                'billing_phone_personal' => 'required|string',
                'billing_email_personal' => 'required|email',
                'billing_country_personal' => 'required|string',
                'billing_city_personal' => 'required|string',
                'billing_postal_personal' => 'required|string',
                'billing_street_personal' => 'required|string',
            ]);
        } else {
            $validated += $request->validate([
                'billing_company_name' => 'required|string',
                'billing_tax_id' => 'required|string',
                'billing_phone_company' => 'required|string',
                'billing_email_company' => 'required|email',
                'billing_country_company' => 'required|string',
                'billing_city_company' => 'required|string',
                'billing_postal_company' => 'required|string',
                'billing_street_company' => 'required|string',
            ]);
        }

        
        session()->put('checkout_details', $validated);

        
        return view('checkout-payment-method');
    }

    public function processCheckout(Request $request)
    {
        $checkoutDetails = session()->get('checkout_details', []);

        if (empty($checkoutDetails)) {
            return redirect()->route('checkout.details')->with('error', 'Please provide your billing details first.');
        }

        $billingData = $this->mapBillingDetails($checkoutDetails);

        $cardSource = $request->input('card_source', 'new');
        
        $rules = [
            'payment_method' => 'required|string|in:card,paypal,google_pay,apple_pay',
        ];

        
        if ($request->input('payment_method') === 'card') {
            if ($cardSource === 'saved') {
                
                $rules['card_cvc_saved'] = 'required|string|regex:/^\d{3}$/';
            } else {
                
                $rules['card_number'] = 'required|regex:/^\d{4} \d{4} \d{4} \d{4}$/';
                $rules['card_expiry'] = 'required|regex:/^\d{2}\/\d{2}$/';
                $rules['card_cvc'] = 'required|regex:/^\d{3}$/';
                $rules['card_name'] = 'required|string|max:255';
            }
        } elseif ($request->input('payment_method') === 'paypal') {
            $rules['paypal_email'] = 'required|email';
        } elseif ($request->input('payment_method') === 'google_pay') {
            $rules['google_email'] = 'required|email';
        } elseif ($request->input('payment_method') === 'apple_pay') {
            $rules['apple_email'] = 'required|email';
        }

        $request->validate($rules);

        if (empty($billingData['email'])) {
            return redirect()->route('checkout.details')->with('error', 'The billing email address is missing.');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'Your cart is empty.');
        }

        
        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $licenses = [];

        DB::transaction(function () use ($request, $billingData, $cart, $total, &$licenses) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'email' => $billingData['email'],
                'total_amount' => $total,
                'payment_method' => $request->input('payment_method'),
                'status' => 'completed',
                'currency' => 'HUF',
                'billing_name' => $billingData['name'],
                'billing_email' => $billingData['email'],
                'billing_phone' => $billingData['phone'],
                'billing_country' => $billingData['country'],
                'billing_city' => $billingData['city'],
                'billing_postal' => $billingData['postal'],
                'billing_street' => $billingData['street'],
                'billing_company_name' => $billingData['company_name'],
                'billing_tax_id' => $billingData['tax_id'],
                'account_type' => $billingData['account_type'],
            ]);

            foreach ($cart as $cartKey => $item) {
                for ($index = 0; $index < $item['quantity']; $index++) {
                    $licenseKey = $this->generateLicenseKey($cartKey, $index);

                    $licenses[] = [
                        'name' => $item['name'],
                        'seller' => $item['seller'] ?? 'N/A',
                        'key' => $licenseKey,
                    ];

                    $order->items()->create([
                        'product_offer_id' => $item['offer_id'],
                        'price_at_purchase' => $item['price'],
                        'quantity' => 1,
                        'license_key' => $licenseKey,
                    ]);
                }
            }
        });

        
        session()->forget('cart');
        session()->forget('checkout_details');
        
        if (Auth::check()) {
            
            
            Auth::user()->update(['cart_data' => null]);
        }

        return view('checkout-success', [
            'email' => $billingData['email'],
            'licenses' => $licenses,
            'total' => $total,
            'payment_method' => $request->payment_method
        ]);
    }

    private function mapBillingDetails(array $checkoutDetails): array
    {
        $accountType = $checkoutDetails['account_type'] ?? 'personal';

        if ($accountType === 'company') {
            return [
                'account_type' => 'company',
                'name' => $checkoutDetails['billing_company_name'] ?? null,
                'email' => $checkoutDetails['billing_email_company'] ?? null,
                'phone' => $checkoutDetails['billing_phone_company'] ?? null,
                'country' => $checkoutDetails['billing_country_company'] ?? null,
                'city' => $checkoutDetails['billing_city_company'] ?? null,
                'postal' => $checkoutDetails['billing_postal_company'] ?? null,
                'street' => $checkoutDetails['billing_street_company'] ?? null,
                'company_name' => $checkoutDetails['billing_company_name'] ?? null,
                'tax_id' => $checkoutDetails['billing_tax_id'] ?? null,
            ];
        }

        return [
            'account_type' => 'personal',
            'name' => $checkoutDetails['billing_name_personal'] ?? null,
            'email' => $checkoutDetails['billing_email_personal'] ?? null,
            'phone' => $checkoutDetails['billing_phone_personal'] ?? null,
            'country' => $checkoutDetails['billing_country_personal'] ?? null,
            'city' => $checkoutDetails['billing_city_personal'] ?? null,
            'postal' => $checkoutDetails['billing_postal_personal'] ?? null,
            'street' => $checkoutDetails['billing_street_personal'] ?? null,
            'company_name' => null,
            'tax_id' => null,
        ];
    }

    private function generateLicenseKey(string $cartKey, int $index): string
    {
        return strtoupper(implode('-', [
            Str::upper(Str::random(4)),
            Str::upper(Str::random(4)),
            Str::upper(Str::random(4)),
            substr(hash('sha256', $cartKey . microtime(true) . $index), 0, 4),
        ]));
    }

    private function getProducts()
    {
        return [
            1 => [
                'name' => 'Call of Duty WW2',
                'price' => 15999,
                'image' => 'images/cod_ww2.jpg'
            ],
            2 => [
                'name' => 'Dead by Daylight',
                'price' => 19999,
                'image' => 'images/dbd.jpg'
            ],
            3 => [
                'name' => 'The Last of Us Part I',
                'price' => 8999,
                'image' => 'images/lou_part1.jpg'
            ],
            4 => [
                'name' => 'Battlefield 6',
                'price' => 3999,
                'image' => 'images/bf_6.jpg'
            ],
            5 => [
                'name' => 'FC 26',
                'price' => 3500,
                'image' => 'images/fc_26.jpg'
            ],
            6 => [
                'name' => 'Red Dead Redemption 2',
                'price' => 4999,
                'image' => 'images/rdr_2.jpg'
            ],
            7 => [
                'name' => 'YouCam',
                'price' => 12999,
                'image' => 'images/youcam.jpg'
            ],
            8 => [
                'name' => 'McAfee',
                'price' => 9999,
                'image' => 'images/mcafee.jpg'
            ],
            9 => [
                'name' => 'Backup',
                'price' => 2999,
                'image' => 'images/backup.jpg'
            ],
        ];
    }
}
