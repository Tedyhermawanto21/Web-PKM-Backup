<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-3">PKM Center</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu Kaprodi
    </div>

    <!-- Nav Item - Verifikasi Proposal -->
    <li class="nav-item {{ request()->routeIs('kaprodi.proposals.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kaprodi.proposals.index') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Verifikasi Proposal</span>
        </a>
    </li>

    <!-- Nav Item - Laporan PKM -->
    <li class="nav-item {{ request()->routeIs('kaprodi.reports.*') ? 'active' : '' }}">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Laporan PKM</span>
        </a>
    </li>

    <!-- Nav Item - Profil -->
    <li class="nav-item {{ request()->routeIs('kaprodi.profile.*') ? 'active' : '' }}">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
