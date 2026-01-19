<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Admin Paling Aktif Bulan Ini
        $topAdmins = User::where('role', 'admin')
            ->withCount(['approvals as total_processed' => function($query) {
                $query->whereMonth('created_at', Carbon::now()->month);
            }])
            ->orderBy('total_processed', 'desc')
            ->take(5)
            ->get();

        // 2. Statistik Kondisi Barang
        $itemStats = [
            'good'   => Item::sum('quantity'),
            'repair' => Item::sum('quantity_repair'),
            'broken' => Item::sum('quantity_broken'),
        ];

        // 3. Total Pinjaman per Bulan (Tahun Ini)
        $monthlyLoans = Loan::select(DB::raw('count(*) as total'), DB::raw('MONTH(loan_date) as month'))
            ->whereYear('loan_date', date('Y'))
            ->groupBy('month')
            ->get();

        return view('admin.reports.index', compact('topAdmins', 'itemStats', 'monthlyLoans'));
    }
}