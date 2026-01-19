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
            // Tambahkan ->nullable() sebelum ->constrained()
            $table->foreignId('location_id')->after('category_id')->nullable()->constrained();
        });
    }

    public function index()
    {
        // Mengambil semua data lokasi
        $locations = \App\Models\Location::all();
        return view('admin.locations.index', compact('locations'));
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
};
