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
        Schema::table('analisis_partisipasi', function (Blueprint $table) {
            $table->foreign(['config_id'])->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_klassifikasi'], 'analisis_partisipasi_klasifikasi_fk')->references(['id'])->on('analisis_klasifikasi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_master'], 'analisis_partisipasi_master_fk')->references(['id'])->on('analisis_master')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_periode'], 'analisis_partisipasi_periode_fk')->references(['id'])->on('analisis_periode')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_subjek'], 'analisis_partisipasi_subjek_fk')->references(['id'])->on('analisis_parameter')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analisis_partisipasi', function (Blueprint $table) {
            $table->dropForeign('analisis_partisipasi_config_id_foreign');
            $table->dropForeign('analisis_partisipasi_klasifikasi_fk');
            $table->dropForeign('analisis_partisipasi_master_fk');
            $table->dropForeign('analisis_partisipasi_periode_fk');
            $table->dropForeign('analisis_partisipasi_subjek_fk');
        });
    }
};
