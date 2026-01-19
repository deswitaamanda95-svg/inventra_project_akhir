<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('items', function (Blueprint $table) {
            // Hapus foreign key yang lama
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
            
            // Tambahkan kolom teks biasa untuk lokasi/ruangan
            $table->string('room')->after('condition')->default('Gudang Utama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
