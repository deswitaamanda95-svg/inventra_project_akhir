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
        Schema::table('maintenance_logs', function (Blueprint $table) {
            // Mengubah issue_detail menjadi damage_note jika masih versi lama
            if (Schema::hasColumn('maintenance_logs', 'issue_detail')) {
                $table->renameColumn('issue_detail', 'damage_note');
            }
            // Menambah repair_cost & payment_status jika belum ada
            if (!Schema::hasColumn('maintenance_logs', 'repair_cost')) {
                $table->decimal('repair_cost', 12, 2)->default(0)->after('item_id');
            }
            if (!Schema::hasColumn('maintenance_logs', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'waived'])->default('pending')->after('repair_cost');
            }
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
