@extends('layouts.admin')

@section('content')
<style>
    /* --- Fondasi & Tipografi --- */
    .header-halaman { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }
    .judul-halaman { font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; margin: 0; }
    .sub-judul { color: #64748b; font-size: 14px; margin-top: 4px; }

    /* --- Wadah Tabel Premium --- */
    .wadah-tabel { 
        background: white; border-radius: 16px; border: 1px solid #f1f5f9; 
        overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); 
    }
    .tabel-modern { width: 100%; border-collapse: collapse; }
    .tabel-modern th { 
        background: #f8fafc; padding: 14px 24px; text-align: left; 
        font-size: 10px; font-weight: 800; color: #94a3b8; 
        text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #f1f5f9; 
    }
    .tabel-modern td { padding: 16px 24px; border-bottom: 1px solid #f8fafc; font-size: 14px; vertical-align: middle; color: #475569; }
    .tabel-modern tr:hover td { background: #fcfdfe; }

    /* --- Identitas Pengguna --- */
    .info-pengguna { display: flex; align-items: center; gap: 14px; }
    .avatar-kecil { 
        width: 42px; height: 42px; border-radius: 12px; 
        object-fit: cover; border: 1px solid #f1f5f9; flex-shrink: 0; 
    }
    .nama-pengguna { font-weight: 700; color: #1e293b; font-size: 14px; line-height: 1.2; }
    .tag-id { 
        font-family: 'JetBrains Mono', monospace; font-size: 9px; color: #94a3b8; 
        background: #f8fafc; padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0; margin-left: 5px; 
    }

    /* --- Status & Label Hak Akses --- */
    .pill-akses {
        display: inline-flex; align-items: center; gap: 6px; 
        padding: 5px 12px; border-radius: 100px; font-size: 10px; font-weight: 800;
        letter-spacing: 0.03em;
    }
    .a-admin { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
    .a-user { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    /* --- Tombol Aksi Ringkas (Sistematis) --- */
    .btn-aksi-sm {
        padding: 6px 14px; border-radius: 8px; font-size: 11px; font-weight: 700;
        text-decoration: none; border: 1.5px solid #e2e8f0; color: #64748b;
        transition: 0.2s; background: white; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-aksi-sm:hover { border-color: #2563eb; color: #2563eb; background: #f0f7ff; }
    .btn-hapus { border-color: #fee2e2; color: #ef4444; }
    .btn-hapus:hover { background: #fef2f2; border-color: #ef4444; }

    .btn-tambah {
        background: #0f172a; color: white; padding: 10px 20px; border-radius: 10px; 
        font-weight: 700; text-decoration: none; font-size: 13px; transition: 0.3s;
        display: flex; align-items: center; gap: 8px;
    }
    .btn-tambah:hover { background: #1e293b; transform: translateY(-1px); }
</style>

<div class="header-halaman">
    <div>
        <h2 class="judul-halaman">Direktori Pengguna</h2>
        <p class="sub-judul">Kelola otoritas akses dan akun personel dalam ekosistem sistem <b>INVENTRA</b>.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-tambah">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        TAMBAH AKUN
    </a>
</div>

{{-- Alert Notifikasi Luwes --}}
@if(session('success'))
    <div style="background: #f0fdf4; border: 1px solid #dcfce7; color: #16a34a; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
@endif

<div class="wadah-tabel">
    <table class="tabel-modern">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center;">No</th> 
                <th style="width: 35%;">Informasi Pengguna</th>
                <th>Alamat Email</th>
                <th>Tingkat Akses</th>
                <th style="text-align: right;">Opsi Pengelola</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td style="text-align: center; color: #cbd5e1; font-weight: 800;">{{ $loop->iteration }}</td>
                
                <td>
                    <div class="info-pengguna">
                        {{-- Fallback Avatar Otomatis yang Terpadu --}}
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f8fafc&color=94a3b8&bold=true' }}" 
                             class="avatar-kecil" alt="Profil">
                        <div style="display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: center;">
                                <span class="nama-pengguna">{{ $user->name }}</span>
                                <span class="tag-id">#ID-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <span style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-top: 3px;">
                                Anggota Aktif
                            </span>
                        </div>
                    </div>
                </td>

                <td style="color: #475569; font-weight: 600;">{{ $user->email }}</td>
                
                <td>
                    <span class="pill-akses {{ $user->role == 'admin' ? 'a-admin' : 'a-user' }}">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></div>
                        {{ strtoupper($user->role == 'admin' ? 'Admin' : 'User') }}
                    </span>
                </td>

                <td>
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-aksi-sm">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            EDIT
                        </a>
                        
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini secara permanen? Seluruh riwayat peminjaman mungkin akan terpengaruh.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-aksi-sm btn-hapus">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                HAPUS
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 100px; text-align: center; color: #94a3b8; font-weight: 700; font-size: 14px;">
                    Belum ada data pengguna yang terdaftar di basis data.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection