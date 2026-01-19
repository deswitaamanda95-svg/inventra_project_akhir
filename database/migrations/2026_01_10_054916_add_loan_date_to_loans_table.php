<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            // Hanya tambah jika kolom belum ada
            if (!Schema::hasColumn('loans', 'quantity_borrowed')) {
                $table->integer('quantity_borrowed')->default(1);
            }
            
            // Tambahkan kolom lain jika ada...
            if (!Schema::hasColumn('loans', 'loan_date')) {
                $table->date('loan_date')->nullable();
            }
        });
    }
    
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['loan_date', 'quantity_borrowed']);
        });
    }
};
