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
        Schema::create('analisis_periode', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('analisis_periode_config_fk');
            $table->integer('id_master')->index('id_master');
            $table->string('nama', 50);
            $table->tinyInteger('id_state')->default(1)->index('id_state');
            $table->boolean('aktif')->default(false);
            $table->string('keterangan', 100);
            $table->year('tahun_pelaksanaan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analisis_periode');
    }
};
