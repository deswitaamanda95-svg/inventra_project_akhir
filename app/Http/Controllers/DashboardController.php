<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers'      => \App\Models\User::where('role', 'user')->count(),
            'totalCategories' => \App\Models\Category::count(),
            'totalItems'      => \App\Models\Item::count(),
            // Nanti tambahkan 'totalLoans' jika module 4 sudah siap
        ]);
    }
    /**
     * Categories Page
     */
    public function categories()
    {
        $categories = Category::latest()->get();

        return view('admin.categories', compact('categories'));
    }

    /**
     * Items Page
     */
    public function items()
    {
        $items = Item::with('category')->latest()->get();

        return view('admin.items', compact('items'));
    }

    /**
     * Loans Page
     */
    public function loans()
    {
        // Kalau model Loan belum ada, boleh dikosongkan dulu
        $loans = class_exists(Loan::class)
            ? Loan::latest()->get()
            : collect();

        return view('admin.loans', compact('loans'));
    }
}
