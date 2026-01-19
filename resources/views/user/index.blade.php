@extends('layouts.admin') {{-- Pastikan merujuk pada layout yang benar --}}

@section('content')
<div style="margin-bottom: 40px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px;">
    <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.025em; margin: 0;">Katalog Inventaris</h2>
    <p style="color: #64748b; font-size: 15px; margin-top: 6px;">Telusuri ketersediaan aset kantor dan ajukan peminjaman secara mandiri.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 28px;">
    @forelse($items as $item)
    <div style="background: white; border-radius: 20px; border: 1px solid #f1f5f9; padding: 28px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: 0.3s; display: flex; flex-direction: column;">
        {{-- Status & Kategori --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
            <span style="background: #f8fafc; padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid #f1f5f9;">
                {{ $item->category->name ?? 'Tanpa Kategori' }}
            </span>
            <span style="color: #16a34a; font-size: 10px; font-weight: 800; text-transform: uppercase; display: flex; align-items: center; gap: 6px; background: #f0fdf4; padding: 4px 10px; border-radius: 20px;">
                <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%;"></span>
                Tersedia
            </span>
        </div>
        
        {{-- Nama & Lokasi --}}
        <h3 style="font-size: 19px; font-weight: 800; color: #1e293b; margin: 0 0 10px 0; line-height: 1.3;">{{ $item->name }}</h3>
        <div style="display: flex; align-items: center; gap: 6px; color: #94a3b8; font-size: 13px; font-weight: 600; margin-bottom: 25px;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $item->room ?? 'Gudang Utama' }}
        </div>

        {{-- Stok & Tombol Aksi --}}
        <div style="margin-top: auto; padding-top: 20px; border-top: 1px dashed #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; flex-direction: column;">
                <span style="font-size: 10px; font-weight: 800; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.05em;">Stok Fisik</span>
                <span style="font-size: 15px; color: #475569; font-weight: 700;">{{ $item->quantity }} <small style="font-weight: 600;">Unit</small></span>
            </div>
            
            <a href="#" style="background: #0f172a; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 13px; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.2);">
                Ajukan Pinjam
            </a>
        </div>
    </div>
    @empty
    {{-- Tampilan Saat Kosong --}}
    <div style="grid-column: 1 / -1; text-align: center; padding: 100px 0; background: white; border-radius: 24px; border: 2px dashed #f1f5f9;">
        <div style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;">📦</div>
        <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 5px;">Belum Ada Aset Tersedia</h4>
        <p style="color: #94a3b8; font-size: 14px;">Saat ini belum ada barang yang dapat dipinjam oleh pengguna.</p>
    </div>
    @endforelse
</div>
@endsection