<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Buat Proposal PKM - PKM Center</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Buat Proposal PKM Baru</h1>
                        <a href="{{ route('mahasiswa.proposals.index') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                        </a>
                    </div>

                    <!-- Alert Messages -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Form Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Formulir Proposal PKM</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mahasiswa.proposals.store') }}" method="POST">
                                @csrf

                                <!-- Informasi Kelompok -->
                                <div class="mb-4">
                                    <h5 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-users"></i> Informasi Kelompok
                                    </h5>

                                    <div class="form-group">
                                        <label for="nama_kelompok">Nama Kelompok <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('nama_kelompok') is-invalid @enderror"
                                            id="nama_kelompok" name="nama_kelompok"
                                            value="{{ old('nama_kelompok') }}"
                                            placeholder="Contoh: Tim Inovasi Teknologi" required>
                                        @error('nama_kelompok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="judul_kelompok">Judul PKM <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('judul_kelompok') is-invalid @enderror" id="judul_kelompok"
                                            name="judul_kelompok" rows="3" placeholder="Masukkan judul lengkap proposal PKM Anda" required>{{ old('judul_kelompok') }}</textarea>
                                        @error('judul_kelompok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="skema">Skema PKM <span class="text-danger">*</span></label>
                                        <select class="form-control @error('skema') is-invalid @enderror"
                                            id="skema" name="skema" required>
                                            <option value="">-- Pilih Skema PKM --</option>
                                            <option value="PKM-KC" {{ old('skema') == 'PKM-KC' ? 'selected' : '' }}>
                                                PKM-KC (Karsa Cipta)</option>
                                            <option value="PKM-RE" {{ old('skema') == 'PKM-RE' ? 'selected' : '' }}>
                                                PKM-RE (Riset Eksakta)</option>
                                            <option value="PKM-GT" {{ old('skema') == 'PKM-GT' ? 'selected' : '' }}>
                                                PKM-GT (Gagasan Tertulis)</option>
                                            <option value="PKM-AI" {{ old('skema') == 'PKM-AI' ? 'selected' : '' }}>
                                                PKM-AI (Artikel Ilmiah)</option>
                                            <option value="PKM-PM" {{ old('skema') == 'PKM-PM' ? 'selected' : '' }}>
                                                PKM-PM (Pengabdian Masyarakat)</option>
                                            <option value="PKM-K" {{ old('skema') == 'PKM-K' ? 'selected' : '' }}>
                                                PKM-K (Kewirausahaan)</option>
                                            <option value="PKM-VGK" {{ old('skema') == 'PKM-VGK' ? 'selected' : '' }}>
                                                PKM-VGK (Video Gagasan Konstruktif)</option>
                                        </select>
                                        @error('skema')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="dosen_pembimbing_id">Dosen Pembimbing <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('dosen_pembimbing_id') is-invalid @enderror"
                                            id="dosen_pembimbing_id" name="dosen_pembimbing_id" required>
                                            <option value="">-- Pilih Dosen Pembimbing --</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->id }}"
                                                    {{ old('dosen_pembimbing_id') == $dosen->id ? 'selected' : '' }}>
                                                    {{ $dosen->name }} - {{ $dosen->program_studi }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle"></i> Proposal akan menunggu persetujuan dari
                                            dosen pembimbing yang dipilih
                                        </small>
                                        @error('dosen_pembimbing_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Informasi Ketua -->
                                <div class="mb-4">
                                    <h5 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-user-tie"></i> Ketua Kelompok
                                    </h5>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->nim }}) -
                                        {{ Auth::user()->program_studi }}
                                        <br><small>Anda secara otomatis terdaftar sebagai ketua kelompok</small>
                                    </div>
                                </div>

                                <!-- Anggota Kelompok -->
                                <div class="mb-4">
                                    <h5 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-users"></i> Anggota Kelompok (Maksimal 4 Anggota)
                                    </h5>

                                    <div id="anggota-container">
                                        @for ($i = 0; $i < 4; $i++)
                                            <div class="card mb-3 anggota-card">
                                                <div class="card-header bg-light">
                                                    <strong>Anggota {{ $i + 1 }}</strong>
                                                    @if ($i > 0)
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger float-right remove-anggota">
                                                            <i class="fas fa-times"></i> Hapus
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Nama <span class="text-danger">*</span></label>
                                                                <input type="text"
                                                                    class="form-control @error('anggota.' . $i . '.nama') is-invalid @enderror"
                                                                    name="anggota[{{ $i }}][nama]"
                                                                    value="{{ old('anggota.' . $i . '.nama') }}"
                                                                    placeholder="Nama lengkap anggota" required>
                                                                @error('anggota.' . $i . '.nama')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>NIM <span class="text-danger">*</span></label>
                                                                <input type="text"
                                                                    class="form-control @error('anggota.' . $i . '.nim') is-invalid @enderror"
                                                                    name="anggota[{{ $i }}][nim]"
                                                                    value="{{ old('anggota.' . $i . '.nim') }}"
                                                                    placeholder="NIM anggota" required>
                                                                @error('anggota.' . $i . '.nim')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Program Studi <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text"
                                                                    class="form-control @error('anggota.' . $i . '.program_studi') is-invalid @enderror"
                                                                    name="anggota[{{ $i }}][program_studi]"
                                                                    value="{{ old('anggota.' . $i . '.program_studi') }}"
                                                                    placeholder="Program studi anggota" required>
                                                                @error('anggota.' . $i . '.program_studi')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane"></i> Ajukan Proposal
                                    </button>
                                    <a href="{{ route('mahasiswa.proposals.index') }}"
                                        class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

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

    <script>
        $(document).ready(function() {
            // Handle remove anggota button
            $(document).on('click', '.remove-anggota', function() {
                $(this).closest('.anggota-card').remove();
            });
        });
    </script>
</body>

</html>
