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
        Schema::create('log_surat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('log_surat_config_fk');
            $table->integer('id_format_surat');
            $table->integer('id_pend')->nullable();
            $table->integer('id_pamong');
            $table->string('nama_pamong', 100)->nullable()->comment('Nama pamong agar tidak berubah saat ada perubahan di master pamong');
            $table->string('nama_jabatan', 100)->nullable();
            $table->integer('id_user');
            $table->timestamp('tanggal')->useCurrent();
            $table->string('bulan', 2)->nullable();
            $table->string('tahun', 4)->nullable();
            $table->string('no_surat', 20)->nullable();
            $table->string('nama_surat', 100)->nullable();
            $table->string('lampiran', 100)->nullable();
            $table->decimal('nik_non_warga', 16, 0)->nullable();
            $table->string('nama_non_warga', 100)->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->string('lokasi_arsip', 150)->nullable()->default('');
            $table->integer('urls_id')->nullable()->unique('urls_id');
            $table->tinyInteger('status')->default(0)->comment('0. Konsep, 1. Cetak');
            $table->string('log_verifikasi', 100)->nullable();
            $table->boolean('tte')->nullable();
            $table->boolean('verifikasi_operator')->nullable();
            $table->boolean('verifikasi_kades')->nullable();
            $table->boolean('verifikasi_sekdes')->nullable();
            $table->longText('isi_surat')->nullable();
            $table->boolean('kecamatan')->default(true);
            $table->dateTime('deleted_at')->nullable();
            $table->string('pemohon', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_surat');
    }
};
