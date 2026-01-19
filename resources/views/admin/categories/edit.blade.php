@extends('layouts.admin')

@section('content')
<style>
    /* --- Fondasi & Tata Letak --- */
    .form-container {
        display: flex; justify-content: center; align-items: flex-start;
        min-height: 80vh; padding-top: 40px;
    }

    .form-card {
        width: 100%; max-width: 560px; background: #ffffff;
        border-radius: 16px; border: 1px solid #f1f5f9;
        padding: 40px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }

    /* --- Tipografi & Teks --- */
    .text-utama { font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; }
    .text-sub { color: #64748b; font-size: 14px; margin-top: 6px; line-height: 1.5; }
    
    .label-gaya {
        display: block; font-size: 10px; font-weight: 800; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;
    }

    /* --- Komponen Input --- */
    .input-gaya {
        width: 100%; padding: 12px 16px; border-radius: 10px;
        border: 1px solid #e2e8f0; font-size: 14px; outline: none;
        background: #f8fafc; transition: 0.2s; font-weight: 500;
    }
    .input-gaya:focus {
        border-color: #3b82f6; background: #ffffff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .input-error { border-color: #ef4444; background: #fff1f2; }

    /* --- Tombol Sistematis --- */
    .btn-group {
        display: flex; gap: 12px; margin-top: 30px;
        padding-top: 25px; border-top: 1px solid #f1f5f9;
    }
    .btn-aksi {
        padding: 12px 20px; border-radius: 10px; font-size: 13px;
        font-weight: 700; cursor: pointer; border: none; transition: 0.2s;
        text-decoration: none; display: inline-flex; justify-content: center; align-items: center;
    }
    .btn-simpan { background: #2563eb; color: #ffffff; flex: 2; }
    .btn-simpan:hover { background: #1d4ed8; transform: translateY(-1px); }
    
    .btn-batal { background: #f1f5f9; color: #64748b; flex: 1; }
    .btn-batal:hover { background: #e2e8f0; color: #475569; }

    .pesan-error { color: #ef4444; font-size: 12px; margin-top: 6px; font-weight: 600; }
</style>

<div class="form-container">
    <div style="width: 100%; max-width: 560px;">
        {{-- Header Form --}}
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 class="text-utama">Perbarui Kategori</h2>
            <p class="text-sub">Sesuaikan rincian klasifikasi aset Anda untuk menjaga akurasi data inventaris.</p>
        </div>

        {{-- Kartu Form --}}
        <div class="form-card">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- Input Nama Kategori --}}
                <div style="margin-bottom: 20px;">
                    <label class="label-gaya">Nama Kategori</label>
                    <input type="text" 
                           name="name" 
                           class="input-gaya @error('name') input-error @enderror" 
                           value="{{ old('name', $category->name) }}" 
                           required 
                           autofocus>
                    @error('name') 
                        <div class="pesan-error">{{ $message }}</div> 
                    @enderror
                </div>

                {{-- Input Deskripsi --}}
                <div style="margin-bottom: 10px;">
                    <label class="label-gaya">Keterangan Kategori</label>
                    <textarea name="description" 
                              rows="3" 
                              class="input-gaya @error('description') input-error @enderror" 
                              style="resize: none;">{{ old('description', $category->description) }}</textarea>
                    @error('description') 
                        <div class="pesan-error">{{ $message }}</div> 
                    @enderror
                </div>

                {{-- Navigasi Aksi --}}
                <div class="btn-group">
                    <button type="submit" class="btn-aksi btn-simpan">Simpan Perubahan</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn-aksi btn-batal">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection