<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceLog::with(['item', 'user']);

        // Filter berdasarkan user jika dipilih
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->orderBy('id', 'desc')->get();
        $itemsInRepair = Item::where('quantity_repair', '>', 0)->get();
        $users = User::where('role', 'user')->orderBy('name', 'asc')->get();

        return view('admin.maintenance.index', compact('logs', 'itemsInRepair', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'damage_note' => 'required|string',
            'technician_name' => 'required|string',
            'start_date' => 'required|date',
        ]);

        // Cari peminjam terakhir secara otomatis
        $lastLoan = Loan::where('item_id', $request->item_id)
            ->orderBy('id', 'desc')
            ->first();

        MaintenanceLog::create([
            'item_id' => $request->item_id,
            'user_id' => $lastLoan ? $lastLoan->user_id : null,
            'damage_note' => $request->damage_note,
            'technician_name' => $request->technician_name,
            'start_date' => $request->start_date,
            'estimated_finish' => $request->estimated_finish,
            'status' => 'ongoing',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('admin.maintenance.index')->with('success', 'Log berhasil didaftarkan.');
    }

    public function finish(Request $request, MaintenanceLog $log)
    {
        $request->validate(['repair_cost' => 'required|numeric|min:0']);

        DB::beginTransaction();
        try {
            $item = $log->item;
            if ($item->quantity_repair > 0) {
                $item->decrement('quantity_repair');
                $item->increment('quantity');
            }

            $log->update([
                'status' => 'fixed',
                'completion_date' => now(),
                'repair_cost' => $request->repair_cost,
                'payment_status' => 'pending'
            ]);

            DB::commit();
            return back()->with('success', 'Perbaikan selesai.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        $query = MaintenanceLog::with(['item', 'user']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
            $targetUser = User::find($request->user_id);
            $title = "LAPORAN PEMELIHARAAN - " . strtoupper($targetUser->name);
        } else {
            $title = "LAPORAN PEMELIHARAAN KESELURUHAN";
        }

        $logs = $query->orderBy('created_at', 'desc')->get();
        $companyName = "PT. INVENTRA TEKNOLOGI INDONESIA";

        $pdf = Pdf::loadView('admin.maintenance.report', compact('logs', 'companyName', 'title'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Maintenance_Inventra.pdf');
    }

    public function printIndividual(MaintenanceLog $log)
    {
        $log->load(['item', 'user']);
        $companyName = "PT. INVENTRA TEKNOLOGI INDONESIA";
        $pdf = Pdf::loadView('admin.maintenance.print_individual', compact('log', 'companyName'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Berita_Acara_Kerusakan_' . $log->id . '.pdf');
    }

    public function pay(MaintenanceLog $log)
    {
        // Mengubah status pembayaran menjadi lunas
        $log->update(['payment_status' => 'paid']);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi. Status aset kini SELESAI.');
    }
}
