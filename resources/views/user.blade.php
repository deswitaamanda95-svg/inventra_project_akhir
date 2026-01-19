<nav class="space-y-2">

    {{-- Beranda --}}
    <a href="{{ route('user.dashboard') }}"
       class="
           block px-3 py-2 rounded
           {{ request()->routeIs('user.dashboard')
                ? 'bg-slate-700 font-semibold'
                : 'hover:bg-slate-700' }}
       ">
        Beranda
    </a>

    {{-- Pinjaman Saya --}}
    <a href="{{ route('user.loans') }}"
       class="
           block px-3 py-2 rounded
           {{ request()->routeIs('user.loans')
                ? 'bg-slate-700 font-semibold'
                : 'hover:bg-slate-700' }}
       ">
        Pinjaman Saya
    </a>

    {{-- Katalog Barang --}}
    <a href="{{ route('user.items') }}"
       class="
           block px-3 py-2 rounded
           {{ request()->routeIs('user.items')
                ? 'bg-slate-700 font-semibold'
                : 'hover:bg-slate-700' }}
       ">
        Katalog Barang
    </a>

</nav>