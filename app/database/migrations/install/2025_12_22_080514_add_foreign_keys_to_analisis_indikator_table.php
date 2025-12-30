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
        Schema::table('analisis_indikator', function (Blueprint $table) {
            $table->foreign(['config_id'], 'analisis_indikator_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_kategori'], 'analisis_indikator_id_kategori_fk')->references(['id'])->on('analisis_kategori_indikator')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_master'], 'analisis_indikator_master_fk')->references(['id'])->on('analisis_master')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_tipe'], 'analisis_indikator_tipe_fk')->references(['id'])->on('analisis_tipe_indikator')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analisis_indikator', function (Blueprint $table) {
            $table->dropForeign('analisis_indikator_config_fk');
            $table->dropForeign('analisis_indikator_id_kategori_fk');
            $table->dropForeign('analisis_indikator_master_fk');
            $table->dropForeign('analisis_indikator_tipe_fk');
        });
    }
};
