<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    private function rememberAccount(Request $request, int $userId): void
    {
        $remembered = collect($request->session()->get('remembered_account_ids', []))
            ->map(fn ($id) => (int) $id)
            ->push($userId)
            ->unique()
            ->values()
            ->all();

        $request->session()->put('remembered_account_ids', $remembered);
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $this->rememberAccount($request, $user->id);

            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|in:admin,seller,user',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user',
            'balance' => 50.00, // €50 registration bonus
        ]);

        Auth::login($user);
        $this->rememberAccount($request, $user->id);

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }

    public function switchAccount(Request $request, User $user)
    {
        Auth::login($user, true);
        $request->session()->regenerate();
        $this->rememberAccount($request, $user->id);

        return redirect()->route('dashboard')->with('success', 'Switched account to ' . $user->name . '.');
    }

    public function switcher(Request $request)
    {
        $rememberedAccountIds = collect($request->session()->get('remembered_account_ids', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->filter(fn ($id) => $id !== Auth::id())
            ->values();

        $accounts = $rememberedAccountIds->isNotEmpty()
            ? User::whereIn('id', $rememberedAccountIds->all())->orderBy('name')->get()
            : User::where('id', '!=', Auth::id())->orderBy('name')->get();

        return view('auth.switch-account', compact('accounts'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
