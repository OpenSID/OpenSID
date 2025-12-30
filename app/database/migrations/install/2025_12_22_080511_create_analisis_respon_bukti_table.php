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
        Schema::create('analisis_respon_bukti', function (Blueprint $table) {
            $table->integer('id_master')->nullable()->index('analisis_respon_bukti_master_fk');
            $table->integer('config_id')->index('analisis_respon_bukti_config_fk');
            $table->integer('id_periode')->nullable()->index('analisis_respon_bukti_periode_fk');
            $table->integer('id_subjek')->nullable();
            $table->string('pengesahan', 100);
            $table->timestamp('tgl_update')->useCurrent();
            $table->integer('penduduk_id')->nullable()->index('analisis_respon_bukti_penduduk_id_foreign');
            $table->integer('keluarga_id')->nullable()->index('analisis_respon_bukti_keluarga_id_foreign');
            $table->integer('kelompok_id')->nullable()->index('analisis_respon_bukti_kelompok_id_foreign');
            $table->integer('rtm_id')->nullable()->index('analisis_respon_bukti_rtm_id_foreign');
            $table->integer('desa_id')->nullable()->index('analisis_respon_bukti_desa_id_foreign');
            $table->integer('dusun_id')->nullable()->index('analisis_respon_bukti_dusun_id_foreign');
            $table->integer('rw_id')->nullable()->index('analisis_respon_bukti_rw_id_foreign');
            $table->integer('rt_id')->nullable()->index('analisis_respon_bukti_rt_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_respon_bukti');
    }
};
