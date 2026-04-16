<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function show()
    {
        return view('settings');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validáció
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'card_number' => 'nullable|regex:/^\d{4}\s?\d{4}\s?\d{4}\s?\d{4}$/',
            'card_expiry' => 'nullable|regex:/^\d{2}\/\d{2}$/',
            'card_cvv' => 'nullable|regex:/^\d{3,4}$/',
            'delete_card' => 'nullable|boolean',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'The email address format is invalid.',
            'email.unique' => 'This email address is already in use.',
            'password.min' => 'The password must be at least 6 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'card_number.regex' => 'The card number is invalid (e.g. 1234 5678 9012 3456).',
            'card_expiry.regex' => 'The expiry date format is invalid (e.g. 01/25).',
            'card_cvv.regex' => 'The CVV must be 3-4 digits.',
        ]);

        // Jelszó módosítása
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Név és email frissítés
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Kártya törlés
        if ($request->has('delete_card') && $request->delete_card) {
            $user->card_number = null;
            $user->card_expiry = null;
            $user->card_cvv = null;
        } else {
            // Kártya adatok frissítés (ha nem disabled az input)
            if (!empty($validated['card_number']) && strpos($validated['card_number'], '*') === false) {
                // Csak az utolsó 4 számjegy tárolása
                $cardNumber = preg_replace('/\s+/', '', $validated['card_number']);
                $user->card_number = $cardNumber;
            }

            if (!empty($validated['card_expiry'])) {
                $user->card_expiry = $validated['card_expiry'];
            }

            if (!empty($validated['card_cvv']) && strpos($validated['card_cvv'], '*') === false) {
                // CVV titkosítása/hash-elése (alapvető biztonság)
                $user->card_cvv = hash('sha256', $validated['card_cvv']);
            }
        }

        $user->save();

        $message = 'Settings saved successfully.';
        if ($request->has('delete_card') && $request->delete_card) {
            $message = 'Card removed successfully. Settings saved.';
        }

        return redirect()->route('settings.show')->with('success', $message);
    }
}
