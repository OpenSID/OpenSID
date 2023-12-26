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
        Schema::table('kelompok_anggota', function (Blueprint $table) {
            $table->foreign(['id_kelompok'], 'kelompok_anggota_kelompok_fk')->references(['id'])->on('kelompok')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'kelompok_anggota_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_penduduk'], 'kelompok_anggota_penduduk_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelompok_anggota', function (Blueprint $table) {
            $table->dropForeign('kelompok_anggota_kelompok_fk');
            $table->dropForeign('kelompok_anggota_config_fk');
            $table->dropForeign('kelompok_anggota_penduduk_fk');
        });
    }
};
