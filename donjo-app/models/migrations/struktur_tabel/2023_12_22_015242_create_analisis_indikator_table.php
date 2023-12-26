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
        Schema::create('analisis_indikator', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->nullable()->index('analisis_indikator_config_fk');
            $table->integer('id_master');
            $table->string('nomor', 10)->nullable();
            $table->string('pertanyaan', 400);
            $table->tinyInteger('id_tipe')->default(1)->index();
            $table->tinyInteger('bobot')->default(0);
            $table->tinyInteger('act_analisis')->default(2);
            $table->integer('id_kategori')->index();
            $table->boolean('is_publik')->default(false);
            $table->boolean('is_teks')->default(false);
            $table->string('referensi', 50)->nullable();

            $table->index(['id_master', 'id_tipe']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analisis_indikator');
    }
};
