@extends('layouts.app-modern')

@section('title', 'Kelola Jadwal')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Kelola Jadwal PKM</h1>
            <p class="text-slate-500">Buat dan atur jadwal kegiatan PKM (Upload, Review, Revisi).</p>
        </div>
        <a href="{{ route('admin.schedules.create') }}"
            class="inline-flex items-center px-4 py-2 bg-uhamka-600 text-white text-sm font-bold rounded-xl shadow-md hover:bg-uhamka-700 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Jadwal
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3 text-green-700 animate-fade-in-down">
            <div class="bg-green-100 p-2 rounded-full">
                 <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Schedules Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50">
            <h6 class="font-bold text-slate-800">Daftar Jadwal</h6>
        </div>
        <div class="p-0">
             <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase text-slate-500 font-bold tracking-wider">
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Tipe</th>
                            <th class="px-6 py-4">Nama Jadwal</th>
                            <th class="px-6 py-4">Periode</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Kondisi</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($schedules as $schedule)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4 text-slate-500 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                                         {{ \App\Models\Schedule::getTypes()[$schedule->type] ?? $schedule->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                     <span class="font-bold text-slate-700 block">{{ $schedule->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="w-16 text-xs text-slate-400 font-bold uppercase">Mulai:</span>
                                            <span class="font-medium">{{ $schedule->start_date->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                             <span class="w-16 text-xs text-slate-400 font-bold uppercase">Selesai:</span>
                                            <span class="font-medium">{{ $schedule->end_date->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($schedule->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                            Non-aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($schedule->isOngoing())
                                        <div class="flex items-center text-blue-600 font-bold text-xs gap-1">
                                             <span class="relative flex h-3 w-3 mr-1">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                            </span>
                                            Berlangsung
                                        </div>
                                    @elseif($schedule->isPast())
                                         <div class="flex items-center text-slate-500 font-medium text-xs gap-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Sudah Lewat
                                        </div>
                                    @else
                                         <div class="flex items-center text-amber-600 font-medium text-xs gap-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Akan Datang
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex bg-white rounded-lg border border-slate-200 shadow-sm">
                                        <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="p-2 text-slate-500 hover:text-uhamka-600 hover:bg-slate-50 rounded-l-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                        <div class="w-px bg-slate-200"></div>
                                        <form action="{{ route('admin.schedules.toggle-status', $schedule->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 text-slate-500 hover:bg-slate-50 transition-colors {{ $schedule->is_active ? 'hover:text-amber-500' : 'hover:text-green-500' }}" title="{{ $schedule->is_active ? 'Non-aktifkan' : 'Aktifkan' }}">
                                                 <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    @if($schedule->is_active)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    @endif
                                                </svg>
                                            </button>
                                        </form>
                                        <div class="w-px bg-slate-200"></div>
                                        <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-500 hover:text-red-600 hover:bg-slate-50 rounded-r-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                       <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                       <p class="font-medium">Belum ada jadwal yang dibuat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
