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
        Schema::create('analisis_partisipasi', function (Blueprint $table) {
            $table->integer('id_subjek')->nullable();
            $table->integer('id_master')->nullable()->index('id_master');
            $table->integer('id_periode')->nullable()->index('id_periode');
            $table->integer('id_klassifikasi')->nullable()->index('id_klassifikasi');
            $table->integer('config_id')->nullable()->index('analisis_partisipasi_config_id_foreign');

            $table->index(['id_subjek', 'id_master', 'id_periode', 'id_klassifikasi'], 'id_subjek');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_partisipasi');
    }
};
