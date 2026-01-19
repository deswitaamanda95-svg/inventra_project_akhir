@extends('layouts.admin') {{-- Pastikan menggunakan layout yang sesuai --}}

@section('content')
<style>
    /* --- Fondasi Tata Letak --- */
    .pembungkus-halaman { display: flex; justify-content: center; padding: 20px 0 60px 0; }
    
    .kartu-rincian { 
        background: white; width: 100%; max-width: 950px; border-radius: 28px; 
        display: flex; gap: 50px; padding: 50px; border: 1px solid #f1f5f9;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);
    }
    
    .area-visual { flex: 1; max-width: 420px; }
    .area-informasi { flex: 1.2; }

    /* --- Komponen Visual --- */
    .kotak-foto { 
        width: 100%; height: 420px; border-radius: 20px; overflow: hidden; 
        background: #f8fafc; border: 1px solid #f1f5f9; 
    }
    .kotak-foto img { width: 100%; height: 100%; object-fit: cover; }

    .tag-lokasi {
        display: inline-flex; align-items: center; gap: 8px; background: #eff6ff;
        color: #2563eb; padding: 8px 16px; border-radius: 12px; font-size: 11px;
        font-weight: 800; text-transform: uppercase; margin-bottom: 25px;
        border: 1px solid #dbeafe;
    }

    /* --- Metadata Grid Sistematis --- */
    .grid-meta { 
        display: grid; grid-template-columns: 1fr 1fr; gap: 30px; 
        margin: 35px 0; padding: 30px 0; border-top: 1px dashed #e2e8f0; border-bottom: 1px dashed #e2e8f0; 
    }
    .label-meta { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px; display: block; letter-spacing: 0.05em; }
    .nilai-meta { font-size: 16px; font-weight: 700; color: #1e293b; }

    /* --- Tombol Navigasi & Aksi --- */
    .btn-kembali { 
        text-decoration: none; color: #94a3b8; font-size: 13px; font-weight: 700; 
        display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;
        margin-bottom: 30px;
    }
    .btn-kembali:hover { color: #2563eb; transform: translateX(-3px); }

    .btn-pinjam-utama { 
        width: 100%; background: #0f172a; color: white; padding: 20px; border: none; 
        border-radius: 18px; font-size: 15px; font-weight: 800; cursor: pointer; 
        transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 12px;
        box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.2);
    }
    .btn-pinjam-utama:hover:not(:disabled) { background: #2563eb; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); }
    .btn-pinjam-utama:disabled { background: #f1f5f9; color: #cbd5e1; cursor: not-allowed; box-shadow: none; }
</style>

<a href="{{ route('user.items.index') }}" class="btn-kembali">
    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
    KEMBALI KE KATALOG
</a>

<div class="pembungkus-halaman">
    <div class="kartu-rincian">
        {{-- Sisi Kiri: Dokumentasi Visual --}}
        <div class="area-visual">
            <div class="kotak-foto">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                @else
                    <div style="height:100%; display:flex; align-items:center; justify-content:center; color:#cbd5e1;">
                        <svg width="80" height="80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sisi Kanan: Informasi Komprehensif --}}
        <div class="area-informasi">
            <div class="tag-lokasi">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                Lokasi: {{ $item->room ?? 'Gudang Pusat' }}
            </div>

            <h1 style="font-size: 36px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.04em; line-height: 1.1;">{{ $item->name }}</h1>
            <p style="color: #64748b; font-size: 15px; margin-top: 12px; font-weight: 500;">
                Klasifikasi: <span style="color: #2563eb; font-weight: 700;">{{ $item->category->name ?? 'Umum' }}</span>
                <span style="margin: 0 12px; color: #e2e8f0;">|</span>
                ID Aset: <span style="font-family: 'JetBrains Mono', monospace; font-weight: 600;">#ASET-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
            </p>

            <div class="grid-meta">
                <div class="meta-box">
                    <span class="label-meta">Kondisi Fisik</span>
                    @php 
                        $color = $item->condition == 'Good' ? '#16a34a' : ($item->condition == 'Repair' ? '#d97706' : '#dc2626');
                    @endphp
                    <span class="nilai-meta" style="color: {{ $color }};">{{ strtoupper($item->condition) }}</span>
                </div>
                <div class="meta-box">
                    <span class="label-meta">Ketersediaan Stok</span>
                    <span class="nilai-meta">{{ $available }} <small style="color: #94a3b8; font-size: 12px;">dari</small> {{ $item->quantity }} Unit</span>
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                <span class="label-meta">Deskripsi Barang</span>
                <p style="color: #475569; font-size: 15px; line-height: 1.7; margin-top: 10px;">
                    {{ $item->description ?? 'Tidak ada deskripsi tambahan yang tersedia untuk aset ini.' }}
                </p>
            </div>

            {{-- Tombol Aksi --}}
            <button type="button" 
                    class="btn-pinjam-utama"
                    onclick="openBorrowModal({{ $item->id }}, '{{ $item->name }}', {{ $available }})"
                    {{ $available <= 0 ? 'disabled' : '' }}>
                @if($available > 0)
                    AJUKAN PEMINJAMAN ASET
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                @else
                    STOK SAAT INI HABIS
                @endif
            </button>
        </div>
    </div>
</div>

@include('user.items.partials.borrow-modal')
@endsection