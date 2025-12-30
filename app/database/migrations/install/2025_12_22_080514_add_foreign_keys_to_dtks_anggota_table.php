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
        Schema::table('dtks_anggota', function (Blueprint $table) {
            $table->foreign(['config_id'], 'dtks_anggota_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_dtks'], 'FK_dtks_dtks_anggota')->references(['id'])->on('dtks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_keluarga'], 'FK_kel_dtks_anggota')->references(['id'])->on('tweb_keluarga')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['id_penduduk'], 'FK_pend_dtks_anggota')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dtks_anggota', function (Blueprint $table) {
            $table->dropForeign('dtks_anggota_config_fk');
            $table->dropForeign('FK_dtks_dtks_anggota');
            $table->dropForeign('FK_kel_dtks_anggota');
            $table->dropForeign('FK_pend_dtks_anggota');
        });
    }
};
