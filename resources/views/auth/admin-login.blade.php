<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Masuk - PKM Center UHAMKA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 selection:bg-uhamka-gold selection:text-white">

    <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl overflow-hidden border border-slate-100 p-8 sm:p-12">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Admin Portal</h1>
            <p class="text-slate-500">Silakan masuk sebagai Administrator.</p>
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

        <form class="space-y-6" method="POST" action="{{ route('admin.login.process') }}">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-uhamka-700 focus:ring-4 focus:ring-uhamka-700/10 transition-all outline-none text-slate-900 placeholder-slate-400 font-medium" placeholder="nama@uhamka.ac.id">
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
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-uhamka-700 hover:bg-uhamka-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                Masuk sebagai Admin
            </button>
            
            <a href="{{ url('/') }}" class="w-full block text-center py-3 px-4 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all mt-4">
                Kembali ke Beranda
            </a>
        </form>

    </div>

</body>
</html>
