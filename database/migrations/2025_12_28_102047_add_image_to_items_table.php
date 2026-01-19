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
        Schema::table('items', function (Blueprint $table) {
            // Menambahkan kolom image yang boleh kosong (nullable)
            // Diletakkan setelah kolom description (atau sesuaikan posisinya)
            $table->string('image')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }         
   
};
