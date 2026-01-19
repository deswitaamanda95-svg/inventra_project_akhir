<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Item;
use App\Models\User;
use App\Models\MaintenanceLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['item', 'user', 'admin'])->orderBy('id', 'desc')->get();
        return view('admin.loans.index', compact('loans'));
    }

    private function checkUserRestrictions($userId)
    {
        $overdue = Loan::where('user_id', $userId)
            ->where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now())
            ->exists();

        if ($overdue) return "User memiliki aset yang melewati tenggat pengembalian.";

        $pendingPayment = MaintenanceLog::where('user_id', $userId)
            ->where('payment_status', 'pending')
            ->exists();

        if ($pendingPayment) return "User memiliki tunggakan biaya perbaikan aset.";

        return null;
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date|after:today',
        ]);

        $error = $this->checkUserRestrictions($request->user_id);
        if ($error) return back()->with('error', 'Akses Ditolak: ' . $error);

        DB::beginTransaction();
        try {
            $item = Item::findOrFail($request->item_id);
            if ($item->quantity <= 0) return back()->with('error', 'Stok fisik habis.');

            $item->decrement('quantity');
            Loan::create([
                'item_id'     => $request->item_id,
                'user_id'     => $request->user_id,
                'loan_date'   => now(),
                'due_date'    => $request->due_date,
                'status'      => 'borrowed',
                'approved_by' => Auth::id()
            ]);

            DB::commit();
            return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses data peminjaman.');
        }
    }

    public function approve(Request $request, Loan $loan)
    {
        $request->validate(['due_date' => 'required|date|after:today']);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $loan->item->decrement('quantity');
            $loan->update([
                'status'      => 'borrowed',
                'due_date'    => $request->due_date,
                'approved_by' => Auth::id(),
                'loan_date'   => now()
            ]);

            DB::commit();
            return back()->with('success', 'Permintaan peminjaman telah disetujui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyetujui permintaan: ' . $e->getMessage());
        }
    }

    public function confirmReturn(Request $request, Loan $loan)
    {
        $request->validate([
            'return_condition' => 'required|in:Good,Repair,Broken',
            'repair_cost'      => 'required_if:return_condition,Repair,Broken|numeric|min:0',
            'damage_note'      => 'required_if:return_condition,Repair,Broken|string',
            'technician_name'  => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $condition = $request->return_condition;

            if ($condition == 'Good') {
                $loan->item->increment('quantity');
            } else {
                \App\Models\MaintenanceLog::create([
                    'item_id'         => $loan->item_id,
                    'user_id'         => $loan->user_id,
                    'repair_cost'     => $request->repair_cost ?? 0,
                    'damage_note'     => $request->damage_note,
                    'technician_name' => $request->filled('technician_name') ? $request->technician_name : 'INTERNAL',
                    'payment_status'  => 'pending',
                    // PENTING: Gunakan 'ongoing' karena 'broken' tidak ada di ENUM database Anda
                    'status'          => 'ongoing',
                    'start_date'      => now()
                ]);

                $condition == 'Repair' ? $loan->item->increment('quantity_repair') : $loan->item->increment('quantity_broken');
            }

            $loan->update(['status' => 'returned', 'return_date' => now()]);
            DB::commit();
            return back()->with('success', "Aset berhasil dikembalikan.");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
