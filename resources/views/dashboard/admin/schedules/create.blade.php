@extends('layouts.admin')

@section('title', 'Tambah Jadwal')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Jadwal Baru</h1>
        <a href="{{ route('admin.schedules.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Jadwal</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.schedules.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="type">Tipe Jadwal <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type"
                                required>
                                <option value="">Pilih Tipe Jadwal</option>
                                @foreach ($types as $key => $value)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Nama Jadwal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}"
                                placeholder="Contoh: Periode Upload Proposal Semester Genap 2025" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="datetime-local"
                                        class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                                        name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="datetime-local"
                                        class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                                        name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" placeholder="Deskripsi atau catatan tambahan (opsional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    Aktifkan jadwal ini
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Jadwal yang tidak aktif tidak akan berlaku meskipun tanggalnya sudah
                                tiba
                            </small>
                        </div>

                        <hr>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Jadwal
                            </button>
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
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
                    <h6 class="font-weight-bold">Tipe Jadwal:</h6>
                    <ul class="pl-3">
                        <li><strong>Upload Proposal:</strong> Jadwal untuk mahasiswa mengupload proposal
                            PKM</li>
                        <li><strong>Revisi Tahap 1-3:</strong> Jadwal untuk mahasiswa melakukan revisi
                            proposal sesuai tahapan</li>
                    </ul>

                    <hr>

                    <h6 class="font-weight-bold">Catatan:</h6>
                    <ul class="pl-3 mb-0">
                        <li>Pastikan tanggal mulai lebih awal dari tanggal selesai</li>
                        <li>Jadwal yang sedang berlangsung akan ditampilkan ke mahasiswa</li>
                        <li>Anda dapat mengaktifkan/non-aktifkan jadwal kapan saja</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
