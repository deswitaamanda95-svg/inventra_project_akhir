@extends('layouts.admin')

@section('content')
<style>
    /* --- Fondasi & Tata Letak --- */
    .pembungkus-pusat { 
        display: flex; flex-direction: column; align-items: center; 
        width: 100%; padding: 20px 0 60px 0; 
    }
    
    .kartu-form-sys { 
        background: #ffffff; 
        border-radius: 16px; 
        padding: 40px; 
        width: 100%; 
        max-width: 560px; 
        border: 1px solid #f1f5f9; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); 
        position: relative; 
    }
    
    /* Garis Aksen Khas Inventra */
    .kartu-form-sys::before { 
        content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px; 
        background: #2563eb; border-radius: 16px 16px 0 0; 
    }
    
    /* --- Elemen Tipografi & Label --- */
    .label-sys { 
        display: block; 
        font-size: 10px; 
        font-weight: 800; 
        color: #94a3b8; 
        text-transform: uppercase; 
        margin-bottom: 8px; 
        letter-spacing: 0.08em; 
    }
    
    .judul-halaman { font-size: 26px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.02em; }
    .sub-deskripsi { color: #64748b; font-size: 14px; margin-top: 6px; margin-bottom: 35px; }

    /* --- Komponen Input --- */
    .input-sys { 
        width: 100%; 
        padding: 12px 16px; 
        border-radius: 10px; 
        border: 1px solid #e2e8f0; 
        font-size: 14px; 
        font-weight: 600; 
        color: #1e293b;
        outline: none; 
        transition: 0.2s; 
        background-color: #f8fafc; 
        box-sizing: border-box; 
    }
    .input-sys:focus { 
        border-color: #2563eb; 
        background-color: #fff; 
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08); 
    }
    
    /* --- Tombol Sistematis & Ringkas --- */
    .grup-tombol { 
        display: flex; gap: 12px; margin-top: 35px; 
        padding-top: 25px; border-top: 1px solid #f8fafc; 
    }
    
    .btn-sys { 
        padding: 12px 24px; border-radius: 10px; font-size: 13px; 
        font-weight: 700; cursor: pointer; border: none; transition: 0.3s; 
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-primary-sys { background: #0f172a; color: white; flex: 2; }
    .btn-primary-sys:hover { background: #1e293b; transform: translateY(-1px); }
    
    .btn-outline-sys { 
        background: #f1f5f9; color: #64748b; text-decoration: none; flex: 1; 
    }
    .btn-outline-sys:hover { background: #e2e8f0; color: #475569; }

    .pesan-error { color: #ef4444; font-size: 11px; font-weight: 700; margin-top: 6px; }
</style>

<div class="pembungkus-pusat">
    <div style="text-align: center;">
        <h2 class="judul-halaman">Tambah Pengguna Baru</h2>
        <p class="sub-deskripsi">Daftarkan personel baru ke dalam ekosistem manajemen aset <b>INVENTRA</b>.</p>
    </div>

    <div class="kartu-form-sys">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            {{-- Nama Lengkap --}}
            <div style="margin-bottom: 24px;">
                <label class="label-sys">Nama Lengkap</label>
                <input type="text" name="name" class="input-sys" placeholder="Misal: Ahmad Zaelani" value="{{ old('name') }}" required autofocus>
                @error('name') <p class="pesan-error">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div style="margin-bottom: 24px;">
                <label class="label-sys">Alamat Email Resmi</label>
                <input type="email" name="email" class="input-sys" placeholder="nama@perusahaan.com" value="{{ old('email') }}" required>
                @error('email') <p class="pesan-error">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div style="margin-bottom: 24px;">
                <label class="label-sys">Kata Sandi Akses</label>
                <input type="password" name="password" class="input-sys" placeholder="Minimal 8 karakter unik" required>
                @error('password') <p class="pesan-error">{{ $message }}</p> @enderror
            </div>

            {{-- Role / Hak Akses --}}
            <div style="margin-bottom: 10px;">
                <label class="label-sys">Peran / Hak Akses Sistem</label>
                <select name="role" class="input-sys" required>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>USER (Peminjam Aset)</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>ADMIN (Pengelola Sistem)</option>
                </select>
            </div>

            {{-- Aksi --}}
            <div class="grup-tombol">
                <button type="submit" class="btn-sys btn-primary-sys">
                    DAFTARKAN AKUN
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn-sys btn-outline-sys">
                    BATAL
                </a>
            </div>
        </form>
    </div>
</div>
@endsection