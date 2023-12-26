<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persil', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->nullable()->index('persil_config_fk');
            $table->string('nomor', 20);
            $table->smallInteger('nomor_urut_bidang')->nullable()->default(1);
            $table->integer('kelas');
            $table->decimal('luas_persil', 7, 0)->nullable();
            $table->integer('id_wilayah')->nullable();
            $table->text('lokasi')->nullable();
            $table->text('path')->nullable();
            $table->unsignedInteger('cdesa_awal')->nullable();
            $table->integer('id_peta')->nullable();

            $table->index(['nomor', 'nomor_urut_bidang'], 'nomor_nomor_urut_bidang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persil');
    }
};
