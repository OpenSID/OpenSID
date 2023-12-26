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
        Schema::create('kelompok_anggota', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->integer('id_kelompok')->index('kelompok_anggota_kelompok_fk');
            $table->integer('id_penduduk')->index('kelompok_anggota_penduduk_fk');
            $table->string('no_anggota', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('jabatan', 50)->nullable()->default('90');
            $table->string('no_sk_jabatan', 50)->nullable();
            $table->string('tipe', 100)->nullable()->default('kelompok');
            $table->string('periode')->nullable();
            $table->string('nmr_sk_pengangkatan')->nullable();
            $table->date('tgl_sk_pengangkatan')->nullable();
            $table->string('nmr_sk_pemberhentian')->nullable();
            $table->date('tgl_sk_pemberhentian')->nullable();

            $table->unique(['config_id', 'id_kelompok', 'no_anggota'], 'no_anggota_config');
            $table->unique(['config_id', 'id_kelompok', 'id_penduduk'], 'id_kelompok_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelompok_anggota');
    }
};
