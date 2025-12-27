<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="sidebar-brand-text mx-3">PKM Center</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') && auth()->user()->role->name == 'admin' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu Admin
    </div>

    <!-- Nav Item - Review Proposal -->
    <li class="nav-item {{ request()->routeIs('admin.proposals.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.proposals.index') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Review Proposal</span>
        </a>
    </li>

    <!-- Nav Item - Manajemen User -->
    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-users"></i>
            <span>Manajemen User</span>
        </a>
    </li>

    <!-- Nav Item - Kelola Jadwal -->
    <li class="nav-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.schedules.index') }}">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Kelola Jadwal</span>
        </a>
    </li>

    <!-- Nav Item - Pengaturan -->
    <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-cog"></i>
            <span>Pengaturan Sistem</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
