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
        Schema::create('ibu_hamil', function (Blueprint $table) {
            $table->increments('id_ibu_hamil');
            $table->integer('config_id')->nullable()->index('ibu_hamil_config_fk');
            $table->integer('posyandu_id');
            $table->integer('kia_id');
            $table->boolean('status_kehamilan')->nullable();
            $table->tinyInteger('usia_kehamilan')->nullable();
            $table->date('tanggal_melahirkan')->nullable();
            $table->boolean('pemeriksaan_kehamilan');
            $table->boolean('konsumsi_pil_fe');
            $table->integer('butir_pil_fe');
            $table->boolean('pemeriksaan_nifas');
            $table->boolean('konseling_gizi');
            $table->boolean('kunjungan_rumah');
            $table->boolean('akses_air_bersih');
            $table->boolean('kepemilikan_jamban');
            $table->boolean('jaminan_kesehatan');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ibu_hamil');
    }
};
