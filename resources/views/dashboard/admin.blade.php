<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - PKM Center</title>
    <link href="{{ asset('dashboard-assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard-assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <div class="sidebar-brand-icon"><i class="fas fa-user-shield"></i></div>
                <div class="sidebar-brand-text mx-3">PKM Center</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('dashboard') }}"><i
                        class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Admin</div>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.proposals.index') }}"><i
                        class="fas fa-fw fa-file-alt"></i><span>Review Proposal</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-users"></i><span>Manajemen
                        User</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.schedules.index') }}"><i
                        class="fas fa-fw fa-calendar-alt"></i><span>Kelola Jadwal</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-cog"></i><span>Pengaturan
                        Sistem</span></a></li>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                <span class="mr-2 text-gray-600">{{ $user->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('dashboard-assets/img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-danger">Selamat Datang, Administrator!</h6>
                        </div>
                        <div class="card-body">
                            <p>Sebagai Administrator, Anda memiliki akses penuh untuk:</p>
                            <ul>
                                <li>Mengelola semua pengguna sistem</li>
                                <li>Mengatur konfigurasi sistem</li>
                                <li>Melihat semua aktivitas dan log sistem</li>
                                <li>Manajemen database dan backup</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center"><span>Copyright &copy; PKM Center 2025</span></div>
                </div>
            </footer>
        </div>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Yakin ingin keluar?</h5>
                </div>
                <div class="modal-body">Pilih "Logout" untuk mengakhiri sesi.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('dashboard-assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard-assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
