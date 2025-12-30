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
        Schema::table('analisis_master', function (Blueprint $table) {
            $table->foreign(['config_id'], 'analisis_master_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['subjek_tipe'], 'analisis_master_subjek_fk')->references(['id'])->on('analisis_ref_subjek')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analisis_master', function (Blueprint $table) {
            $table->dropForeign('analisis_master_config_fk');
            $table->dropForeign('analisis_master_subjek_fk');
        });
    }
};
