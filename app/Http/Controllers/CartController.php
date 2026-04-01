<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                return response()->json(['success' => false, 'message' => 'Ajánlat nem található.']);
            }
            return redirect()->back()->with('error', 'Ajánlat nem található.');
        }

        $cart = session()->get('cart', []);
        $cartKey = 'offer_' . $id; // Unique per product offer

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

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Termék hozzáadva a kosárhoz.', 'cart_count' => array_sum(array_column($cart, 'quantity'))]);
        }
        return redirect()->back()->with('success', 'Termék hozzáadva a kosárhoz.');
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        if ($request->expectsJson()) {
            $total = array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));
            return response()->json(['success' => true, 'message' => 'Termék eltávolítva a kosárból.', 'cart_count' => array_sum(array_column($cart, 'quantity')), 'total' => $total]);
        }
        return redirect()->back()->with('success', 'Termék eltávolítva a kosárból.');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        if ($request->expectsJson()) {
            $total = array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));
            return response()->json(['success' => true, 'message' => 'Mennyiség frissítve.', 'cart_count' => array_sum(array_column($cart, 'quantity')), 'total' => $total]);
        }
        return redirect()->back()->with('success', 'Mennyiség frissítve.');
    }

    public function checkout(Request $request)
    {
        // Ha GET kérés, irányítsd a fizetési módok kiválasztásához
        if ($request->isMethod('get')) {
            return redirect()->route('checkout.payment');
        }

        $request->validate([
            'payment_method' => 'required|string|in:card,paypal,google_pay,apple_pay',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'A kosár üres.');
        }

        // Calculate total
        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        // Get stored checkout details from session
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
        // Ellenőrizd, hogy bejelentkezve van-e a felhasználó
        if (!Auth::check()) {
            // Irányítsd a login oldalra checkout paraméterrel
            return redirect()->route('login', ['from_checkout' => true])->with('info', 'A fizetéshez jelentkezz be vagy regisztrálj.');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'A kosár üres.');
        }

        // Calculate total
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

        // Validate billing data based on account type
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

        // Store in session
        session()->put('checkout_details', $validated);

        // Redirect to checkout (payment method selection)
        return view('checkout-payment-method');
    }

    public function processCheckout(Request $request)
    {
        $cardSource = $request->input('card_source', 'new');
        
        $rules = [
            'email' => 'required|email',
            'payment_method' => 'required|string|in:card,paypal,google_pay,apple_pay',
        ];

        // Validáció függ a kártya forrásától
        if ($request->input('payment_method') === 'card') {
            if ($cardSource === 'saved') {
                // Mentett kártya: csak CVC kell
                $rules['card_cvc_saved'] = 'required|string|regex:/^\d{3}$/';
            } else {
                // Új kártya: összes mező
                $rules['card_number'] = 'required|regex:/^\d{4} \d{4} \d{4} \d{4}$/';
                $rules['card_expiry'] = 'required|regex:/^\d{2}\/\d{2}$/';
                $rules['card_cvc'] = 'required|regex:/^\d{3}$/';
                $rules['card_name'] = 'required|string|max:255';
                $rules['country'] = 'required|string';
                $rules['postal_code'] = 'required|regex:/^\d{1,4}$/';
            }
        } elseif ($request->input('payment_method') === 'paypal') {
            $rules['paypal_email'] = 'required|email';
        } elseif ($request->input('payment_method') === 'google_pay') {
            $rules['google_email'] = 'required|email';
        } elseif ($request->input('payment_method') === 'apple_pay') {
            $rules['apple_email'] = 'required|email';
        }

        $request->validate($rules);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'A kosár üres.');
        }

        // Generate random keys for each product
        $licenses = [];
        foreach ($cart as $id => $item) {
            for ($i = 0; $i < $item['quantity']; $i++) {
                $licenses[] = [
                    'name' => $item['name'],
                    'seller' => $item['seller'] ?? 'N/A',
                    'key' => strtoupper(substr(md5(rand() . time() . $id . $i), 0, 16))
                ];
            }
        }

        // Calculate total
        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        // Save order to database if user is authenticated
        if (Auth::check()) {
            Order::create([
                'user_id' => Auth::id(),
                'email' => $request->email,
                'total_amount' => $total,
                'payment_method' => $request->input('payment_method'),
                'items' => $cart,
                'licenses' => $licenses
            ]);
        }

        // Clear cart
        session()->forget('cart');

        return view('checkout-success', [
            'email' => $request->email,
            'licenses' => $licenses,
            'total' => $total,
            'payment_method' => $request->payment_method
        ]);
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
