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
        Schema::create('kehadiran_pengaduan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->index('kehadiran_pengaduan_config_fk');
            $table->dateTime('waktu');
            $table->boolean('status')->default(false);
            $table->text('keterangan')->nullable();
            $table->integer('id_penduduk')->nullable()->index('kehadiran_pengaduan_penduduk_fk');
            $table->integer('id_pamong')->nullable()->index('kehadiran_pengaduan_pamong_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_pengaduan');
    }
};
