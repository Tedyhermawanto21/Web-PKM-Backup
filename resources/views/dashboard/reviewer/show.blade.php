@extends('layouts.app-modern')

@section('title', 'Review Proposal')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Review Proposal</h1>
        <p class="text-slate-500">Gunakan form berikut untuk memberikan skor dan komentar.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <h3 class="font-bold text-lg">{{ $proposal->judul_kelompok }}</h3>
        <p class="text-sm text-slate-500">{{ $proposal->nama_kelompok }} • Ketua: {{ $proposal->ketua->name }}</p>
        <div class="mt-4">
            @if ($proposal->file_proposal)
                <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
                    class="px-3 py-2 bg-green-50 text-green-700 rounded">Download Proposal</a>
            @endif
        </div>
    </div>

    <form action="{{ route('reviewer.assigned.submit', $proposal->id) }}" method="POST"
        class="bg-white p-6 rounded-2xl border">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700 mb-2">Skor (0-100)</label>
            <input type="number" name="score" min="0" max="100" step="0.1" required
                class="w-32 p-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700 mb-2">Komentar</label>
            <textarea name="comments" rows="6" class="w-full p-3 border rounded"></textarea>
        </div>

        <div>
            <button type="submit" class="px-4 py-2 bg-uhamka-500 text-white rounded-lg">Kirim Review</button>
            <a href="{{ route('reviewer.assigned.index') }}" class="ml-2 text-sm text-slate-500">Batal</a>
        </div>
    </form>
@endsection
