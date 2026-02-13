<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PKM Center UHAMKA - Portal Resmi Kemahasiswaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Gold Shine Effect */
        .gold-shine {
            background: linear-gradient(45deg, #D4AF37 0%, #F9F1C8 50%, #D4AF37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: shine 4s linear infinite;
        }

        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }

        .bento-card {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .bento-card:hover {
            transform: translateY(-8px);
        }

        /* Clean Timeline */
        .timeline-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 999px;
        }

        .clean-line {
            background: linear-gradient(90deg, #024E9C 0%, #D4AF37 100%);
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
        }

        /* LIGHTWEIGHT ANIMATIONS */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        /* Scroll Reveal Utility */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="antialiased text-slate-900 bg-slate-50 relative selection:bg-uhamka-gold selection:text-white">

    <!-- Top Bar (University Formal) -->
    <div
        class="bg-uhamka-900 text-white py-2 text-xs font-medium relative z-50 px-4 sm:px-6 lg:px-8 border-b border-white/10">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <span>Universitas Muhammadiyah Prof. DR. HAMKA</span>
            <div class="flex gap-4">
                <a href="#" class="hover:text-uhamka-gold transition-colors">Portal UHAMKA</a>
                <span>|</span>
                <a href="#" class="hover:text-uhamka-gold transition-colors">Kemahasiswaan</a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="sticky w-full z-40 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-200 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 bg-uhamka-700 rounded-lg flex items-center justify-center text-white font-serif text-2xl font-bold shadow-md border border-uhamka-600">
                        U
                    </div>
                    <div>
                        <h1 class="font-heading font-extrabold text-xl text-uhamka-900 leading-none">PKM Center</h1>
                        <span class="text-[10px] font-bold text-uhamka-gold-600 tracking-[0.2em] uppercase">Hub Inovasi
                            Mahasiswa</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#"
                        class="text-sm font-semibold text-slate-600 hover:text-uhamka-700 hover:bg-slate-50 px-3 py-2 rounded-lg transition-all">Beranda</a>
                    <a href="#kategori"
                        class="text-sm font-semibold text-slate-600 hover:text-uhamka-700 hover:bg-slate-50 px-3 py-2 rounded-lg transition-all">Kategori</a>
                    <a href="#alur"
                        class="text-sm font-semibold text-slate-600 hover:text-uhamka-700 hover:bg-slate-50 px-3 py-2 rounded-lg transition-all">Alur
                        Seleksi</a>
                    <a href="#berita"
                        class="text-sm font-semibold text-slate-600 hover:text-uhamka-700 hover:bg-slate-50 px-3 py-2 rounded-lg transition-all">Berita
                        PIMNAS</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                        class="font-bold bg-uhamka-900 text-white px-5 py-2.5 rounded-lg hover:bg-uhamka-800 transition-all shadow-md">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Prestige Academic) -->
    <section class="relative pt-24 pb-32 overflow-hidden">
        <!-- Background Elements -->
        <div
            class="absolute top-0 right-0 -mr-40 -mt-40 w-[600px] h-[600px] rounded-full bg-uhamka-50 opacity-50 blur-3xl -z-10">
        </div>
        <div
            class="absolute bottom-0 left-0 -ml-40 -mb-40 w-[500px] h-[500px] rounded-full bg-blue-50 opacity-50 blur-3xl -z-10">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-16 items-center">

                <!-- Left Content (5 cols) -->
                <div class="lg:col-span-5 flex flex-col items-start text-left">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-white border border-uhamka-gold-400/50 rounded-full shadow-sm mb-8 animate-fade-in-up">
                        <span class="w-2 h-2 rounded-full bg-uhamka-gold-500 animate-pulse"></span>
                        <span class="text-xs font-bold text-uhamka-900 uppercase tracking-wider">Menuju PIMNAS
                            2025</span>
                    </div>

                    <h1
                        class="font-heading text-5xl lg:text-6xl font-bold text-slate-900 leading-[1.1] mb-6 animate-fade-in-up delay-100">
                        Wujudkan <br>
                        <span class="gold-shine">Ide Kreatif,</span> <br>
                        Bersama UHAMKA
                    </h1>

                    <p
                        class="text-lg text-slate-600 mb-10 leading-relaxed border-l-4 border-uhamka-gold-500 pl-6 animate-fade-in-up delay-200">
                        Platform resmi pengajuan, bimbingan, dan monitoring proposal
                        <span class="font-semibold text-uhamka-700">Program Kreativitas Mahasiswa (PKM)</span>
                        Universitas Muhammadiyah Prof. DR. HAMKA.
                    </p>

                    <div
                        class="flex flex-col sm:flex-row gap-6 w-full animate-fade-in-up delay-300 justify-center sm:justify-start">
                        <a href="{{ route('register') }}"
                            class="group px-10 py-5 bg-uhamka-yellow-400 text-uhamka-900 font-bold rounded-2xl hover:bg-yellow-500 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1 flex items-center justify-center gap-4 text-lg">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Daftar Sekarang
                        </a>

                        <a href="#"
                            class="group px-10 py-5 bg-white text-uhamka-900 font-bold rounded-2xl border-2 border-slate-200 hover:border-uhamka-yellow-400 hover:text-uhamka-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 flex items-center justify-center gap-4 text-lg">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Panduan 2025
                        </a>
                    </div>
                </div>

                <!-- Right Content (7 cols) - Bento Grid -->
                <div class="lg:col-span-7 w-full h-[600px] relative animate-fade-in-up delay-200">
                    <div class="absolute inset-0 grid grid-cols-12 grid-rows-12 gap-4">

                        <!-- Card 1: Main Photo (Kolaborasi Riset) -->
                        <div
                            class="col-span-7 row-span-8 bg-slate-900 rounded-3xl overflow-hidden relative shadow-2xl bento-card group border border-white/10">
                            <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=800&q=80"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-90"
                                alt="Mahasiswi UHAMKA di Lab">
                            <!-- Overlay Gradient -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-uhamka-900 via-uhamka-900/20 to-transparent">
                            </div>

                            <div class="absolute bottom-6 left-6 right-6">
                                <div class="flex items-center gap-2 mb-2">
                                    <span
                                        class="bg-uhamka-gold-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">SAINTEK</span>
                                    <span
                                        class="bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded">Riset
                                        Eksakta</span>
                                </div>
                                <h3 class="text-white font-heading font-bold text-xl leading-tight">Kolaborasi Riset
                                    Farmasi & Kedokteran</h3>
                            </div>
                        </div>

                        <!-- Card 2: Status (Seleksi Internal) -->
                        <div
                            class="col-span-5 row-span-4 bg-white rounded-3xl p-6 shadow-xl border border-slate-100 bento-card flex flex-col justify-between relative overflow-hidden">
                            <div class="flex justify-between items-start">
                                <div
                                    class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center border border-green-100 text-green-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="flex h-3 w-3 relative">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                            </div>
                            <div>
                                <h4 class="text-slate-500 font-bold text-xs uppercase tracking-wide mb-1">Status
                                    Kegiatan</h4>
                                <p class="text-uhamka-900 font-bold text-lg leading-tight">Seleksi Internal Berlangsung
                                </p>
                                <p class="text-slate-400 text-xs mt-2">Batas Akhir: 15 Okt 2025</p>
                            </div>
                        </div>

                        <!-- Card 3: Achievement (Gold Trophy) -->
                        <div
                            class="col-span-5 row-span-4 bg-uhamka-900 rounded-3xl p-6 shadow-xl border border-uhamka-800 bento-card relative overflow-hidden">
                            <div
                                class="absolute -right-4 -top-4 w-24 h-24 bg-uhamka-gold-500/20 rounded-full blur-2xl">
                            </div>

                            <div class="flex items-center gap-4 mb-4">
                                <div class="text-5xl drop-shadow-lg filter">🏆</div>
                                <div>
                                    <span class="block text-uhamka-gold-400 font-bold text-xs uppercase">Target
                                        Kita</span>
                                    <span class="block text-white font-bold text-2xl">Emas</span>
                                </div>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-1">
                                <div
                                    class="bg-uhamka-gold-500 h-1 rounded-full w-full shadow-[0_0_10px_rgba(212,175,55,0.5)]">
                                </div>
                            </div>
                            <p class="text-uhamka-200 text-xs mt-3 text-center">PIMNAS ke-38 - 2025</p>
                        </div>

                        <!-- Card 4: Funding Stats -->
                        <div
                            class="col-span-6 row-span-4 bg-white rounded-3xl p-6 shadow-xl border border-slate-100 bento-card flex items-center gap-5">
                            <div class="w-16 h-16 rounded-2xl bg-uhamka-50 flex items-center justify-center text-3xl">
                                💰
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs font-bold uppercase">Total Hibah</p>
                                <h3 class="text-uhamka-700 font-extrabold text-2xl">Rp 500 Juta+</h3>
                                <div class="flex items-center gap-1 text-xs font-bold text-green-600 mt-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    <span>75% Tersalurkan</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 5: Profile/User -->
                        <div
                            class="col-span-6 row-span-4 bg-gradient-to-br from-uhamka-600 to-uhamka-800 rounded-3xl p-6 shadow-xl text-white bento-card relative overflow-hidden flex flex-col justify-center">
                            <div class="absolute top-0 right-0 p-4 opacity-20">
                                <svg class="w-20 h-20" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5 10 5 10-5-5-2.5-5 2.5z" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-lg mb-1">500+ Proposal</h4>
                            <p class="text-uhamka-100 text-sm mb-4">Telah bergabung semester ini.</p>
                            <div class="flex -space-x-3">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=64&h=64"
                                    class="w-8 h-8 rounded-full border-2 border-uhamka-700 object-cover"
                                    alt="Student">
                                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=64&h=64"
                                    class="w-8 h-8 rounded-full border-2 border-uhamka-700 object-cover"
                                    alt="Student">
                                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=64&h=64"
                                    class="w-8 h-8 rounded-full border-2 border-uhamka-700 object-cover"
                                    alt="Student">
                                <div
                                    class="w-8 h-8 rounded-full border-2 border-uhamka-700 bg-uhamka-500 text-[10px] flex items-center justify-center font-bold">
                                    +400</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Categories Section (Interactive) -->
    <section id="kategori" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <span class="text-uhamka-gold-600 font-bold tracking-widest uppercase text-xs">Bidang Kompetisi</span>
                <h2 class="mt-2 text-4xl font-heading font-bold text-slate-900">Pilih Jalur Kreativitasmu</h2>
                <p class="mt-4 text-slate-500 max-w-2xl mx-auto">Terdapat 5 bidang PKM 5 Bidang dan 3 Bidang PKM Karya
                    Tulis/Artikel. Temukan yang sesuai dengan passsion tim kamu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card PKM-RE -->
                <div
                    class="group relative bg-white rounded-3xl p-1 border border-slate-100 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 reveal delay-100">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-uhamka-gold-400 to-uhamka-600 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur">
                    </div>
                    <div class="relative bg-white rounded-[20px] p-8 h-full overflow-hidden">
                        <div
                            class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-4xl mb-6 group-hover:scale-110 transition-transform duration-300">
                            🔬
                        </div>
                        <h3
                            class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-uhamka-700 transition-colors">
                            PKM-RE</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">Riset Eksakta. Pengamatan mendalam
                            berbasis iptek untuk mengungkap informasi baru di bidang eksakta.</p>
                        <a href="#"
                            class="inline-flex items-center text-sm font-bold text-uhamka-700 md:opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            Lihat Contoh Proposal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Card PKM-RSH -->
                <div
                    class="group relative bg-white rounded-3xl p-1 border border-slate-100 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 reveal delay-200">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-green-400 to-teal-600 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur">
                    </div>
                    <div class="relative bg-white rounded-[20px] p-8 h-full overflow-hidden">
                        <div
                            class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-4xl mb-6 group-hover:scale-110 transition-transform duration-300">
                            ⚖️
                        </div>
                        <h3
                            class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-uhamka-teal-500 transition-colors">
                            PKM-RSH</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">Riset Sosial Humaniora. Upaya pemecahan
                            masalah sosial melalui pendekatan humaniora dan seni.</p>
                        <a href="#"
                            class="inline-flex items-center text-sm font-bold text-uhamka-teal-500 md:opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            Lihat Contoh Proposal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Card PKM-K -->
                <div
                    class="group relative bg-white rounded-3xl p-1 border border-slate-100 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 reveal delay-300">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-orange-400 to-red-500 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur">
                    </div>
                    <div class="relative bg-white rounded-[20px] p-8 h-full overflow-hidden">
                        <div
                            class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-4xl mb-6 group-hover:scale-110 transition-transform duration-300">
                            💼
                        </div>
                        <h3
                            class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-orange-600 transition-colors">
                            PKM-K</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">Kewirausahaan. Pengembangan usaha
                            kreatif yang berorientasi laba dan berbasis iptek.</p>
                        <a href="#"
                            class="inline-flex items-center text-sm font-bold text-orange-600 md:opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            Lihat Contoh Proposal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Card PKM-PM -->
                <div
                    class="group relative bg-white rounded-3xl p-1 border border-slate-100 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 reveal delay-100">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-pink-400 to-purple-500 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur">
                    </div>
                    <div class="relative bg-white rounded-[20px] p-8 h-full overflow-hidden">
                        <div
                            class="w-16 h-16 bg-pink-50 rounded-2xl flex items-center justify-center text-4xl mb-6 group-hover:scale-110 transition-transform duration-300">
                            🤝
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-pink-600 transition-colors">
                            PKM-PM</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">Pengabdian Masyarakat. Solusi iptek bagi
                            mitra non-profit untuk menyelesaikan masalah.</p>
                        <a href="#"
                            class="inline-flex items-center text-sm font-bold text-pink-600 md:opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            Lihat Contoh Proposal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Card PKM-KC -->
                <div
                    class="group relative bg-white rounded-3xl p-1 border border-slate-100 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 reveal delay-200">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-cyan-500 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur">
                    </div>
                    <div class="relative bg-white rounded-[20px] p-8 h-full overflow-hidden">
                        <div
                            class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-4xl mb-6 group-hover:scale-110 transition-transform duration-300">
                            🤖
                        </div>
                        <h3
                            class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">
                            PKM-KC</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">Karsa Cipta. Menghasilkan karya berupa
                            konstruksi karsa yang fungsional.</p>
                        <a href="#"
                            class="inline-flex items-center text-sm font-bold text-indigo-600 md:opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            Lihat Contoh Proposal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Explore More -->
                <div
                    class="group relative bg-slate-50 rounded-3xl p-8 border border-slate-200 border-dashed hover:border-solid hover:border-uhamka-gold-500 transition-all duration-300 flex flex-col items-center justify-center text-center cursor-pointer reveal delay-300">
                    <div
                        class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-2xl mb-4 shadow-sm group-hover:scale-110 transition-transform">
                        📚
                    </div>
                    <h3 class="text-xl font-bold text-slate-400 group-hover:text-uhamka-gold-600 transition-colors">
                        Lihat Semua 8 Bidang</h3>
                    <p class="text-slate-400 text-xs mt-2">Termasuk PKM-PI, PKM-KI, dan PKM-VGK</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Cool Interactive Selection Process (The Golden Path) -->
    <section id="alur" class="py-24 bg-uhamka-950 relative overflow-hidden text-white">
        <!-- Background Decor -->
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-uhamka-900 rounded-full blur-3xl opacity-30 -z-10">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 reveal">
                <span class="text-uhamka-gold-400 font-bold tracking-widest uppercase text-xs animate-pulse">Roadmap to
                    Glory</span>
                <h2 class="mt-2 text-4xl font-heading font-bold">Alur Perjalanan Menuju PIMNAS</h2>
            </div>

            <!-- The Clean Interactive Path -->
            <div class="relative">
                <!-- Track (Background) -->
                <div class="hidden lg:block absolute top-1/2 left-0 w-full h-1 timeline-track -translate-y-1/2 z-0">
                </div>

                <!-- The Clean Line (Progress) -->
                <div
                    class="hidden lg:block absolute top-1/2 left-0 h-1 clean-line -translate-y-1/2 z-0 w-0 transition-all duration-[2000ms] ease-out scroll-trigger-line rounded-full">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-4 relative z-10">

                    <!-- Step 1 -->
                    <div class="group relative perspective-1000 reveal delay-100">
                        <div
                            class="relative z-10 bg-uhamka-900 border border-uhamka-700/50 p-6 rounded-2xl hover:bg-uhamka-800 hover:border-uhamka-gold-500 hover:shadow-[0_0_30px_rgba(212,175,55,0.2)] transition-all duration-300 hover:-translate-y-2 cursor-pointer h-full backdrop-blur-xl">
                            <div
                                class="w-12 h-12 bg-uhamka-950 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-green-500 group-hover:text-white transition-colors border-2 border-uhamka-800 shadow-xl relative z-20 group-hover:scale-110 duration-300">
                                📝
                                <div
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-slate-700 rounded-full text-[10px] flex items-center justify-center border-2 border-uhamka-900 font-mono">
                                    01</div>
                            </div>
                            <h3 class="font-bold text-lg mb-2 group-hover:text-green-400 transition-colors">Pengajuan
                            </h3>
                            <p class="text-uhamka-200 text-xs leading-relaxed">Submit proposalmu sebelum deadline.
                                Pastikan format sesuai panduan.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="group relative mt-8 lg:mt-0 perspective-1000 reveal delay-200">
                        <div
                            class="relative z-10 bg-uhamka-900 border border-uhamka-700/50 p-6 rounded-2xl hover:bg-uhamka-800 hover:border-uhamka-gold-500 hover:shadow-[0_0_30px_rgba(212,175,55,0.2)] transition-all duration-300 hover:-translate-y-2 cursor-pointer h-full backdrop-blur-xl">
                            <div
                                class="w-12 h-12 bg-uhamka-950 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-blue-500 group-hover:text-white transition-colors border-2 border-uhamka-800 shadow-xl relative z-20 group-hover:scale-110 duration-300">
                                🔎
                                <div
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-slate-700 rounded-full text-[10px] flex items-center justify-center border-2 border-uhamka-900 font-mono">
                                    02</div>
                            </div>
                            <h3 class="font-bold text-lg mb-2 group-hover:text-blue-400 transition-colors">Desk Eval
                            </h3>
                            <p class="text-uhamka-200 text-xs leading-relaxed">Seleksi administratif dan substansi oleh
                                reviewer internal.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="group relative perspective-1000 reveal delay-300">
                        <div
                            class="relative z-10 bg-uhamka-900 border border-uhamka-700/50 p-6 rounded-2xl hover:bg-uhamka-800 hover:border-uhamka-gold-500 hover:shadow-[0_0_30px_rgba(212,175,55,0.2)] transition-all duration-300 hover:-translate-y-2 cursor-pointer h-full backdrop-blur-xl">
                            <div
                                class="w-12 h-12 bg-uhamka-950 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-uhamka-gold-500 group-hover:text-white transition-colors border-2 border-uhamka-800 shadow-xl relative z-20 group-hover:scale-110 duration-300">
                                💰
                                <div
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-slate-700 rounded-full text-[10px] flex items-center justify-center border-2 border-uhamka-900 font-mono">
                                    03</div>
                            </div>
                            <h3 class="font-bold text-lg mb-2 group-hover:text-uhamka-gold-400 transition-colors">
                                Pendanaan</h3>
                            <p class="text-uhamka-200 text-xs leading-relaxed">Pengumuman lolos pendanaan DIKTI.
                                Cairkan dana, jalankan program.</p>
                            <div class="absolute top-4 right-4 animate-ping w-2 h-2 bg-uhamka-gold-500 rounded-full">
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="group relative mt-8 lg:mt-0 perspective-1000 reveal delay-100">
                        <div
                            class="relative z-10 bg-uhamka-900 border border-uhamka-700/50 p-6 rounded-2xl hover:bg-uhamka-800 hover:border-uhamka-gold-500 hover:shadow-[0_0_30px_rgba(212,175,55,0.2)] transition-all duration-300 hover:-translate-y-2 cursor-pointer h-full backdrop-blur-xl">
                            <div
                                class="w-12 h-12 bg-uhamka-950 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-purple-500 group-hover:text-white transition-colors border-2 border-uhamka-800 shadow-xl relative z-20 group-hover:scale-110 duration-300">
                                📊
                                <div
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-slate-700 rounded-full text-[10px] flex items-center justify-center border-2 border-uhamka-900 font-mono">
                                    04</div>
                            </div>
                            <h3 class="font-bold text-lg mb-2 group-hover:text-purple-400 transition-colors">PKP2</h3>
                            <p class="text-uhamka-200 text-xs leading-relaxed">Monitoring dan Evaluasi kemajuan
                                pelaksanaan program.</p>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="group relative perspective-1000 reveal delay-300">
                        <div
                            class="relative z-10 bg-gradient-to-br from-uhamka-gold-600 to-uhamka-gold-800 border-2 border-uhamka-gold-400 p-6 rounded-2xl shadow-[0_0_50px_rgba(212,175,55,0.3)] hover:-translate-y-2 transition-transform duration-300 cursor-pointer h-full backdrop-blur-xl overflow-hidden">
                            <!-- Shine Effect on Box -->
                            <div
                                class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/20 to-transparent translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-1000">
                            </div>

                            <div
                                class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-2xl mb-4 text-uhamka-gold-600 border-4 border-uhamka-gold-300 shadow-xl relative z-20">
                                🏆
                                <div
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-white text-uhamka-900 rounded-full text-[10px] flex items-center justify-center border-2 border-uhamka-gold-600 font-mono">
                                    05</div>
                            </div>
                            <h3 class="font-bold text-xl mb-2 text-white">PIMNAS</h3>
                            <p class="text-uhamka-gold-50 text-xs leading-relaxed">Puncak kompetisi nasional. Rebut
                                Medali Emas untuk UHAMKA!</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- News Section (Berita PIMNAS) -->
    <section id="berita" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-heading font-bold text-slate-900">Kabar PIMNAS</h2>
                    <p class="mt-2 text-slate-500">Berita terbaru seputar prestasi dan kegiatan PKM UHAMKA.</p>
                </div>
                <a href="#"
                    class="hidden md:flex items-center gap-2 font-bold text-uhamka-700 hover:text-uhamka-900 transition-colors">
                    Lihat Semua Berita <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- News 1 -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group reveal">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=600&q=80"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="News">
                        <span
                            class="absolute top-4 left-4 bg-uhamka-700 text-white text-[10px] font-bold px-3 py-1 rounded-full">PRESTASI</span>
                    </div>
                    <div class="p-6">
                        <div class="text-xs text-slate-400 mb-2 font-bold">20 Oktober 2024</div>
                        <h3
                            class="font-bold text-lg text-slate-900 mb-3 group-hover:text-uhamka-700 transition-colors line-clamp-2">
                            Tim Farmasi UHAMKA Raih Emas di PIMNAS ke-37 UNAIR</h3>
                        <p class="text-slate-500 text-sm line-clamp-3 mb-4">Tim Program Kreativitas Mahasiswa Riset
                            Eksakta (PKM-RE) Fakultas Farmasi dan Sains Uhamka berhasil menorehkan prestasi gemilang...
                        </p>
                        <a href="#"
                            class="text-sm font-bold text-uhamka-700 underline decoration-2 underline-offset-4">Baca
                            Selengkapnya</a>
                    </div>
                </div>

                <!-- News 2 -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group reveal delay-100">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=600&q=80"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="News">
                        <span
                            class="absolute top-4 left-4 bg-uhamka-gold-500 text-white text-[10px] font-bold px-3 py-1 rounded-full">SOSIALISASI</span>
                    </div>
                    <div class="p-6">
                        <div class="text-xs text-slate-400 mb-2 font-bold">15 September 2024</div>
                        <h3
                            class="font-bold text-lg text-slate-900 mb-3 group-hover:text-uhamka-700 transition-colors line-clamp-2">
                            Sosialisasi PKM 2025: Strategi Jitu Lolos Pendanaan</h3>
                        <p class="text-slate-500 text-sm line-clamp-3 mb-4">Ratusan mahasiswa memadati Aula Ahmad
                            Dahlan untuk mengikuti sosialisasi kiat sukses menembus pendanaan PKM tahun 2025...</p>
                        <a href="#"
                            class="text-sm font-bold text-uhamka-700 underline decoration-2 underline-offset-4">Baca
                            Selengkapnya</a>
                    </div>
                </div>

                <!-- News 3 -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group reveal delay-200">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1544531586-fde5298cdd40?auto=format&fit=crop&w=600&q=80"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="News">
                        <span
                            class="absolute top-4 left-4 bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-full">WORSHOP</span>
                    </div>
                    <div class="p-6">
                        <div class="text-xs text-slate-400 mb-2 font-bold">10 September 2024</div>
                        <h3
                            class="font-bold text-lg text-slate-900 mb-3 group-hover:text-uhamka-700 transition-colors line-clamp-2">
                            Workshop Penulisan Proposal PKM 5 Bidang</h3>
                        <p class="text-slate-500 text-sm line-clamp-3 mb-4">Biro Kemahasiswaan menyelenggarakan
                            coaching clinic intensif bagi mahasiswa yang berminat mengajukan proposal...</p>
                        <a href="#"
                            class="text-sm font-bold text-uhamka-700 underline decoration-2 underline-offset-4">Baca
                            Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Info Section -->
    <section id="daftar"
        class="py-24 bg-gradient-to-br from-uhamka-900 via-uhamka-800 to-uhamka-900 relative overflow-hidden">
        <!-- Background Effects -->
        <div
            class="absolute top-0 right-0 -mr-32 -mt-32 w-64 h-64 bg-uhamka-gold-400 rounded-full opacity-10 blur-3xl">
        </div>
        <div class="absolute bottom-0 left-0 -ml-32 -mb-32 w-64 h-64 bg-blue-400 rounded-full opacity-10 blur-3xl">
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <span class="text-uhamka-gold-400 font-bold tracking-widest uppercase text-xs">Akses Sistem</span>
                <h2 class="mt-2 text-4xl font-heading font-bold text-white">Cara Mendaftar PKM Center</h2>
                <p class="mt-4 text-uhamka-100 max-w-2xl mx-auto">
                    Dapatkan akses untuk mengajukan proposal PKM dan mengikuti seluruh program kreativitas mahasiswa
                    UHAMKA
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div
                    class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20 text-center group hover:bg-white/15 transition-all">
                    <div
                        class="w-16 h-16 bg-uhamka-gold-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Hubungi Admin</h3>
                    <p class="text-uhamka-100 text-sm leading-relaxed mb-6">
                        Mahasiswa UHAMKA dapat menghubungi Biro Kemahasiswaan atau admin PKM Center untuk pendaftaran
                        akun
                    </p>
                    <a href="mailto:kemahasiswaan@uhamka.ac.id"
                        class="inline-flex items-center gap-2 text-uhamka-gold-400 font-semibold text-sm hover:text-uhamka-gold-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Email Admin
                    </a>
                </div>

                <!-- Step 2 -->
                <div
                    class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20 text-center group hover:bg-white/15 transition-all">
                    <div
                        class="w-16 h-16 bg-uhamka-gold-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Verifikasi Data</h3>
                    <p class="text-uhamka-100 text-sm leading-relaxed mb-6">
                        Siapkan data diri: NIM, Nama Lengkap, Program Studi, dan Email Aktif untuk proses verifikasi
                    </p>
                    <div class="inline-flex items-center gap-2 text-uhamka-gold-400 font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Data Valid
                    </div>
                </div>

                <!-- Step 3 -->
                <div
                    class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20 text-center group hover:bg-white/15 transition-all">
                    <div
                        class="w-16 h-16 bg-uhamka-gold-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Mulai Ajukan</h3>
                    <p class="text-uhamka-100 text-sm leading-relaxed mb-6">
                        Setelah akun aktif, login dan mulai ajukan proposal PKM sesuai bidang yang diminati
                    </p>
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 text-uhamka-gold-400 font-semibold text-sm hover:text-uhamka-gold-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 0v3a2 2 0 01-2 2H9a2 2 0 01-2-2v-3z" />
                        </svg>
                        Login Sekarang
                    </a>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="mt-16 text-center">
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10">
                    <h3 class="text-xl font-bold text-white mb-4">Kontak Bantuan</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-uhamka-gold-400 mb-2">Biro Kemahasiswaan UHAMKA</h4>
                            <p class="text-sm text-uhamka-100">kemahasiswaan@uhamka.ac.id</p>
                            <p class="text-sm text-uhamka-100">Telp: (021) 8400941</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-uhamka-gold-400 mb-2">PKM Center Support</h4>
                            <p class="text-sm text-uhamka-100">pkm.center@uhamka.ac.id</p>
                            <p class="text-sm text-uhamka-100">WhatsApp: 0812-3456-7890</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive FAQ Section -->
    <section id="faq" class="py-24 bg-white relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <span class="text-uhamka-gold-600 font-bold tracking-widest uppercase text-xs">Pusat Bantuan</span>
                <h2 class="mt-2 text-4xl font-heading font-bold text-slate-900">Pertanyaan Umum (FAQ)</h2>
                <p class="mt-4 text-slate-500">Temukan jawaban atas pertanyaan yang sering diajukan seputar PKM UHAMKA.
                </p>
            </div>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <div
                    class="group border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300 hover:border-uhamka-gold-500 reveal delay-100">
                    <button
                        class="w-full flex justify-between items-center p-6 text-left bg-white hover:bg-slate-50 focus:outline-none faq-toggle">
                        <span
                            class="font-bold text-lg text-slate-900 group-hover:text-uhamka-700 transition-colors">Siapa
                            yang boleh mendaftar PKM?</span>
                        <span class="transform transition-transform duration-300 text-uhamka-gold-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div
                        class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out bg-slate-50 faq-content">
                        <div class="p-6 pt-0 text-slate-600 leading-relaxed border-t border-slate-100 mt-2">
                            Seluruh mahasiswa aktif Universitas Muhammadiyah Prof. DR. HAMKA (Jenjang S1/D4/D3) dari
                            semua fakultas diperbolehkan mendaftar. Proposal dapat diajukan secara berkelompok (3-5
                            orang).
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div
                    class="group border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300 hover:border-uhamka-gold-500 reveal delay-200">
                    <button
                        class="w-full flex justify-between items-center p-6 text-left bg-white hover:bg-slate-50 focus:outline-none faq-toggle">
                        <span
                            class="font-bold text-lg text-slate-900 group-hover:text-uhamka-700 transition-colors">Bagaimana
                            cara mengajukan proposal?</span>
                        <span class="transform transition-transform duration-300 text-uhamka-gold-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div
                        class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out bg-slate-50 faq-content">
                        <div class="p-6 pt-0 text-slate-600 leading-relaxed border-t border-slate-100 mt-2">
                            Anda dapat mengajukan proposal melalui tombol "Masuk" di pojok kanan atas. Login menggunakan
                            akun SSO UHAMKA, lalu pilih menu "Pengajuan Proposal" di dashboard mahasiswa. Pastikan
                            proposal sesuai template pedoman terbaru.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div
                    class="group border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300 hover:border-uhamka-gold-500 reveal delay-300">
                    <button
                        class="w-full flex justify-between items-center p-6 text-left bg-white hover:bg-slate-50 focus:outline-none faq-toggle">
                        <span
                            class="font-bold text-lg text-slate-900 group-hover:text-uhamka-700 transition-colors">Apakah
                            ada pendanaan untuk proposal yang lolos?</span>
                        <span class="transform transition-transform duration-300 text-uhamka-gold-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div
                        class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out bg-slate-50 faq-content">
                        <div class="p-6 pt-0 text-slate-600 leading-relaxed border-t border-slate-100 mt-2">
                            Ya, proposal yang lolos seleksi internal dan didanai oleh Kemendikbudristek akan mendapatkan
                            insentif pendanaan mulai dari Rp 6.000.000 hingga Rp 10.000.000 per judul (sesuai skema
                            tahun berjalan).
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div
                    class="group border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300 hover:border-uhamka-gold-500 reveal delay-100">
                    <button
                        class="w-full flex justify-between items-center p-6 text-left bg-white hover:bg-slate-50 focus:outline-none faq-toggle">
                        <span
                            class="font-bold text-lg text-slate-900 group-hover:text-uhamka-700 transition-colors">Apa
                            manfaat mengikuti PKM?</span>
                        <span class="transform transition-transform duration-300 text-uhamka-gold-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div
                        class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out bg-slate-50 faq-content">
                        <div class="p-6 pt-0 text-slate-600 leading-relaxed border-t border-slate-100 mt-2">
                            Selain pendanaan, mahasiswa mendapatkan konversi SKS (MBKM), pengalaman riset/kewirausahaan,
                            sertifikat penghargaan, dan kesempatan tampil di ajang nasional PIMNAS yang bergengsi.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer (Academic) -->
    <footer class="bg-uhamka-950 text-white py-16 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-12 h-12 bg-white rounded flex items-center justify-center text-uhamka-900 font-serif font-bold text-2xl">
                            U</div>
                        <div>
                            <h5 class="font-bold text-xl">UHAMKA</h5>
                            <p class="text-uhamka-400 text-sm">Universitas Muhammadiyah Prof. DR. HAMKA</p>
                        </div>
                    </div>
                    <p class="text-uhamka-300 text-sm leading-relaxed max-w-sm mb-6">
                        Kampus Islami, Unggul, dan Berkemajuan. PKM Center adalah unit di bawah Biro Kemahasiswaan yang
                        berdedikasi mengembangkan kultur akademik dan inovasi.
                    </p>
                    <div class="flex gap-4">
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 hover:bg-uhamka-gold-500 hover:text-white flex items-center justify-center transition-all">IG</a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 hover:bg-uhamka-gold-500 hover:text-white flex items-center justify-center transition-all">YT</a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 hover:bg-uhamka-gold-500 hover:text-white flex items-center justify-center transition-all">TW</a>
                    </div>
                </div>

                <div>
                    <h5 class="font-bold text-lg mb-6 text-white">Tautan Penting</h5>
                    <ul class="space-y-4 text-sm text-uhamka-300">
                        <li><a href="#" class="hover:text-uhamka-gold-400 transition-colors">Simbelmawa
                                DIKTI</a></li>
                        <li><a href="#" class="hover:text-uhamka-gold-400 transition-colors">Panduan Umum
                                PKM</a></li>
                        <li><a href="#" class="hover:text-uhamka-gold-400 transition-colors">Kemahasiswaan
                                UHAMKA</a></li>
                        <li><a href="#" class="hover:text-uhamka-gold-400 transition-colors">Klinik Proposal</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-bold text-lg mb-6 text-white">Kontak Kami</h5>
                    <ul class="space-y-4 text-sm text-uhamka-300">
                        <li class="flex items-start gap-3">
                            <span>📍</span>
                            <span>Jl. Limau II, Kebayoran Baru, Jakarta Selatan</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span>📞</span>
                            <span>(021) 7208177</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span>✉️</span>
                            <span>pkm@uhamka.ac.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-white/10 pt-8 text-center text-xs text-uhamka-500 flex flex-col md:flex-row justify-between items-center">
                <p>&copy; {{ date('Y') }} PKM Center UHAMKA. All rights reserved.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white">Privacy Policy</a>
                    <a href="#" class="hover:text-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Clean Scroll Animation Trigger
        document.addEventListener('scroll', () => {
            const line = document.querySelector('.scroll-trigger-line');
            const section = document.getElementById('alur');

            if (section && line) {
                const rect = section.getBoundingClientRect();
                // Trigger earlier for better UX
                if (rect.top < window.innerHeight * 0.75 && rect.bottom > 0) {
                    line.style.width = '100%';
                }
            }
        });

        // Intersection Observer for Scroll Reveal
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => {
            observer.observe(el);
        });

        // FAQ Accordion Logic
        document.querySelectorAll('.faq-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const icon = button.querySelector('svg');

                // Toggle current
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                    icon.classList.remove('rotate-180');
                } else {
                    // Close others (Optional - remove if you want multiple open)
                    document.querySelectorAll('.faq-content').forEach(c => c.style.maxHeight = null);
                    document.querySelectorAll('.faq-toggle svg').forEach(i => i.classList.remove(
                        'rotate-180'));

                    content.style.maxHeight = content.scrollHeight + "px";
                    icon.classList.add('rotate-180');
                }
            });
        });
    </script>
</body>

</html>
