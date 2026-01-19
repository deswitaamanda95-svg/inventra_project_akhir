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
            // Mengubah nama kolom agar sinkron dengan Model
            $table->renameColumn('issue_detail', 'damage_note');
        });
    }

    public function down()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->renameColumn('damage_note', 'issue_detail');
        });
    }
};
