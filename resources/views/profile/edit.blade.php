@extends('layouts.admin')

@section('content')
{{-- Container Utama untuk Centering --}}
<div style="display: flex; justify-content: center; align-items: flex-start; min-height: 100vh; padding: 20px 0;">
    
    {{-- Pembungkus Utama (Max Width 900px agar serasi dengan Edit User) --}}
    <div style="width: 100%; max-width: 900px;">
        
        {{-- Header Halaman --}}
        <div style="margin-bottom: 35px;">
            <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; margin: 0;">Personal Settings</h2>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Kelola identitas visual dan lapisan keamanan akun Anda.</p>
        </div>

        @if(session('success'))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 15px; border-radius: 12px; margin-bottom: 25px; font-size: 14px; font-weight: 700;">
                {{ session('success') }}
            </div>
        @endif

        {{-- BAGIAN 1: INFORMASI PROFIL & AVATAR --}}
        <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); margin-bottom: 30px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 30px;">
                <div style="background: #eff6ff; color: #2563eb; padding: 8px; border-radius: 10px;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0;">Profil Akun</h3>
            </div>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 35px; padding: 25px; background: #f8fafc; border-radius: 20px; border: 1px solid #f1f5f9;">
                    <div id="avatarPreview" style="width: 100px; height: 100px; border-radius: 50%; background: white; border: 4px solid white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-size: 32px; font-weight: 800; color: #2563eb;">{{ substr($user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">Foto Profil Baru</label>
                        <div style="position: relative; display: inline-block;">
                            <button type="button" style="background: white; border: 1.5px solid #e2e8f0; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">Pilih File Gambar</button>
                            <input type="file" name="avatar" accept="image/*" onchange="previewImage(this)" style="position: absolute; inset: 0; opacity: 0; cursor: pointer;">
                        </div>
                        <p style="color: #94a3b8; font-size: 11px; margin-top: 8px;">JPG, PNG atau JPEG. Maksimal 2MB.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">Nama Lengkap</label>
                        <input type="text" name="name" class="input-field" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">Alamat Email</label>
                        <input type="email" name="email" class="input-field" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; outline: none;">
                    </div>
                </div>

                <div style="margin-top: 30px; text-align: right;">
                    <button type="submit" style="background: #2563eb; color: white; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s;">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        {{-- BAGIAN 2: KEAMANAN PASSWORD --}}
        <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); margin-bottom: 30px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 30px;">
                <div style="background: #fff1f2; color: #be123c; padding: 8px; border-radius: 10px;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0;">Keamanan Password</h3>
            </div>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div style="margin-bottom: 25px; max-width: 400px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">Password Saat Ini</label>
                    <input type="password" name="current_password" style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; outline: none;" placeholder="••••••••">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">Password Baru</label>
                        <input type="password" name="password" style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; outline: none;" placeholder="Minimal 8 karakter">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; outline: none;" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div style="margin-top: 30px; text-align: right;">
                    <button type="submit" style="background: #0f172a; color: white; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s;">Update Password</button>
                </div>
            </form>
        </div>

        {{-- BAGIAN 3: DANGER ZONE (HAPUS AKUN) --}}
        <div style="background: #fff1f2; border-radius: 24px; border: 1px solid #fee2e2; padding: 40px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="font-size: 18px; font-weight: 800; color: #9f1239; margin: 0;">Danger Zone</h3>
                    <p style="color: #be123c; font-size: 13px; margin-top: 5px;">Setelah akun dihapus, semua data peminjaman Anda akan diarsipkan secara permanen.</p>
                </div>
                <button type="button" onclick="confirmDelete()" style="background: #e11d48; color: white; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 700; cursor: pointer;">Hapus Akun</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HAPUS AKUN (Opsional: Butuh SweetAlert atau JS sederhana) --}}
<form id="delete-account-form" action="{{ route('profile.destroy') }}" method="POST" style="display: none;">
    @csrf
    @method('delete')
</form>

<script>
    function previewImage(input) {
        const preview = document.getElementById('avatarPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function confirmDelete() {
        if (confirm('PERINGATAN: Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('delete-account-form').submit();
        }
    }
</script>

<style>
    input:focus { border-color: #2563eb !important; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
    button:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
</style>
@endsection