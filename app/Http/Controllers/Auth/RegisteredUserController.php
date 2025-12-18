<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Accept both the original "name" field (used by Breeze tests) and the
        // split "first_name/last_name" fields used elsewhere in the app.
        if ($request->filled('name')) {
            $request->validate([
                'name'          => ['required', 'string', 'max:255'],
                'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password'      => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Attempt to split the full name; fallback keeps everything in first_name.
            $parts = preg_split('/\s+/', trim($request->name), 2);
            $firstName = $parts[0] ?? '';
            $lastName = $parts[1] ?? '';
        } else {
            $request->validate([
                'last_name'     => ['required', 'string', 'max:255'],
                'first_name'    => ['required', 'string', 'max:255'],
                'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password'      => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $firstName = $request->first_name;
            $lastName = $request->last_name;
        }

        $user = User::create([
            'name'          => trim("{$firstName} {$lastName}") ?: null,
            'last_name'     => $lastName ?: null,
            'first_name'    => $firstName ?: null,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
