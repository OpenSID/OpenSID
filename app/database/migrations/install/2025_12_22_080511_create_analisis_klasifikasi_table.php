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
        Schema::create('analisis_klasifikasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('analisis_klasifikasi_config_fk');
            $table->integer('id_master')->nullable()->index('id_master');
            $table->string('nama', 50);
            $table->double('minval', 7, 2);
            $table->double('maxval', 7, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_klasifikasi');
    }
};
