@extends('layouts.admin')

@section('content')
<style>
    /* --- Tipografi & Header --- */
    .header-halaman { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .judul-halaman { font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; }
    .sub-judul { color: #64748b; font-size: 14px; margin-top: 4px; }

    /* --- Kartu Statistik (Metric Cards) --- */
    .grid-statistik { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; }
    .kartu-stat {
        background: white; padding: 22px 24px; border-radius: 16px; border: 1px solid #f1f5f9;
        display: flex; align-items: center; gap: 18px; transition: 0.3s;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .kartu-stat:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
    
    .lingkaran-ikon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
    .aksen-baik { background: #f0fdf4; color: #16a34a; }
    .aksen-servis { background: #fffbeb; color: #d97706; }
    .aksen-rusak { background: #fef2f2; color: #dc2626; }

    /* --- Area Pencarian Sistematis --- */
    .area-pencarian { margin-bottom: 25px; }
    .formulir-cari { display: flex; gap: 12px; align-items: center; width: 100%; }
    .pembungkus-input { position: relative; flex: 1; }
    .input-pencarian {
        width: 100%; padding: 12px 16px 12px 42px; border-radius: 12px; border: 1px solid #e2e8f0;
        font-size: 14px; font-weight: 500; color: #334155; outline: none; transition: 0.2s; background: #ffffff;
    }
    .input-pencarian:focus { border-color: #2563eb; background: white; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08); }

    .btn-tambah {
        background: #0f172a; color: #ffffff; padding: 12px 24px; border-radius: 12px;
        font-size: 13px; font-weight: 700; border: none; cursor: pointer; transition: 0.3s;
        display: flex; align-items: center; gap: 8px; text-decoration: none;
    }
    .btn-tambah:hover { background: #1e293b; transform: translateY(-2px); }

    /* --- Desain Tabel Premium --- */
    .wadah-tabel { 
        background: white; border-radius: 16px; border: 1px solid #f1f5f9; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow-x: auto; width: 100%;
    }
    .tabel-modern { width: 100%; border-collapse: collapse; min-width: 1100px; }
    .tabel-modern th { 
        background: #f8fafc; padding: 16px 24px; text-align: left; 
        font-size: 10px; font-weight: 800; color: #94a3b8; 
        text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #f1f5f9; 
    }
    .tabel-modern td { padding: 16px 24px; border-bottom: 1px solid #f8fafc; font-size: 14px; vertical-align: middle; color: #475569; }
    .tabel-modern tr:hover td { background: #fcfdfe; }

    /* Detail Baris */
    .identitas-aset { display: flex; align-items: center; gap: 14px; }
    .foto-aset { width: 46px; height: 46px; border-radius: 10px; object-fit: cover; border: 1px solid #f1f5f9; flex-shrink: 0; background: #f8fafc; }
    .nama-aset { font-weight: 700; color: #1e293b; font-size: 14px; display: block; }
    .kode-aset { font-family: 'JetBrains Mono', monospace; font-size: 9px; color: #94a3b8; background: #f8fafc; padding: 2px 6px; border-radius: 4px; border: 1px solid #f1f5f9; }

    /* Label & Status */
    .label-lokasi {
        display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; color: #475569;
        padding: 5px 10px; border-radius: 8px; font-size: 10px; font-weight: 800; border: 1px solid #e2e8f0;
    }
    .badge-kondisi { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 100px; font-size: 10px; font-weight: 800; }
    
    .angka-peminjaman { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; }
    .dipinjam-aktif { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }
    .dipinjam-nol { background: #f8fafc; color: #cbd5e1; border: 1px solid #f1f5f9; }

    /* Tombol Aksi Ringkas */
    .grup-aksi { display: flex; gap: 6px; justify-content: flex-end; }
    .btn-aksi-kecil { 
        padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: 700; 
        text-decoration: none; border: 1.5px solid #e2e8f0; color: #64748b; 
        transition: 0.2s; background: white; cursor: pointer;
    }
    .btn-aksi-kecil:hover { border-color: #2563eb; color: #2563eb; background: #f0f7ff; }
    .btn-hapus:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
</style>

<div class="header-halaman">
    <div>
        <h2 class="judul-halaman">Manajemen Inventaris</h2>
        <p class="sub-judul">Pantau sirkulasi aset, ketersediaan stok, dan kondisi unit secara real-time.</p>
    </div>
    <a href="{{ route('admin.items.create') }}" class="btn-tambah">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"></path></svg>
        TAMBAH BARANG
    </a>
</div>

{{-- --- RINGKASAN KONDISI --- --}}
<div class="grid-statistik">
    <div class="kartu-stat">
        <div class="lingkaran-ikon aksen-baik"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
        <div>
            <div style="font-size: 22px; font-weight: 800; color: #1e293b;">{{ $totalGood }} <small style="font-size: 12px; color: #94a3b8; font-weight: 600;">Unit</small></div>
            <div style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Kondisi Baik</div>
        </div>
    </div>
    <div class="kartu-stat">
        <div class="lingkaran-ikon aksen-servis"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg></div>
        <div>
            <div style="font-size: 22px; font-weight: 800; color: #1e293b;">{{ $totalRepair }} <small style="font-size: 12px; color: #94a3b8; font-weight: 600;">Unit</small></div>
            <div style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Dalam Servis</div>
        </div>
    </div>
    <div class="kartu-stat">
        <div class="lingkaran-ikon aksen-rusak"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
        <div>
            <div style="font-size: 22px; font-weight: 800; color: #1e293b;">{{ $totalBroken }} <small style="font-size: 12px; color: #94a3b8; font-weight: 600;">Unit</small></div>
            <div style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Rusak Berat</div>
        </div>
    </div>
</div>

{{-- --- FORM PENCARIAN --- --}}
<div class="area-pencarian">
    <form action="{{ route('admin.items.index') }}" method="GET" class="formulir-cari">
        <div class="pembungkus-input">
            <svg style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" class="input-pencarian" placeholder="Cari berdasarkan nama barang, kode aset, atau lokasi ruangan...">
        </div>
        <button type="submit" class="btn-tambah" style="background: #2563eb;">CARI</button>
    </form>
</div>

{{-- --- TABEL DATA --- --}}
<div class="wadah-tabel">
    <table class="tabel-modern">
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">NO</th> 
                <th style="width: 350px;">RINCIAN BARANG</th>
                <th style="width: 150px;">STOK TERSEDIA</th>
                <th style="width: 100px; text-align: center;">DIPINJAM</th>
                <th style="width: 160px;">KONDISI</th>
                <th style="width: 180px;">LOKASI</th>
                <th style="width: 240px; text-align: right;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            @php $readyStock = ($item->condition == 'Good') ? ($item->quantity - $item->loans_count) : 0; @endphp
            <tr>
                <td style="text-align: center; color: #94a3b8; font-weight: 800;">{{ $loop->iteration }}</td>
                <td>
                    <div class="identitas-aset">
                        <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/placeholder.png') }}" class="foto-aset">
                        <div style="display: flex; flex-direction: column; gap: 3px;">
                            <span class="nama-aset">{{ $item->name }}</span>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="kode-aset">#ASET-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span style="font-size: 9px; color: #94a3b8; font-weight: 800; text-transform: uppercase;">{{ $item->category->name ?? 'Umum' }}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: baseline; gap: 4px;">
                        <span style="font-size: 16px; font-weight: 800; color: #0f172a;">{{ $readyStock }}</span>
                        <span style="font-size: 11px; font-weight: 600; color: #94a3b8;">/ {{ $item->quantity }}</span>
                    </div>
                    <div style="font-size: 9px; font-weight: 800; color: #10b981; text-transform: uppercase;">Siap Pakai</div>
                </td>
                <td style="text-align: center;">
                    <div class="angka-peminjaman {{ $item->loans_count > 0 ? 'dipinjam-aktif' : 'dipinjam-nol' }}">
                        {{ $item->loans_count }}
                    </div>
                </td>
                <td>
                    @php 
                        $warna = $item->condition == 'Good' ? '#16a34a' : ($item->condition == 'Repair' ? '#d97706' : '#dc2626');
                        $latar = $item->condition == 'Good' ? '#f0fdf4' : ($item->condition == 'Repair' ? '#fffbeb' : '#fef2f2');
                        $teksKondisi = $item->condition == 'Good' ? 'NORMAL' : ($item->condition == 'Repair' ? 'SERVIS' : 'RUSAK');
                    @endphp
                    <div class="badge-kondisi" style="color: {{ $warna }}; background: {{ $latar }};">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></div>
                        {{ $teksKondisi }}
                    </div>
                </td>
                <td>
                    @if($item->room)
                        <div class="label-lokasi">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                            </svg>
                            {{ strtoupper($item->room) }}
                        </div>
                    @else
                        <span style="color: #cbd5e1; font-size: 10px; font-style: italic; font-weight: 700;">BELUM DIATUR</span>
                    @endif
                </td>
                <td>
                    <div class="grup-aksi">
                        <a href="{{ route('admin.items.show', $item->id) }}" class="btn-aksi-kecil">DETAIL</a>
                        <a href="{{ route('admin.items.edit', $item->id) }}" class="btn-aksi-kecil">EDIT</a>
                        <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus aset ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-aksi-kecil btn-hapus">HAPUS</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="padding: 100px; text-align: center; color: #94a3b8; font-weight: 700; font-size: 14px;">Data inventaris tidak ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection