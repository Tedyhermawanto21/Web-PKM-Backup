<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Show the application registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nomor_induk' => ['required', 'string', 'unique:nomor_induks,value'],
            'type' => ['required', 'in:nim,nidn'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $nomorInduk = \App\Models\NomorInduk::create([
            'value' => $validated['nomor_induk'],
            'type' => $validated['type'],
        ]);

        $roleName = $validated['type'] === 'nim' ? 'mahasiswa' : 'dosen';
        $roleId = \App\Models\Role::where('name', $roleName)->first()->id;

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role_id' => $roleId,
            'nomor_induk_id' => $nomorInduk->id,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
