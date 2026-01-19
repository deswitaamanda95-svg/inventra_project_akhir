@extends('layouts.admin')

@section('content')
<style>
    /* --- Fondasi & Grid Sistematis --- */
    .analitik-wrapper { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); 
        gap: 30px; 
        margin-bottom: 40px; 
    }

    /* --- Desain Kartu Premium --- */
    .kartu-analitik { 
        background: white; 
        padding: 32px; 
        border-radius: 20px; 
        border: 1px solid #f1f5f9; 
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); 
        transition: 0.3s;
    }
    .kartu-analitik:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); }

    /* --- Tipografi & Label --- */
    .label-kategori { 
        font-size: 10px; 
        font-weight: 800; 
        color: #94a3b8; 
        text-transform: uppercase; 
        letter-spacing: 0.1em; 
        margin-bottom: 20px; 
        display: block;
    }

    /* --- List Performa Admin --- */
    .baris-admin { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 14px 0; 
        border-bottom: 1px solid #f8fafc; 
    }
    .baris-admin:last-child { border: none; }
    
    .avatar-inisial { 
        width: 36px; 
        height: 36px; 
        background: #eff6ff; 
        border-radius: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 13px; 
        font-weight: 800; 
        color: #2563eb; 
    }

    /* --- Status Kesehatan Aset --- */
    .indikator-warna { 
        display: inline-block; 
        width: 10px; 
        height: 10px; 
        border-radius: 3px; 
        margin-right: 10px; 
    }
    
    .progres-mini {
        height: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 8px;
    }
    .progres-bar { height: 100%; border-radius: 10px; }
</style>

<div style="margin-bottom: 40px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px;">
    <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; margin: 0;">Analitik Inventaris</h2>
    <p style="color: #64748b; font-size: 15px; margin-top: 4px;">Pantau performa operasional admin dan ringkasan kondisi aset secara real-time.</p>
</div>

<div class="analitik-wrapper">
    {{-- KARTU 1: PERFORMA ADMIN --}}
    <div class="kartu-analitik">
        <span class="label-kategori">Admin Teraktif (Bulan Ini)</span>
        
        @forelse($topAdmins as $admin)
        <div class="baris-admin">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div class="avatar-inisial">{{ strtoupper(substr($admin->name, 0, 1)) }}</div>
                <div>
                    <div style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $admin->name }}</div>
                    <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">Otorisator Sistem</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 14px; font-weight: 800; color: #0f172a;">{{ $admin->total_processed }}</div>
                <div style="font-size: 10px; font-weight: 700; color: #2563eb; text-transform: uppercase;">Persetujuan</div>
            </div>
        </div>
        @empty
        <p style="color: #94a3b8; font-size: 13px; text-align: center; padding: 20px;">Belum ada aktivitas otorisasi bulan ini.</p>
        @endforelse
    </div>

    {{-- KARTU 2: RINGKASAN KONDISI --}}
    <div class="kartu-analitik">
        <span class="label-kategori">Kesehatan Aset Keseluruhan</span>
        
        <div style="display: flex; flex-direction: column; gap: 24px;">
            {{-- Kondisi Prima --}}
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700;">
                    <span style="color: #475569;"><span class="indikator-warna" style="background: #10b981;"></span>Kondisi Baik</span>
                    <span style="color: #0f172a;">{{ $itemStats['good'] }} Unit</span>
                </div>
                <div class="progres-mini">
                    <div class="progres-bar" style="width: {{ ($itemStats['good'] / max(array_sum($itemStats), 1)) * 100 }}%; background: #10b981;"></div>
                </div>
            </div>

            {{-- Perlu Perbaikan --}}
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700;">
                    <span style="color: #475569;"><span class="indikator-warna" style="background: #f59e0b;"></span>Perlu Servis</span>
                    <span style="color: #0f172a;">{{ $itemStats['repair'] }} Unit</span>
                </div>
                <div class="progres-mini">
                    <div class="progres-bar" style="width: {{ ($itemStats['repair'] / max(array_sum($itemStats), 1)) * 100 }}%; background: #f59e0b;"></div>
                </div>
            </div>

            {{-- Rusak/Hilang --}}
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700;">
                    <span style="color: #475569;"><span class="indikator-warna" style="background: #ef4444;"></span>Rusak / Hilang</span>
                    <span style="color: #0f172a;">{{ $itemStats['broken'] }} Unit</span>
                </div>
                <div class="progres-mini">
                    <div class="progres-bar" style="width: {{ ($itemStats['broken'] / max(array_sum($itemStats), 1)) * 100 }}%; background: #ef4444;"></div>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px dashed #e2e8f0; text-align: center;">
            <span style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Total Aset Terdata: {{ array_sum($itemStats) }} Unit</span>
        </div>
    </div>
</div>
@endsection