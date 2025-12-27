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

    <li class="nav-item {{ request()->routeIs('mahasiswa.proposals.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('mahasiswa.proposals.index') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Pengajuan PKM</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('mahasiswa.upload.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('mahasiswa.upload.index') }}">
            <i class="fas fa-fw fa-upload"></i>
            <span>Upload Proposal</span>
        </a>
    </li>

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
