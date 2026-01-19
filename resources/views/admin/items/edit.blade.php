@extends('layouts.admin')

@section('content')
<style>
    /* --- Fondasi & Tata Letak --- */
    .pembungkus-utama { 
        max-width: 850px; 
        margin: 0 auto; 
        padding-bottom: 60px; 
    }

    .btn-kembali { 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        color: #94a3b8; 
        text-decoration: none; 
        font-weight: 700; 
        font-size: 13px; 
        transition: 0.2s; 
        margin-bottom: 25px;
    }
    .btn-kembali:hover { color: #2563eb; transform: translateX(-3px); }

    /* --- Wadah Formulir Premium --- */
    .kartu-form { 
        background: white; 
        border-radius: 20px; 
        border: 1px solid #f1f5f9; 
        padding: 40px; 
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); 
        position: relative; 
    }
    .kartu-form::before { 
        content: ""; position: absolute; top: 0; left: 0; right: 0; height: 5px; 
        background: #2563eb; border-radius: 20px 20px 0 0; 
    }

    /* --- Pratinjau Gambar --- */
    .bingkai-pratinjau { 
        width: 100%; 
        height: 340px; 
        border-radius: 16px; 
        border: 2px dashed #e2e8f0; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin-bottom: 20px; 
        overflow: hidden; 
        background: #f8fafc; 
        transition: 0.3s;
    }
    .bingkai-pratinjau img { width: 100%; height: 100%; object-fit: contain; }

    /* --- Elemen Tipografi & Input --- */
    .label-sys { 
        display: block; 
        font-size: 10px; 
        font-weight: 800; 
        color: #94a3b8; 
        text-transform: uppercase; 
        margin-bottom: 10px; 
        letter-spacing: 0.08em; 
    }
    .input-sys { 
        width: 100%; 
        padding: 12px 16px; 
        border-radius: 12px; 
        border: 1px solid #e2e8f0; 
        font-size: 14px; 
        color: #1e293b;
        outline: none; 
        transition: 0.2s; 
        background-color: #f8fafc; 
        box-sizing: border-box; 
    }
    .input-sys:focus { 
        border-color: #2563eb; 
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08); 
        background-color: #fff; 
    }

    /* --- Grid Manajemen Kondisi --- */
    .grid-kondisi { 
        display: grid; 
        grid-template-columns: repeat(3, 1fr); 
        gap: 20px; 
        margin-top: 15px;
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
        margin-top: 35px; 
        font-size: 14px; 
    }
    .btn-simpan:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.2); }
</style>

<div class="pembungkus-utama">
    {{-- Navigasi Kembali --}}
    <a href="{{ route('admin.items.index') }}" class="btn-back">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Inventaris
    </a>

    {{-- Judul Halaman --}}
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.02em;">Perbarui Informasi Aset</h2>
        <p style="color: #64748b; font-size: 15px; margin-top: 6px;">ID Aset: <span style="font-weight: 700; color: #1e293b;">#{{ $item->id }}</span> — <strong>{{ $item->name }}</strong></p>
    </div>

    <div class="kartu-form">
        <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf 
            @method('PUT') 
            
            {{-- Manajemen Gambar --}}
            <label class="label-sys">Foto Dokumentasi Aset</label>
            <div class="bingkai-pratinjau" id="bingkai-pratinjau">
                <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/placeholder.png') }}" id="img-preview">
            </div>
            <input type="file" name="image" id="input-gambar" class="input-sys" accept="image/*" onchange="previewImage()" style="margin-bottom: 30px; padding: 10px;">

            {{-- Informasi Dasar --}}
            <div style="margin-bottom: 25px;">
                <label class="label-sys">Nama Barang / Model</label>
                <input type="text" name="name" class="input-sys" value="{{ old('name', $item->name) }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div>
                    <label class="label-sys">Kategori Klasifikasi</label>
                    <select name="category_id" class="input-sys" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label-sys">Lokasi Penempatan</label>
                    <input type="text" name="room" class="input-sys" value="{{ old('room', $item->room) }}" required>
                </div>
            </div>

            {{-- Manajemen Stok Berdasarkan Kondisi --}}
            <div class="grid-kondisi">
                <div>
                    <label class="label-sys" style="color: #10b981;">Unit Normal (Siap Pakai)</label>
                    <input type="number" name="quantity" class="input-sys" value="{{ old('quantity', $item->quantity) }}" min="0" required>
                </div>
                <div>
                    <label class="label-sys" style="color: #f59e0b;">Unit Perlu Servis (Repair)</label>
                    <input type="number" name="quantity_repair" class="input-sys" value="{{ old('quantity_repair', $item->quantity_repair) }}" min="0" required>
                </div>
                <div>
                    <label class="label-sys" style="color: #ef4444;">Unit Rusak Total (Broken)</label>
                    <input type="number" name="quantity_broken" class="input-sys" value="{{ old('quantity_broken', $item->quantity_broken) }}" min="0" required>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div style="margin-top: 30px;">
                <label class="label-sys">Catatan Tambahan (Opsional)</label>
                <textarea name="description" class="input-sys" style="height: 100px; resize: none;" placeholder="Tambahkan rincian spesifikasi atau catatan kondisi...">{{ old('description', $item->description) }}</textarea>
            </div>

            <button type="submit" class="btn-simpan">SIMPAN PERUBAHAN DATA</button>
        </form>
    </div>
</div>

<script>
    /**
     * Fungsi pratinjau gambar otomatis saat file dipilih
     */
    function previewImage() {
        const input = document.getElementById('input-gambar');
        const box = document.getElementById('bingkai-pratinjau');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { 
                box.style.borderStyle = 'solid';
                box.style.borderColor = '#2563eb';
                box.innerHTML = `<img src="${e.target.result}">`; 
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection