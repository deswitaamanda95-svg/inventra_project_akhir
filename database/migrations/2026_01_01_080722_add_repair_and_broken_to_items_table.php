<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum mencoba menambahkannya
            if (!Schema::hasColumn('items', 'quantity_repair')) {
                $table->integer('quantity_repair')->default(0)->after('quantity');
            }
            
            if (!Schema::hasColumn('items', 'quantity_broken')) {
                $table->integer('quantity_broken')->default(0)->after('quantity_repair');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['quantity_repair', 'quantity_broken']);
        });
    }
};
