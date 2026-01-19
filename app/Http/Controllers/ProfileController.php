<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil premium.
     */
    public function edit(Request $request): View 
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memproses pembaruan informasi dasar & avatar.
     */
    public function update(Request $request): RedirectResponse 
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Logika Pengunggahan Avatar
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada untuk menghemat penyimpanan
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;

        // Jika email berubah, Anda mungkin ingin membatalkan verifikasi email (opsional)
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save(); 

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Memproses pembaruan keamanan (Password).
     */
    public function updatePassword(Request $request): RedirectResponse 
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Keamanan akun: Password berhasil diganti!');
    }

    /**
     * PERBAIKAN: Menambahkan method destroy yang ada di rute web.php
     * Mencegah error "Method destroy does not exist"
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Hapus avatar fisik sebelum menghapus akun
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus dari sistem.');
    }
}