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
        Schema::table('kelompok_anggota', function (Blueprint $table) {
            $table->foreign(['config_id'], 'kelompok_anggota_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_kelompok'], 'kelompok_anggota_kelompok_fk')->references(['id'])->on('kelompok')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_penduduk'], 'kelompok_anggota_penduduk_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompok_anggota', function (Blueprint $table) {
            $table->dropForeign('kelompok_anggota_config_fk');
            $table->dropForeign('kelompok_anggota_kelompok_fk');
            $table->dropForeign('kelompok_anggota_penduduk_fk');
        });
    }
};
