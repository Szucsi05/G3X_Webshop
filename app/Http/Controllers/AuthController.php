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

        
        $sessionCart = session()->has('cart') ? session()->get('cart') : [];

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        
        Auth::login($user);
        $request->session()->regenerate();

        
        $mergedCart = $sessionCart;
        if ($user->cart_data && is_array($user->cart_data)) {
            foreach ($user->cart_data as $id => $item) {
                if (isset($mergedCart[$id])) {
                    
                    $mergedCart[$id]['quantity'] += $item['quantity'];
                } else {
                    
                    $mergedCart[$id] = $item;
                }
            }
        }
        
        
        session()->put('cart', $mergedCart);
        
        
        $user->update(['cart_data' => $mergedCart]);

        
        if (($request->query('from_checkout') || $request->input('from_checkout')) && !empty($mergedCart)) {
            return redirect()->route('checkout.details')->with('success', 'Registration and sign-in completed successfully.');
        }

        
        return redirect('/')->with('success', 'Registration and sign-in completed successfully.');
    }

    public function showLoginForm(Request $request)
    {
        return view('auth.login_new');
    }

    
    public function apiLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        
        $user = User::where('username', $request->username)
                    ->orWhere('email', $request->username)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid username or password'], 401);
        }

        
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
            
            
            $sessionCart = session()->has('cart') ? session()->get('cart') : [];
            
            
            $user = Auth::user();
            $mergedCart = $sessionCart;
            
            if ($user && $user->cart_data && is_array($user->cart_data)) {
                foreach ($user->cart_data as $id => $item) {
                    if (isset($mergedCart[$id])) {
                        
                        $mergedCart[$id]['quantity'] += $item['quantity'];
                    } else {
                        
                        $mergedCart[$id] = $item;
                    }
                }
            }
            
            
            session()->put('cart', $mergedCart);
            
            
            if ($user) {
                $user->update(['cart_data' => $mergedCart]);
            }
            
            
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
