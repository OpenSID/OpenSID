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
        Schema::create('lokasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('lokasi_config_fk');
            $table->text('desk');
            $table->string('nama', 50);
            $table->integer('enabled')->default(1);
            $table->string('lat', 30)->nullable();
            $table->string('lng', 30)->nullable();
            $table->integer('ref_point')->nullable()->index('ref_point');
            $table->string('foto', 100)->nullable();
            $table->integer('id_cluster')->nullable()->index('lokasi_cluster_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};
