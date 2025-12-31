@extends('layouts.mahasiswa')

@section('title', 'Upload Proposal Baru')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Upload Proposal PKM</h1>
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

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pilih Proposal dan Upload File</h6>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">
                <i class="fas fa-info-circle"></i> Pilih proposal yang sudah disetujui oleh Dosen dan
                Kaprodi,
                kemudian upload file proposal dalam format PDF, DOC, atau DOCX (maksimal 5MB).
            </p>

            @if ($proposals->count() > 0)
                <form action="{{ route('mahasiswa.upload.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="proposal_id">Pilih Proposal <span class="text-danger">*</span></label>
                        <select name="proposal_id" id="proposal_id" class="form-control" required>
                            <option value="">-- Pilih Proposal --</option>
                            @foreach ($proposals as $proposal)
                                <option value="{{ $proposal->id }}"
                                    {{ old('proposal_id') == $proposal->id ? 'selected' : '' }}>
                                    {{ $proposal->nama_kelompok }} - {{ $proposal->judul_kelompok }}
                                    ({{ $proposal->skema }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="file_proposal">File Proposal <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file_proposal" name="file_proposal"
                                accept=".pdf,.doc,.docx" required>
                            <label class="custom-file-label" for="file_proposal">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted">
                            Format: PDF, DOC, DOCX | Maksimal 5MB
                        </small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Proposal
                        </button>
                        <a href="{{ route('mahasiswa.upload.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Belum ada proposal yang siap diupload. Proposal harus disetujui oleh Dosen dan
                    Kaprodi terlebih dahulu.
                </div>
                <a href="{{ route('mahasiswa.pengajuan_kelompok_pkm.index') }}" class="btn btn-primary">
                    <i class="fas fa-eye"></i> Lihat Pengajuan PKM
                </a>
            @endif
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
