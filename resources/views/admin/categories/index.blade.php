@extends('layouts.admin')

@section('content')
<style>
    /* --- Fondasi & Tata Letak --- */
    .header-kategori {
        display: flex; justify-content: space-between; align-items: flex-end;
        margin-bottom: 32px;
    }

    .card-tabel {
        background: white; border-radius: 16px; border: 1px solid #f1f5f9;
        overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    /* --- Desain Tabel Sistematis --- */
    .tabel-inventra { width: 100%; border-collapse: collapse; }
    .tabel-inventra th {
        background: #f8fafc; padding: 14px 24px; text-align: left;
        font-size: 10px; font-weight: 800; text-transform: uppercase;
        color: #94a3b8; letter-spacing: 0.05em; border-bottom: 1px solid #f1f5f9;
    }
    .tabel-inventra td {
        padding: 16px 24px; border-bottom: 1px solid #f1f5f9;
        font-size: 14px; color: #475569; vertical-align: middle;
    }

    /* --- Komponen Kecil & Ringkas --- */
    .identitas-kategori { font-weight: 700; color: #1e293b; }
    .id-tag {
        display: inline-block; font-size: 10px; font-weight: 800;
        color: #94a3b8; margin-top: 4px;
    }

    .badge-notif {
        background: #f0fdf4; border: 1px solid #dcfce7; color: #16a34a;
        padding: 12px 20px; border-radius: 12px; margin-bottom: 24px;
        font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;
    }

    /* --- Tombol Aksi --- */
    .btn-aksi {
        padding: 6px 12px; border-radius: 8px; font-size: 11px;
        font-weight: 700; text-decoration: none; border: 1.5px solid #e2e8f0;
        color: #64748b; transition: 0.2s; background: white; cursor: pointer;
    }
    .btn-aksi:hover { border-color: #3b82f6; color: #3b82f6; background: #f0f7ff; }
    
    .btn-tambah {
        background: #0f172a; color: white; padding: 10px 18px;
        border-radius: 10px; font-weight: 700; text-decoration: none;
        font-size: 12px; transition: 0.3s; border: none;
    }
    .btn-tambah:hover { background: #1e293b; transform: translateY(-1px); }

    .btn-hapus { border-color: #fee2e2; color: #dc2626; }
    .btn-hapus:hover { border-color: #dc2626; background: #fef2f2; color: #dc2626; }
</style>

<div class="header-kategori">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0;">Manajemen Kategori</h2>
        <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Kelola klasifikasi aset untuk pengorganisasian inventaris.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-tambah">
        + Tambah Kategori
    </a>
</div>

{{-- Notifikasi Sukses --}}
@if(session('success'))
    <div class="badge-notif">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
@endif

<div class="card-tabel">
    <table class="tabel-inventra">
        <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th>Identitas Kategori</th>
                <th>Keterangan</th>
                <th style="text-align: center; width: 200px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td style="color: #94a3b8; font-weight: 600;">{{ $loop->iteration }}</td>
                <td>
                    <div class="identitas-kategori">{{ $category->name }}</div>
                    <div class="id-tag">#CAT-{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td style="color: #64748b; font-size: 13px; line-height: 1.5;">
                    {{ $category->description ?? 'Tidak ada keterangan tambahan.' }}
                </td>
                <td>
                    <div style="display: flex; gap: 8px; justify-content: center;">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-aksi">
                            EDIT
                        </a>
                        
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Seluruh aset terkait mungkin akan terdampak.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-aksi btn-hapus">
                                HAPUS
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 100px 24px; text-align: center;">
                    <div style="color: #94a3b8;">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 16px; opacity: 0.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        <p style="font-weight: 600; font-size: 14px; margin: 0;">Belum ada kategori yang terdaftar.</p>
                        <p style="font-size: 12px; margin-top: 4px;">Klik tombol di pojok kanan atas untuk menambah data baru.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection