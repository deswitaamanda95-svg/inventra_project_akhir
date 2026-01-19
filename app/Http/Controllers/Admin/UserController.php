<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Loan;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user (Admin Mode)
     */
    public function index()
    {
        // Menggunakan orderBy('id', 'desc') karena tabel mungkin tidak memiliki timestamps
        $users = User::orderBy('id', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        // Pastikan model User memiliki property $fillable untuk kolom-kolom ini
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil didaftarkan ke sistem!');
    }

    /**
     * Menampilkan form edit user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'role']);

        // Update password hanya jika kolom diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Menghapus user dari sistem
     */
    public function destroy(User $user)
    {
        // Proteksi agar tidak menghapus diri sendiri saat login
        if (Auth::id() == $user->id) {
            return back()->with('error', 'Keamanan Sistem: Anda tidak diizinkan menghapus akun administrator yang sedang aktif!');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Akun user telah berhasil dihapus dari database.');
    }

    /**
     * Dashboard Personalisasi untuk User
     */
    public function dashboard()
    {
        $userId = Auth::id();

        // Mengambil statistik pinjaman user aktif
        $myActiveLoans = Loan::where('user_id', $userId)->where('status', 'borrowed')->count();
        $totalPending = Loan::where('user_id', $userId)->where('status', 'pending')->count();
        
        // Menghitung pinjaman yang terlambat (Overdue)
        $totalOverdue = Loan::where('user_id', $userId)
                            ->where('status', 'borrowed')
                            ->where('due_date', '<', now())
                            ->count();

        // Menghitung total stok seluruh barang
        $availableItemsCount = Item::sum('quantity');

        // Mengambil riwayat pinjaman terakhir user
        $recentLoans = Loan::where('user_id', $userId)
                           ->with('item')
                           ->orderBy('id', 'desc') 
                           ->take(5)
                           ->get();

        return view('user.dashboard', compact(
            'myActiveLoans', 'totalPending', 'totalOverdue', 'availableItemsCount', 'recentLoans'
        ));
    }
}