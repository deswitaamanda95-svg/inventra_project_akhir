@extends('layouts.admin') {{-- Pastikan ini sesuai dengan layout yang Anda gunakan --}}

@section('content')

{{-- 1. LOGIKA CEK KETERLAMBATAN DI LEVEL VIEW --}}
@php
    $hasOverdue = Auth::user()->loans()
        ->where('status', 'borrowed')
        ->where('due_date', '<', now())
        ->exists();
@endphp

<style>
    /* --- 1. Hero & Header Section --- */
    .catalog-hero {
        background: #ffffff;
        padding: 40px;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    .catalog-hero::after {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: #f1f5f9;
        border-radius: 50%;
        z-index: 0;
    }
    .hero-content { position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: center; }
    .hero-title { font-size: 28px; font-weight: 800; color: #0f172a; letter-spacing: -0.03em; margin: 0; }
    .hero-subtitle { color: #64748b; font-size: 15px; margin-top: 4px; font-weight: 500; }

    /* Search Bar Terintegrasi */
    .search-wrapper { position: relative; width: 100%; max-width: 350px; }
    .search-input-modern {
        width: 100%;
        padding: 14px 18px 14px 45px;
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        font-size: 14px;
        font-weight: 600;
        outline: none;
        transition: 0.3s;
        background: #f8fafc;
    }
    .search-input-modern:focus { border-color: #2563eb; background: white; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.05); }
    .search-icon-fixed { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; }

    /* --- 2. Grid & Card Architecture --- */
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 30px;
    }

    .item-card-premium {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    .item-card-premium:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1;
    }

    .image-container {
        width: 100%;
        height: 230px;
        position: relative;
        overflow: hidden;
        background: #f8fafc;
    }
    .img-fluid { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
    .item-card-premium:hover .img-fluid { transform: scale(1.08); }

    /* Badge Melayang */
    .badge-float {
        position: absolute;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        backdrop-filter: blur(8px);
    }
    .cat-tag { top: 15px; left: 15px; background: rgba(255, 255, 255, 0.9); color: #2563eb; }
    .status-tag { top: 15px; right: 15px; display: flex; align-items: center; gap: 5px; }
    .ready { background: rgba(240, 253, 244, 0.9); color: #16a34a; }
    .unavailable { background: rgba(254, 242, 242, 0.9); color: #ef4444; }

    /* Konten Kartu */
    .card-body-premium { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
    .item-name { font-size: 19px; font-weight: 800; color: #0f172a; margin: 0 0 15px 0; line-height: 1.3; }

    .meta-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; color: #64748b; font-size: 13px; font-weight: 600; }
    .meta-row svg { color: #cbd5e1; }

    /* Stok & Tombol Aksi */
    .card-footer-premium {
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .stock-label-group { display: flex; justify-content: space-between; align-items: flex-end; }
    .btn-group-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    .btn-action {
        padding: 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-outline { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
    .btn-outline:hover { background: #f1f5f9; color: #0f172a; border-color: #cbd5e1; }
    
    /* Tombol Dark Mode dengan Filter untuk Status Disabled */
    .btn-dark { background: #0f172a; color: white; border: none; cursor: pointer; }
    .btn-dark:hover:not(:disabled) { background: #2563eb; transform: translateY(-2px); }
    .btn-dark:disabled { background: #cbd5e1; color: #94a3b8; cursor: not-allowed; }
</style>

<div class="catalog-hero">
    <div class="hero-content">
        <div>
            <h2 class="hero-title">Katalog Inventaris</h2>
            <p class="hero-subtitle">Telusuri ketersediaan barang dan ajukan peminjaman secara mandiri.</p>
        </div>
        
        <form action="{{ route('user.items.index') }}" method="GET" class="search-wrapper">
            <svg class="search-icon-fixed" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="search" class="search-input-modern" placeholder="Cari nama aset atau kategori..." value="{{ request('search') }}">
        </form>
    </div>
</div>

<div class="items-grid">
    @forelse($items as $item)
    @php
        // Hitung stok tersedia (total - sedang dipinjam)
        $available = $item->quantity - $item->loans()->where('status', 'borrowed')->count(); 
    @endphp
    <div class="item-card-premium">
        <div class="image-container">
            <span class="badge-float cat-tag">{{ $item->category->name ?? 'Kategori Umum' }}</span>
            <span class="badge-float status-tag {{ $available > 0 ? 'ready' : 'unavailable' }}">
                <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span>
                {{ $available > 0 ? 'Tersedia' : 'Sedang Dipakai' }}
            </span>
            
            <a href="{{ route('user.items.show', $item->id) }}">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid" alt="{{ $item->name }}">
                @else
                    <div style="height:100%; display:flex; align-items:center; justify-content:center; color:#cbd5e1; background:#f8fafc;">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </a>
        </div>

        <div class="card-body-premium">
            <a href="{{ route('user.items.show', $item->id) }}" style="text-decoration:none;">
                <h3 class="item-name">{{ $item->name }}</h3>
            </a>

            <div class="meta-row">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Lokasi: {{ strtoupper($item->room ?? 'Gudang Pusat') }}
            </div>
            
            <div class="meta-row">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Kondisi Fisik: <b>{{ $item->condition }}</b>
            </div>

            <div class="card-footer-premium">
                <div class="stock-label-group">
                    <span style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Ketersediaan Unit</span>
                    <span style="font-size: 14px; font-weight: 800; color: #0f172a;">
                        <span style="{{ $available <= 0 ? 'color: #ef4444;' : '' }}">{{ $available }}</span>
                        <span style="color: #cbd5e1; font-weight: 600;">/ {{ $item->quantity }} PCS</span>
                    </span>
                </div>
                
                <div class="btn-group-grid">
                    <a href="{{ route('user.items.show', $item->id) }}" class="btn-action btn-outline">RINCIAN</a>
                    
                    {{-- IMPLEMENTASI PERBAIKAN TOMBOL --}}
                    <button type="button" 
                            class="btn-action btn-dark" 
                            {{ ($available <= 0 || $hasOverdue) ? 'disabled' : '' }}
                            onclick="openBorrowModal({{ $item->id }}, '{{ $item->name }}', {{ $available }})">
                        @if($hasOverdue)
                            AKSES TERKUNCI
                        @else
                            PINJAM ASET
                        @endif
                    </button>
                </div>

                {{-- PESAN PERINGATAN DI BAWAH TOMBOL --}}
                @if($hasOverdue)
                    <small style="color: #ef4444; display: block; margin-top: 8px; font-weight: 700; text-align: center; font-size: 10px;">
                        ⚠️ Selesaikan tanggungan barang sebelumnya
                    </small>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1/-1; padding: 100px; text-align: center; color: #94a3b8;">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom:20px; opacity:0.5;"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <p style="font-weight: 700; font-size: 18px;">Aset tidak ditemukan</p>
        <p style="font-size: 14px;">Silakan gunakan kata kunci pencarian lain atau hubungi Admin.</p>
    </div>
    @endforelse
</div>

{{-- PANGGIL MODAL PINJAM --}}
@include('user.items.partials.borrow-modal')

@endsection