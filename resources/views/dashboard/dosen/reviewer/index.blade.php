@extends('layouts.app-modern')

@section('title', 'Daftar Review Proposal')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Daftar Review Proposal</h1>
        <p class="text-slate-500">Proposal yang ditugaskan kepada Anda untuk direview</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4">
        @forelse($proposals as $proposal)
            @php
                $reviewStatus = $proposal->reviewers->first()->pivot->status ?? 'pending';
                $reviewScore = $proposal->reviewers->first()->pivot->score ?? null;
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            {{ $proposal->judul_kelompok ?? ($proposal->judul_pkm ?? 'Judul tidak tersedia') }}
                        </h3>
                        <p class="text-sm text-slate-500 mb-3">{{ $proposal->nama_kelompok ?? '-' }}</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                {{ $proposal->skema ?? ($proposal->skema_pkm ?? 'PKM-KC') }}
                            </span>
                            @if ($reviewStatus === 'reviewed')
                                <span
                                    class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Sudah Direview (Skor: {{ $reviewScore }})
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                    Menunggu Review
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('dosen.reviewer.show', $proposal->id) }}"
                            class="px-4 py-2 bg-uhamka-500 text-white rounded-lg text-sm font-medium hover:bg-uhamka-600 transition-colors">
                            {{ $reviewStatus === 'reviewed' ? 'Lihat Review' : 'Review Proposal' }}
                        </a>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-4 mt-4">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Ketua: {{ $proposal->ketua->name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span>Pembimbing: {{ $proposal->dosenPembimbing->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Proposal untuk Direview</h3>
                <p class="text-slate-500">Anda belum ditugaskan untuk mereview proposal apapun.</p>
            </div>
        @endforelse
    </div>
@endsection
