@extends('layouts.mahasiswa')

@section('title', 'Upload Ulang Proposal')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Upload Ulang Proposal</h1>
        <a href="{{ route('mahasiswa.upload.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> Terdapat kesalahan:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if ($proposal->catatan_admin)
        <div class="alert alert-danger">
            <h5><i class="fas fa-exclamation-triangle"></i> Catatan Admin:</h5>
            <p class="mb-0">{{ $proposal->catatan_admin }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upload File Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('mahasiswa.upload.update', $proposal->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Nama Kelompok</label>
                            <input type="text" class="form-control" value="{{ $proposal->nama_kelompok }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Judul PKM</label>
                            <input type="text" class="form-control" value="{{ $proposal->judul_kelompok }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>File Saat Ini</label>
                            <div>
                                @if ($proposal->file_proposal)
                                    <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-file-download"></i> Download File Lama
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="file_proposal">File Proposal Baru <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file_proposal" name="file_proposal"
                                    accept=".pdf,.doc,.docx" required>
                                <label class="custom-file-label" for="file_proposal">Pilih file
                                    baru...</label>
                            </div>
                            <small class="form-text text-muted">
                                Format: PDF, DOC, DOCX | Maksimal 5MB
                            </small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Ulang
                            </button>
                            <a href="{{ route('mahasiswa.upload.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Skema</th>
                            <td>: {{ $proposal->skema }}</td>
                        </tr>
                        <tr>
                            <th>Dosen</th>
                            <td>: {{ $proposal->dosenPembimbing->name }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: <span class="badge badge-danger">Ditolak</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Update label when file is selected
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>
@endpush
