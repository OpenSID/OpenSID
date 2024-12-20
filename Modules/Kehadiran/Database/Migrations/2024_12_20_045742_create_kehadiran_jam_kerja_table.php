<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Kehadiran\Models\JamKerja;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('kehadiran_jam_kerja')) {
            Schema::create('kehadiran_jam_kerja', function (Blueprint $table) {
                $table->increments('id');
                $table->configId();
                $table->string('nama_hari', 65);
                $table->time('jam_masuk');
                $table->time('jam_keluar');
                $table->status();
                $table->mediumText('keterangan')->nullable();
                $table->unique(['config_id', 'nama_hari'], 'jam_kerja_config');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExistsDBGabungan('kehadiran_jam_kerja', function () {
            JamKerja::withoutConfigId(identitas('id'))->delete();
        });
    }
};
