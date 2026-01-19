<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Tambahkan ini
use App\Models\Loan; // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Membagikan variabel $pendingCount ke semua view
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $count = Loan::where('status', 'pending')->count();
                $view->with('pendingCount', $count);
            }
        });
    }
}