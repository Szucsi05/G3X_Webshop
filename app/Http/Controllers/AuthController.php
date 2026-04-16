<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        return view('auth.register_new');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Mentsd el az aktuális session kosárat, mielőtt létrehozod az usert
        $sessionCart = session()->has('cart') ? session()->get('cart') : [];

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Automatikus bejelentkeztetés
        Auth::login($user);
        $request->session()->regenerate();

        // Merge: session kosár + user mentett kosár (ha van)
        $mergedCart = $sessionCart;
        if ($user->cart_data && is_array($user->cart_data)) {
            foreach ($user->cart_data as $id => $item) {
                if (isset($mergedCart[$id])) {
                    // Ha már benne van, add össze a mennyiséget
                    $mergedCart[$id]['quantity'] += $item['quantity'];
                } else {
                    // Ha nincs benne, add hozzá
                    $mergedCart[$id] = $item;
                }
            }
        }
        
        // Session kosár frissítése
        session()->put('cart', $mergedCart);
        
        // Felhasználó kosárának frissítése
        $user->update(['cart_data' => $mergedCart]);

        // Ha checkout-ból érkezik és van kosár, szamlázási adatokra
        if (($request->query('from_checkout') || $request->input('from_checkout')) && !empty($mergedCart)) {
            return redirect()->route('checkout.details')->with('success', 'Registration and sign-in completed successfully.');
        }

        // Egyébként a főoldalra
        return redirect('/')->with('success', 'Registration and sign-in completed successfully.');
    }

    public function showLoginForm(Request $request)
    {
        return view('auth.login_new');
    }

    // API login - returns X-API-TOKEN
    public function apiLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // Keressük az usert username vagy email alapján
        $user = User::where('username', $request->username)
                    ->orWhere('email', $request->username)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid username or password'], 401);
        }

        // Generate a simple token for API access
        $apiToken = env('API_TOKEN') ?? 'test-api-token-' . hash('sha256', $user->id . time());

        return response()->json([
            'token' => $apiToken,
            'api_token' => $apiToken,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Mentsd el az aktuális session kosárat
            $sessionCart = session()->has('cart') ? session()->get('cart') : [];
            
            // Töltsd be a felhasználó mentett kosárját
            $user = Auth::user();
            $mergedCart = $sessionCart;
            
            if ($user && $user->cart_data && is_array($user->cart_data)) {
                foreach ($user->cart_data as $id => $item) {
                    if (isset($mergedCart[$id])) {
                        // Ha már benne van, add össze a mennyiséget
                        $mergedCart[$id]['quantity'] += $item['quantity'];
                    } else {
                        // Ha nincs benne, add hozzá
                        $mergedCart[$id] = $item;
                    }
                }
            }
            
            // Session kosár frissítése
            session()->put('cart', $mergedCart);
            
            // Felhasználó kosárának frissítése
            if ($user) {
                $user->update(['cart_data' => $mergedCart]);
            }
            
            // Ha checkout-ból érkezik és van kosár, szamlázási adatokra
            if (($request->query('from_checkout') || $request->input('from_checkout')) && !empty($mergedCart)) {
                return redirect()->route('checkout.details')->with('success', 'Signed in successfully.');
            }

            return redirect('/')->with('success', 'Signed in successfully.');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
