<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - PKM Center UHAMKA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 selection:bg-uhamka-gold selection:text-white">

    <div class="w-full max-w-6xl mx-auto flex bg-white rounded-[2rem] shadow-2xl overflow-hidden min-h-[600px] border border-slate-100">
        
        <!-- Left Side: Visual Brand (40%) -->
        <div class="hidden lg:flex lg:w-5/12 bg-uhamka-900 relative flex-col justify-between p-12 overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-uhamka-800 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-60 h-60 bg-uhamka-gold-600 rounded-full blur-3xl opacity-20"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-uhamka-900/90 to-uhamka-900/40 z-10"></div>
            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80" class="absolute inset-0 w-full h-full object-cover grayscale opacity-30" alt="University Vibe">

            <!-- Content -->
            <div class="relative z-20">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-uhamka-900 font-serif font-bold text-2xl shadow-lg mb-6">U</div>
                <h2 class="text-3xl font-extrabold text-white leading-tight">
                    Academic Innovation Hub
                </h2>
                <p class="text-uhamka-200 mt-4 text-sm leading-relaxed">
                    Bergabunglah dengan ribuan mahasiswa inovatif UHAMKA lainnya. Wujudkan ide kreatifmu menuju PIMNAS 2025.
                </p>
            </div>

            <div class="relative z-20">
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10">
                    <div class="text-4xl">🏆</div>
                    <div>
                        <p class="text-uhamka-gold-400 text-xs font-bold uppercase tracking-wider">Target 2025</p>
                        <p class="text-white font-bold">Juara Umum PIMNAS</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form (60%) -->
        <div class="w-full lg:w-7/12 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">
                
                <div class="text-left mb-10">
                    <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Selamat Datang Kembali!</h1>
                    <p class="text-slate-500">Silakan masuk untuk mengakses Dashboard PKM.</p>
                </div>

                <!-- Error Handling -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-100 text-red-600 p-4 rounded-xl text-sm flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-100 text-red-600 p-4 rounded-xl text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                <form class="space-y-6" method="POST" action="{{ route('login.process') }}">
                    @csrf
                    
                    <div>
                    <div>
                        <label for="nomor_induk" class="block text-sm font-bold text-slate-700 mb-2">Nomor Induk (NIM / NIDN)</label>
                        <div class="flex gap-4">
                            <select name="type" class="w-1/3 px-4 py-3 rounded-xl border border-slate-200 focus:border-uhamka-700 focus:ring-4 focus:ring-uhamka-700/10 transition-all outline-none text-slate-900 font-medium">
                                <option value="nim">NIM</option>
                                <option value="nidn">NIDN</option>
                            </select>
                            <input type="text" name="nomor_induk" id="nomor_induk" required class="w-2/3 px-4 py-3 rounded-xl border border-slate-200 focus:border-uhamka-700 focus:ring-4 focus:ring-uhamka-700/10 transition-all outline-none text-slate-900 placeholder-slate-400 font-medium" placeholder="Contoh: 12345678">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                        <input type="password" name="password" id="password" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-uhamka-700 focus:ring-4 focus:ring-uhamka-700/10 transition-all outline-none text-slate-900 placeholder-slate-400 font-medium" placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded text-uhamka-700 border-slate-300 focus:ring-uhamka-700">
                            <label for="remember" class="ml-2 block text-sm text-slate-600 font-medium">Ingat Saya</label>
                        </div>
                        <a href="#" class="text-xs font-bold text-uhamka-600 hover:text-uhamka-800 transition-colors">Lupa Password?</a>
                    </div>

                    <div class="space-y-4 pt-4">
                        <button type="submit" class="w-full py-3 px-4 bg-uhamka-700 hover:bg-uhamka-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            Masuk
                        </button>
                        
                        <a href="{{ url('/') }}" class="w-full block text-center py-3 px-4 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all">
                            Kembali ke Beranda
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>
