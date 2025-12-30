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
        Schema::table('kehadiran_pengaduan', function (Blueprint $table) {
            $table->foreign(['config_id'], 'kehadiran_pengaduan_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pamong'], 'kehadiran_pengaduan_pamong_fk')->references(['pamong_id'])->on('tweb_desa_pamong')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_penduduk'], 'kehadiran_pengaduan_penduduk_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kehadiran_pengaduan', function (Blueprint $table) {
            $table->dropForeign('kehadiran_pengaduan_config_fk');
            $table->dropForeign('kehadiran_pengaduan_pamong_fk');
            $table->dropForeign('kehadiran_pengaduan_penduduk_fk');
        });
    }
};
