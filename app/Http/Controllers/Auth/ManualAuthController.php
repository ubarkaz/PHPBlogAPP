<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ManualAuthController extends Controller
{
    // Show registration form
    public function showRegisterForm()
    {
        return view('auth.manual-register');
    }

    // Handle user registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        try {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            // Force login the newly registered user
            Auth::logout(); // Logout the previous user (important)
            Auth::login($user); // Log in the newly created user
            $request->session()->regenerate(); // Regenerate session to prevent session fixation
    
            Log::info('New User Registered and Logged In:', ['user' => Auth::user()]);
    
            return redirect()->route('dashboard')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            Log::error('User Registration Failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'User registration failed.']);
        }
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.manual-login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/manual-login')->with('success', 'Logged out successfully!');
    }
}
