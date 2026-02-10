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
            case 'reviewer':
                // Provide assigned counts for reviewer dashboard
                $totalAssigned = \App\Models\ProposalReviewer::where('reviewer_id', $user->id)->count();
                $reviewed = \App\Models\ProposalReviewer::where('reviewer_id', $user->id)->where('status', 'reviewed')->count();
                $pending = \App\Models\ProposalReviewer::where('reviewer_id', $user->id)->where('status', 'pending')->count();

                // Also pass a short list of recent assigned proposals for quick access
                $proposalIds = \App\Models\ProposalReviewer::where('reviewer_id', $user->id)->pluck('proposal_id')->unique();
                $recentAssigned = collect();
                if ($proposalIds->count()) {
                    $recentAssigned = \App\Models\Proposal::whereIn('id', $proposalIds)
                        ->with(['ketua'])
                        ->latest()
                        ->limit(5)
                        ->get();
                }

                return view('dashboard.reviewer', compact('user', 'totalAssigned', 'reviewed', 'pending', 'recentAssigned'));
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
        Route::get('revisi', [App\Http\Controllers\Mahasiswa\RevisionController::class, 'index'])->name('revisi.index');
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
        
        // Bimbingan Mahasiswa
        Route::get('bimbingan-mahasiswa', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'bimbinganMahasiswa'])->name('bimbingan_mahasiswa.index');
        Route::get('bimbingan-mahasiswa/{kelompok}', [App\Http\Controllers\Dosen\ProposalApprovalController::class, 'showBimbingan'])->name('bimbingan_mahasiswa.show');
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
        
        // Daftar Mahasiswa
        Route::get('daftar-mahasiswa', [App\Http\Controllers\Kaprodi\ProposalController::class, 'daftarMahasiswa'])->name('daftar_mahasiswa.index');
        Route::get('daftar-mahasiswa/{kelompok}', [App\Http\Controllers\Kaprodi\ProposalController::class, 'daftarMahasiswaShow'])->name('daftar_mahasiswa.show');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('pengajuan-kelompok-pkm', [App\Http\Controllers\Admin\ProposalController::class, 'index'])->name('pengajuan_kelompok_pkm.index');
        Route::get('pengajuan-kelompok-pkm/{proposal}', [App\Http\Controllers\Admin\ProposalController::class, 'show'])->name('pengajuan_kelompok_pkm.show');
        Route::post('pengajuan-kelompok-pkm/{proposal}/approve', [App\Http\Controllers\Admin\ProposalController::class, 'approve'])->name('pengajuan_kelompok_pkm.approve');
        Route::post('pengajuan-kelompok-pkm/{proposal}/reject', [App\Http\Controllers\Admin\ProposalController::class, 'reject'])->name('pengajuan_kelompok_pkm.reject');
        Route::post('pengajuan-kelompok-pkm/{proposal}/assign-reviewer', [App\Http\Controllers\Admin\ProposalController::class, 'assignReviewer'])->name('pengajuan_kelompok_pkm.assign_reviewer');
        Route::post('pengajuan-kelompok-pkm/{proposal}/unassign-reviewer', [App\Http\Controllers\Admin\ProposalController::class, 'unassignReviewer'])->name('pengajuan_kelompok_pkm.unassign_reviewer');
        
        // Schedule Management Routes
        // Reviewer Management (admin creates reviewer accounts)
        Route::resource('reviewers', App\Http\Controllers\Admin\ReviewerController::class);

        Route::resource('schedules', App\Http\Controllers\Admin\ScheduleController::class);
        Route::patch('schedules/{schedule}/toggle-status', [App\Http\Controllers\Admin\ScheduleController::class, 'toggleStatus'])->name('schedules.toggle-status');
    });

    // Reviewer Routes
    Route::prefix('reviewer')->name('reviewer.')->group(function () {
        Route::get('assigned', [App\Http\Controllers\Reviewer\ReviewController::class, 'index'])->name('assigned.index');
        Route::get('assigned/{proposal}', [App\Http\Controllers\Reviewer\ReviewController::class, 'show'])->name('assigned.show');
        Route::post('assigned/{proposal}/submit', [App\Http\Controllers\Reviewer\ReviewController::class, 'submit'])->name('assigned.submit');
    });
});
