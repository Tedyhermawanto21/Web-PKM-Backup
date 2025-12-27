<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Detail Proposal - PKM Center</title>

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
                Menu Kaprodi
            </div>

            <!-- Nav Item - Verifikasi Proposal -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('kaprodi.proposals.index') }}">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Verifikasi Proposal</span>
                </a>
            </li>

            <!-- Nav Item - Laporan PKM -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Laporan PKM</span>
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
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
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

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Detail Proposal PKM</h1>
                        <a href="{{ route('kaprodi.proposals.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm"></i> Kembali
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Status Section -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-body text-center py-4">
                                    <h4 class="mb-3">Status Verifikasi</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Status Dosen Pembimbing</h6>
                                            @if ($proposal->status_dosen == 'disetujui')
                                                <span class="badge badge-success p-3"><i
                                                        class="fas fa-check-circle"></i> Disetujui</span>
                                            @elseif($proposal->status_dosen == 'ditolak')
                                                <span class="badge badge-danger p-3"><i class="fas fa-times-circle"></i>
                                                    Ditolak</span>
                                            @else
                                                <span class="badge badge-warning p-3"><i class="fas fa-clock"></i>
                                                    Menunggu</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Status Kaprodi</h6>
                                            @if ($proposal->status_kaprodi == 'disetujui')
                                                <span class="badge badge-success p-3"><i
                                                        class="fas fa-check-circle"></i> Disetujui</span>
                                            @elseif($proposal->status_kaprodi == 'ditolak')
                                                <span class="badge badge-danger p-3"><i
                                                        class="fas fa-times-circle"></i> Ditolak</span>
                                            @else
                                                <span class="badge badge-warning p-3"><i class="fas fa-clock"></i>
                                                    Menunggu Verifikasi</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Proposal -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Informasi Proposal</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Nama Kelompok:</strong></div>
                                <div class="col-md-8">{{ $proposal->nama_kelompok }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Judul PKM:</strong></div>
                                <div class="col-md-8">{{ $proposal->judul_kelompok }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Skema:</strong></div>
                                <div class="col-md-8"><span class="badge badge-info">{{ $proposal->skema }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Ketua Kelompok:</strong></div>
                                <div class="col-md-8">{{ $proposal->ketua->name }} ({{ $proposal->ketua->nim }})
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Dosen Pembimbing:</strong></div>
                                <div class="col-md-8">{{ $proposal->dosenPembimbing->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Anggota -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Anggota Kelompok</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>NIM</th>
                                            <th>Program Studi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($proposal->anggota as $index => $anggota)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $anggota->nama }}</td>
                                                <td>{{ $anggota->nim }}</td>
                                                <td>{{ $anggota->program_studi }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Belum ada anggota</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    @if ($proposal->catatan_dosen)
                        <div class="alert alert-info">
                            <strong><i class="fas fa-comment"></i> Catatan Dosen:</strong><br>
                            {{ $proposal->catatan_dosen }}
                        </div>
                    @endif

                    @if ($proposal->catatan_kaprodi)
                        <div class="alert alert-warning">
                            <strong><i class="fas fa-comment"></i> Catatan Kaprodi:</strong><br>
                            {{ $proposal->catatan_kaprodi }}
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    @if ($proposal->status_kaprodi == 'menunggu' && $proposal->status_dosen == 'disetujui')
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 bg-primary text-white">
                                <h6 class="m-0 font-weight-bold">Verifikasi Proposal</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="{{ route('kaprodi.proposals.approve', $proposal->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Catatan (opsional):</label>
                                                <textarea name="catatan_kaprodi" class="form-control" rows="3"
                                                    placeholder="Tambahkan catatan jika diperlukan"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-block">
                                                <i class="fas fa-check"></i> Setujui Proposal
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="{{ route('kaprodi.proposals.reject', $proposal->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Alasan Penolakan <span class="text-danger">*</span>:</label>
                                                <textarea name="catatan_kaprodi" class="form-control" rows="3" placeholder="Berikan alasan penolakan"
                                                    required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-danger btn-block">
                                                <i class="fas fa-times"></i> Tolak Proposal
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
                    <button class="close" type="button" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
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
    <script src="{{ asset('dashboard-assets/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
