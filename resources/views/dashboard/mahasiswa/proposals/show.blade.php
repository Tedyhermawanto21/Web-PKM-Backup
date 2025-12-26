<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Detail Proposal PKM - PKM Center</title>

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
                Menu Mahasiswa
            </div>

            <!-- Nav Item - Kelompok PKM -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Kelompok PKM Saya</span>
                </a>
            </li>

            <!-- Nav Item - Pengajuan PKM -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('mahasiswa.proposals.index') }}">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Pengajuan PKM</span>
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
                        <a href="{{ route('mahasiswa.proposals.index') }}"
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
                                    @if ($proposal->status == 'draft')
                                        <span class="badge badge-secondary p-3" style="font-size: 1.2rem;">
                                            <i class="fas fa-file-alt"></i> Draft
                                        </span>
                                        <p class="mt-3 text-muted">Proposal masih dalam bentuk draft dan belum diajukan
                                        </p>
                                    @elseif($proposal->status == 'menunggu_approval')
                                        <span class="badge badge-warning p-3" style="font-size: 1.2rem;">
                                            <i class="fas fa-clock"></i> Menunggu Approval
                                        </span>
                                        <p class="mt-3 text-muted">Proposal sedang menunggu persetujuan dari dosen
                                            pembimbing</p>
                                    @elseif($proposal->status == 'disetujui')
                                        <span class="badge badge-success p-3" style="font-size: 1.2rem;">
                                            <i class="fas fa-check-circle"></i> Disetujui
                                        </span>
                                        <p class="mt-3 text-success font-weight-bold">Selamat! Proposal Anda telah
                                            disetujui oleh dosen pembimbing</p>
                                    @elseif($proposal->status == 'ditolak')
                                        <span class="badge badge-danger p-3" style="font-size: 1.2rem;">
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        </span>
                                        <p class="mt-3 text-danger">Proposal ditolak oleh dosen pembimbing</p>
                                        @if ($proposal->catatan_penolakan)
                                            <div class="alert alert-danger mt-3">
                                                <strong><i class="fas fa-info-circle"></i> Catatan
                                                    Penolakan:</strong><br>
                                                {{ $proposal->catatan_penolakan }}
                                            </div>
                                            <p class="text-muted">Anda dapat mengedit dan mengajukan proposal ini
                                                kembali dengan dosen pembimbing yang berbeda</p>
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
                                            <th>Dosen Pembimbing</th>
                                            <td>
                                                @if ($proposal->dosenPembimbing)
                                                    <strong>{{ $proposal->dosenPembimbing->name }}</strong><br>
                                                    <small
                                                        class="text-muted">{{ $proposal->dosenPembimbing->program_studi }}</small>
                                                @else
                                                    <span class="text-muted">Belum dipilih</span>
                                                @endif
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
                                        Kelompok</h6>
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

                    <!-- Action Buttons -->
                    @if (in_array($proposal->status, ['draft', 'ditolak']))
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card shadow">
                                    <div class="card-body text-center py-4">
                                        <h5 class="mb-3">Tindakan</h5>
                                        <a href="{{ route('mahasiswa.proposals.edit', $proposal->id) }}"
                                            class="btn btn-warning btn-lg mr-2">
                                            <i class="fas fa-edit"></i> Edit Proposal
                                        </a>
                                        <form action="{{ route('mahasiswa.proposals.destroy', $proposal->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus proposal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-lg">
                                                <i class="fas fa-trash"></i> Hapus Proposal
                                            </button>
                                        </form>
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
