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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('surat_masuk_config_fk');
            $table->smallInteger('nomor_urut')->nullable();
            $table->date('tanggal_penerimaan');
            $table->string('nomor_surat', 35)->nullable();
            $table->string('kode_surat', 10)->nullable();
            $table->date('tanggal_surat');
            $table->string('pengirim', 100)->nullable();
            $table->string('isi_singkat', 200)->nullable();
            $table->string('isi_disposisi', 200)->nullable();
            $table->string('berkas_scan', 100)->nullable();
            $table->string('lokasi_arsip', 150)->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_masuk');
    }
};
