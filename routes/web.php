<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('landing');
});

// Test route sederhana
Route::get('/test', function() {
    return 'Laravel works! Time: ' . now();
});

Route::get('/test-auth', function() {
    $user = Auth::user();
    if ($user) {
        return 'Logged in as: ' . $user->name . ' (' . $user->email . ') - Role: ' . $user->role->name;
    }
    return 'Not logged in. Session ID: ' . session()->getId();
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function() {
        $user = Auth::user();
        
        // Load relationships
        $user->load('role', 'kelompoks', 'kelompokAsKetua', 'kelompokAsDosen');
        
        // Redirect based on role
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
    })->name('dashboard');

    // Mahasiswa Routes
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::resource('proposals', App\Http\Controllers\Mahasiswa\ProposalController::class);
        Route::resource('upload', App\Http\Controllers\Mahasiswa\UploadController::class);
    });

    // Dosen Routes
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('proposals', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'index'])->name('proposals.index');
        Route::get('proposals/{proposal}', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'show'])->name('proposals.show');
        Route::post('proposals/{proposal}/approve', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'approve'])->name('proposals.approve');
        Route::post('proposals/{proposal}/reject', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'reject'])->name('proposals.reject');
    });

    // Kaprodi Routes
    Route::prefix('kaprodi')->name('kaprodi.')->group(function () {
        Route::get('proposals', [App\Http\Controllers\Kaprodi\ProposalController::class, 'index'])->name('proposals.index');
        Route::get('proposals/{proposal}', [App\Http\Controllers\Kaprodi\ProposalController::class, 'show'])->name('proposals.show');
        Route::post('proposals/{proposal}/approve', [App\Http\Controllers\Kaprodi\ProposalController::class, 'approve'])->name('proposals.approve');
        Route::post('proposals/{proposal}/reject', [App\Http\Controllers\Kaprodi\ProposalController::class, 'reject'])->name('proposals.reject');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('proposals', [App\Http\Controllers\Admin\ProposalController::class, 'index'])->name('proposals.index');
        Route::get('proposals/{proposal}', [App\Http\Controllers\Admin\ProposalController::class, 'show'])->name('proposals.show');
        Route::post('proposals/{proposal}/approve', [App\Http\Controllers\Admin\ProposalController::class, 'approve'])->name('proposals.approve');
        Route::post('proposals/{proposal}/reject', [App\Http\Controllers\Admin\ProposalController::class, 'reject'])->name('proposals.reject');
        
        // Schedule Management Routes
        Route::resource('schedules', App\Http\Controllers\Admin\ScheduleController::class);
        Route::patch('schedules/{schedule}/toggle-status', [App\Http\Controllers\Admin\ScheduleController::class, 'toggleStatus'])->name('schedules.toggle-status');
    });
});
