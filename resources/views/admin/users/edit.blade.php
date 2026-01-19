@extends('layouts.admin')

@section('content')
<style>
    /* --- Fondasi & Tata Letak --- */
    .pembungkus-halaman { 
        display: flex; justify-content: center; align-items: flex-start; 
        min-height: 100vh; padding: 20px 0 60px 0; 
    }
    
    .kontainer-edit { width: 100%; max-width: 860px; }

    /* --- Tombol Kembali & Navigasi --- */
    .btn-kembali { 
        color: #94a3b8; text-decoration: none; display: inline-flex; 
        align-items: center; gap: 8px; font-weight: 700; font-size: 12px; 
        transition: 0.2s; margin-bottom: 25px;
    }
    .btn-kembali:hover { color: #2563eb; transform: translateX(-3px); }

    /* --- Kartu Utama --- */
    .kartu-premium { 
        background: white; border-radius: 20px; border: 1px solid #f1f5f9; 
        padding: 40px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); 
        position: relative; 
    }
    .kartu-premium::before { 
        content: ""; position: absolute; top: 0; left: 0; right: 0; height: 5px; 
        background: #2563eb; border-radius: 20px 20px 0 0; 
    }

    /* --- Tipografi & Label --- */
    .judul-halaman { font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; margin: 0; }
    .sub-judul { color: #64748b; font-size: 14px; margin-top: 4px; }
    
    .label-sys { 
        display: block; font-size: 10px; font-weight: 800; color: #94a3b8; 
        text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.08em; 
    }

    /* --- Komponen Input --- */
    .input-sys { 
        width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0; 
        font-size: 14px; font-weight: 600; color: #1e293b; outline: none; 
        transition: 0.2s; background-color: #f8fafc; box-sizing: border-box; 
    }
    .input-sys:focus { 
        border-color: #2563eb; background-color: #fff; 
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08); 
    }

    /* --- Area Keamanan (Grey Box) --- */
    .kotak-keamanan { 
        background: #f8fafc; padding: 25px; border-radius: 16px; 
        border: 1px solid #f1f5f9; height: 100%; 
    }

    /* --- Tombol Sistematis (Kecil & Rapi) --- */
    .grup-aksi { 
        margin-top: 40px; display: flex; justify-content: flex-end; 
        gap: 12px; border-top: 1px solid #f8fafc; padding-top: 30px; 
    }
    .btn-aksi { 
        padding: 10px 20px; border-radius: 8px; font-size: 12px; 
        font-weight: 700; cursor: pointer; border: none; transition: 0.2s; 
        text-decoration: none; display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-simpan { background: #0f172a; color: #ffffff; min-width: 160px; }
    .btn-simpan:hover { background: #1e293b; transform: translateY(-1px); }
    
    .btn-batal { background: #f1f5f9; color: #64748b; }
    .btn-batal:hover { background: #e2e8f0; color: #475569; }

    .notif-error { 
        background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; 
        padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-size: 13px; 
    }
</style>

<div class="pembungkus-halaman">
    <div class="kontainer-edit"> 
        
        {{-- Header & Navigasi Kembali --}}
        <div style="margin-bottom: 30px; display: flex; align-items: flex-end; justify-content: space-between;">
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn-kembali">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
                    KEMBALI
                </a>
                <h2 class="judul-halaman">Perbarui Akun Pengguna</h2>
                <p class="sub-judul">Sesuaikan data personal dan hak akses untuk <strong>{{ $user->name }}</strong>.</p>
            </div>
            <span style="background: #eff6ff; color: #2563eb; padding: 6px 14px; border-radius: 10px; font-size: 10px; font-weight: 800; letter-spacing: 0.05em; border: 1px solid #dbeafe;">
                ID PENGGUNA: #{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}
            </span>
        </div>

        {{-- Pesan Kesalahan Validasi --}}
        @if ($errors->any())
            <div class="notif-error">
                <strong style="display: block; margin-bottom: 5px;">Mohon periksa kembali:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Kartu Formulir --}}
        <div class="kartu-premium">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 35px;">
                    
                    {{-- Kolom Kiri: Profil Utama --}}
                    <div>
                        <div style="margin-bottom: 24px;">
                            <label class="label-sys">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-sys" required>
                        </div>

                        <div style="margin-bottom: 24px;">
                            <label class="label-sys">Alamat Email Resmi</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-sys" required>
                        </div>

                        <div>
                            <label class="label-sys">Tingkat Akses Sistem</label>
                            <select name="role" class="input-sys" style="background-image: none; cursor: pointer;">
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>USER (Peminjam Aset)</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>ADMIN (Pengelola Inventaris)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Keamanan & Password --}}
                    <div>
                        <div class="kotak-keamanan">
                            <h4 style="font-size: 13px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                                <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Keamanan Akun
                            </h4>
                            <p style="font-size: 11px; color: #94a3b8; margin-bottom: 20px; line-height: 1.5;">
                                Biarkan kosong jika tidak ada perubahan pada kata sandi pengguna.
                            </p>

                            <div style="margin-bottom: 18px;">
                                <label class="label-sys">Kata Sandi Baru</label>
                                <input type="password" name="password" class="input-sys" placeholder="••••••••">
                            </div>

                            <div>
                                <label class="label-sys">Ulangi Kata Sandi</label>
                                <input type="password" name="password_confirmation" class="input-sys" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Baris Tombol Aksi (Kecil & Sistematis) --}}
                <div class="grup-aksi">
                    <a href="{{ route('admin.users.index') }}" class="btn-aksi btn-batal">BATAL</a>
                    <button type="submit" class="btn-aksi btn-simpan">SIMPAN PERUBAHAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection