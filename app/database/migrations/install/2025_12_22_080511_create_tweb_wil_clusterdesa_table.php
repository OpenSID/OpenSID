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
        Schema::create('tweb_wil_clusterdesa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id');
            $table->string('rt', 10)->default('0');
            $table->string('rw', 10)->default('0');
            $table->string('dusun', 50)->default('0');
            $table->integer('id_kepala')->nullable()->index('id_kepala');
            $table->string('lat', 20)->nullable();
            $table->string('lng', 20)->nullable();
            $table->integer('zoom')->nullable();
            $table->text('path')->nullable();
            $table->string('map_tipe', 20)->nullable();
            $table->string('warna', 25)->nullable();
            $table->string('border', 25)->nullable();
            $table->integer('urut')->nullable();
            $table->integer('urut_cetak')->nullable();

            $table->unique(['config_id', 'rt', 'rw', 'dusun'], 'rt_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweb_wil_clusterdesa');
    }
};
