<?php
 namespace App\Http\Controllers\Auth;

 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 
 class LoginController extends Controller
 {
     public function showLoginForm()
     {
         return view('auth.manual-login'); // This will render the login form
     }
 
     public function login(Request $request)
     {
         $credentials = $request->validate([
             'email' => 'required|email',
             'password' => 'required',
         ]);
 
         if (Auth::attempt($credentials)) {
             $request->session()->regenerate();
             return redirect()->intended('/dashboard'); // Redirect to intended page after login
         }
 
         return back()->withErrors(['email' => 'Invalid credentials']);
     }
 
     public function logout(Request $request)
     {
         Auth::logout();
         $request->session()->invalidate();
         $request->session()->regenerateToken();
         return redirect('/login'); // Redirect back to login page after logout
     }
 }
 