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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Tambahkan ini
            $table->text('damage_note'); // Ubah dari issue_detail
            $table->string('technician_name')->nullable();
            $table->date('start_date');
            $table->date('estimated_finish')->nullable();
            $table->date('completion_date')->nullable();
            // Tambahkan 'broken' ke dalam daftar ENUM agar tidak error lagi
            $table->enum('status', ['pending', 'ongoing', 'fixed', 'broken'])->default('pending');
            $table->decimal('repair_cost', 15, 2)->default(0); // Ubah dari cost
            $table->string('payment_status')->default('pending'); // Tambahkan ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
