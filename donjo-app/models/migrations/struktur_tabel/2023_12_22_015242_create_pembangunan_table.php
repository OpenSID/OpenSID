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
        Schema::create('pembangunan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->integer('id_lokasi')->nullable()->index('id_lokasi');
            $table->string('sumber_dana')->nullable();
            $table->string('judul')->nullable();
            $table->string('slug')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('lokasi')->nullable();
            $table->string('lat', 225)->nullable();
            $table->string('lng')->nullable();
            $table->string('volume', 100)->nullable();
            $table->year('tahun_anggaran')->nullable();
            $table->string('pelaksana_kegiatan')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('foto')->nullable();
            $table->bigInteger('anggaran')->nullable()->default(0);
            $table->integer('perubahan_anggaran')->nullable()->default(0);
            $table->bigInteger('sumber_biaya_pemerintah')->nullable()->default(0);
            $table->bigInteger('sumber_biaya_provinsi')->nullable()->default(0);
            $table->bigInteger('sumber_biaya_kab_kota')->nullable()->default(0);
            $table->bigInteger('sumber_biaya_swadaya')->nullable()->default(0);
            $table->bigInteger('sumber_biaya_jumlah')->nullable()->default(0);
            $table->text('manfaat')->nullable();
            $table->integer('waktu')->nullable()->default(0);
            $table->boolean('satuan_waktu')->default(false)->comment('1 = Hari, 2 = Minggu, 3 = Bulan, 4 = Tahun');
            $table->string('sifat_proyek', 100)->nullable()->default('BARU');

            $table->unique(['config_id', 'slug'], 'slug_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembangunan');
    }
};
