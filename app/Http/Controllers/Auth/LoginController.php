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
        \Log::info('Login attempt:', ['nomor_induk' => $request->nomor_induk, 'type' => $request->type]);
        
        $request->validate([
            'nomor_induk' => ['required', 'string'],
            'type' => ['required', 'in:nim,nidn'],
            'password' => ['required'],
        ]);

        // Handle NIM/NIDN Login
        $nomorInduk = \App\Models\NomorInduk::where('value', $request->nomor_induk)
            ->where('type', $request->type)
            ->first();

        if ($nomorInduk && $nomorInduk->user) {
            if (Auth::attempt(['email' => $nomorInduk->user->email, 'password' => $request->password], $request->filled('remember'))) {
                $request->session()->regenerate();
                $request->session()->save();
                
                $user = Auth::user();
                \Log::info('Login successful:', ['user_id' => $user->id, 'role' => $user->role->name]);
                return redirect()->route('dashboard');
            }
        }

        \Log::warning('Login failed:', ['nomor_induk' => $request->nomor_induk]);
        
        return back()->withErrors([
            'email' => 'Nomor Induk atau password yang Anda masukkan salah.',
        ])->onlyInput('nomor_induk');
    }

    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            if ($user->role->name !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun ini bukan akun Admin.']);
            }

            $request->session()->regenerate();
            $request->session()->save();
            
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $redirectTarget = '/';
        
        // Check if user is admin before logging out
        if (Auth::check() && $request->user()->role->name === 'admin') {
            $redirectTarget = route('admin.login');
        } 
        // Fallback: if sesssion expired but they were on admin page
        else if (str_contains(url()->previous(), '/admin')) {
            $redirectTarget = route('admin.login');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($redirectTarget);
    }
}
