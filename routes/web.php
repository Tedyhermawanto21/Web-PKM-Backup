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
        Route::resource('pengajuan-kelompok-pkm', App\Http\Controllers\Mahasiswa\ProposalController::class)->names([
            'index' => 'pengajuan_kelompok_pkm.index',
            'create' => 'pengajuan_kelompok_pkm.create',
            'store' => 'pengajuan_kelompok_pkm.store',
            'show' => 'pengajuan_kelompok_pkm.show',
            'edit' => 'pengajuan_kelompok_pkm.edit',
            'update' => 'pengajuan_kelompok_pkm.update',
            'destroy' => 'pengajuan_kelompok_pkm.destroy',
        ]);
        Route::resource('kelompoks', App\Http\Controllers\Mahasiswa\KelompokController::class);
        Route::resource('upload', App\Http\Controllers\Mahasiswa\UploadController::class);
    });

    // Dosen Routes
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('pengajuan-kelompok-pkm', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'index'])->name('pengajuan_kelompok_pkm.index');
        Route::get('pengajuan-kelompok-pkm/{proposal}', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'show'])->name('pengajuan_kelompok_pkm.show');
        Route::post('pengajuan-kelompok-pkm/{proposal}/approve', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'approve'])->name('pengajuan_kelompok_pkm.approve');
        Route::post('pengajuan-kelompok-pkm/{proposal}/reject', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'reject'])->name('pengajuan_kelompok_pkm.reject');

        // Kelompok requests (mahasiswa meminta acc dosen sebagai pembimbing)
        Route::get('kelompok-requests', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'kelompokRequests'])->name('kelompok_requests.index');
        Route::get('kelompok-requests/{kelompok}', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'kelompokShow'])->name('kelompok_requests.show');
        Route::post('kelompok-requests/{kelompok}/accept', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'acceptKelompok'])->name('kelompok_requests.accept');
        Route::post('kelompok-requests/{kelompok}/reject', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'rejectKelompok'])->name('kelompok_requests.reject');
    });

    // Kaprodi Routes
    Route::prefix('kaprodi')->name('kaprodi.')->group(function () {
        Route::get('pengajuan-kelompok-pkm', [App\Http\Controllers\Kaprodi\ProposalController::class, 'index'])->name('pengajuan_kelompok_pkm.index');
        Route::get('pengajuan-kelompok-pkm/{proposal}', [App\Http\Controllers\Kaprodi\ProposalController::class, 'show'])->name('pengajuan_kelompok_pkm.show');
        Route::post('pengajuan-kelompok-pkm/{proposal}/approve', [App\Http\Controllers\Kaprodi\ProposalController::class, 'approve'])->name('pengajuan_kelompok_pkm.approve');
        Route::post('pengajuan-kelompok-pkm/{proposal}/reject', [App\Http\Controllers\Kaprodi\ProposalController::class, 'reject'])->name('pengajuan_kelompok_pkm.reject');

        // Kelompok verification for Kaprodi
        Route::get('kelompok-requests', [App\Http\Controllers\Kaprodi\ProposalController::class, 'kelompokRequests'])->name('kelompok_requests.index');
        Route::get('kelompok-requests/{kelompok}', [App\Http\Controllers\Kaprodi\ProposalController::class, 'kelompokShow'])->name('kelompok_requests.show');
        Route::post('kelompok-requests/{kelompok}/accept', [App\Http\Controllers\Kaprodi\ProposalController::class, 'acceptKelompok'])->name('kelompok_requests.accept');
        Route::post('kelompok-requests/{kelompok}/reject', [App\Http\Controllers\Kaprodi\ProposalController::class, 'rejectKelompok'])->name('kelompok_requests.reject');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('pengajuan-kelompok-pkm', [App\Http\Controllers\Admin\ProposalController::class, 'index'])->name('pengajuan_kelompok_pkm.index');
        Route::get('pengajuan-kelompok-pkm/{proposal}', [App\Http\Controllers\Admin\ProposalController::class, 'show'])->name('pengajuan_kelompok_pkm.show');
        Route::post('pengajuan-kelompok-pkm/{proposal}/approve', [App\Http\Controllers\Admin\ProposalController::class, 'approve'])->name('pengajuan_kelompok_pkm.approve');
        Route::post('pengajuan-kelompok-pkm/{proposal}/reject', [App\Http\Controllers\Admin\ProposalController::class, 'reject'])->name('pengajuan_kelompok_pkm.reject');
        
        // Schedule Management Routes
        Route::resource('schedules', App\Http\Controllers\Admin\ScheduleController::class);
        Route::patch('schedules/{schedule}/toggle-status', [App\Http\Controllers\Admin\ScheduleController::class, 'toggleStatus'])->name('schedules.toggle-status');
    });
});
