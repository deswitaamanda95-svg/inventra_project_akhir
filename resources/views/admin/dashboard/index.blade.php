@extends('layouts.admin')

@section('content')
<style>
    /* --- Fondasi UI & Tipografi --- */
    .dashboard-header { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; }
    .page-title { font-size: 28px; font-weight: 800; color: #0f172a; letter-spacing: -0.03em; margin: 0; }
    .subtitle { color: #64748b; font-size: 15px; margin-top: 6px; }

    /* --- Desain Kartu Metrik Premium --- */
    .metric-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; }
    
    .metric-card {
        background: #ffffff; padding: 28px; border-radius: 20px; border: 1px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .metric-card:hover { transform: translateY(-5px); border-color: #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }

    /* Dekorasi Ikon di Belakang */
    .icon-wrapper {
        width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; 
        justify-content: center; margin-bottom: 20px; transition: 0.3s;
    }
    .bg-blue { background: #eff6ff; color: #2563eb; }
    .bg-emerald { background: #ecfdf5; color: #10b981; }
    .bg-amber { background: #fffbeb; color: #d97706; }
    .bg-slate { background: #f8fafc; color: #475569; }

    .metric-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
    .metric-value { font-size: 34px; font-weight: 800; color: #1e293b; line-height: 1.2; margin: 4px 0; }
    .metric-footer { font-size: 13px; color: #64748b; font-weight: 500; display: flex; align-items: center; gap: 4px; }

    /* --- Akses Cepat (Quick Access) --- */
    .quick-access-section { margin-top: 56px; }
    .section-label { font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 24px; display: block; }
    
    .btn-sys-card {
        background: white; border: 1px solid #f1f5f9; padding: 16px 24px; border-radius: 14px;
        color: #1e293b; text-decoration: none; font-size: 14px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 10px; transition: 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .btn-sys-card:hover { background: #f8fafc; border-color: #cbd5e1; transform: scale(1.02); color: #2563eb; }
    .btn-sys-card svg { transition: 0.3s; }
    .btn-sys-card:hover svg { transform: translateX(3px); }
</style>

<div class="dashboard-header">
    <h2 class="page-title">Ringkasan Operasional</h2>
    <p class="subtitle">Selamat datang kembali. Berikut adalah status terkini ekosistem inventaris <b>INVENTRA</b>.</p>
</div>

<div class="metric-grid">
    {{-- Metrik: Total Pengguna --}}
    <div class="metric-card">
        <div class="icon-wrapper bg-slate">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <span class="metric-label">Akun Terdaftar</span>
        <div class="metric-value">{{ $totalUsers }}</div>
        <div class="metric-footer">Pengguna aktif dalam sistem</div>
    </div>

    {{-- Metrik: Klasifikasi Kategori --}}
    <div class="metric-card">
        <div class="icon-wrapper bg-amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <span class="metric-label">Kelompok Barang</span>
        <div class="metric-value">{{ $totalCategories }}</div>
        <div class="metric-footer">Kategori klasifikasi aktif</div>
    </div>

    {{-- Metrik: Total Inventaris --}}
    <div class="metric-card">
        <div class="icon-wrapper bg-emerald">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <span class="metric-label">Total Unit Barang</span>
        <div class="metric-value" style="color: #10b981;">{{ $totalItems }}</div>
        <div class="metric-footer">Aset yang tercatat di gudang</div>
    </div>

    {{-- Metrik: Distribusi Aktif (Peminjaman) --}}
    <div class="metric-card" style="border-left: 5px solid #2563eb;">
        <div class="icon-wrapper bg-blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
        </div>
        <span class="metric-label" style="color: #2563eb;">Distribusi Aktif</span>
        <div class="metric-value">{{ $activeLoans ?? $totalLoans }}</div>
        <div class="metric-footer">Barang yang sedang dipinjam</div>
    </div>
</div>

{{-- Shortcut Navigation --}}
<div class="quick-access-section">
    <span class="section-label">Akses Cepat Manajemen</span>
    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
        <a href="{{ route('admin.items.index') }}" class="btn-sys-card">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            Kelola Daftar Barang
        </a>
        <a href="{{ route('admin.loans.index') }}" class="btn-sys-card">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Riwayat Peminjaman
        </a>
        <a href="{{ route('admin.maintenance.index') }}" class="btn-sys-card">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            Log Pemeliharaan
        </a>
    </div>
</div>
@endsection