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
        Schema::create('analisis_periode', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('analisis_periode_config_fk');
            $table->integer('id_master')->nullable()->index('id_master');
            $table->string('nama', 50);
            $table->integer('id_state')->nullable()->index('id_state');
            $table->boolean('aktif')->default(false);
            $table->string('keterangan', 100);
            $table->year('tahun_pelaksanaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_periode');
    }
};
