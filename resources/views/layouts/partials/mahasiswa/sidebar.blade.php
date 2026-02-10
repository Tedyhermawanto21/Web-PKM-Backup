<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-3">PKM Center</div>
    </a>

    <hr class="sidebar-divider my-0">
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Menu Mahasiswa</div>

    <li class="nav-item {{ request()->routeIs('mahasiswa.kelompok.*') ? 'active' : '' }}">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-users"></i>
            <span>Kelompok PKM Saya</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('mahasiswa.pengajuan_kelompok_pkm.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('mahasiswa.pengajuan_kelompok_pkm.index') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Pengajuan Kelompok</span>
        </a>
    </li>

    @php
        $showUpload = \App\Models\Schedule::ofType(\App\Models\Schedule::TYPE_UPLOAD_PROPOSAL)->ongoing()->exists();
        $showRevision = \App\Models\Schedule::whereIn('type', [
            \App\Models\Schedule::TYPE_REVISI_1,
            \App\Models\Schedule::TYPE_REVISI_2,
            \App\Models\Schedule::TYPE_REVISI_3,
        ])
            ->ongoing()
            ->exists();
    @endphp

    @if ($showUpload)
        <li class="nav-item {{ request()->routeIs('mahasiswa.upload.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('mahasiswa.upload.index') }}">
                <i class="fas fa-fw fa-upload"></i>
                <span>Upload Proposal</span>
            </a>
        </li>
    @endif

    @if ($showRevision)
        <li class="nav-item {{ request()->routeIs('mahasiswa.revisi.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('mahasiswa.revisi.index') }}">
                <i class="fas fa-fw fa-edit"></i>
                <span>Revisi Proposal</span>
            </a>
        </li>
    @endif

    <li class="nav-item {{ request()->routeIs('mahasiswa.profil.*') ? 'active' : '' }}">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
