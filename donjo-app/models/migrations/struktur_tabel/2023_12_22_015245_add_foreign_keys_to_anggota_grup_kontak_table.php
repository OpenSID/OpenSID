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
        Schema::table('anggota_grup_kontak', function (Blueprint $table) {
            $table->foreign(['id_penduduk'], 'anggota_grup_kontak_id_penduduk_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_grup'], 'anggota_grup_kontak_ke_kontak_grup')->references(['id_grup'])->on('kontak_grup')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'anggota_grup_kontak_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_kontak'], 'anggota_grup_kontak_ke_kontak')->references(['id_kontak'])->on('kontak')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggota_grup_kontak', function (Blueprint $table) {
            $table->dropForeign('anggota_grup_kontak_id_penduduk_fk');
            $table->dropForeign('anggota_grup_kontak_ke_kontak_grup');
            $table->dropForeign('anggota_grup_kontak_config_fk');
            $table->dropForeign('anggota_grup_kontak_ke_kontak');
        });
    }
};
