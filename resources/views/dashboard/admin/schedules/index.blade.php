@extends('layouts.admin')

@section('title', 'Kelola Jadwal')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Jadwal PKM</h1>
        <a href="{{ route('admin.schedules.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Jadwal
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Jadwal</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tipe</th>
                            <th>Nama Jadwal</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ \App\Models\Schedule::getTypes()[$schedule->type] ?? $schedule->type }}
                                    </span>
                                </td>
                                <td>{{ $schedule->name }}</td>
                                <td>{{ $schedule->start_date->format('d/m/Y H:i') }}</td>
                                <td>{{ $schedule->end_date->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if ($schedule->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Non-aktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($schedule->isOngoing())
                                        <span class="badge badge-primary">Sedang Berlangsung</span>
                                    @elseif($schedule->isPast())
                                        <span class="badge badge-dark">Sudah Lewat</span>
                                    @else
                                        <span class="badge badge-warning">Akan Datang</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.schedules.toggle-status', $schedule->id) }}"
                                        method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-info"
                                            title="{{ $schedule->is_active ? 'Non-aktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-{{ $schedule->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST"
                                        style="display: inline-block;"
                                        onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada jadwal</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
