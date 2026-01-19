@extends('layouts.admin')

@section('content')
{{-- Header Halaman dengan Tipografi yang Dipertegas --}}
<div style="margin-bottom: 35px;">
    <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.025em; margin: 0;">
        Panel Kendali Utama
    </h2>
    <p style="color: #64748b; font-size: 15px; margin-top: 4px;">Ringkasan aktivitas sistem dan statistik inventaris secara real-time.</p>
</div>

{{-- Grid Statistik Sistematis --}}
<div style="display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 24px; @media (min-width: 768px) { grid-template-columns: repeat(4, minmax(0, 1fr)); }">

    {{-- Kartu: Pengguna Terdaftar --}}
    <div style="background: white; border-radius: 20px; border: 1px solid #f1f5f9; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: 0.3s;">
        <p style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
            Pengguna Terdaftar
        </p>
        <h3 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0;">{{ $totalUsers ?? 0 }}</h3>
        <div style="margin-top: 12px; font-size: 11px; font-weight: 700; color: #2563eb; background: #eff6ff; padding: 4px 10px; border-radius: 6px; display: inline-block;">
            Total Akun Aktif
        </div>
    </div>

    {{-- Kartu: Koleksi Aset --}}
    <div style="background: white; border-radius: 20px; border: 1px solid #f1f5f9; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: 0.3s;">
        <p style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
            Total Unit Aset
        </p>
        <h3 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0;">{{ $totalItems ?? 0 }}</h3>
        <div style="margin-top: 12px; font-size: 11px; font-weight: 700; color: #059669; background: #ecfdf5; padding: 4px 10px; border-radius: 6px; display: inline-block;">
            Inventaris Terdata
        </div>
    </div>

    {{-- Kartu: Aset Terdistribusi --}}
    <div style="background: white; border-radius: 20px; border: 1px solid #f1f5f9; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: 0.3s;">
        <p style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
            Aset Terdistribusi
        </p>
        <h3 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0;">{{ $itemsBorrowed ?? 0 }}</h3>
        <div style="margin-top: 12px; font-size: 11px; font-weight: 700; color: #d97706; background: #fffbeb; padding: 4px 10px; border-radius: 6px; display: inline-block;">
            Sedang Digunakan
        </div>
    </div>

    {{-- Kartu: Peminjaman Aktif --}}
    <div style="background: white; border-radius: 20px; border: 1px solid #f1f5f9; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: 0.3s;">
        <p style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
            Peminjaman Aktif
        </p>
        <h3 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0;">{{ $activeLoans ?? 0 }}</h3>
        <div style="margin-top: 12px; font-size: 11px; font-weight: 700; color: #dc2626; background: #fef2f2; padding: 4px 10px; border-radius: 6px; display: inline-block;">
            Transaksi Berjalan
        </div>
    </div>

</div>
@endsection