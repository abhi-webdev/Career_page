<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show registration page.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Register a new user (always default role: user/candidate).
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 'user', // strictly enforced candidate role
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Registration successful');
    }

    /**
     * Show login page.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Login user and redirect to appropriate role portal.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['email'] = strtolower($credentials['email']);

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        $defaultRoute = self::getDashboardRouteForUser(Auth::user());

        return redirect()
            ->route($defaultRoute)
            ->with('success', 'Login successful');
    }

    /**
     * Get the designated dashboard route name for a user based on their RBAC role.
     */
    public static function getDashboardRouteForUser(User $user): string
    {
        return match ($user->role) {
            'admin' => 'admin.dashboard',
            'hr' => 'hr.dashboard',
            'tr' => 'tr.dashboard',
            'employee' => 'employee.dashboard',
            default => 'dashboard',
        };
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Logout successful');
    }

    /**
     * Get currently authenticated user.
     */
    public function me()
    {
        return response()->json([
            'user' => Auth::user(),
        ]);
    }
}
