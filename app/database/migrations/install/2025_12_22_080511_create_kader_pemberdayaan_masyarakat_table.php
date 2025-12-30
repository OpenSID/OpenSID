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
        Schema::create('kader_pemberdayaan_masyarakat', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->index('kader_pemberdayaan_masyarakat_config_fk');
            $table->integer('penduduk_id')->nullable()->index('kader_pemberdayaan_masyarakat_penduduk_fk');
            $table->text('kursus')->nullable();
            $table->text('bidang')->nullable();
            $table->text('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kader_pemberdayaan_masyarakat');
    }
};
