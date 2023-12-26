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
        Schema::create('log_penduduk', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('config_id');
            $table->integer('id_pend')->index('id_pend');
            $table->integer('kode_peristiwa')->nullable()->index('kode_peristiwa');
            $table->string('meninggal_di', 50)->nullable();
            $table->string('jam_mati', 10)->nullable();
            $table->string('sebab', 50)->nullable();
            $table->string('penolong_mati', 50)->nullable();
            $table->string('akta_mati', 50)->nullable();
            $table->tinyText('alamat_tujuan')->nullable();
            $table->timestamp('tgl_lapor')->useCurrent();
            $table->dateTime('tgl_peristiwa')->nullable()->useCurrent()->index('tgl_peristiwa');
            $table->text('catatan')->nullable();
            $table->string('no_kk', 16)->nullable();
            $table->string('nama_kk', 100)->nullable();
            $table->tinyInteger('ref_pindah')->nullable()->default(1)->index('id_ref_pindah');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
            $table->string('maksud_tujuan_kedatangan', 50)->nullable();

            $table->unique(['config_id', 'id_pend', 'kode_peristiwa', 'tgl_peristiwa'], 'id_pend_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_penduduk');
    }
};
