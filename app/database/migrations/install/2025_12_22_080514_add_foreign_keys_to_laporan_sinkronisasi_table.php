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
        Schema::table('laporan_sinkronisasi', function (Blueprint $table) {
            $table->foreign(['config_id'], 'laporan_sinkronisasi_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_sinkronisasi', function (Blueprint $table) {
            $table->dropForeign('laporan_sinkronisasi_config_fk');
        });
    }
};
