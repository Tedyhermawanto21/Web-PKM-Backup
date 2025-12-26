<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard Kaprodi - PKM Center</title>
    <link href="{{ asset('dashboard-assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard-assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <div class="sidebar-brand-icon"><i class="fas fa-user-tie"></i></div>
                <div class="sidebar-brand-text mx-3">PKM Center</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('dashboard') }}"><i
                        class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Kaprodi</div>
            <li class="nav-item"><a class="nav-link" href="#"><i
                        class="fas fa-fw fa-file-alt"></i><span>Verifikasi Proposal</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-chart-bar"></i><span>Laporan
                        PKM</span></a></li>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->name }}</span>
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
                    <h1 class="h3 mb-4 text-gray-800">Dashboard Kaprodi</h1>
                    <div class="row">
                        <div class="col-xl-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Profil Kaprodi
                                    </div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-600">NIDN: {{ $user->nidn }}</div>
                                    <div class="text-xs text-gray-600">Prodi: {{ $user->program_studi }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-info">Selamat Datang, {{ $user->name }}!</h6>
                        </div>
                        <div class="card-body">
                            <p>Sebagai Kepala Program Studi, Anda dapat:</p>
                            <ul>
                                <li>Memverifikasi proposal PKM dari program studi</li>
                                <li>Melihat laporan dan statistik PKM</li>
                                <li>Mengelola dosen pembimbing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto"><span>Copyright &copy; PKM Center 2025</span></div>
                </div>
            </footer>
        </div>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin ingin keluar?</h5>
                </div>
                <div class="modal-body">Pilih "Logout" jika Anda ingin mengakhiri sesi.</div>
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
