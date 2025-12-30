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
        Schema::table('analisis_respon_hasil', function (Blueprint $table) {
            $table->foreign(['config_id'], 'analisis_respon_hasil_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['desa_id'])->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['dusun_id'])->references(['id'])->on('tweb_wil_clusterdesa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['kelompok_id'])->references(['id'])->on('kelompok')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['keluarga_id'])->references(['id'])->on('tweb_keluarga')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_master'], 'analisis_respon_hasil_master_fk')->references(['id'])->on('analisis_master')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['penduduk_id'])->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_periode'], 'analisis_respon_hasil_periode_fk')->references(['id'])->on('analisis_periode')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['rtm_id'])->references(['id'])->on('tweb_rtm')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['rt_id'])->references(['id'])->on('tweb_wil_clusterdesa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['rw_id'])->references(['id'])->on('tweb_wil_clusterdesa')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analisis_respon_hasil', function (Blueprint $table) {
            $table->dropForeign('analisis_respon_hasil_config_fk');
            $table->dropForeign('analisis_respon_hasil_desa_id_foreign');
            $table->dropForeign('analisis_respon_hasil_dusun_id_foreign');
            $table->dropForeign('analisis_respon_hasil_kelompok_id_foreign');
            $table->dropForeign('analisis_respon_hasil_keluarga_id_foreign');
            $table->dropForeign('analisis_respon_hasil_master_fk');
            $table->dropForeign('analisis_respon_hasil_penduduk_id_foreign');
            $table->dropForeign('analisis_respon_hasil_periode_fk');
            $table->dropForeign('analisis_respon_hasil_rtm_id_foreign');
            $table->dropForeign('analisis_respon_hasil_rt_id_foreign');
            $table->dropForeign('analisis_respon_hasil_rw_id_foreign');
        });
    }
};
