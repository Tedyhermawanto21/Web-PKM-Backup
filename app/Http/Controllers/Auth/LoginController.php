<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        // Log request
        \Log::info('Login attempt:', ['email' => $request->email]);
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Force save session
            $request->session()->save();
            
            $user = Auth::user();
            
            // Debug logging
            \Log::info('Login successful:', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role->name,
                'session_id' => session()->getId()
            ]);
            
            // Redirect ke dashboard yang akan mengarahkan sesuai role
            return redirect()->route('dashboard');
        }

        \Log::warning('Login failed:', ['email' => $request->email]);
        
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
