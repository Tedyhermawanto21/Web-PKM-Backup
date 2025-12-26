<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Detail Proposal - PKM Center</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('dashboard-assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet"
        type="text/css">
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
            <li class="nav-item">
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
            <li class="nav-item active">
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
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
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
                        <h1 class="h3 mb-0 text-gray-800">Detail Proposal PKM</h1>
                        <a href="{{ route('dosen.proposals.index') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                        </a>
                    </div>

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-body text-center py-4">
                                    <h4 class="mb-3">Status Proposal</h4>
                                    @if ($proposal->status == 'menunggu_approval')
                                        <span class="badge badge-warning p-3" style="font-size: 1.2rem;">
                                            <i class="fas fa-clock"></i> Menunggu Approval
                                        </span>
                                        <p class="mt-3 text-muted">Proposal ini memerlukan persetujuan Anda</p>
                                    @elseif($proposal->status == 'disetujui')
                                        <span class="badge badge-success p-3" style="font-size: 1.2rem;">
                                            <i class="fas fa-check-circle"></i> Disetujui
                                        </span>
                                        <p class="mt-3 text-success font-weight-bold">Anda telah menyetujui proposal
                                            ini</p>
                                    @elseif($proposal->status == 'ditolak')
                                        <span class="badge badge-danger p-3" style="font-size: 1.2rem;">
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        </span>
                                        <p class="mt-3 text-danger">Proposal ini telah ditolak</p>
                                        @if ($proposal->catatan_penolakan)
                                            <div class="alert alert-danger mt-3">
                                                <strong><i class="fas fa-info-circle"></i> Catatan
                                                    Penolakan:</strong><br>
                                                {{ $proposal->catatan_penolakan }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Proposal Details -->
                    <div class="row">
                        <!-- Informasi Kelompok -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3 bg-primary text-white">
                                    <h6 class="m-0 font-weight-bold"><i class="fas fa-users"></i> Informasi Kelompok
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Nama Kelompok</th>
                                            <td>{{ $proposal->nama_kelompok }}</td>
                                        </tr>
                                        <tr>
                                            <th>Judul PKM</th>
                                            <td>{{ $proposal->judul_kelompok }}</td>
                                        </tr>
                                        <tr>
                                            <th>Skema</th>
                                            <td><span class="badge badge-info">{{ $proposal->skema }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Ketua Kelompok</th>
                                            <td>
                                                <strong>{{ $proposal->ketua->name }}</strong><br>
                                                <small class="text-muted">NIM: {{ $proposal->ketua->nim }}</small><br>
                                                <small
                                                    class="text-muted">{{ $proposal->ketua->program_studi }}</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pengajuan</th>
                                            <td>{{ $proposal->created_at->format('d F Y, H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Anggota -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3 bg-primary text-white">
                                    <h6 class="m-0 font-weight-bold"><i class="fas fa-user-friends"></i> Anggota
                                        Kelompok ({{ $proposal->anggota->count() }} Orang)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        @foreach ($proposal->anggota as $index => $anggota)
                                            <div class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">
                                                            @if ($anggota->posisi == 'ketua')
                                                                <span class="badge badge-primary mr-2">Ketua</span>
                                                            @else
                                                                <span class="badge badge-secondary mr-2">Anggota
                                                                    {{ $index }}</span>
                                                            @endif
                                                            {{ $anggota->nama }}
                                                        </h6>
                                                        <p class="mb-0"><small class="text-muted">NIM:
                                                                {{ $anggota->nim }}</small></p>
                                                        <p class="mb-0"><small
                                                                class="text-muted">{{ $anggota->program_studi }}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Actions -->
                    @if ($proposal->status == 'menunggu_approval')
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card shadow border-left-warning">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Tindakan Diperlukan
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-4">Silakan tinjau proposal ini dan pilih tindakan yang sesuai:
                                        </p>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-success">
                                                    <div class="card-body text-center">
                                                        <h5 class="text-success"><i class="fas fa-check-circle"></i>
                                                            Setujui Proposal</h5>
                                                        <p class="text-muted">Dengan menyetujui, Anda akan menjadi
                                                            dosen pembimbing kelompok ini.</p>
                                                        <form
                                                            action="{{ route('dosen.proposals.approve', $proposal->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menyetujui proposal ini dan menjadi dosen pembimbing?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-lg">
                                                                <i class="fas fa-check"></i> Setujui Proposal
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="card border-danger">
                                                    <div class="card-body text-center">
                                                        <h5 class="text-danger"><i class="fas fa-times-circle"></i>
                                                            Tolak Proposal</h5>
                                                        <p class="text-muted">Berikan alasan penolakan untuk membantu
                                                            mahasiswa.</p>
                                                        <button type="button" class="btn btn-danger btn-lg"
                                                            data-toggle="modal" data-target="#rejectModal">
                                                            <i class="fas fa-times"></i> Tolak Proposal
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <!-- /.container-fluid -->
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

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('dosen.proposals.reject', $proposal->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalLabel">
                            <i class="fas fa-times-circle"></i> Tolak Proposal
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="catatan_penolakan">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="catatan_penolakan" name="catatan_penolakan" rows="5" required
                                placeholder="Berikan alasan yang jelas mengapa proposal ini ditolak. Ini akan membantu mahasiswa untuk memperbaiki proposal mereka."></textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Catatan ini akan dikirimkan kepada ketua kelompok.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-paper-plane"></i> Kirim Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" jika Anda siap untuk mengakhiri sesi Anda saat ini.</div>
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

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('dashboard-assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard-assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('dashboard-assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('dashboard-assets/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
