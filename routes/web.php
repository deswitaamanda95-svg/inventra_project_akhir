<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\MaintenanceController; 

// User Controllers
use App\Http\Controllers\User\UserDashboardController as UserDashboard;

/*
|--------------------------------------------------------------------------
| Web Routes - INVENTRA Intelligence Panel
|--------------------------------------------------------------------------
*/

// 1. Landing & Authentication
Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__ . '/auth.php';

// 2. Authenticated Routes Group
Route::middleware(['auth'])->group(function () {
        
    // --- AREA ADMIN (Akses Penuh Manajemen) ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // A. Dashboard & Reports
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        
        // B. Custom Loan Actions (Persetujuan & Pengembalian)
        // Rute khusus diletakkan di atas agar tidak terbaca sebagai {loan}
        Route::get('/loans/export', [LoanController::class, 'exportExcel'])->name('loans.export');
        Route::post('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::post('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject'); 
        Route::post('/loans/{loan}/confirm-return', [LoanController::class, 'confirmReturn'])->name('loans.confirm-return');
        Route::post('/loans/{loan}/force-return', [LoanController::class, 'forceReturn'])->name('loans.force-return'); 

        // C. Maintenance Logs (Pemeliharaan Aset)
        // Rute Export & Print (Letakkan di atas index/resource)
        Route::get('/maintenance/export', [MaintenanceController::class, 'exportPdf'])->name('maintenance.export');
        Route::get('/maintenance/{log}/print', [MaintenanceController::class, 'printIndividual'])->name('maintenance.print');
        
        Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::post('/maintenance/{log}/finish', [MaintenanceController::class, 'finish'])->name('maintenance.finish');
        
        // Perbaikan: Rute Konfirmasi Lunas (Status Pembayaran)
        Route::post('/maintenance/{log}/pay', [MaintenanceController::class, 'pay'])->name('maintenance.pay');

        // D. Items Additional Features
        Route::get('/items/damaged', [ItemController::class, 'damagedReport'])->name('items.damaged');
        
        // E. Resource Routes (CRUD Standar)
        Route::resource('categories', CategoryController::class);
        Route::resource('items', ItemController::class);
        Route::resource('loans', LoanController::class);
        Route::resource('users', UserController::class);
    });

    // --- AREA USER (Katalog Barang & Peminjaman Personal) ---
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
        Route::get('/items', [UserDashboard::class, 'items'])->name('items.index');
        Route::get('/items/{item}', [UserDashboard::class, 'show'])->name('items.show'); 
        Route::get('/loans', [UserDashboard::class, 'loans'])->name('loans.index');
        
        // Pembatalan & Pengembalian oleh User
        Route::delete('/loans/{loan}/cancel', [UserDashboard::class, 'cancel'])->name('loans.cancel');
        Route::post('/items/{item}/borrow', [UserDashboard::class, 'borrow'])->name('items.borrow');
        Route::post('/loans/{loan}/return', [UserDashboard::class, 'returnRequest'])->name('loans.return');
    });

    // --- Global Profile Settings (Admin & User) ---
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::put('/profile/password', 'updatePassword')->name('profile.password');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});