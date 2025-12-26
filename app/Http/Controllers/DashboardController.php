<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Check authentication manually
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        try {
            $user = Auth::user();
            
            // Load relationships
            $user->load('role', 'kelompoks', 'kelompokAsKetua', 'kelompokAsDosen');
            
            // Redirect based on user role
            $roleName = $user->role->name ?? 'default';
            
            switch ($roleName) {
                case 'mahasiswa':
                    return view('dashboard.mahasiswa', compact('user'));
                case 'dosen':
                    return view('dashboard.dosen', compact('user'));
                case 'kaprodi':
                    return view('dashboard.kaprodi', compact('user'));
                case 'admin':
                    return view('dashboard.admin', compact('user'));
                default:
                    return view('dashboard.index', compact('user'));
            }
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
