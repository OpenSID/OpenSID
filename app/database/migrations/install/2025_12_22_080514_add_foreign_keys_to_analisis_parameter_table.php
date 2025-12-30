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
        Schema::table('analisis_parameter', function (Blueprint $table) {
            $table->foreign(['config_id'], 'analisis_parameter_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_indikator'], 'analisis_parameter_indikator_fk')->references(['id'])->on('analisis_indikator')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analisis_parameter', function (Blueprint $table) {
            $table->dropForeign('analisis_parameter_config_fk');
            $table->dropForeign('analisis_parameter_indikator_fk');
        });
    }
};
