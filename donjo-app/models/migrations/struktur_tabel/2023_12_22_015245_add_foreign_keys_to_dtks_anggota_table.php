<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtks_anggota', function (Blueprint $table) {
            $table->foreign(['id_keluarga'], 'FK_kel_dtks_anggota')->references(['id'])->on('tweb_keluarga')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['config_id'], 'dtks_anggota_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_dtks'], 'FK_dtks_dtks_anggota')->references(['id'])->on('dtks')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_penduduk'], 'FK_pend_dtks_anggota')->references(['id'])->on('tweb_penduduk')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dtks_anggota', function (Blueprint $table) {
            $table->dropForeign('FK_kel_dtks_anggota');
            $table->dropForeign('dtks_anggota_config_fk');
            $table->dropForeign('FK_dtks_dtks_anggota');
            $table->dropForeign('FK_pend_dtks_anggota');
        });
    }
};
