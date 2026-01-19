@extends('layouts.admin')

@section('content')
<style>
    /* --- Fondasi & Tipografi --- */
    .header-halaman { margin-bottom: 32px; }
    .judul-halaman { font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; margin-bottom: 6px; }
    .sub-judul { color: #64748b; font-size: 15px; }
    
    /* --- Kartu Statistik (Metric Cards) --- */
    .grid-statistik { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 35px; }
    .kartu-stat { 
        background: white; padding: 24px; border-radius: 16px; border: 1px solid #f1f5f9; 
        display: flex; align-items: center; gap: 20px; transition: 0.3s;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); 
    }
    .kartu-stat:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    
    .bingkai-ikon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .stat-konten h3 { font-size: 32px; font-weight: 800; margin: 0; line-height: 1; }
    .stat-konten p { margin: 6px 0 0; color: #94a3b8; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }

    /* Aksen Warna Status */
    .aksen-servis { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
    .aksen-rusak { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

    /* --- Desain Tabel Premium --- */
    .wadah-tabel { background: white; border-radius: 16px; border: 1px solid #f1f5f9; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
    .tabel-modern { width: 100%; border-collapse: collapse; }
    .tabel-modern th { 
        background: #f8fafc; padding: 16px 24px; text-align: left; font-size: 10px; 
        font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; 
        border-bottom: 1px solid #f1f5f9; 
    }
    .tabel-modern td { padding: 16px 24px; border-bottom: 1px solid #f8fafc; font-size: 14px; color: #475569; vertical-align: middle; }
    .tabel-modern tr:hover td { background: #fcfdfe; }
    
    /* Komponen Visual */
    .foto-aset { width: 48px; height: 48px; border-radius: 10px; object-fit: cover; background: #f1f5f9; border: 1px solid #e2e8f0; }
    .label-kondisi { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 8px; font-weight: 800; font-size: 10px; }
    
    .btn-kembali { 
        display: inline-flex; align-items: center; gap: 8px; color: #94a3b8; 
        text-decoration: none; font-weight: 700; font-size: 13px; margin-bottom: 24px; transition: 0.2s;
    }
    .btn-kembali:hover { color: #2563eb; }

    .btn-update { 
        padding: 6px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; 
        text-decoration: none; border: 1.5px solid #e2e8f0; color: #64748b; 
        background: white; transition: 0.2s; 
    }
    .btn-update:hover { border-color: #3b82f6; color: #3b82f6; background: #f0f7ff; }
</style>

{{-- Navigasi Kembali --}}
<a href="{{ route('admin.items.index') }}" class="btn-kembali">
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    Kembali ke Semua Aset
</a>

<div class="header-halaman">
    <h2 class="judul-halaman">Laporan Aset Bermasalah</h2>
    <p class="sub-judul">Pantau unit inventaris yang membutuhkan penanganan teknis atau penggantian permanen.</p>
</div>

{{-- --- BAGIAN METRIK STATISTIK --- --}}
<div class="grid-statistik">
    <div class="kartu-stat">
        <div class="bingkai-ikon aksen-servis">
            {{-- Ikon Wrench untuk Repair --}}
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
        </div>
        <div class="stat-konten">
            <h3 style="color: #b45309;">{{ $totalRepair }} <small style="font-size: 14px; opacity: 0.7;">Unit</small></h3>
            <p>Dalam Perbaikan (Repair)</p>
        </div>
    </div>
    <div class="kartu-stat">
        <div class="bingkai-ikon aksen-rusak">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="stat-konten">
            <h3 style="color: #b91c1c;">{{ $totalBroken }} <small style="font-size: 14px; opacity: 0.7;">Unit</small></h3>
            <p>Rusak Permanen (Broken)</p>
        </div>
    </div>
</div>

{{-- --- BAGIAN TABEL DATA --- --}}
<div class="wadah-tabel">
    <table class="tabel-modern">
        <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th>Detail Aset</th>
                <th>Qty Bermasalah</th>
                <th>Status Kondisi</th>
                <th>Lokasi Penyimpanan</th>
                <th style="text-align: center;">Opsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($damagedItems as $item)
            <tr>
                <td style="color: #94a3b8; font-weight: 700;">{{ $loop->iteration }}</td>
                <td>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="foto-aset" alt="{{ $item->name }}">
                        @else
                            <div class="foto-aset" style="display: flex; align-items: center; justify-content: center;">
                                <svg width="18" height="18" fill="none" stroke="#cbd5e1" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div>
                            <div style="font-weight: 800; color: #1e293b; font-size: 15px;">{{ $item->name }}</div>
                            <div style="color: #94a3b8; font-size: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">{{ $item->category->name ?? 'Umum' }}</div>
                        </div>
                    </div>
                </td>
                
                <td>
                    <b style="font-size: 16px; color: #1e293b;">{{ $item->quantity }}</b> <span style="font-size: 12px; color: #94a3b8; font-weight: 700;">Unit</span>
                </td>

                <td>
                    {{-- Badge Kondisi Dinamis --}}
                    @php 
                        $isRepair = $item->condition == 'Repair';
                        $statusText = $isRepair ? 'DALAM SERVIS' : 'RUSAK TOTAL';
                        $aksenClass = $isRepair ? 'aksen-servis' : 'aksen-rusak';
                    @endphp
                    <div class="label-kondisi {{ $aksenClass }}">
                        @if($isRepair)
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                        @else
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        @endif
                        {{ $statusText }}
                    </div>
                </td>

                <td style="color: #64748b; font-weight: 500;">
                    {{ $item->room ?? 'Gudang Utama' }}
                </td>

                <td style="text-align: center;">
                    <a href="{{ route('admin.items.edit', $item->id) }}" class="btn-update">
                        Ubah Status
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 80px 24px; text-align: center; color: #94a3b8;">
                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 15px; opacity: 0.3;"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p style="font-weight: 700; font-size: 14px;">Seluruh unit saat ini dalam kondisi prima (Normal).</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection