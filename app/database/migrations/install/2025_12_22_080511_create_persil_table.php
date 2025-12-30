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
        Schema::create('persil', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->index('persil_config_fk');
            $table->string('nomor', 20);
            $table->smallInteger('nomor_urut_bidang')->nullable()->default(1);
            $table->integer('kelas');
            $table->decimal('luas_persil', 7, 0)->nullable();
            $table->integer('id_wilayah')->nullable();
            $table->text('lokasi')->nullable();
            $table->text('path')->nullable();
            $table->unsignedInteger('cdesa_awal')->nullable();
            $table->integer('id_peta')->nullable()->index('persil_peta_fk');
            $table->tinyInteger('is_publik')->default(1)->comment('1 = tampilkan di web publik, 0 = tidak ditampilkan di web publik');

            $table->index(['nomor', 'nomor_urut_bidang'], 'nomor_nomor_urut_bidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persil');
    }
};
