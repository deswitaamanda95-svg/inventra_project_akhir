@extends('layouts.admin')

@section('content')
<style>
    /* --- Tipografi & Header --- */
    .header-detail { display: flex; align-items: center; justify-content: space-between; margin-bottom: 35px; }
    .btn-kembali { display: flex; align-items: center; gap: 8px; color: #94a3b8; text-decoration: none; font-weight: 700; font-size: 13px; transition: 0.2s; }
    .btn-kembali:hover { color: #2563eb; transform: translateX(-3px); }

    /* --- Tata Letak Grid Premium --- */
    .grid-rincian { display: grid; grid-template-columns: 380px 1fr; gap: 40px; align-items: start; }

    /* --- Kartu Visual Aset --- */
    .kartu-visual { background: white; border-radius: 20px; border: 1px solid #f1f5f9; padding: 24px; position: sticky; top: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
    .bingkai-foto { width: 100%; aspect-ratio: 1/1; border-radius: 16px; overflow: hidden; background: #f8fafc; border: 1px solid #f1f5f9; margin-bottom: 20px; }
    .bingkai-foto img { width: 100%; height: 100%; object-fit: contain; }
    
    .label-kondisi-besar { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px; border-radius: 12px; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; }
    .aksen-siap { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .aksen-servis { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
    .aksen-rusak { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

    /* --- Kartu Informasi --- */
    .kartu-info { background: white; border-radius: 20px; border: 1px solid #f1f5f9; padding: 40px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
    .tag-id { font-family: 'JetBrains Mono', monospace; font-size: 12px; color: #94a3b8; margin-bottom: 10px; display: block; font-weight: 700; }
    .judul-aset { font-size: 30px; font-weight: 800; color: #0f172a; letter-spacing: -0.03em; margin: 0 0 20px 0; }
    
    .grid-spek { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; margin-top: 30px; padding-top: 30px; border-top: 1px dashed #f1f5f9; }
    .item-spek { display: flex; flex-direction: column; gap: 6px; }
    .label-spek { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; }
    .nilai-spek { font-size: 15px; font-weight: 700; color: #1e293b; }

    /* --- Tabel Riwayat Sistematis --- */
    .seksi-riwayat { margin-top: 40px; }
    .judul-riwayat { font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
    .wadah-tabel-mini { background: white; border-radius: 16px; border: 1px solid #f1f5f9; overflow: hidden; }
    .tabel-mini { width: 100%; border-collapse: collapse; }
    .tabel-mini th { background: #f8fafc; text-align: left; font-size: 10px; font-weight: 800; color: #94a3b8; padding: 14px 20px; border-bottom: 1px solid #f1f5f9; text-transform: uppercase; }
    .tabel-mini td { padding: 18px 20px; font-size: 13px; border-bottom: 1px solid #f8fafc; color: #475569; vertical-align: middle; }
    
    .inisial-user { width: 28px; height: 28px; border-radius: 8px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; flex-shrink: 0; }

    /* --- Tombol Aksi --- */
    .btn-aksi-rincian { padding: 8px 16px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none; transition: 0.2s; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
    .btn-edit-aset { background: #ffffff; border: 1.5px solid #e2e8f0; color: #475569; }
    .btn-edit-aset:hover { border-color: #2563eb; color: #2563eb; background: #f0f7ff; }
    .btn-hapus-aset { background: #ffffff; border: 1.5px solid #fee2e2; color: #dc2626; }
    .btn-hapus-aset:hover { background: #fef2f2; border-color: #dc2626; }
</style>

<div class="header-detail">
    <a href="{{ route('admin.items.index') }}" class="btn-kembali">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
        KEMBALI KE INVENTARIS
    </a>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.items.edit', $item->id) }}" class="btn-aksi-rincian btn-edit-aset">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            EDIT ASET
        </a>
        <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus aset ini secara permanen?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-aksi-rincian btn-hapus-aset">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                HAPUS
            </button>
        </form>
    </div>
</div>

<div class="grid-rincian">
    {{-- Sisi Kiri: Visual & Status Stok --}}
    <div class="kartu-visual">
        <div class="bingkai-foto">
            <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/placeholder.png') }}" alt="{{ $item->name }}">
        </div>
        
        @php 
            $statusClass = $item->condition == 'Good' ? 'aksen-siap' : ($item->condition == 'Repair' ? 'aksen-servis' : 'aksen-rusak');
            $teksKondisi = $item->condition == 'Good' ? 'NORMAL / PRIMA' : ($item->condition == 'Repair' ? 'DALAM SERVIS' : 'RUSAK TOTAL');
        @endphp
        
        <div class="label-kondisi-besar {{ $statusClass }}">
            <div style="width: 8px; height: 8px; border-radius: 50%; background: currentColor;"></div>
            KONDISI: {{ $teksKondisi }}
        </div>

        <div style="margin-top: 25px; padding: 22px; border-radius: 16px; background: #f8fafc; border: 1px solid #f1f5f9;">
            <div class="label-spek" style="margin-bottom: 12px; color: #475569;">Ringkasan Stok</div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 26px; font-weight: 800; color: #0f172a;">{{ $item->quantity }} <small style="font-size: 13px; color: #94a3b8; font-weight: 600;">Unit</small></div>
                    <div style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-top: 4px;">Total Inventaris</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 15px; font-weight: 800; color: #16a34a;">{{ $item->quantity }} <span style="font-size: 11px;">Tersedia</span></div>
                    <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-top: 4px;">Aset Aktif</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Informasi Detail & Riwayat --}}
    <div>
        <div class="kartu-info">
            <span class="tag-id">#LABEL-ASET-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
            <h1 class="judul-aset">{{ $item->name }}</h1>
            
            <p style="color: #64748b; line-height: 1.7; font-size: 15px; margin-bottom: 0;">
                {{ $item->description ?? 'Tidak ada keterangan tambahan untuk aset ini. Barang ini merupakan bagian dari aset resmi yang terdaftar dalam ekosistem sistem Inventra.' }}
            </p>

            <div class="grid-spek">
                <div class="item-spek">
                    <span class="label-spek">Kelompok Kategori</span>
                    <span class="nilai-spek">{{ $item->category->name ?? 'Umum / General' }}</span>
                </div>
                <div class="item-spek">
                    <span class="label-spek">Ruangan / Lokasi</span>
                    <span class="nilai-spek">{{ $item->room ?? 'Gudang Utama' }}</span>
                </div>
                <div class="item-spek">
                    <span class="label-spek">Status Pemeliharaan</span>
                    <span class="nilai-spek">{{ $item->condition == 'Good' ? 'Tidak Ada Kendala' : 'Sedang Ditinjau' }}</span>
                </div>
                <div class="item-spek">
                    <span class="label-spek">Stok Awal Terdaftar</span>
                    <span class="nilai-spek">{{ $item->quantity }} Unit</span>
                </div>
            </div>
        </div>

        {{-- SEKSI RIWAYAT PEMINJAMAN --}}
        <div class="seksi-riwayat">
            <h3 class="judul-riwayat">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Peminjaman Terkini
            </h3>
            
            <div class="wadah-tabel-mini">
                <table class="tabel-mini">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Status</th>
                            <th style="text-align: right;">Petugas / Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Logika orderBy ID desc karena created_at tidak tersedia --}}
                        @forelse($item->loans()->reorder()->orderBy('id', 'desc')->take(5)->get() as $loan)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div class="inisial-user">{{ strtoupper(substr($loan->user->name, 0, 1)) }}</div>
                                    <span style="font-weight: 700; color: #1e293b;">{{ $loan->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span style="color: #475569; font-weight: 600;">
                                    {{ $loan->loan_date ? $loan->loan_date->format('d M Y') : '-' }}
                                </span>
                            </td>
                            <td>
                                @php $isReturned = $loan->status == 'returned'; @endphp
                                <span style="font-size: 9px; font-weight: 800; color: {{ $isReturned ? '#16a34a' : '#2563eb' }}; background: {{ $isReturned ? '#f0fdf4' : '#eff6ff' }}; padding: 4px 10px; border-radius: 6px; border: 1px solid currentColor;">
                                    {{ $isReturned ? 'DIKEMBALIKAN' : 'DIPINJAM' }}
                                </span>
                            </td>
                            <td style="text-align: right; font-size: 11px; color: #94a3b8; font-weight: 700;">
                                OLEH: {{ strtoupper($loan->admin->name ?? 'SISTEM') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 60px; color: #94a3b8; font-weight: 600; font-size: 14px;">
                                <p style="margin-bottom: 5px;">Aset ini belum pernah dipinjam.</p>
                                <small style="font-weight: 500; opacity: 0.7;">Seluruh riwayat transaksi akan muncul di sini secara otomatis.</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection