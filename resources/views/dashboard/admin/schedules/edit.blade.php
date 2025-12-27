@extends('layouts.admin')

@section('title', 'Edit Jadwal')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Jadwal</h1>
        <a href="{{ route('admin.schedules.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Jadwal</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="type">Tipe Jadwal <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type"
                                required>
                                <option value="">Pilih Tipe Jadwal</option>
                                @foreach ($types as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ old('type', $schedule->type) == $key ? 'selected' : '' }}>
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
                                name="name" value="{{ old('name', $schedule->name) }}"
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
                                        name="start_date"
                                        value="{{ old('start_date', $schedule->start_date->format('Y-m-d\TH:i')) }}"
                                        required>
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
                                        name="end_date"
                                        value="{{ old('end_date', $schedule->end_date->format('Y-m-d\TH:i')) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" placeholder="Deskripsi atau catatan tambahan (opsional)">{{ old('description', $schedule->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
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
                                <i class="fas fa-save"></i> Update Jadwal
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
                    <h6 class="m-0 font-weight-bold text-primary">Status Jadwal</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Status Aktif:</dt>
                        <dd class="col-sm-7">
                            @if ($schedule->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Non-aktif</span>
                            @endif
                        </dd>

                        <dt class="col-sm-5">Kondisi:</dt>
                        <dd class="col-sm-7">
                            @if ($schedule->isOngoing())
                                <span class="badge badge-primary">Sedang Berlangsung</span>
                            @elseif($schedule->isPast())
                                <span class="badge badge-dark">Sudah Lewat</span>
                            @else
                                <span class="badge badge-warning">Akan Datang</span>
                            @endif
                        </dd>

                        <dt class="col-sm-5">Dibuat:</dt>
                        <dd class="col-sm-7">{{ $schedule->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-5">Terakhir Update:</dt>
                        <dd class="col-sm-7 mb-0">{{ $schedule->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi</h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold">Catatan:</h6>
                    <ul class="pl-3 mb-0">
                        <li>Pastikan tanggal mulai lebih awal dari tanggal selesai</li>
                        <li>Perubahan akan segera berlaku setelah disimpan</li>
                        <li>Jadwal yang sedang berlangsung dapat dinonaktifkan kapan saja</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
