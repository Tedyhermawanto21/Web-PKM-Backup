<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard Dosen - PKM Center</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('dashboard-assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('dashboard-assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
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
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu Dosen
            </div>

            <!-- Nav Item - Pengajuan Proposal -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dosen.proposals.index') }}">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Pengajuan Proposal</span>
                </a>
            </li>

            <!-- Nav Item - PKM Bimbingan -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-users"></i>
                    <span>PKM Bimbingan</span>
                </a>
            </li>

            <!-- Nav Item - Profil -->
            <li class="nav-item">
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
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('dashboard-assets/img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard Dosen</h1>
                    </div>

                    <!-- Statistics Cards Row -->
                    <div class="row">
                        <!-- Proposal Perlu Review Card -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Menunggu Approval</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $user->proposalsAsDosen()->where('status', 'menunggu_approval')->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Disetujui Card -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Disetujui</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $user->proposalsAsDosen()->where('status', 'disetujui')->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ditolak Card -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Ditolak</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $user->proposalsAsDosen()->where('status', 'ditolak')->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert if proposals pending -->
                    @if ($user->proposalsAsDosen()->where('status', 'menunggu_approval')->count() > 0)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Perhatian!</strong> Anda memiliki
                            <strong>{{ $user->proposalsAsDosen()->where('status', 'menunggu_approval')->count() }}</strong>
                            proposal yang menunggu review Anda.
                            <a href="{{ route('dosen.proposals.index') }}" class="alert-link">Klik di
                                sini untuk mereview</a>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Welcome Card -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Selamat Datang,
                                        {{ $user->name }}!</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="mb-2"><strong>NIDN:</strong>
                                                {{ $user->nidn }}</p>
                                            <p class="mb-2"><strong>Program Studi:</strong>
                                                {{ $user->program_studi }}</p>
                                            <hr>
                                            <p class="mb-2">Sebagai dosen pembimbing, Anda dapat:</p>
                                            <ul class="mb-0">
                                                <li>Mereview dan menyetujui/menolak proposal PKM
                                                    mahasiswa</li>
                                                <li>Membimbing kelompok PKM mahasiswa</li>
                                                <li>Memberikan review dan masukan pada proposal</li>
                                                <li>Memantau progress kelompok bimbingan</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <i class="fas fa-chalkboard-teacher fa-5x text-gray-300 mb-3"></i>
                                            <p class="text-muted">Dosen Pembimbing PKM</p>
                                        </div>
                                        <!-- End of Main Content -->

                                        <!-- Footer -->
                                        <footer class="sticky-footer bg-white">
                                            <div class="container my-auto">
                                                <div class="copyright text-center my-auto">
                                                    <span>Copyright &copy; PKM Center 2025</span>
                                                </div>
                                            </div>
                                        </footer>
                                        <!-- End of Footer -->
                                    </div>
                                    <!-- End of Content Wrapper -->
                                </div>
                                <!-- End of Page Wrapper -->

                                <!-- Scroll to Top Button-->
                                <a class="scroll-to-top rounded" href="#page-top">
                                    <i class="fas fa-angle-up"></i>
                                </a>

                                <!-- Logout Modal-->
                                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Yakin
                                                    ingin keluar?</h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Pilih "Logout" jika Anda siap untuk
                                                mengakhiri sesi Anda saat ini.</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button"
                                                    data-dismiss="modal">Batal</button>
                                                <form action="{{ route('logout') }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">Logout</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bootstrap core JavaScript-->
                                <script src="{{ asset('dashboard-assets/vendor/jquery/jquery.min.js') }}"></script>
                                <script src="{{ asset('dashboard-assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

                                <!-- Core plugin JavaScript-->
                                <script src="{{ asset('dashboard-assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

                                <!-- Custom scripts for all pages--
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">Pilih "Logout" jika Anda ingin mengakhiri sesi.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
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
    <script src="{{ asset('dashboard-assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
