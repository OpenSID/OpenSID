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
        Schema::create('analisis_respon', function (Blueprint $table) {
            $table->integer('id_indikator')->nullable()->index('id_indikator');
            $table->integer('config_id')->index('analisis_respon_config_fk');
            $table->integer('id_parameter')->nullable();
            $table->integer('id_subjek')->nullable();
            $table->integer('id_periode')->nullable()->index('id_periode');
            $table->integer('penduduk_id')->nullable()->index('analisis_respon_penduduk_id_foreign');
            $table->integer('keluarga_id')->nullable()->index('analisis_respon_keluarga_id_foreign');
            $table->integer('kelompok_id')->nullable()->index('analisis_respon_kelompok_id_foreign');
            $table->integer('rtm_id')->nullable()->index('analisis_respon_rtm_id_foreign');
            $table->integer('desa_id')->nullable()->index('analisis_respon_desa_id_foreign');
            $table->integer('dusun_id')->nullable()->index('analisis_respon_dusun_id_foreign');
            $table->integer('rw_id')->nullable()->index('analisis_respon_rw_id_foreign');
            $table->integer('rt_id')->nullable()->index('analisis_respon_rt_id_foreign');

            $table->index(['id_parameter', 'id_subjek'], 'id_parameter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_respon');
    }
};
