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
        Schema::create('analisis_kategori_indikator', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('analisis_kategori_indikator_config_fk');
            $table->tinyInteger('id_master')->index('id_master');
            $table->string('kategori', 50);
            $table->string('kategori_kode', 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analisis_kategori_indikator');
    }
};
