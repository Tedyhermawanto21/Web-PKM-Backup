@extends('layouts.mahasiswa')

@section('title', 'Edit Proposal PKM')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Proposal PKM</h1>
        <a href="{{ route('mahasiswa.proposals.show', $proposal->id) }}"
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

    @if ($proposal->status == 'ditolak' && $proposal->catatan_penolakan)
        <div class="alert alert-danger">
            <strong><i class="fas fa-exclamation-triangle"></i> Catatan Penolakan:</strong><br>
            {{ $proposal->catatan_penolakan }}
        </div>
    @endif

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Proposal PKM</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('mahasiswa.proposals.update', $proposal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Informasi Kelompok -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-users"></i> Informasi Kelompok
                    </h5>

                    <div class="form-group">
                        <label for="nama_kelompok">Nama Kelompok <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_kelompok') is-invalid @enderror"
                            id="nama_kelompok" name="nama_kelompok"
                            value="{{ old('nama_kelompok', $proposal->nama_kelompok) }}"
                            placeholder="Contoh: Tim Inovasi Teknologi" required>
                        @error('nama_kelompok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="judul_kelompok">Judul PKM <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('judul_kelompok') is-invalid @enderror" id="judul_kelompok" name="judul_kelompok"
                            rows="3" placeholder="Masukkan judul lengkap proposal PKM Anda" required>{{ old('judul_kelompok', $proposal->judul_kelompok) }}</textarea>
                        @error('judul_kelompok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="skema">Skema PKM <span class="text-danger">*</span></label>
                        <select class="form-control @error('skema') is-invalid @enderror" id="skema" name="skema"
                            required>
                            <option value="">-- Pilih Skema PKM --</option>
                            <option value="PKM-KC" {{ old('skema', $proposal->skema) == 'PKM-KC' ? 'selected' : '' }}>
                                PKM-KC (Karsa Cipta)</option>
                            <option value="PKM-RE" {{ old('skema', $proposal->skema) == 'PKM-RE' ? 'selected' : '' }}>
                                PKM-RE (Riset Eksakta)</option>
                            <option value="PKM-GT" {{ old('skema', $proposal->skema) == 'PKM-GT' ? 'selected' : '' }}>
                                PKM-GT (Gagasan Tertulis)</option>
                            <option value="PKM-AI" {{ old('skema', $proposal->skema) == 'PKM-AI' ? 'selected' : '' }}>
                                PKM-AI (Artikel Ilmiah)</option>
                            <option value="PKM-PM" {{ old('skema', $proposal->skema) == 'PKM-PM' ? 'selected' : '' }}>
                                PKM-PM (Pengabdian Masyarakat)</option>
                            <option value="PKM-K" {{ old('skema', $proposal->skema) == 'PKM-K' ? 'selected' : '' }}>PKM-K
                                (Kewirausahaan)</option>
                            <option value="PKM-VGK" {{ old('skema', $proposal->skema) == 'PKM-VGK' ? 'selected' : '' }}>
                                PKM-VGK (Video Gagasan Konstruktif)</option>
                        </select>
                        @error('skema')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="dosen_pembimbing_id">Dosen Pembimbing <span class="text-danger">*</span></label>
                        <select class="form-control @error('dosen_pembimbing_id') is-invalid @enderror"
                            id="dosen_pembimbing_id" name="dosen_pembimbing_id" required>
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}"
                                    {{ old('dosen_pembimbing_id', $proposal->dosen_pembimbing_id) == $dosen->id ? 'selected' : '' }}>
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
                        @php
                            $anggotaData = $proposal->anggota->where('posisi', 'anggota')->values();
                        @endphp
                        @for ($i = 0; $i < 4; $i++)
                            @php
                                $anggota = $anggotaData->get($i);
                            @endphp
                            <div class="card mb-3 anggota-card">
                                <div class="card-header bg-light">
                                    <strong>Anggota {{ $i + 1 }}</strong>
                                    @if ($i > 0)
                                        <button type="button" class="btn btn-sm btn-danger float-right remove-anggota">
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
                                                    value="{{ old('anggota.' . $i . '.nama', $anggota->nama ?? '') }}"
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
                                                    value="{{ old('anggota.' . $i . '.nim', $anggota->nim ?? '') }}"
                                                    placeholder="NIM anggota" required>
                                                @error('anggota.' . $i . '.nim')
                                                    <div class="invalid-feedback">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Program Studi <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('anggota.' . $i . '.program_studi') is-invalid @enderror"
                                                    name="anggota[{{ $i }}][program_studi]"
                                                    value="{{ old('anggota.' . $i . '.program_studi', $anggota->program_studi ?? '') }}"
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
                        <i class="fas fa-save"></i> Perbarui & Ajukan Proposal
                    </button>
                    <a href="{{ route('mahasiswa.proposals.show', $proposal->id) }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle remove anggota button
            $(document).on('click', '.remove-anggota', function() {
                $(this).closest('.anggota-card').remove();
            });
        });
    </script>
@endpush
