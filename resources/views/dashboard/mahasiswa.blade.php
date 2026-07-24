@extends('layouts.app-modern')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <!-- Welcome Banner -->
    <div
        class="relative w-full rounded-3xl overflow-hidden bg-gradient-to-r from-uhamka-900 via-uhamka-800 to-uhamka-900 shadow-2xl mb-8 group">
        <!-- Background Decor -->
        <div
            class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-uhamka-yellow-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob">
        </div>
        <div
            class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob animation-delay-2000">
        </div>

        <div class="relative z-10 p-8 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2 leading-tight">
                    Selamat Datang, <br>
                    <span class="text-uhamka-yellow-400">{{ $user->name }}</span>! 👋
                </h1>
                <p class="text-uhamka-100 max-w-lg text-sm sm:text-base leading-relaxed">
                    NIM: {{ $user->nim ?? '-' }} | Prodi: {{ $user->program_studi ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Bento Grid Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        <!-- Profile Card -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-uhamka-200 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div
                    class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 text-xl group-hover:scale-110 transition-transform">
                    👤
                </div>
                <span
                    class="px-3 py-1 bg-slate-50 text-slate-500 text-xs font-bold rounded-lg uppercase tracking-wider">Biodata</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Mahasiswa Aktif</h3>
            <p class="text-lg font-bold text-slate-800 mb-4">{{ $user->name }}</p>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="text-xs text-slate-500 font-semibold">NIM</span>
                    <span class="text-xs font-bold font-mono text-slate-700">{{ $user->nim ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="text-xs text-slate-500 font-semibold">Program Studi</span>
                    <span class="text-xs font-bold text-uhamka-600 text-right">{{ $user->program_studi ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Kelompok Stats -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-green-200 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div
                    class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 text-xl group-hover:scale-110 transition-transform">
                    👥
                </div>
                <span
                    class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-lg uppercase tracking-wider">Tim</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Kelompok Diikuti</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-slate-900">{{ $user->kelompoks->count() }}</span>
                <span class="text-sm font-bold text-slate-400">Tim</span>
            </div>
            <p class="text-xs text-slate-400 mt-2">Bergabung sebagai anggota atau ketua.</p>

            <div class="mt-6">
                <a href="{{ route('mahasiswa.kelompoks.index') }}"
                    class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-slate-200 rounded-xl shadow-sm text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition-all">
                    Lihat Detail Kelompok
                </a>
            </div>
        </div>

        <!-- Pengajuan Stats -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-uhamka-yellow-200 transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-uhamka-yellow-50 rounded-bl-full -mr-4 -mt-4 opacity-50"></div>

            <div class="flex items-start justify-between mb-4 relative z-10">
                <div
                    class="w-12 h-12 bg-uhamka-yellow-50 rounded-xl flex items-center justify-center text-uhamka-yellow-600 text-xl group-hover:scale-110 transition-transform">
                    📝
                </div>
                <span
                    class="px-3 py-1 bg-uhamka-yellow-50 text-uhamka-yellow-700 text-xs font-bold rounded-lg uppercase tracking-wider">Proposal</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Pengajuan Ketua</h3>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="text-4xl font-extrabold text-slate-900">{{ $user->kelompokAsKetua->count() }}</span>
                <span class="text-sm font-bold text-slate-400">Judul</span>
            </div>

            <div class="mt-6 relative z-10">
                <a href="{{ route('mahasiswa.pengajuan_kelompok_pkm.create') }}"
                    class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-uhamka-900 bg-uhamka-yellow-400 hover:bg-uhamka-yellow-500 focus:outline-none transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Proposal Baru
                </a>
            </div>
        </div>
    </div>

    <!-- PKM AI Recommendation -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-5">
            <div>
                <h3 class="font-bold text-lg text-slate-900">Asisten Rekomendasi PKM</h3>
                <p class="text-sm text-slate-500 mt-1">Masukkan abstrak atau sinopsis proposal untuk rekomendasi dosen pembimbing dan alternatif judul.</p>
            </div>
            <span id="pkmAiStatus"
                class="hidden px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200"></span>
        </div>

        <form id="pkmAiForm" class="space-y-4">
            @csrf
            <div>
                <label for="ide_proposal" class="block text-sm font-bold text-slate-700 mb-2">Abstrak Proposal</label>
                <textarea id="ide_proposal" name="ide_proposal" rows="7" minlength="10" required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-uhamka-500 focus:ring-2 focus:ring-uhamka-100 focus:outline-none resize-y"
                    placeholder="Contoh: Kami ingin mengembangkan sistem monitoring tanaman cabai berbasis IoT dan machine learning untuk membantu petani mendeteksi kebutuhan air dan risiko penyakit lebih awal."></textarea>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:gap-6">
                <div>
                    <span class="block text-sm font-bold text-slate-700 mb-2">Metode Rekomendasi</span>
                    <div class="inline-flex flex-wrap rounded-xl border border-slate-200 bg-slate-50 p-1 gap-1">
                        <label class="cursor-pointer">
                            <input type="radio" name="mode" value="retrieval_based" class="peer sr-only" checked>
                            <span class="block rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 peer-checked:bg-uhamka-900 peer-checked:text-white">Retrieval-Based — Pendekatan A</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="mode" value="full_llm" class="peer sr-only">
                            <span class="block rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 peer-checked:bg-uhamka-900 peer-checked:text-white">Full LLM — Pendekatan B</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="pkm_category" class="block text-sm font-bold text-slate-700 mb-2">Kategori PKM (opsional)</label>
                    <select id="pkm_category" name="pkm_category"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:border-uhamka-500 focus:ring-2 focus:ring-uhamka-100 focus:outline-none">
                        <option value="">- Otomatis -</option>
                        <option value="PKM-KC">PKM-KC</option>
                        <option value="PKM-RE">PKM-RE</option>
                        <option value="PKM-RSH">PKM-RSH</option>
                        <option value="PKM-PM">PKM-PM</option>
                        <option value="PKM-PI">PKM-PI</option>
                        <option value="PKM-K">PKM-K</option>
                        <option value="PKM-KI">PKM-KI</option>
                        <option value="PKM-VGK">PKM-VGK</option>
                        <option value="PKM-GFT">PKM-GFT</option>
                        <option value="PKM-AI">PKM-AI</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600">
                        <input type="checkbox" name="has_partner" value="1"
                            class="rounded border-slate-300 text-uhamka-600 focus:ring-uhamka-500">
                        Ada mitra
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600">
                        <input type="checkbox" name="partner_is_profit" value="1"
                            class="rounded border-slate-300 text-uhamka-600 focus:ring-uhamka-500">
                        Mitra profit
                    </label>
                </div>

                <button id="pkmAiSubmit" type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-uhamka-900 px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-uhamka-800 focus:outline-none focus:ring-2 focus:ring-uhamka-300 disabled:cursor-not-allowed disabled:opacity-60">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Proses Rekomendasi
                </button>
            </div>
        </form>

        <div id="pkmAiError" class="hidden mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700"></div>

        <div id="pkmAiResults" class="hidden mt-6 space-y-6">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Metode</span>
                        <p id="pkmAiSchema" class="mt-1 text-sm font-bold text-slate-800">-</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Model</span>
                        <p id="pkmAiModel" class="mt-1 text-sm font-bold text-slate-800">-</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Waktu Respons</span>
                        <p id="pkmAiMode" class="mt-1 text-sm font-bold text-slate-800">-</p>
                    </div>
                </div>
                <p id="pkmAiNotes" class="mt-3 text-sm text-slate-600"></p>
            </div>

            <div>
                <h4 class="text-sm font-bold text-slate-900 mb-3">Rekomendasi Dosen Pembimbing</h4>
                <div id="pkmAiDosens" class="grid grid-cols-1 lg:grid-cols-3 gap-4"></div>
            </div>

            <div>
                <h4 class="text-sm font-bold text-slate-900 mb-3">Rekomendasi Judul PKM</h4>
                <div id="pkmAiTitles" class="grid grid-cols-1 lg:grid-cols-3 gap-4"></div>
            </div>
        </div>
    </div>

    <!-- Welcome Message Content -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-bold text-lg text-slate-900 mb-4">Informasi Dashboard</h3>
        <p class="text-slate-600 leading-relaxed mb-4">Selamat datang di PKM Center. Sistem informasi manajemen Program
            Kreativitas Mahasiswa.</p>
        <p class="text-slate-600 leading-relaxed mb-4">Melalui dashboard ini, Anda dapat:</p>
        <ul class="list-disc list-inside text-slate-600 mb-6 space-y-2">
            <li>Mengelola kelompok PKM</li>
            <li>Mengajukan proposal PKM</li>
            <li>Melihat status pengajuan</li>
            <li>Berkomunikasi dengan dosen pembimbing</li>
        </ul>
    </div>
@endsection

@push('scripts')
    <script>
        const pkmAiForm = document.getElementById('pkmAiForm');
        const pkmAiSubmit = document.getElementById('pkmAiSubmit');
        const pkmAiStatus = document.getElementById('pkmAiStatus');
        const pkmAiError = document.getElementById('pkmAiError');
        const pkmAiResults = document.getElementById('pkmAiResults');

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[char]);
        }

        function setPkmAiStatus(text, visible = true) {
            pkmAiStatus.textContent = text;
            pkmAiStatus.classList.toggle('hidden', !visible);
        }

        function renderDosens(dosens) {
            const container = document.getElementById('pkmAiDosens');
            container.className = 'grid grid-cols-1 lg:grid-cols-3 gap-4';
            container.innerHTML = '';

            if (!dosens || dosens.length === 0) {
                container.innerHTML = '<p class="text-sm text-slate-500">Tidak ada rekomendasi dosen.</p>';
                return;
            }

            container.innerHTML = dosens.map((dosen) => `
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-uhamka-600">Peringkat ${escapeHtml(dosen.rank ?? '-')}</p>
                            <h5 class="mt-1 font-bold text-slate-900">${escapeHtml(dosen.lecturer_name || '-')}</h5>
                        </div>
                        <span class="rounded-lg bg-uhamka-yellow-50 px-2 py-1 text-xs font-bold text-uhamka-yellow-700">
                            ${escapeHtml(Math.round((Number(dosen.score) || 0) * 100))}%
                        </span>
                    </div>
                    ${dosen.program_studi ? `<p class="text-xs font-semibold text-slate-500 mb-2">${escapeHtml(dosen.program_studi)}</p>` : ''}
                    <p class="text-sm leading-relaxed text-slate-700">${escapeHtml(dosen.reason || 'Alasan kecocokan belum tersedia.')}</p>
                </div>
            `).join('');
        }

        function renderTitles(titles) {
            const container = document.getElementById('pkmAiTitles');
            container.className = 'grid grid-cols-1 lg:grid-cols-3 gap-4';
            container.innerHTML = '';

            if (!titles || titles.length === 0) {
                container.innerHTML = '<p class="text-sm text-slate-500">Tidak ada judul yang dihasilkan.</p>';
                return;
            }

            container.innerHTML = titles.map((t) => `
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Judul ${escapeHtml(t.rank ?? '-')}</span>
                    <p class="mt-2 text-sm font-bold leading-relaxed text-slate-900">${escapeHtml(t.title || '-')}</p>
                    <dl class="mt-3 space-y-1 text-xs text-slate-500">
                        ${t.method ? `<div><span class="font-semibold text-slate-600">Metode:</span> ${escapeHtml(t.method)}</div>` : ''}
                        ${t.object ? `<div><span class="font-semibold text-slate-600">Objek:</span> ${escapeHtml(t.object)}</div>` : ''}
                        ${t.goal ? `<div><span class="font-semibold text-slate-600">Tujuan:</span> ${escapeHtml(t.goal)}</div>` : ''}
                        ${t.context ? `<div><span class="font-semibold text-slate-600">Konteks:</span> ${escapeHtml(t.context)}</div>` : ''}
                    </dl>
                </div>
            `).join('');
        }

        pkmAiForm?.addEventListener('submit', async (event) => {
            event.preventDefault();
            pkmAiError.classList.add('hidden');
            pkmAiResults.classList.add('hidden');
            pkmAiSubmit.disabled = true;
            setPkmAiStatus('Memproses...');

            const formData = new FormData(pkmAiForm);
            const ide = formData.get('ide_proposal');
            const payload = {
                idea: ide,
                abstract: ide,
                pkm_category: formData.get('pkm_category') || '',
                mode: formData.get('mode') || 'retrieval_based',
                top_k_lecturers: 3,
                total_titles: 3,
            };

            try {
                const response = await fetch("{{ route('mahasiswa.pkm_ai.experiment') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': formData.get('_token'),
                    },
                    body: JSON.stringify(payload),
                });

                const result = await response.json();
                if (!response.ok || result.status !== 'success') {
                    throw new Error(result.message || 'Rekomendasi gagal diproses.');
                }

                const data = result.data || {};
                const meta = data.experiment_metadata || {};
                document.getElementById('pkmAiSchema').textContent = data.method_label
                    || (data.mode === 'full_llm' ? 'Pendekatan B' : 'Pendekatan A');
                document.getElementById('pkmAiModel').textContent = meta.model_name || meta.provider || '-';
                document.getElementById('pkmAiMode').textContent = (meta.latency_ms != null) ? (meta.latency_ms + ' ms') : '-';
                document.getElementById('pkmAiNotes').textContent = meta.error ? ('Catatan: ' + meta.error) : '';

                renderDosens(data.lecturer_recommendations || []);
                renderTitles(data.title_recommendations || []);

                pkmAiResults.classList.remove('hidden');
                setPkmAiStatus('Selesai');
            } catch (error) {
                pkmAiError.textContent = error.message;
                pkmAiError.classList.remove('hidden');
                setPkmAiStatus('Gagal');
            } finally {
                pkmAiSubmit.disabled = false;
            }
        });
    </script>
@endpush
