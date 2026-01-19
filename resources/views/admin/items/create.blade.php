@extends('layouts.admin')

@section('content')
<style>
    /* --- Tata Letak & Fondasi --- */
    .form-wrapper { max-width: 640px; margin: 0 auto; padding-bottom: 60px; }
    .card-form { 
        background: white; border-radius: 20px; border: 1px solid #f1f5f9; 
        padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); 
    }

    /* --- Tipografi & Label --- */
    .judul-utama { font-size: 26px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.02em; }
    .sub-judul { color: #64748b; font-size: 15px; margin-top: 6px; margin-bottom: 35px; }
    .label-sys { 
        display: block; font-size: 10px; font-weight: 800; color: #94a3b8; 
        text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px; 
    }

    /* --- Komponen Input Sistematis --- */
    .input-sys { 
        width: 100%; padding: 12px 18px; border-radius: 12px; border: 1px solid #e2e8f0; 
        font-size: 14px; font-weight: 600; outline: none; transition: 0.2s; background: #f8fafc; 
    }
    .input-sys:focus { 
        border-color: #2563eb; background: white; 
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08); 
    }

    /* --- Preview Gambar --- */
    .preview-bingkai { 
        width: 100%; height: 220px; border-radius: 16px; border: 2px dashed #e2e8f0; 
        display: flex; align-items: center; justify-content: center; overflow: hidden; 
        background: #f8fafc; margin-bottom: 15px; transition: 0.3s;
    }
    .preview-bingkai img { width: 100%; height: 100%; object-fit: cover; }
    .preview-placeholder { color: #cbd5e1; font-size: 13px; font-weight: 700; text-align: center; }

    /* --- Tombol Aksi --- */
    .btn-utama { 
        background: #0f172a; color: white; border: none; padding: 16px; 
        border-radius: 14px; font-weight: 800; cursor: pointer; width: 100%; 
        font-size: 14px; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.2);
    }
    .btn-utama:hover { background: #1e293b; transform: translateY(-2px); }
    
    .btn-batal { 
        display: block; text-align: center; margin-top: 20px; color: #94a3b8; 
        font-size: 13px; font-weight: 700; text-decoration: none; transition: 0.2s;
    }
    .btn-batal:hover { color: #475569; }

    .teks-error { color: #ef4444; font-size: 12px; font-weight: 700; margin-top: 6px; display: block; }
</style>

<div class="form-wrapper">
    {{-- Header Halaman --}}
    <div style="text-align: center;">
        <h2 class="judul-utama">Daftarkan Aset Baru</h2>
        <p class="sub-judul">Tambahkan unit barang atau peralatan baru ke dalam basis data inventaris Anda.</p>
    </div>

    <div class="card-form">
        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Bagian Upload Gambar --}}
            <div style="margin-bottom: 24px;">
                <label class="label-sys">Foto Dokumentasi Barang</label>
                <div class="preview-bingkai" id="imagePreview">
                    <div class="preview-placeholder">
                        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-bottom: 10px; display: block; margin-left: auto; margin-right: auto;"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Belum ada foto terpilih
                    </div>
                </div>
                <input type="file" name="image" class="input-sys" accept="image/*" onchange="previewImage(this)" style="padding: 10px;">
                @error('image') <span class="teks-error">{{ $message }}</span> @enderror
            </div>

            {{-- Nama Barang --}}
            <div style="margin-bottom: 24px;">
                <label class="label-sys">Nama Barang / Aset</label>
                <input type="text" name="name" class="input-sys" value="{{ old('name') }}" placeholder="Contoh: MacBook Pro M2 2023" required>
                @error('name') <span class="teks-error">{{ $message }}</span> @enderror
            </div>

            {{-- Kategori --}}
            <div style="margin-bottom: 24px;">
                <label class="label-sys">Kategori Klasifikasi</label>
                <select name="category_id" class="input-sys" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Jumlah & Kondisi --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label class="label-sys">Jumlah Stok</label>
                    <input type="number" name="quantity" class="input-sys" value="{{ old('quantity', 1) }}" min="1" required>
                </div>
                <div>
                    <label class="label-sys">Kondisi Fisik</label>
                    <select name="condition" class="input-sys">
                        <option value="Good">BAIK (NORMAL)</option>
                        <option value="Repair">PERLU SERVIS</option>
                        <option value="Broken">RUSAK BERAT</option>
                    </select>
                </div>
            </div>

            {{-- Lokasi Penempatan --}}
            <div style="margin-bottom: 24px;">
                <label class="label-sys">Lokasi Penempatan / Ruangan</label>
                <input type="text" name="room" class="input-sys" value="{{ old('room') }}" placeholder="Contoh: Lab Komputer 1 atau Gudang B" required>
            </div>

            {{-- Deskripsi Tambahan --}}
            <div style="margin-bottom: 35px;">
                <label class="label-sys">Catatan Tambahan (Opsional)</label>
                <textarea name="description" class="input-sys" rows="3" style="resize: none;" placeholder="Berikan rincian tambahan jika diperlukan...">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn-utama">DAFTARKAN ASET</button>
            <a href="{{ route('admin.items.index') }}" class="btn-batal">Batal dan Kembali</a>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const bingkai = document.getElementById('imagePreview');
                bingkai.style.borderStyle = 'solid';
                bingkai.style.borderColor = '#2563eb';
                bingkai.innerHTML = `<img src="${e.target.result}">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection