<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Utama User
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Menghitung metrik untuk dashboard user
        $myActiveLoans = Loan::where('user_id', $userId)->where('status', 'borrowed')->count();
        $totalPending = Loan::where('user_id', $userId)->where('status', 'pending')->count();
        $totalReturned = Loan::where('user_id', $userId)->where('status', 'returned')->count();
        
        // Menghitung pinjaman yang sudah melewati batas waktu (Overdue)
        $totalOverdue = Loan::where('user_id', $userId)
                            ->where('status', 'borrowed')
                            ->where('due_date', '<', Carbon::now())
                            ->count();

        // 2. Statistik tambahan informasi stok global untuk user
        $availableItemsCount = Item::sum('quantity');

        // 3. PERBAIKAN: Ganti latest() menjadi orderBy('id', 'desc')
        $recentLoans = Loan::with('item')
            ->where('user_id', $userId)
            ->orderBy('id', 'desc') 
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'myActiveLoans', 
            'totalPending', 
            'totalReturned', 
            'totalOverdue',
            'recentLoans',
            'availableItemsCount'
        ));
    }

    /**
     * Menampilkan Katalog Barang
     */
    public function items(Request $request)
    {
        $search = $request->search;

        $items = Item::where('quantity', '>', 0)
                    ->with('category')
                    ->when($search, function($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('room', 'like', "%{$search}%");
                    })
                    // PERBAIKAN: Ganti latest() menjadi orderBy('id', 'desc')
                    ->orderBy('id', 'desc') 
                    ->get();

        return view('user.items.index', compact('items'));
    }

    /**
     * Menampilkan Semua Riwayat Pinjaman User
     */
    public function loans()
    {
        $loans = Loan::where('user_id', Auth::id())
                    ->with(['item', 'admin'])
                    // PERBAIKAN: Ganti latest() menjadi orderBy('id', 'desc')
                    ->orderBy('id', 'desc') 
                    ->get();

        return view('user.loans.index', compact('loans'));
    }

    /**
     * Aksi: Mengajukan Permintaan Pinjam
     */
    public function borrow(Request $request, Item $item)
    {
        $request->validate([
            'due_date' => 'required|date|after:today',
        ]);

        if ($item->quantity <= 0) {
            return back()->with('error', 'Maaf, stok barang baru saja habis.');
        }

        Loan::create([
            'user_id'           => Auth::id(),
            'item_id'           => $item->id,
            'loan_date'         => now(),
            'due_date'          => $request->due_date,
            'quantity_borrowed' => $request->quantity_borrowed, // Tambahkan ini agar jumlah yang diinput tersimpan
            'status'            => 'pending',
        ]);

        return redirect()->route('user.loans.index')
                         ->with('success', 'Permintaan peminjaman ' . $item->name . ' telah dikirim!');
    }

    /**
     * Aksi: Mengajukan Pengembalian Barang
     */
    public function returnRequest(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if ($loan->status !== 'borrowed') {
            return back()->with('error', 'Barang ini tidak dalam status aktif untuk dikembalikan.');
        }

        $loan->update(['status' => 'pending_return']);

        return back()->with('success', 'Permintaan pengembalian dikirim.');
    }

    public function show(Item $item)
    {
        // Memuat relasi kategori untuk detail yang lebih lengkap
        $item->load('category');
        
        // Menghitung stok tersedia saat ini
        $available = $item->quantity - $item->loans()->where('status', 'borrowed')->count();

        return view('user.items.show', compact('item', 'available'));
    }

    public function cancel(Loan $loan)
    {
        // Pastikan user hanya bisa membatalkan miliknya sendiri dan statusnya masih pending
        if ($loan->user_id == auth()->id() && $loan->status == 'pending') {
            $loan->delete();
            return redirect()->back()->with('success', 'Permintaan pinjaman telah dibatalkan.');
        }

        return redirect()->back()->with('error', 'Tidak dapat membatalkan pinjaman ini.');
    }
}