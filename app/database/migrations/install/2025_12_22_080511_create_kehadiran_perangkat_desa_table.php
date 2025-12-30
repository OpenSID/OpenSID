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
        Schema::create('kehadiran_perangkat_desa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->index('kehadiran_perangkat_desa_config_fk');
            $table->date('tanggal')->nullable();
            $table->integer('pamong_id')->nullable()->index('kehadiran_perangkat_desa_pamong_fk');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->string('status_kehadiran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_perangkat_desa');
    }
};
