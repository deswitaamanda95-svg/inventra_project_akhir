@extends('layouts.admin') {{-- Pastikan ini merujuk ke layout yang benar --}}

@section('content')

{{-- 1. LOGIKA DETEKSI OVERDUE (REAL-TIME) --}}
@php
    $hasOverdue = Auth::user()->loans()->where('status', 'borrowed')->where('due_date', '<', now())->exists();
    $overdueCount = Auth::user()->loans()->where('status', 'borrowed')->where('due_date', '<', now())->count();
@endphp

<style>
    /* --- 1. Hero Utama (Slate Blue) --- */
    .blue-hero-v2 {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%); 
        padding: 60px; border-radius: 30px; color: white; margin-bottom: 35px;
        position: relative; overflow: hidden; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .blue-hero-v2:hover {
        transform: translateY(-3px);
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.4);
    }
    .blue-hero-v2::after {
        content: ""; position: absolute; right: -30px; bottom: -30px;
        width: 250px; height: 250px; background: rgba(255, 255, 255, 0.05); border-radius: 50%;
    }
    .hero-v2-title { font-size: 38px; font-weight: 800; line-height: 1.1; margin-bottom: 15px; letter-spacing: -0.04em; }
    .hero-v2-subtitle { font-size: 16px; color: rgba(255, 255, 255, 0.8); max-width: 600px; font-weight: 500; line-height: 1.6; }

    /* --- 2. Header Selamat Datang --- */
    .dashboard-hero {
        background: #ffffff; padding: 40px; border-radius: 24px; border: 1px solid #f1f5f9;
        margin-bottom: 35px; position: relative; overflow: hidden;
    }
    .dashboard-hero::after {
        content: ""; position: absolute; top: -20px; right: -20px; width: 150px; height: 150px;
        background: #f8fafc; border-radius: 50%; opacity: 0.6;
    }
    .hero-content { position: relative; z-index: 1; }
    .page-title { font-size: 28px; font-weight: 800; color: #0f172a; letter-spacing: -0.03em; margin: 0; }
    .page-subtitle { color: #64748b; font-size: 15px; margin-top: 6px; font-weight: 500; }

    /* --- 3. Grid Kartu Statistik --- */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 35px; }
    .stat-card-pro { 
        background: white; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9; 
        display: flex; align-items: center; gap: 20px; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .stat-card-pro:hover { transform: translateY(-5px); border-color: #cbd5e1; box-shadow: 0 15px 25px -5px rgba(0,0,0,0.05); }
    .icon-box { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
    .stat-val { font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1; margin-bottom: 4px; }
    .stat-lbl { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }

    .bg-blue { background: #eff6ff; color: #2563eb; }
    .bg-orange { background: #fffbeb; color: #d97706; }
    .bg-red { background: #fef2f2; color: #ef4444; }

    /* --- 4. Alert Keterlambatan Profesional --- */
    .alert-enterprise {
        background: #fef2f2; border-left: 5px solid #dc2626; padding: 24px;
        border-radius: 0 20px 20px 0; margin-bottom: 35px; display: flex;
        align-items: flex-start; gap: 20px; box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.05);
    }
    .alert-icon-box {
        width: 44px; height: 44px; background: #fee2e2; color: #dc2626;
        border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .alert-title { color: #991b1b; font-weight: 800; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .alert-desc { color: #b91c1c; font-size: 14px; line-height: 1.5; margin: 0; font-weight: 500; }
    .alert-desc strong { font-weight: 800; text-decoration: underline; }
    .btn-alert-action {
        background: #dc2626; color: white; padding: 10px 20px; border-radius: 10px;
        font-size: 12px; font-weight: 700; text-decoration: none; transition: 0.2s;
        display: inline-block; margin-top: 12px;
    }
    .btn-alert-action:hover { background: #991b1b; transform: translateX(5px); }

    /* --- 5. Perbaikan Tombol Lihat Semua --- */
    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #eff6ff; /* Blue-50 */
        color: #2563eb; /* Blue-600 */
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }

    .btn-view-all:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-view-all svg {
        transition: transform 0.3s ease;
    }

    .btn-view-all:hover svg {
        transform: translateX(3px);
    }

    /* --- 6. Banner Aksi & Tabel --- */
    .action-banner { 
        background-image: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.6)), 
                          url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&q=80&w=1920');
        background-size: cover; background-position: center; padding: 70px 45px;
        border-radius: 28px; color: white; text-align: center; position: relative; overflow: hidden;
    }
    .btn-explore {
        background: #2563eb; color: white; padding: 16px 35px; border-radius: 14px; 
        font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; 
        gap: 10px; transition: 0.3s; border: none; cursor: pointer;
    }
    .table-container { background: white; border-radius: 20px; border: 1px solid #f1f5f9; overflow: hidden; }
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th { background: #f8fafc; padding: 15px 24px; text-align: left; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; border-bottom: 1px solid #f1f5f9; }
    .modern-table td { padding: 18px 24px; border-bottom: 1px solid #f8fafc; font-size: 14px; color: #1e293b; }
    .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 100px; font-size: 10px; font-weight: 800; }
    .s-pending { background: #fffbeb; color: #d97706; }
    .s-borrowed { background: #eff6ff; color: #2563eb; }
    .s-returned { background: #f0fdf4; color: #16a34a; }
</style>

{{-- 2. ALERT KETERLAMBATAN PROFESIONAL (Hanya muncul jika ada barang terlambat) --}}
@if($hasOverdue)
<div class="alert-enterprise">
    <div class="alert-icon-box">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
    </div>
    <div>
        <h4 class="alert-title">Penangguhan Aktivitas Peminjaman</h4>
        <p class="alert-desc">
            Sistem mendeteksi <strong>{{ $overdueCount }} aset yang telah melewati tenggat</strong> pengembalian. Akses peminjaman mandiri ditangguhkan sementara hingga aset sebelumnya dikembalikan dan diverifikasi oleh Admin.
        </p>
        <a href="{{ route('user.loans.index') }}" class="btn-alert-action">
            Selesaikan Pengembalian Sekarang &rarr;
        </a>
    </div>
</div>
@endif

{{-- 3. HERO INFORMASI --}}
<div class="blue-hero-v2">
    <div class="hero-v2-title">Temukan kebutuhan Anda, <br>kapan pun diperlukan.</div>
    <div class="hero-v2-subtitle">
        Akses pusat inventaris perusahaan. Cek ketersediaan stok secara real-time dan ajukan permintaan barang langsung ke meja kerja Anda.
    </div>
</div>

<div class="dashboard-hero">
    <div class="hero-content">
        <h2 class="page-title">Beranda Pribadi</h2>
        <p class="page-subtitle">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>! Berikut ringkasan aktivitas penggunaan aset Anda.</p>
    </div>
</div>

{{-- 4. GRID STATISTIK --}}
<div class="stats-grid">
    <div class="stat-card-pro">
        <div class="icon-box bg-blue">
            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
        <div class="stat-info">
            <div class="stat-val">{{ $myActiveLoans }}</div>
            <div class="stat-lbl">Pinjaman Aktif</div>
        </div>
    </div>

    <div class="stat-card-pro">
        <div class="icon-box bg-orange">
            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="stat-info">
            <div class="stat-val">{{ $totalPending }}</div>
            <div class="stat-lbl">Menunggu Persetujuan</div>
        </div>
    </div>

    <div class="stat-card-pro">
        <div class="icon-box bg-red">
            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="stat-info">
            <div class="stat-val text-red-600">{{ $totalOverdue }}</div>
            <div class="stat-lbl">Tenggat Terlewati</div>
        </div>
    </div>
</div>

{{-- 5. BANNER AKSI (CTA) --}}
<div class="action-banner">
    <div class="action-content">
        <div class="white-badge">
            <span style="width: 6px; height: 6px; background: #ffffff; border-radius: 50%;"></span>
            {{ $availableItemsCount ?? 0 }} UNIT BARANG SIAP PINJAM
        </div>
        <h4 style="font-size: 28px; font-weight: 800; margin-bottom: 12px;">Butuh dukungan aset hari ini?</h4>
        <p style="color: rgba(255,255,255,0.7); font-size: 15px; margin-bottom: 30px; max-width: 550px; margin-left: auto; margin-right: auto; line-height: 1.6;">Jelajahi katalog inventaris kami yang luas dan ajukan permintaan peminjaman hanya dengan beberapa klik saja.</p>
        <a href="{{ route('user.items.index') }}" class="btn-explore">
            Buka Katalog Inventaris
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
    </div>
</div>

{{-- 6. RIWAYAT TERBARU --}}
<div style="margin-top: 45px;">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0;">Riwayat Aktivitas Terkini</h3>
        
        {{-- TOMBOL LIHAT SEMUA PROFESIONAL --}}
        <a href="{{ route('user.loans.index') }}" class="btn-view-all">
            Lihat Semua
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
            </svg>
        </a>
    </div>

    <div class="table-container">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Rincian Barang</th>
                    <th>Status Pinjaman</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLoans as $loan)
                <tr>
                    <td style="font-weight: 700; color: #1e293b;">{{ $loan->item->name }}</td>
                    <td>
                        @php
                            $class = $loan->status == 'pending' ? 's-pending' : ($loan->status == 'borrowed' ? 's-borrowed' : 's-returned');
                            $statusIndo = [
                                'pending' => 'MENUNGGU',
                                'borrowed' => 'DIPINJAM',
                                'returned' => 'KEMBALI',
                            ][$loan->status] ?? strtoupper($loan->status);
                        @endphp
                        <span class="status-pill {{ $class }}">
                            <div style="width: 5px; height: 5px; border-radius: 50%; background: currentColor;"></div>
                            {{ $statusIndo }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="padding: 50px; text-align: center; color: #94a3b8;">
                        <p style="margin: 0; font-weight: 600;">Belum ada riwayat aktivitas peminjaman.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection