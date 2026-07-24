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

Route::get('/daftar', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/daftar', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.process');

    // Admin Login Routes
    Route::get('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'adminLogin'])->name('admin.login.process');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', function() {
    return redirect('/dashboard');
});

Route::middleware('auth')->group(function () {
    // Generic Dashboard Route (Redirector)
    Route::get('/dashboard', function() {
        $user = Auth::user();
        $role = $user->role->name ?? 'mahasiswa';
        
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'dosen' => redirect()->route('dosen.dashboard'),
            'kaprodi' => redirect()->route('kaprodi.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default => redirect('/'),
        };
    })->name('dashboard');

    // Mahasiswa Dashboard
    Route::group(['prefix' => 'mahasiswa', 'as' => 'mahasiswa.', 'middleware' => ['role:mahasiswa']], function () {
        Route::get('/dashboard', function() {
            $user = Auth::user();
            $user->load('role', 'kelompoks', 'kelompokAsKetua'); 
            return view('dashboard.mahasiswa', compact('user'));
        })->name('dashboard');

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
        Route::get('upload/{upload}/download', [App\Http\Controllers\Mahasiswa\UploadController::class, 'download'])->name('upload.download');
        Route::resource('upload', App\Http\Controllers\Mahasiswa\UploadController::class);
        Route::get('revisi', [App\Http\Controllers\Mahasiswa\RevisionController::class, 'index'])->name('revisi.index');
        Route::post('pkm-ai/rekomendasi', [App\Http\Controllers\Mahasiswa\PkmAiController::class, 'generate'])->name('pkm_ai.generate');
        Route::post('pkm-ai/experiment', [App\Http\Controllers\Mahasiswa\PkmAiController::class, 'experiment'])->name('pkm_ai.experiment');
    });

    // Dosen Dashboard
    Route::group(['prefix' => 'dosen', 'as' => 'dosen.', 'middleware' => ['role:dosen']], function () {
        Route::get('/dashboard', function() {
            $user = Auth::user();
            $user->load('role', 'kelompokAsDosen');
            return view('dashboard.dosen', compact('user'));
        })->name('dashboard');

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
        
        // Reviewer Routes for Dosen
        Route::get('reviewer', [App\Http\Controllers\Dosen\ReviewerController::class, 'index'])->name('reviewer.index');
        Route::get('reviewer/{proposal}', [App\Http\Controllers\Dosen\ReviewerController::class, 'show'])->name('reviewer.show');
        Route::post('reviewer/{proposal}/submit', [App\Http\Controllers\Dosen\ReviewerController::class, 'submitReview'])->name('reviewer.submit');
    });

    // Kaprodi Dashboard
    Route::group(['prefix' => 'kaprodi', 'as' => 'kaprodi.', 'middleware' => ['role:kaprodi']], function () {
        Route::get('/dashboard', function() {
            $user = Auth::user();
            $kaprodiProdi = $user->program_studi;
            
            $skemaStats = \App\Models\Skema::withCount(['proposals' => function ($query) use ($kaprodiProdi) {
                $query->whereHas('ketua', function ($q) use ($kaprodiProdi) {
                    $q->where('program_studi', $kaprodiProdi);
                });
            }])->get();

            // Calculate total proposals for this prodi
            $totalProposals = \App\Models\Proposal::whereHas('ketua', function ($q) use ($kaprodiProdi) {
                $q->where('program_studi', $kaprodiProdi);
            })->count();

            // Calculate active dosen pembimbing for this prodi
            $totalDosen = \App\Models\User::where('program_studi', $kaprodiProdi)
                ->whereHas('role', function($q) {
                    $q->where('name', 'dosen');
                })
                ->whereHas('kelompokAsDosen') // Only count those who are actually guiding
                ->count();

            return view('dashboard.kaprodi', compact('user', 'skemaStats', 'totalProposals', 'totalDosen'));
        })->name('dashboard');

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

    // Admin Dashboard
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['role:admin']], function () {
        Route::get('/dashboard', function() {
            $user = Auth::user();
            // Fetch stats for Skemas
            $skemaStats = \App\Models\Skema::withCount('proposals')->get();
            $totalUsers = \App\Models\User::count();
            $totalProposals = \App\Models\Proposal::count();
            
            return view('dashboard.admin', compact('user', 'skemaStats', 'totalUsers', 'totalProposals'));
        })->name('dashboard');

        Route::get('pengajuan-kelompok-pkm', [App\Http\Controllers\Admin\ProposalController::class, 'index'])->name('pengajuan_kelompok_pkm.index');
        Route::get('pengajuan-kelompok-pkm/{proposal}', [App\Http\Controllers\Admin\ProposalController::class, 'show'])->name('pengajuan_kelompok_pkm.show');
        Route::post('pengajuan-kelompok-pkm/{proposal}/approve', [App\Http\Controllers\Admin\ProposalController::class, 'approve'])->name('pengajuan_kelompok_pkm.approve');
        Route::post('pengajuan-kelompok-pkm/{proposal}/reject', [App\Http\Controllers\Admin\ProposalController::class, 'reject'])->name('pengajuan_kelompok_pkm.reject');
        Route::post('pengajuan-kelompok-pkm/{proposal}/assign-reviewer', [App\Http\Controllers\Admin\ProposalController::class, 'assignReviewer'])->name('pengajuan_kelompok_pkm.assign_reviewer');
        Route::post('pengajuan-kelompok-pkm/{proposal}/unassign-reviewer', [App\Http\Controllers\Admin\ProposalController::class, 'unassignReviewer'])->name('pengajuan_kelompok_pkm.unassign_reviewer');
        Route::get('search-dosen', [App\Http\Controllers\Admin\ProposalController::class, 'searchDosen'])->name('search_dosen');
        
        // Schedule Management Routes
        Route::resource('schedules', App\Http\Controllers\Admin\ScheduleController::class);
        Route::patch('schedules/{schedule}/toggle-status', [App\Http\Controllers\Admin\ScheduleController::class, 'toggleStatus'])->name('schedules.toggle-status');

        Route::resource('skemas', App\Http\Controllers\Admin\SkemaController::class);
        
        // Dosen Management
        Route::resource('dosens', App\Http\Controllers\Admin\DosenController::class);

        // Kaprodi Management
        Route::resource('kaprodis', App\Http\Controllers\Admin\KaprodiController::class);

        // Prodi Management
        Route::resource('prodis', App\Http\Controllers\Admin\ProdiController::class);
    });
});
