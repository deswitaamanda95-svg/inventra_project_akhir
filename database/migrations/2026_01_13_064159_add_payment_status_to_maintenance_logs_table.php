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
            // 1. Pastikan kolom repair_cost ada sebagai acuan penempatan
            if (!Schema::hasColumn('maintenance_logs', 'repair_cost')) {
                $table->decimal('repair_cost', 12, 2)->default(0)->after('item_id');
            }

            // 2. Tambahkan status pembayaran setelah repair_cost
            if (!Schema::hasColumn('maintenance_logs', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'waived'])
                    ->default('pending')
                    ->after('repair_cost');
            }

            // 3. Tambahkan user_id untuk mencatat siapa yang merusakkan barang
            if (!Schema::hasColumn('maintenance_logs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('item_id');
            }
        });
    }   

    public function down()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->dropColumn(['repair_cost', 'payment_status', 'user_id']);
        });
    }
};
