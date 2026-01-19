<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - INVENTRA Intelligence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-immersive { background: radial-gradient(circle at top right, #1e40af, #0f172a); background-attachment: fixed; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .input-focus-effect:focus { box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); border-color: #3b82f6; }
    </style>
</head>
<body class="bg-immersive min-h-screen flex items-center justify-center p-4 md:p-10">
    <div class="fixed top-0 left-0 w-80 h-80 bg-blue-600 rounded-full blur-[120px] opacity-10 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>

    <div class="relative w-full max-w-5xl flex flex-col md:flex-row items-center justify-center gap-10 md:gap-16 py-8">
        <div class="w-full md:w-1/2 text-white z-10 text-center md:text-left">
            <div class="inline-flex items-center gap-3 mb-8">
                <div class="p-2.5 bg-blue-600 rounded-xl shadow-xl shadow-blue-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <span class="text-2xl font-black tracking-tighter uppercase">INVENTRA</span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold leading-[1.15] mb-4 tracking-tight">Kelola Aset Kantor <br><span class="text-blue-400">Lebih Cerdas & Terukur.</span></h1>
            <p class="text-sm md:text-base text-slate-300 leading-relaxed max-w-sm mb-8 mx-auto md:mx-0">Optimalkan sirkulasi inventaris perusahaan dengan ekosistem pelacakan real-time.</p>
        </div>

        <div class="w-full max-w-[420px] relative">
            <div class="bg-white rounded-[28px] p-8 md:p-10 shadow-2xl relative z-20">
                {{-- Bagian Header Kartu: Dibuat text-center saja agar selalu di tengah --}}
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-extrabold text-slate-900 mb-1.5 tracking-tight">Selamat Datang</h2>
                    <p class="text-slate-500 font-semibold text-xs uppercase tracking-widest">Masuk ke akun resmi Anda</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf 
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@perusahaan.com" 
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 text-sm font-semibold focus:outline-none input-focus-effect transition-all">
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5 ml-1">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest">Kata Sandi</label>
                            <a href="#" class="text-[11px] font-bold text-blue-600 hover:underline">Lupa?</a>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••" 
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 text-sm font-bold tracking-[0.2em] focus:outline-none input-focus-effect transition-all">
                    </div>

                    @if ($errors->any())
                        <p class="text-red-500 text-[11px] font-bold mt-2 ml-1 italic">{{ $errors->first() }}</p>
                    @endif

                    <div class="flex items-center gap-2.5 py-1">
                        <input type="checkbox" name="remember" id="remember" class="w-4.5 h-4.5 rounded border-slate-300 text-blue-600 cursor-pointer">
                        <label for="remember" class="text-xs font-bold text-slate-600 cursor-pointer tracking-tight">Remember Me</label>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 hover:bg-blue-700 text-white font-extrabold py-4 rounded-2xl shadow-xl transition-all transform active:scale-[0.97] flex items-center justify-center gap-3 text-sm mt-2">
                        Login <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="3"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>