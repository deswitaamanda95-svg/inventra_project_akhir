<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Pengguna - INVENTRA</title>
    
    {{-- Menggunakan Font Inter agar senada dengan Admin --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; margin: 0; background-color: #f8fafc; color: #1e293b; 
        }
        .layout { display: flex; min-height: 100vh; }

        /* --- Sidebar Pengguna --- */
        .sidebar { 
            width: 260px; background-color: #0f172a; color: white; padding: 30px 20px; 
            display: flex; flex-direction: column; 
        }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar li { margin-bottom: 8px; }
        
        .sidebar a, .sidebar button {
            display: block; width: 100%; padding: 12px 16px; color: #94a3b8; 
            text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: 600; 
            transition: 0.2s; border: none; background: transparent; text-align: left; cursor: pointer;
        }

        .sidebar li.active a { background-color: #2563eb; color: white; }
        .sidebar a:hover, .sidebar button:hover { background-color: rgba(255,255,255,0.05); color: white; }

        /* --- Konten Utama --- */
        .content { flex-grow: 1; padding: 40px; box-sizing: border-box; }

        /* Gaya Khusus Tombol Keluar */
        .btn-logout { color: #f87171 !important; margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px !important; }
        .btn-logout:hover { background-color: rgba(239, 68, 68, 0.1) !important; }
    </style>
</head>
<body>

<div class="layout">

    {{-- SIDEBAR PENGGUNA --}}
    <aside class="sidebar">
        <div style="font-size: 20px; font-weight: 800; margin-bottom: 40px; padding-left: 15px;">
            INVENTRA
        </div>
        
        <ul>
            <li class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <a href="{{ route('user.dashboard') }}">Beranda</a>
            </li>

            <li class="{{ request()->routeIs('user.items') ? 'active' : '' }}">
                <a href="{{ route('user.items') }}">Katalog Barang</a>
            </li>

            <li class="{{ request()->routeIs('user.loans') ? 'active' : '' }}">
                <a href="{{ route('user.loans') }}">Pinjaman Saya</a>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Keluar Akun</button>
                </form>
            </li>
        </ul>
    </aside>

    {{-- KONTEN HALAMAN --}}
    <main class="content">
        @yield('content')
    </main>

</div>

</body>
</html>