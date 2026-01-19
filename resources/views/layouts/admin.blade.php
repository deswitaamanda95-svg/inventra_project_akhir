<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'INVENTRA') }} - Panel Kendali Utama</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-width: 280px;
            --navbar-height: 75px;
            --primary-blue: #2563eb;
            /* Vibrant Blue */
            --sidebar-dark: #0f172a;
            /* Deep Navy */
            --bg-light: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #f1f5f9;
        }

        /* Menghilangkan scroll bar secara global */
        ::-webkit-scrollbar {
            display: none;
        }

        html,
        body {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* --- SWEETALERT2 CUSTOM STYLES --- */
        div:where(.swal2-container) div:where(.swal2-popup) {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }

        .inventra-toast-popup {
            border-radius: 16px !important;
            border: 1px solid var(--border-color) !important;
        }

        .inventra-modal-popup {
            border-radius: 24px !important;
            padding: 2.5rem !important;
        }

        /* --- LAYOUT STYLES --- */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR PREMIUM */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            z-index: 999;
        }

        .sidebar-brand {
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            padding: 0 30px;
            margin-bottom: 20px;
        }

        .brand-text {
            color: white;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.05em;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-dot {
            width: 10px;
            height: 10px;
            background: var(--primary-blue);
            border-radius: 4px;
            rotate: 45deg;
        }

        /* NAVIGATION LINKS */
        .nav-list {
            padding: 10px 20px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin: 30px 15px 12px;
            display: block;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            border-radius: 14px;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-link svg {
            width: 20px;
            height: 20px;
            margin-right: 14px;
            stroke: currentColor;
            stroke-width: 2.2;
            fill: none;
            opacity: 0.6;
        }

        .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }

        .nav-link.active {
            background-color: var(--primary-blue);
            color: white;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
        }

        .nav-link.active svg {
            opacity: 1;
        }

        /* MAIN CONTAINER & NAVBAR GLASSMORPHISM */
        .main-container {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
            width: calc(100% - var(--sidebar-width));
        }

        .navbar {
            height: var(--navbar-height);
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .breadcrumb {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .breadcrumb b {
            color: var(--primary-blue);
            font-weight: 800;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .user-profile-link {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            padding-right: 25px;
            border-right: 1px solid var(--border-color);
        }

        .user-name {
            font-weight: 800;
            font-size: 14px;
            color: var(--text-main);
            display: block;
        }

        .user-role {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.1em;
        }

        .user-avatar-nav {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            overflow: hidden;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .logout-btn {
            background: transparent;
            border: 1.5px solid #fee2e2;
            color: #dc2626;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.2s;
            letter-spacing: 0.05em;
        }

        .logout-btn:hover {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
            box-shadow: 0 8px 15px rgba(220, 38, 38, 0.2);
        }

        .page-content {
            padding: 40px;
            width: 100%;
            box-sizing: border-box;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .main-container {
                margin-left: 0;
                width: 100%;
            }

            .navbar {
                padding: 0 25px;
            }

            .user-info,
            .breadcrumb {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="admin-wrapper">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- SIDEBAR PREMIUM DESIGN --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-text">
                    <div class="brand-dot"></div>
                    INVENTRA
                </div>
            </div>

            <nav class="nav-list">
                <span class="nav-label">Menu Utama</span>
                @php $dashboardRoute = Auth::user()->role == 'admin' ? 'admin.dashboard' : 'user.dashboard'; @endphp
                <a href="{{ route($dashboardRoute) }}"
                    class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard
                </a>

                @if (Auth::user()->role == 'admin')
                    <span class="nav-label">Manajemen Inventaris</span>
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z">
                            </path>
                        </svg>
                        Kategori Barang
                    </a>
                    <a href="{{ route('admin.items.index') }}"
                        class="nav-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 8l-2-2H5L3 8v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z"></path>
                            <path d="M3 10h18"></path>
                            <path d="M10 14h4"></path>
                        </svg>
                        Daftar Aset
                    </a>
                    <a href="{{ route('admin.maintenance.index') }}"
                        class="nav-link {{ request()->routeIs('admin.maintenance.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z">
                            </path>
                        </svg>
                        Log Pemeliharaan
                    </a>

                    <span class="nav-label">Administrasi Sistem</span>
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Akun Pengguna
                    </a>
                    <a href="{{ route('admin.loans.index') }}"
                        class="nav-link {{ request()->routeIs('admin.loans.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        Catatan Peminjaman
                        @if (isset($pendingRequests) && $pendingRequests > 0)
                            <span class="sidebar-badge"
                                style="background:#2563eb; padding:2px 10px; border-radius:8px; font-size:9px; margin-left:auto; color:white;">{{ $pendingRequests }}</span>
                        @endif
                    </a>
                @else
                    <span class="nav-label">Layanan Peminjam</span>
                    <a href="{{ route('user.items.index') }}"
                        class="nav-link {{ request()->routeIs('user.items.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 8l-2-2H5L3 8v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z"></path>
                            <path d="M3 10h18"></path>
                        </svg>
                        Katalog Barang
                    </a>
                    <a href="{{ route('user.loans.index') }}"
                        class="nav-link {{ request()->routeIs('user.loans.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                        </svg>
                        Pinjaman Saya
                    </a>
                @endif

                <span class="nav-label">Pengaturan</span>
                <a href="{{ route('profile.edit') }}"
                    class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Profil Saya
                </a>
            </nav>
        </aside>

        {{-- MAIN CONTAINER --}}
        <main class="main-container">
            <header class="navbar">
                <div style="display: flex; align-items: center;">
                    {{-- TOMBOL MOBILE-TOGGLE DIHAPUS (HILANGKAN GARIS TIGA) --}}
                    <div class="breadcrumb">
                        Sistem / <b>{{ ucfirst(request()->segment(1)) }}</b> /
                        {{ ucfirst(request()->segment(2) ?? 'Dashboard') }}
                    </div>
                </div>

                <div class="navbar-actions">
                    @if (Auth::user()->role == 'admin')
                        <a href="{{ route('admin.loans.index', ['status' => 'pending']) }}" class="nav-notification"
                            title="Persetujuan Tertunda" style="position:relative; color: #64748b;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            @if (isset($pendingRequests) && $pendingRequests > 0)
                                <span
                                    style="position:absolute; top:-4px; right:-4px; background:#ef4444; border:2px solid #fff; border-radius:50%; width:10px; height:10px;"></span>
                            @endif
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="user-profile-link">
                        <div class="user-info" style="text-align: right; line-height: 1.2;">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span
                                class="user-role">{{ Auth::user()->role == 'admin' ? 'Administrator' : 'Pengguna' }}</span>
                        </div>
                        <div class="user-avatar-nav">
                            @if (Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                                    style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <span
                                    style="font-weight:800; color:var(--primary-blue);">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">
                            KELUAR
                        </button>
                    </form>
                </div>
            </header>

            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        {{-- SCRIPT TOGGLE SIDEBAR TETAP ADA UNTUK MEDIA QUERY TABLET/MOBILE --}}

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }
        document.getElementById('sidebarOverlay').addEventListener('click', toggleSidebar);

        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                document.getElementById('sidebar').classList.remove('active');
                document.getElementById('sidebarOverlay').classList.remove('active');
            }
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'inventra-toast-popup',
                title: 'inventra-toast-title'
            }
        });

        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif
        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete-trigger');
            if (deleteBtn) {
                e.preventDefault();
                const form = deleteBtn.closest('form');
                Swal.fire({
                    title: 'Konfirmasi Penghapusan',
                    text: "Data akan dihapus permanen dari sistem Intelligence INVENTRA.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0f172a',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Hapus Sekarang',
                    cancelButtonText: 'Batalkan',
                    reverseButtons: true,
                    customClass: {
                        popup: 'inventra-modal-popup'
                    }
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
