@extends('layouts.admin')

@section('content')
<style>
    /* --- Tata Letak & Fondasi --- */
    .pembungkus-form { max-width: 640px; margin: 0 auto; padding-bottom: 60px; }
    
    .kartu-premium { 
        background: white; 
        border-radius: 20px; 
        border: 1px solid #f1f5f9; 
        padding: 45px; 
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); 
        position: relative; 
    }
    
    /* Garis Aksen Khas Inventra */
    .kartu-premium::before { 
        content: ""; position: absolute; top: 0; left: 0; right: 0; height: 5px; 
        background: #2563eb; border-radius: 20px 20px 0 0; 
    }

    /* --- Komponen Tipografi & Label --- */
    .label-sys { 
        display: block; 
        font-size: 10px; 
        font-weight: 800; 
        color: #94a3b8; 
        text-transform: uppercase; 
        margin-bottom: 10px; 
        letter-spacing: 0.08em; 
    }
    
    .judul-utama { font-size: 26px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.02em; }
    .sub-judul { color: #64748b; font-size: 15px; margin-top: 6px; margin-bottom: 35px; }

    /* --- Elemen Input --- */
    .input-sys { 
        width: 100%; 
        padding: 14px 18px; 
        border-radius: 12px; 
        border: 1px solid #e2e8f0; 
        font-size: 14px; 
        font-weight: 600; 
        color: #1e293b;
        outline: none; 
        transition: 0.2s; 
        background-color: #f8fafc; 
        box-sizing: border-box; 
        margin-bottom: 25px;
    }
    .input-sys:focus { 
        border-color: #2563eb; 
        background-color: #fff; 
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08); 
    }

    /* --- Tombol Aksi --- */
    .btn-simpan { 
        background: #0f172a; 
        color: white; 
        border: none; 
        padding: 16px; 
        border-radius: 14px; 
        font-weight: 800; 
        cursor: pointer; 
        width: 100%; 
        transition: 0.3s; 
        font-size: 14px; 
        box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.2);
    }
    .btn-simpan:hover { 
        background: #1e293b; 
        transform: translateY(-1px); 
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.25); 
    }
    
    .btn-batal { 
        display: block; 
        text-align: center; 
        margin-top: 20px; 
        color: #94a3b8; 
        text-decoration: none; 
        font-size: 13px; 
        font-weight: 700; 
        transition: 0.2s;
    }
    .btn-batal:hover { color: #475569; }
</style>

<div class="pembungkus-form">
    {{-- Header Section --}}
    <div style="text-align: center;">
        <h2 class="judul-utama">Catat Peminjaman Baru</h2>
        <p class="sub-judul">Daftarkan sirkulasi aset keluar dari gudang secara sistematis.</p>
    </div>

    <div class="kartu-premium">
        <form action="{{ route('admin.loans.store') }}" method="POST">
            @csrf
            
            {{-- Pilihan Aset --}}
            <label class="label-sys">Aset yang Dipinjam</label>
            <select name="item_id" class="input-sys" required>
                <option value="">-- Pilih Barang dari Inventaris --</option>
                @foreach($items as $item)
                    {{-- Menampilkan info stok dan lokasi agar admin tidak salah pilih --}}
                    <option value="{{ $item->id }}">
                        {{ $item->name }} (Tersedia: {{ $item->quantity }} unit di {{ $item->room }})
                    </option>
                @endforeach
            </select>

            {{-- Pilihan Peminjam --}}
            <label class="label-sys">Nama Peminjam (User/Staf)</label>
            <select name="user_id" class="input-sys" required>
                <option value="">-- Pilih Penanggung Jawab --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ strtoupper($user->role) }})</option>
                @endforeach
            </select>

            {{-- Tombol Konfirmasi --}}
            <button type="submit" class="btn-simpan">KONFIRMASI PEMINJAMAN</button>
            
            <a href="{{ route('admin.loans.index') }}" class="btn-batal">Batal dan Kembali</a>
        </form>
    </div>
</div>
@endsection