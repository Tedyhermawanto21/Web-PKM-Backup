<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Detail Proposal - PKM Center</title>

    <link href="{{ asset('dashboard-assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('dashboard-assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="sidebar-brand-text mx-3">PKM Center</div>
            </a>

            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Admin</div>

            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.proposals.index') }}">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Review Proposal</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Manajemen User</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('dashboard-assets/img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Detail Proposal PKM</h1>
                        <a href="{{ route('admin.proposals.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Informasi Proposal</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="200">Nama Kelompok</th>
                                            <td>: {{ $proposal->nama_kelompok }}</td>
                                        </tr>
                                        <tr>
                                            <th>Judul PKM</th>
                                            <td>: {{ $proposal->judul_kelompok }}</td>
                                        </tr>
                                        <tr>
                                            <th>Skema</th>
                                            <td>: <span class="badge badge-info">{{ $proposal->skema }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Ketua</th>
                                            <td>: {{ $proposal->ketua->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dosen Pembimbing</th>
                                            <td>: {{ $proposal->dosenPembimbing->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status Dosen</th>
                                            <td>:
                                                @if ($proposal->status_dosen == 'menunggu')
                                                    <span class="badge badge-warning">Menunggu</span>
                                                @elseif($proposal->status_dosen == 'disetujui')
                                                    <span class="badge badge-success">Disetujui</span>
                                                @else
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status Kaprodi</th>
                                            <td>:
                                                @if ($proposal->status_kaprodi == 'menunggu')
                                                    <span class="badge badge-warning">Menunggu</span>
                                                @elseif($proposal->status_kaprodi == 'disetujui')
                                                    <span class="badge badge-success">Disetujui</span>
                                                @else
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Proposal</th>
                                            <td>:
                                                <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-file-download"></i> Download File
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

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
                                                    <th>Posisi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($proposal->anggota as $index => $anggota)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $anggota->nama }}</td>
                                                        <td>{{ $anggota->nim }}</td>
                                                        <td>{{ $anggota->program_studi }}</td>
                                                        <td>
                                                            @if ($anggota->posisi == 'ketua')
                                                                <span class="badge badge-primary">Ketua</span>
                                                            @else
                                                                <span class="badge badge-secondary">Anggota</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Status Review</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        @if ($proposal->status_admin == 'menunggu')
                                            <i class="fas fa-clock fa-3x text-warning mb-2"></i>
                                            <h5 class="text-warning">Menunggu Review</h5>
                                        @elseif($proposal->status_admin == 'disetujui')
                                            <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                                            <h5 class="text-success">Disetujui</h5>
                                        @elseif($proposal->status_admin == 'ditolak')
                                            <i class="fas fa-times-circle fa-3x text-danger mb-2"></i>
                                            <h5 class="text-danger">Ditolak</h5>
                                        @endif
                                    </div>

                                    @if ($proposal->catatan_admin)
                                        <div class="alert alert-info">
                                            <strong>Catatan:</strong>
                                            <p class="mb-0 mt-2">{{ $proposal->catatan_admin }}</p>
                                        </div>
                                    @endif

                                    @if ($proposal->status_admin == 'menunggu')
                                        <hr>
                                        <h6 class="font-weight-bold">Review Proposal</h6>

                                        <button type="button" class="btn btn-success btn-block" data-toggle="modal"
                                            data-target="#approveModal">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>

                                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                                            data-target="#rejectModal">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; PKM Center 2025</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Proposal</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('admin.proposals.approve', $proposal->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui proposal ini?</p>
                        <div class="form-group">
                            <label>Catatan (Opsional)</label>
                            <textarea name="catatan_admin" class="form-control" rows="3"
                                placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Ya, Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Proposal</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('admin.proposals.reject', $proposal->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-danger">Proposal yang ditolak dapat diupload ulang oleh mahasiswa.</p>
                        <div class="form-group">
                            <label>Catatan <span class="text-danger">*</span></label>
                            <textarea name="catatan_admin" class="form-control" rows="4" placeholder="Jelaskan alasan penolakan..."
                                required></textarea>
                            <small class="form-text text-muted">Minimal 10 karakter</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Ya, Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
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
