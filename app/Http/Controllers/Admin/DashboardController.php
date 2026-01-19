<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama
        $pendingRequests = Loan::where('status', 'pending')->count(); 

        // VARIABEL WAJIB: $overdueLoans agar sinkron dengan file Blade
        $overdueLoans = Loan::where('status', 'borrowed')
                            ->where('due_date', '<', now())
                            ->count(); 

        $availableItems = Item::sum('quantity'); 
        $needRepair = Item::sum('quantity_repair'); 

        // 2. Log Aktivitas Terbaru
        $recentActivities = Loan::with(['item', 'user'])
                                ->orderBy('id', 'desc')
                                ->take(6)
                                ->get();

        // 3. Statistik Pendukung
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $brokenItems = Item::sum('quantity_broken');
        $activeLoans = Loan::where('status', 'borrowed')->count();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalCategories', 
            'availableItems',
            'needRepair',
            'brokenItems',
            'pendingRequests',
            'activeLoans',
            'overdueLoans', // Nama variabel ini harus pas
            'recentActivities' 
        ));
    }
}